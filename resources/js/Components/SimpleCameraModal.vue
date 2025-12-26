<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="close">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-2xl bg-white px-6 py-6 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
                        >
                            <!-- Header -->
                            <div class="mb-4">
                                <DialogTitle
                                    as="h3"
                                    class="text-xl font-bold text-gray-900 flex items-center justify-between"
                                >
                                    <span>📸 {{ title }}</span>
                                    <button
                                        @click="close"
                                        class="text-gray-400 hover:text-gray-600 transition"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </DialogTitle>
                            </div>

                            <!-- Error Message -->
                            <div
                                v-if="errorMessage"
                                class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg"
                            >
                                <p class="text-sm text-red-700">
                                    {{ errorMessage }}
                                </p>
                            </div>

                            <!-- Camera View -->
                            <div
                                class="relative bg-black rounded-xl overflow-hidden aspect-video"
                            >
                                <!-- Video Preview (when not captured) -->
                                <video
                                    v-show="!capturedPhoto"
                                    ref="videoElement"
                                    autoplay
                                    playsinline
                                    class="w-full h-full object-cover mirror"
                                ></video>

                                <!-- Captured Photo Preview -->
                                <img
                                    v-if="capturedPhoto"
                                    :src="capturedPhoto"
                                    alt="Captured"
                                    class="w-full h-full object-cover"
                                />

                                <!-- Loading Models Overlay -->
                                <div
                                    v-if="loadingModels"
                                    class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75"
                                >
                                    <div class="text-center text-white">
                                        <svg
                                            class="animate-spin h-12 w-12 mx-auto mb-3"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <p class="text-sm font-medium">
                                            Memuat Model Face Recognition...
                                        </p>
                                        <p class="text-xs text-gray-300 mt-1">
                                            Mohon tunggu sebentar
                                        </p>
                                    </div>
                                </div>

                                <!-- Camera Not Ready Overlay -->
                                <div
                                    v-if="
                                        !cameraReady &&
                                        !capturedPhoto &&
                                        !loadingModels
                                    "
                                    class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
                                >
                                    <div class="text-center text-white">
                                        <svg
                                            class="animate-spin h-12 w-12 mx-auto mb-3"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <p class="text-sm">Memuat kamera...</p>
                                    </div>
                                </div>

                                <!-- Face Detection Status Indicator -->
                                <div
                                    v-if="
                                        cameraReady &&
                                        !capturedPhoto &&
                                        modelsLoaded
                                    "
                                    class="absolute top-4 left-4 right-4 flex items-center justify-between"
                                >
                                    <div
                                        :class="[
                                            'px-4 py-2 rounded-lg font-medium text-sm shadow-lg transition-all duration-300',
                                            faceDetected
                                                ? 'bg-green-500 text-white'
                                                : 'bg-yellow-500 text-white',
                                        ]"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg
                                                v-if="faceDetected"
                                                class="w-5 h-5"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <svg
                                                v-else
                                                class="w-5 h-5 animate-pulse"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span>{{
                                                faceDetected
                                                    ? "Wajah Terdeteksi"
                                                    : "Mencari Wajah..."
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Guide Overlay (circle frame) -->
                                <div
                                    v-if="!capturedPhoto && cameraReady"
                                    class="absolute inset-0 pointer-events-none"
                                >
                                    <div
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <div
                                            :class="[
                                                'w-64 h-64 border-4 border-dashed rounded-full transition-all duration-300',
                                                faceDetected
                                                    ? 'border-green-400 opacity-70'
                                                    : 'border-white opacity-50',
                                            ]"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="mt-4 text-center">
                                <p
                                    v-if="!capturedPhoto && !loadingModels"
                                    class="text-sm text-gray-600"
                                >
                                    <span v-if="!faceDetected"
                                        >Posisikan wajah Anda di dalam lingkaran
                                        dan pastikan pencahayaan cukup</span
                                    >
                                    <span
                                        v-else
                                        class="text-green-600 font-medium"
                                        >✓ Wajah terdeteksi! Klik "Ambil Foto"
                                        untuk melanjutkan</span
                                    >
                                </p>
                                <p
                                    v-else-if="capturedPhoto"
                                    class="text-sm text-green-600 font-medium"
                                >
                                    ✓ Foto berhasil diambil! Klik konfirmasi
                                    untuk melanjutkan
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex gap-3">
                                <!-- Cancel Button -->
                                <button
                                    type="button"
                                    @click="close"
                                    class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition"
                                >
                                    Batal
                                </button>

                                <!-- Retake Button (shown when photo captured) -->
                                <button
                                    v-if="capturedPhoto"
                                    type="button"
                                    @click="retakePhoto"
                                    class="flex-1 px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-xl transition flex items-center justify-center gap-2"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                    Ambil Ulang
                                </button>

                                <!-- Capture Button (shown when no photo) -->
                                <button
                                    v-if="!capturedPhoto"
                                    type="button"
                                    @click="capturePhoto"
                                    :disabled="
                                        !cameraReady ||
                                        !faceDetected ||
                                        loadingModels
                                    "
                                    :class="[
                                        'flex-1 px-4 py-3 font-medium rounded-xl transition flex items-center justify-center gap-2',
                                        cameraReady &&
                                        faceDetected &&
                                        !loadingModels
                                            ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                            : 'bg-gray-300 cursor-not-allowed text-gray-500',
                                    ]"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    {{
                                        faceDetected && !loadingModels
                                            ? "Ambil Foto"
                                            : "Tunggu Deteksi Wajah..."
                                    }}
                                </button>

                                <!-- Confirm Button (shown when photo captured) -->
                                <button
                                    v-if="capturedPhoto"
                                    type="button"
                                    @click="confirmPhoto"
                                    class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition flex items-center justify-center gap-2"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    Konfirmasi
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, watch, onBeforeUnmount, onMounted } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import * as faceapi from "face-api.js";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "Ambil Foto Absensi",
    },
});

