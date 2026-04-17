import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import * as faceapi from "face-api.js";

export function useSimpleCameraModal(props, emit) {
    const videoElement = ref(null);
    const capturedPhoto = ref(null);
    const cameraReady = ref(false);
    const errorMessage = ref("");
    const modelsLoaded = ref(false);
    const faceDetected = ref(false);
    const faceDescriptor = ref(null);
    const loadingModels = ref(true);

    let stream = null;
    let detectionInterval = null;

    const startFaceDetection = () => {
        if (detectionInterval) {
            clearInterval(detectionInterval);
        }

        detectionInterval = setInterval(async () => {
            if (
                !videoElement.value ||
                !cameraReady.value ||
                capturedPhoto.value
            ) {
                return;
            }

            try {
                const detections = await faceapi
                    .detectSingleFace(
                        videoElement.value,
                        new faceapi.TinyFaceDetectorOptions({
                            inputSize: 224,
                            scoreThreshold: 0.5,
                        }),
                    )
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detections && detections.descriptor) {
                    faceDetected.value = true;
                    faceDescriptor.value = Array.from(detections.descriptor);
                } else {
                    faceDetected.value = false;
                    faceDescriptor.value = null;
                }
            } catch (error) {
                console.error("Face detection error:", error);
                faceDetected.value = false;
            }
        }, 300);
    };

    const stopCamera = () => {
        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }

        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }

        if (videoElement.value) {
            videoElement.value.srcObject = null;
        }

        cameraReady.value = false;
        capturedPhoto.value = null;
        errorMessage.value = "";
        faceDetected.value = false;
        faceDescriptor.value = null;
    };

    const startCamera = async () => {
        try {
            errorMessage.value = "";
            cameraReady.value = false;

            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user",
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                },
                audio: false,
            });

            if (videoElement.value) {
                videoElement.value.srcObject = stream;
                await videoElement.value.play();
                cameraReady.value = true;

                if (modelsLoaded.value) {
                    startFaceDetection();
                }
            }
        } catch (error) {
            console.error("Camera error:", error);
            errorMessage.value =
                "Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera.";
            cameraReady.value = false;
        }
    };

    const capturePhoto = () => {
        if (!videoElement.value || !cameraReady.value) {
            errorMessage.value = "Kamera belum siap";
            return;
        }

        if (!faceDetected.value) {
            errorMessage.value =
                "❌ Wajah tidak terdeteksi! Posisikan wajah Anda di dalam frame dengan pencahayaan yang cukup.";
            return;
        }

        const canvas = document.createElement("canvas");
        canvas.width = videoElement.value.videoWidth;
        canvas.height = videoElement.value.videoHeight;

        const context = canvas.getContext("2d");
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(videoElement.value, 0, 0);

        capturedPhoto.value = canvas.toDataURL("image/jpeg", 0.85);

        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
    };

    const retakePhoto = () => {
        capturedPhoto.value = null;
        faceDetected.value = false;
        faceDescriptor.value = null;

        if (modelsLoaded.value && cameraReady.value) {
            startFaceDetection();
        }
    };

    const close = () => {
        stopCamera();
        emit("close");
    };

    const confirmPhoto = () => {
        if (!capturedPhoto.value) {
            errorMessage.value = "Tidak ada foto yang diambil";
            return;
        }

        if (!faceDescriptor.value || faceDescriptor.value.length !== 128) {
            errorMessage.value =
                "Face descriptor tidak valid. Silakan ambil foto ulang.";
            return;
        }

        emit("photo-captured", {
            photo: capturedPhoto.value,
            faceDescriptor: faceDescriptor.value,
        });
        close();
    };

    watch(
        () => props.show,
        (newVal) => {
            if (newVal) {
                startCamera();
            } else {
                stopCamera();
            }
        },
    );

    onMounted(async () => {
        try {
            loadingModels.value = true;

            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri("/models"),
                faceapi.nets.faceLandmark68Net.loadFromUri("/models"),
                faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
            ]);

            modelsLoaded.value = true;
            loadingModels.value = false;
        } catch (error) {
            console.error("Failed to load face-api.js models:", error);
            errorMessage.value =
                "Gagal memuat model face recognition. Silakan refresh halaman.";
            loadingModels.value = false;
        }
    });

    onBeforeUnmount(() => {
        stopCamera();
    });

    return {
        videoElement,
        capturedPhoto,
        cameraReady,
        errorMessage,
        modelsLoaded,
        faceDetected,
        faceDescriptor,
        loadingModels,
        capturePhoto,
        retakePhoto,
        confirmPhoto,
        close,
    };
}
