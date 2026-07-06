import { ref, computed } from "vue";
import axios from "axios";
import * as faceapi from "face-api.js";

const LIVENESS_CHALLENGES = [
    { id: "blink", label: "Kedipkan mata Anda", icon: "👁️" },
    { id: "nod", label: "Anggukkan kepala (atas-bawah)", icon: "↕️" },
    { id: "look-left", label: "Lihat ke kiri", icon: "👈" },
    { id: "look-right", label: "Lihat ke kanan", icon: "👉" },
];

/**
 * Composable managing the face enrollment wizard.
 *
 * Steps:
 *   1  → camera warm-up & face detection
 *   2  → liveness challenge
 *   3  → multi-sample capture (3 frames)
 *   4  → success / error
 */
export function useFaceEnrollment(options = {}) {
    // ── Wizard state ──────────────────────────────────────────────────────────
    const step = ref(1); // 1=warmup | 2=liveness | 3=capturing | 4=done | -1=error
    const errorMessage = ref("");
    const isSubmitting = ref(false);
    const enrolledAt = ref(null);

    // ── Camera refs ───────────────────────────────────────────────────────────
    const videoRef = ref(null);
    const canvasRef = ref(null);
    const cameraReady = ref(false);
    const modelsLoaded = ref(false);
    const loadingModels = ref(true);
    const faceDetected = ref(false);
    const faceDescriptor = ref(null);

    // ── Liveness state ────────────────────────────────────────────────────────
    const currentChallenge = ref(null);
    const challengePassed = ref(false);
    const challengeProgress = ref(0); // 0-100
    const previousLandmarks = ref(null);

    // ── Capture state ─────────────────────────────────────────────────────────
    const capturedSamples = ref([]); // array of 128-float arrays
    const captureCount = computed(() => capturedSamples.value.length);
    const REQUIRED_SAMPLES = 3;

    let stream = null;
    let detectionInterval = null;
    let livenessInterval = null;

    // =========================================================================
    // MODELS + CAMERA
    // =========================================================================

    const loadModels = async () => {
        try {
            loadingModels.value = true;
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri("/models"),
                faceapi.nets.faceLandmark68Net.loadFromUri("/models"),
                faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
            ]);
            modelsLoaded.value = true;
            loadingModels.value = false;
        } catch (err) {
            console.error("Failed to load face-api models:", err);
            loadingModels.value = false;
            errorMessage.value =
                "Gagal memuat model face recognition. Silakan refresh halaman.";
            step.value = -1;
        }
    };

    const startCamera = async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user", width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false,
            });
            if (videoRef.value) {
                videoRef.value.srcObject = stream;
                await videoRef.value.play();
                cameraReady.value = true;
                if (modelsLoaded.value) startFaceDetection();
            }
        } catch (err) {
            console.error("Camera error:", err);
            errorMessage.value =
                "Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera.";
            step.value = -1;
        }
    };

    const stopCamera = () => {
        stopDetection();
        if (stream) {
            stream.getTracks().forEach((t) => t.stop());
            stream = null;
        }
        if (videoRef.value) videoRef.value.srcObject = null;
        cameraReady.value = false;
    };

    // =========================================================================
    // FACE DETECTION LOOP (Step 1 warm-up)
    // =========================================================================

    const startFaceDetection = () => {
        stopDetection();
        detectionInterval = setInterval(async () => {
            if (!videoRef.value || !cameraReady.value) return;
            try {
                const detection = await faceapi
                    .detectSingleFace(videoRef.value, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection?.descriptor) {
                    faceDetected.value = true;
                    faceDescriptor.value = Array.from(detection.descriptor);
                    drawBoundingBox(detection);
                } else {
                    faceDetected.value = false;
                    faceDescriptor.value = null;
                    clearCanvas();
                }
            } catch {
                faceDetected.value = false;
            }
        }, 300);
    };

    const stopDetection = () => {
        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        if (livenessInterval) {
            clearInterval(livenessInterval);
            livenessInterval = null;
        }
    };

    // =========================================================================
    // CANVAS OVERLAY
    // =========================================================================

    const drawBoundingBox = (detection) => {
        if (!canvasRef.value || !videoRef.value) return;
        const canvas = canvasRef.value;
        const displaySize = { width: videoRef.value.videoWidth, height: videoRef.value.videoHeight };
        faceapi.matchDimensions(canvas, displaySize);
        const resized = faceapi.resizeResults(detection, displaySize);
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const box = resized.detection.box;
        const color = challengePassed.value ? "#10b981" : "#3b82f6";
        ctx.strokeStyle = color;
        ctx.lineWidth = 3;
        ctx.strokeRect(box.x, box.y, box.width, box.height);

        // Draw corner accents
        const cornerLen = 20;
        ctx.lineWidth = 5;
        const corners = [
            [box.x, box.y, cornerLen, 0, 0, cornerLen],
            [box.x + box.width, box.y, -cornerLen, 0, 0, cornerLen],
            [box.x, box.y + box.height, cornerLen, 0, 0, -cornerLen],
            [box.x + box.width, box.y + box.height, -cornerLen, 0, 0, -cornerLen],
        ];
        corners.forEach(([x, y, dx1, dy1, dx2, dy2]) => {
            ctx.beginPath();
            ctx.moveTo(x + dx1, y + dy1);
            ctx.lineTo(x, y);
            ctx.lineTo(x + dx2, y + dy2);
            ctx.stroke();
        });
    };

    const clearCanvas = () => {
        if (!canvasRef.value) return;
        const ctx = canvasRef.value.getContext("2d");
        ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
    };

    // =========================================================================
    // LIVENESS CHALLENGE (Step 2)
    // =========================================================================

    const startLivenessChallenge = () => {
        // Pick a random challenge
        const pool = [...LIVENESS_CHALLENGES];
        currentChallenge.value = pool[Math.floor(Math.random() * pool.length)];
        challengePassed.value = false;
        challengeProgress.value = 0;
        previousLandmarks.value = null;
        step.value = 2;

        let motionAccumulated = 0;
        const REQUIRED_MOTION = 5; // arbitrary units

        livenessInterval = setInterval(async () => {
            if (!videoRef.value || !cameraReady.value) return;
            try {
                const detection = await faceapi
                    .detectSingleFace(videoRef.value, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    previousLandmarks.value = null;
                    return;
                }

                faceDescriptor.value = Array.from(detection.descriptor);
                drawBoundingBox(detection);

                const landmarks = detection.landmarks.positions;

                if (previousLandmarks.value) {
                    const motion = computeMotion(previousLandmarks.value, landmarks);
                    motionAccumulated += motion;
                    challengeProgress.value = Math.min(100, (motionAccumulated / REQUIRED_MOTION) * 100);

                    if (motionAccumulated >= REQUIRED_MOTION) {
                        challengePassed.value = true;
                        clearInterval(livenessInterval);
                        livenessInterval = null;
                        setTimeout(() => startMultiSampleCapture(), 600);
                    }
                }

                previousLandmarks.value = landmarks;
            } catch {
                // Ignore transient detection errors
            }
        }, 200);
    };

    const computeMotion = (prev, curr) => {
        let sum = 0;
        const count = Math.min(prev.length, curr.length);
        for (let i = 0; i < count; i++) {
            const dx = curr[i].x - prev[i].x;
            const dy = curr[i].y - prev[i].y;
            sum += Math.sqrt(dx * dx + dy * dy);
        }
        return sum / count;
    };

    // =========================================================================
    // MULTI-SAMPLE CAPTURE (Step 3)
    // =========================================================================

    const startMultiSampleCapture = async () => {
        step.value = 3;
        capturedSamples.value = [];

        for (let i = 0; i < REQUIRED_SAMPLES; i++) {
            await new Promise((resolve) => setTimeout(resolve, 500));
            if (!videoRef.value) break;

            try {
                const detection = await faceapi
                    .detectSingleFace(videoRef.value, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection?.descriptor) {
                    capturedSamples.value.push(Array.from(detection.descriptor));
                }
            } catch {
                // Skip failed frame
            }
        }

        if (capturedSamples.value.length < 1) {
            errorMessage.value = "Gagal mengambil sampel wajah. Pastikan wajah Anda terlihat jelas dan coba lagi.";
            step.value = -1;
            return;
        }

        await submitEnrollment();
    };

    // =========================================================================
    // SUBMIT TO BACKEND
    // =========================================================================

    const submitEnrollment = async () => {
        isSubmitting.value = true;
        try {
            const response = await axios.post(route("profile.attendance.enroll-face"), {
                descriptors: capturedSamples.value,
            });

            if (response.data.success) {
                enrolledAt.value = response.data.face_registered_at;
                step.value = 4;
                if (typeof options.onSuccess === "function") {
                    options.onSuccess();
                }
            } else {
                throw new Error(response.data.message || "Unknown error");
            }
        } catch (err) {
            console.error("Enrollment submission error:", err);
            errorMessage.value =
                err.response?.data?.message ||
                err.message ||
                "Pendaftaran wajah gagal. Silakan coba lagi.";
            step.value = -1;
        } finally {
            isSubmitting.value = false;
        }
    };

    // =========================================================================
    // STEP NAVIGATION
    // =========================================================================

    /** Called when user clicks "Mulai" on Step 1 */
    const proceedToLiveness = () => {
        if (!faceDetected.value) return;
        stopDetection();
        startLivenessChallenge();
    };

    /** Retry from any error state */
    const retry = () => {
        errorMessage.value = "";
        challengePassed.value = false;
        challengeProgress.value = 0;
        capturedSamples.value = [];
        step.value = 1;
        startFaceDetection();
    };

    return {
        // State
        step,
        errorMessage,
        isSubmitting,
        enrolledAt,
        // Camera
        videoRef,
        canvasRef,
        cameraReady,
        loadingModels,
        modelsLoaded,
        faceDetected,
        // Challenge
        currentChallenge,
        challengePassed,
        challengeProgress,
        // Capture
        capturedSamples,
        captureCount,
        REQUIRED_SAMPLES,
        // Actions
        loadModels,
        startCamera,
        stopCamera,
        proceedToLiveness,
        retry,
    };
}