const emit = defineEmits(["close", "photo-captured"]);

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

// Load face-api.js models on component mount
onMounted(async () => {
    try {
        loadingModels.value = true;
        console.log("Loading face-api.js models...");

        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri("/models"),
            faceapi.nets.faceLandmark68Net.loadFromUri("/models"),
            faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
        ]);

        modelsLoaded.value = true;
        loadingModels.value = false;
        console.log("✓ All face-api.js models loaded successfully");
    } catch (error) {
        console.error("Failed to load face-api.js models:", error);
        errorMessage.value =
            "Gagal memuat model face recognition. Silakan refresh halaman.";
        loadingModels.value = false;
    }
});

// Watch for modal open/close
watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            startCamera();
        } else {
            stopCamera();
        }
    }
);

// Start camera
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

            // Start face detection loop
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

// Real-time face detection loop
const startFaceDetection = () => {
    if (detectionInterval) {
        clearInterval(detectionInterval);
    }

    detectionInterval = setInterval(async () => {
        if (!videoElement.value || !cameraReady.value || capturedPhoto.value) {
            return;
        }

        try {
            const detections = await faceapi
                .detectSingleFace(
                    videoElement.value,
                    new faceapi.TinyFaceDetectorOptions({
                        inputSize: 224,
                        scoreThreshold: 0.5,
                    })
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
    }, 300); // Check every 300ms for better performance
};

// Stop camera
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

// Capture photo
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

    // Flip horizontal for mirror effect
    context.translate(canvas.width, 0);
    context.scale(-1, 1);

    context.drawImage(videoElement.value, 0, 0);

    // Convert to base64
    capturedPhoto.value = canvas.toDataURL("image/jpeg", 0.85);

    // Stop detection when photo captured
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }

    console.log(
        "Photo captured with face descriptor, base64 length:",
        capturedPhoto.value.length
    );
    console.log("Face descriptor length:", faceDescriptor.value?.length);
};

// Retake photo
const retakePhoto = () => {
    capturedPhoto.value = null;
    faceDetected.value = false;
    faceDescriptor.value = null;

    // Restart face detection
    if (modelsLoaded.value && cameraReady.value) {
        startFaceDetection();
    }
};

// Confirm photo
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

    console.log("Emitting photo-captured event with photo and face descriptor");
    emit("photo-captured", {
        photo: capturedPhoto.value,
        faceDescriptor: faceDescriptor.value,
    });
    close();
};

// Close modal
const close = () => {
    stopCamera();
    emit("close");
};

// Cleanup on unmount
onBeforeUnmount(() => {
    stopCamera();
});
</script>

<style scoped>
.mirror {
    transform: scaleX(-1);
}
</style>
