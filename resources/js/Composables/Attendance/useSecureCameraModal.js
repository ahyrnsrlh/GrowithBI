import { onBeforeUnmount, onMounted, ref, watch, computed } from "vue";
import * as faceapi from "face-api.js";
import axios from "axios";

/**
 * Composable for the SecureCameraModal used during check-in / check-out.
 * Adds:
 *  - Real-time face bounding-box canvas overlay (red → green based on confidence)
 *  - Confidence score derived from Euclidean distance (client-side preview only)
 *  - Auto-capture when confidence is stable for 1.5 s (with 3-2-1 countdown)
 *  - Manual capture fallback
 */
export function useSecureCameraModal(props, emit) {
    const videoElement = ref(null);
    const canvasElement = ref(null);
    const capturedPhoto = ref(null);
    const cameraReady = ref(false);
    const errorMessage = ref("");
    const modelsLoaded = ref(false);
    const faceDetected = ref(false);
    const faceDescriptor = ref(null);
    const loadingModels = ref(true);
 
    const verificationStatus = ref("Initializing Camera");
    const verificationFailed = ref(false);

    // Confidence is a 0-100 number derived from comparing consecutive descriptors.
    // It's a client-side quality indicator, NOT the server-side match score.
    const confidence = ref(0);
    const matchStatus = ref("idle"); // "idle" | "scanning" | "good" | "poor"
    const countdown = ref(null); // null | 3 | 2 | 1

    let stream = null;
    let detectionInterval = null;
    let autoCapturePending = null;
    let referenceDescriptor = null; // Stores first detected descriptor per session
    let stableFrames = 0;

    const AUTO_CAPTURE_STABLE_FRAMES = 5; // ~1.5 s at 300 ms interval
    const GOOD_CONFIDENCE_THRESHOLD = 70; // % — auto-capture triggers above this

    // ── Computed label for the badge ──────────────────────────────────────────
    const confidenceLabel = computed(() => {
        if (matchStatus.value === "idle") return "Mencari Wajah...";
        if (matchStatus.value === "scanning") return `${confidence.value}%`;
        if (matchStatus.value === "good") return `✓ ${confidence.value}%`;
        return `${confidence.value}%`;
    });

    const confidenceColor = computed(() => {
        if (!faceDetected.value) return "bg-gray-500";
        if (confidence.value >= 80) return "bg-emerald-600";
        if (confidence.value >= 60) return "bg-amber-500";
        return "bg-red-500";
    });

    // =========================================================================
    // CAMERA
    // =========================================================================

    const startCamera = async () => {
        try {
            errorMessage.value = "";
            cameraReady.value = false;
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user", width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false,
            });
            if (videoElement.value) {
                videoElement.value.srcObject = stream;
                await videoElement.value.play();
                cameraReady.value = true;
                verificationStatus.value = "Detecting Face";
                if (modelsLoaded.value) startFaceDetection();
            }
        } catch (err) {
            console.error("Camera error:", err);
            errorMessage.value = "Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera.";
            cameraReady.value = false;
        }
    };

    const stopCamera = () => {
        stopDetection();
        if (stream) {
            stream.getTracks().forEach((t) => t.stop());
            stream = null;
        }
        if (videoElement.value) videoElement.value.srcObject = null;
        cameraReady.value = false;
        capturedPhoto.value = null;
        errorMessage.value = "";
        faceDetected.value = false;
        faceDescriptor.value = null;
        referenceDescriptor = null;
        stableFrames = 0;
        confidence.value = 0;
        matchStatus.value = "idle";
        countdown.value = null;
        clearCanvas();
    };

    // =========================================================================
    // FACE DETECTION LOOP
    // =========================================================================

    const startFaceDetection = () => {
        stopDetection();
        detectionInterval = setInterval(async () => {
            if (!videoElement.value || !cameraReady.value || capturedPhoto.value) return;
            try {
                const detection = await faceapi
                    .detectSingleFace(videoElement.value, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection?.descriptor) {
                    faceDetected.value = true;
                    verificationStatus.value = "Generating Face Descriptor";
                    const desc = Array.from(detection.descriptor);
                    faceDescriptor.value = desc;

                    // Set reference on first detection per session
                    if (!referenceDescriptor) {
                        referenceDescriptor = desc;
                    }

                    // Calculate intra-session stability as a confidence proxy
                    const dist = euclideanDistance(desc, referenceDescriptor);
                    confidence.value = Math.round(Math.max(0, (1 - dist) * 100));
                    matchStatus.value = confidence.value >= GOOD_CONFIDENCE_THRESHOLD ? "good" : "scanning";

                    drawOverlay(detection, confidence.value);

                    // Auto-capture logic
                    if (confidence.value >= GOOD_CONFIDENCE_THRESHOLD) {
                        stableFrames++;
                        if (stableFrames >= AUTO_CAPTURE_STABLE_FRAMES && !autoCapturePending) {
                            startCountdown();
                        }
                    } else {
                        stableFrames = 0;
                        cancelAutoCapture();
                    }
                } else {
                    faceDetected.value = false;
                    faceDescriptor.value = null;
                    stableFrames = 0;
                    confidence.value = 0;
                    matchStatus.value = "idle";
                    verificationStatus.value = "Detecting Face";
                    cancelAutoCapture();
                    clearCanvas();
                }
            } catch (err) {
                console.error("Face detection error:", err);
                faceDetected.value = false;
            }
        }, 300);
    };

    const stopDetection = () => {
        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        cancelAutoCapture();
    };

    // =========================================================================
    // AUTO CAPTURE COUNTDOWN
    // =========================================================================

    const startCountdown = () => {
        countdown.value = 3;
        let count = 3;
        autoCapturePending = setInterval(() => {
            count--;
            countdown.value = count;
            if (count <= 0) {
                cancelAutoCapture();
                capturePhoto();
            }
        }, 1000);
    };

    const cancelAutoCapture = () => {
        if (autoCapturePending) {
            clearInterval(autoCapturePending);
            autoCapturePending = null;
        }
        countdown.value = null;
    };

    // =========================================================================
    // CANVAS OVERLAY
    // =========================================================================

    const drawOverlay = (detection, conf) => {
        if (!canvasElement.value || !videoElement.value) return;
        const canvas = canvasElement.value;
        const displaySize = {
            width: videoElement.value.videoWidth || 640,
            height: videoElement.value.videoHeight || 480,
        };
        faceapi.matchDimensions(canvas, displaySize);
        const resized = faceapi.resizeResults(detection, displaySize);
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const box = resized.detection.box;
        const color = conf >= 80 ? "#10b981" : conf >= 60 ? "#f59e0b" : "#ef4444";
        const cornerLen = 24;

        // Dashed bounding box
        ctx.setLineDash([6, 3]);
        ctx.strokeStyle = color + "80";
        ctx.lineWidth = 2;
        ctx.strokeRect(box.x, box.y, box.width, box.height);
        ctx.setLineDash([]);

        // Solid corner accents
        ctx.strokeStyle = color;
        ctx.lineWidth = 4;
        const corners = [
            [[box.x, box.y + cornerLen], [box.x, box.y], [box.x + cornerLen, box.y]],
            [[box.x + box.width - cornerLen, box.y], [box.x + box.width, box.y], [box.x + box.width, box.y + cornerLen]],
            [[box.x, box.y + box.height - cornerLen], [box.x, box.y + box.height], [box.x + cornerLen, box.y + box.height]],
            [[box.x + box.width - cornerLen, box.y + box.height], [box.x + box.width, box.y + box.height], [box.x + box.width, box.y + box.height - cornerLen]],
        ];
        corners.forEach(([start, mid, end]) => {
            ctx.beginPath();
            ctx.moveTo(...start);
            ctx.lineTo(...mid);
            ctx.lineTo(...end);
            ctx.stroke();
        });
    };

    const clearCanvas = () => {
        if (!canvasElement.value) return;
        const ctx = canvasElement.value.getContext("2d");
        ctx.clearRect(0, 0, canvasElement.value.width, canvasElement.value.height);
    };

    // =========================================================================
    // CAPTURE & ACTIONS
    // =========================================================================

    const capturePhoto = () => {
        if (!videoElement.value || !cameraReady.value) {
            errorMessage.value = "Kamera belum siap.";
            return;
        }
        if (!faceDetected.value || !faceDescriptor.value) {
            errorMessage.value = "❌ Wajah tidak terdeteksi! Posisikan wajah Anda di dalam frame.";
            return;
        }

        const canvas = document.createElement("canvas");
        canvas.width = videoElement.value.videoWidth;
        canvas.height = videoElement.value.videoHeight;
        const ctx = canvas.getContext("2d");
        // Mirror horizontally so photo matches what user sees
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(videoElement.value, 0, 0);
        capturedPhoto.value = canvas.toDataURL("image/jpeg", 0.85);

        stopDetection();
    };

    const retakePhoto = () => {
        cancelAutoCapture();
        capturedPhoto.value = null;
        faceDetected.value = false;
        faceDescriptor.value = null;
        referenceDescriptor = null;
        stableFrames = 0;
        confidence.value = 0;
        matchStatus.value = "idle";
        verificationFailed.value = false;
        errorMessage.value = "";
        verificationStatus.value = "Detecting Face";
        clearCanvas();
        if (modelsLoaded.value && cameraReady.value) startFaceDetection();
    };

    const confirmPhoto = async () => {
        if (!capturedPhoto.value) {
            errorMessage.value = "Tidak ada foto yang diambil.";
            return;
        }
        if (!faceDescriptor.value || faceDescriptor.value.length !== 128) {
            errorMessage.value = "Face descriptor tidak valid. Silakan ambil foto ulang.";
            return;
        }
 
        verificationStatus.value = "Comparing Face";
        try {
            const response = await axios.post(route("profile.attendance.verify-face"), {
                face_descriptor: faceDescriptor.value,
                photo: capturedPhoto.value,
            });
 
            if (response.data?.success) {
                verificationStatus.value = "Face Verified";
                emit("face-verified", {
                    photo: capturedPhoto.value,
                    faceDescriptor: faceDescriptor.value,
                });
                close();
            } else {
                verificationStatus.value = "Face Verification Failed";
                verificationFailed.value = true;
                errorMessage.value = response.data?.message || "Verifikasi wajah gagal.";
            }
        } catch (error) {
            verificationStatus.value = "Face Verification Failed";
            verificationFailed.value = true;
            errorMessage.value = error.response?.data?.message || "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    };

    const close = () => {
        stopCamera();
        emit("close");
    };

    // =========================================================================
    // UTILS
    // =========================================================================

    const euclideanDistance = (a, b) => {
        let sum = 0;
        for (let i = 0; i < a.length; i++) {
            const diff = a[i] - b[i];
            sum += diff * diff;
        }
        return Math.sqrt(sum);
    };

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    watch(
        () => props.show,
        (val) => {
            if (val) startCamera();
            else stopCamera();
        }
    );

    onMounted(async () => {
        try {
            loadingModels.value = true;
            verificationStatus.value = "Initializing Camera";
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri("/models"),
                faceapi.nets.faceLandmark68Net.loadFromUri("/models"),
                faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
            ]);
            modelsLoaded.value = true;
            loadingModels.value = false;
        } catch (err) {
            console.error("Failed to load face-api.js models:", err);
            errorMessage.value = "Gagal memuat model face recognition. Silakan refresh halaman.";
            loadingModels.value = false;
        }
    });

    onBeforeUnmount(() => stopCamera());

    return {
        videoElement,
        canvasElement,
        capturedPhoto,
        cameraReady,
        errorMessage,
        modelsLoaded,
        faceDetected,
        loadingModels,
        confidence,
        matchStatus,
        confidenceLabel,
        confidenceColor,
        countdown,
        capturePhoto,
        retakePhoto,
        confirmPhoto,
        close,
        verificationStatus,
        verificationFailed,
    };
}
