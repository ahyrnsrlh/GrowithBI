<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="closeModal">
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
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center sm:p-0"
                >
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
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6"
                        >
                            <div>
                                <div class="text-center">
                                    <DialogTitle
                                        as="h3"
                                        class="text-lg font-semibold leading-6 text-gray-900 mb-4"
                                    >
                                        {{ title }}
                                    </DialogTitle>

                                    <div class="mt-4">
                                        <!-- Camera Preview -->
                                        <div
                                            v-if="!capturedImage"
                                            class="relative bg-black rounded-lg overflow-hidden"
                                        >
                                            <video
                                                ref="video"
                                                autoplay
                                                playsinline
                                                class="w-full h-auto max-h-96"
                                                :class="{ mirror: true }"
                                            ></video>

                                            <!-- Camera overlay guide -->
                                            <div
                                                class="absolute inset-0 flex items-center justify-center pointer-events-none"
                                            >
                                                <div
                                                    class="border-4 border-white/50 rounded-full w-64 h-64"
                                                ></div>
                                            </div>
                                        </div>

                                        <!-- Captured Image Preview -->
                                        <div v-else class="relative">
                                            <img
                                                :src="capturedImage"
                                                alt="Captured photo"
                                                class="w-full h-auto max-h-96 rounded-lg"
                                            />
                                        </div>

                                        <!-- Canvas for capturing (hidden) -->
                                        <canvas
                                            ref="canvas"
                                            class="hidden"
                                        ></canvas>

                                        <!-- Error Message -->
                                        <div
                                            v-if="errorMessage"
                                            class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg"
                                        >
                                            <p class="text-sm text-red-600">
                                                {{ errorMessage }}
                                            </p>
                                        </div>

                                        <!-- Instructions -->
                                        <div
                                            v-if="
                                                !capturedImage && !errorMessage
                                            "
                                            class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg"
                                        >
                                            <p class="text-sm text-blue-600">
                                                Posisikan wajah Anda di dalam
                                                lingkaran panduan dan pastikan
                                                pencahayaan cukup.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
                            >
                                <!-- Capture/Retake Button -->
                                <button
                                    v-if="!capturedImage"
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 sm:col-start-2"
                                    @click="capturePhoto"
                                    :disabled="!cameraReady"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Ambil Foto
                                </button>

                                <!-- Confirm Button -->
                                <button
                                    v-if="capturedImage"
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 sm:col-start-2"
                                    @click="confirmPhoto"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Gunakan Foto Ini
                                </button>

                                <!-- Retake Button -->
                                <button
                                    v-if="capturedImage"
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
                                    @click="retakePhoto"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Ambil Ulang
                                </button>

                                <!-- Cancel Button -->
                                <button
                                    v-if="!capturedImage"
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
                                    @click="closeModal"
                                >
                                    Batal
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
import { ref, watch, onBeforeUnmount } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "Ambil Foto untuk Absensi",
    },
});

const emit = defineEmits(["close", "captured"]);

const video = ref(null);
const canvas = ref(null);
const capturedImage = ref(null);
const cameraReady = ref(false);
const errorMessage = ref("");
let stream = null;

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

        // Request camera access
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "user",
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
            audio: false,
        });

        if (video.value) {
            video.value.srcObject = stream;
            video.value.onloadedmetadata = () => {
                cameraReady.value = true;
            };
        }
    } catch (error) {
        console.error("Error accessing camera:", error);
        if (error.name === "NotAllowedError") {
            errorMessage.value =
                "Akses kamera ditolak. Mohon izinkan akses kamera di pengaturan browser Anda.";
        } else if (error.name === "NotFoundError") {
            errorMessage.value =
                "Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.";
        } else {
            errorMessage.value = "Gagal mengakses kamera. Silakan coba lagi.";
        }
    }
};

// Stop camera
const stopCamera = () => {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
    }
    if (video.value) {
        video.value.srcObject = null;
    }
    cameraReady.value = false;
    capturedImage.value = null;
    errorMessage.value = "";
};

// Capture photo
const capturePhoto = () => {
    if (!video.value || !canvas.value) return;

    const context = canvas.value.getContext("2d");
    canvas.value.width = video.value.videoWidth;
    canvas.value.height = video.value.videoHeight;

    // Flip horizontally for mirror effect
    context.translate(canvas.value.width, 0);
    context.scale(-1, 1);

    context.drawImage(video.value, 0, 0);

    // Convert to base64
    capturedImage.value = canvas.value.toDataURL("image/jpeg", 0.8);
};

// Retake photo
const retakePhoto = () => {
    capturedImage.value = null;
};

// Confirm photo
const confirmPhoto = () => {
    emit("captured", capturedImage.value);
    closeModal();
};

// Close modal
const closeModal = () => {
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
