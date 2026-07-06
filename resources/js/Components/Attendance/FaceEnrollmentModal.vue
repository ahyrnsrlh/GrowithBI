<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="handleClose">
            <!-- Backdrop -->
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-950 bg-opacity-80 backdrop-blur-sm transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 scale-95"
                        enter-to="opacity-100 translate-y-0 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 scale-100"
                        leave-to="opacity-0 translate-y-4 scale-95"
                    >
                        <DialogPanel class="relative w-full max-w-lg transform rounded-2xl bg-white shadow-2xl transition-all overflow-hidden">

                            <!-- Header -->
                            <div class="bg-gradient-to-r from-[#1e3a7e] to-[#2F4C9E] px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <DialogTitle as="h3" class="text-lg font-bold text-white">
                                            Pendaftaran Wajah
                                        </DialogTitle>
                                        <p class="text-blue-200 text-xs mt-0.5">Daftarkan wajah Anda untuk absensi biometrik</p>
                                    </div>
                                </div>

                                <!-- Step indicator -->
                                <div class="flex items-center gap-2 mt-4">
                                    <div
                                        v-for="s in 4"
                                        :key="s"
                                        class="h-1.5 rounded-full flex-1 transition-all duration-500"
                                        :class="effectiveStep >= s ? 'bg-white' : 'bg-white/30'"
                                    />
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="px-6 py-5">

                                <!-- ERROR STATE -->
                                <div v-if="step === -1" class="text-center py-6">
                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-2">Pendaftaran Gagal</h4>
                                    <p class="text-sm text-gray-600 mb-6">{{ errorMessage || 'Terjadi kesalahan. Silakan coba lagi.' }}</p>
                                    <button @click="retry" class="w-full py-3 px-4 bg-[#2F4C9E] hover:bg-[#274089] text-white rounded-xl font-medium transition">
                                        Coba Lagi
                                    </button>
                                </div>

                                <!-- STEP 1: Camera warmup -->
                                <div v-else-if="step === 1">
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-900">Langkah 1: Posisikan Wajah</h4>
                                        <p class="text-sm text-gray-500 mt-1">Pastikan wajah Anda terlihat jelas dan pencahayaan cukup.</p>
                                    </div>

                                    <!-- Camera view -->
                                    <div class="relative bg-gray-900 rounded-xl overflow-hidden" style="aspect-ratio: 4/3">
                                        <video
                                            ref="videoRef"
                                            autoplay
                                            playsinline
                                            class="w-full h-full object-cover"
                                            style="transform: scaleX(-1)"
                                        />
                                        <canvas
                                            ref="canvasRef"
                                            class="absolute inset-0 w-full h-full"
                                            style="transform: scaleX(-1)"
                                        />

                                        <!-- Loading overlay -->
                                        <div v-if="loadingModels" class="absolute inset-0 bg-gray-900/80 flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <div class="w-10 h-10 border-3 border-blue-400 border-t-transparent rounded-full animate-spin mx-auto mb-3" />
                                                <p class="text-sm font-medium">Memuat AI Model...</p>
                                            </div>
                                        </div>

                                        <!-- Face status badge -->
                                        <div
                                            v-if="!loadingModels"
                                            class="absolute top-3 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full text-white text-xs font-semibold shadow-lg transition-all duration-300"
                                            :class="faceDetected ? 'bg-emerald-600' : 'bg-amber-600'"
                                        >
                                            {{ faceDetected ? '✓ Wajah Terdeteksi' : '🔍 Cari Wajah...' }}
                                        </div>

                                        <!-- Oval guide -->
                                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                            <div
                                                class="w-44 h-56 border-4 border-dashed rounded-full transition-colors duration-300"
                                                :class="faceDetected ? 'border-emerald-400' : 'border-white/40'"
                                            />
                                        </div>
                                    </div>

                                    <div class="mt-4 flex gap-3">
                                        <button @click="handleClose" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition text-sm">
                                            Batal
                                        </button>
                                        <button
                                            @click="proceedToLiveness"
                                            :disabled="!faceDetected"
                                            class="flex-1 py-3 font-semibold rounded-xl text-white text-sm transition"
                                            :class="faceDetected
                                                ? 'bg-[#2F4C9E] hover:bg-[#274089] shadow-lg shadow-blue-500/30'
                                                : 'bg-gray-300 cursor-not-allowed text-gray-400'"
                                        >
                                            Lanjutkan →
                                        </button>
                                    </div>
                                </div>

                                <!-- STEP 2: Liveness challenge -->
                                <div v-else-if="step === 2">
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-900">Langkah 2: Verifikasi Kehadiran</h4>
                                        <p class="text-sm text-gray-500 mt-1">Lakukan instruksi berikut untuk membuktikan Anda hadir secara langsung.</p>
                                    </div>

                                    <!-- Challenge card -->
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-4 mb-4 text-center">
                                        <div class="text-4xl mb-2">{{ currentChallenge?.icon }}</div>
                                        <p class="font-bold text-blue-900 text-base">{{ currentChallenge?.label }}</p>
                                    </div>

                                    <!-- Camera (small) -->
                                    <div class="relative bg-gray-900 rounded-xl overflow-hidden mb-4" style="aspect-ratio: 16/9">
                                        <video
                                            ref="videoRef"
                                            autoplay playsinline
                                            class="w-full h-full object-cover"
                                            style="transform: scaleX(-1)"
                                        />
                                        <canvas ref="canvasRef" class="absolute inset-0 w-full h-full" style="transform: scaleX(-1)" />
                                        <!-- Passed overlay -->
                                        <div v-if="challengePassed" class="absolute inset-0 bg-emerald-900/60 flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <p class="font-bold mt-1">Verifikasi Berhasil!</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress bar -->
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div
                                            class="h-full bg-emerald-500 rounded-full transition-all duration-300"
                                            :style="{ width: challengeProgress + '%' }"
                                        />
                                    </div>
                                    <p class="text-xs text-gray-500 text-center mt-2">
                                        {{ challengePassed ? 'Mengambil sampel wajah...' : 'Lakukan gerakan untuk melanjutkan' }}
                                    </p>
                                </div>

                                <!-- STEP 3: Multi-sample capture -->
                                <div v-else-if="step === 3" class="text-center py-8">
                                    <div class="w-20 h-20 mx-auto mb-5 relative">
                                        <svg class="w-20 h-20 -rotate-90" viewBox="0 0 80 80">
                                            <circle cx="40" cy="40" r="34" fill="none" stroke="#e5e7eb" stroke-width="6" />
                                            <circle
                                                cx="40" cy="40" r="34" fill="none"
                                                stroke="#2F4C9E" stroke-width="6"
                                                stroke-dasharray="213.6"
                                                :stroke-dashoffset="213.6 - (213.6 * captureCount / REQUIRED_SAMPLES)"
                                                class="transition-all duration-500"
                                            />
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-xl font-bold text-[#2F4C9E]">{{ captureCount }}/{{ REQUIRED_SAMPLES }}</span>
                                        </div>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg">Mengambil Sampel Wajah</h4>
                                    <p class="text-sm text-gray-500 mt-2">Tetap hadap ke kamera...</p>
                                </div>

                                <!-- STEP 4: Success -->
                                <div v-else-if="step === 4" class="text-center py-6">
                                    <div class="relative w-20 h-20 mx-auto mb-5">
                                        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center animate-bounce-in">
                                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-xl mb-2">Wajah Berhasil Didaftarkan! 🎉</h4>
                                    <p class="text-sm text-gray-600 mb-1">Data biometrik Anda telah disimpan dengan aman.</p>
                                    <p class="text-xs text-gray-400 mb-6">Anda sekarang dapat menggunakan fitur absensi.</p>
                                    <button
                                        @click="handleClose"
                                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition shadow-lg shadow-emerald-500/30"
                                    >
                                        Mulai Absensi
                                    </button>
                                </div>

                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed, watch, onMounted, onBeforeUnmount } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useFaceEnrollment } from "@/Composables/Attendance/useFaceEnrollment";

const props = defineProps({
    show: { type: Boolean, default: false },
});

const emit = defineEmits(["close", "enrolled"]);

const {
    step,
    errorMessage,
    isSubmitting,
    enrolledAt,
    videoRef,
    canvasRef,
    cameraReady,
    loadingModels,
    modelsLoaded,
    faceDetected,
    currentChallenge,
    challengePassed,
    challengeProgress,
    capturedSamples,
    captureCount,
    REQUIRED_SAMPLES,
    loadModels,
    startCamera,
    stopCamera,
    proceedToLiveness,
    retry,
} = useFaceEnrollment({
    onSuccess: () => emit("enrolled"),
});

// Map error / success steps to display step numbers
const effectiveStep = computed(() => {
    if (step.value === -1) return 0;
    return step.value;
});

const handleClose = () => {
    if (step.value !== 3 && !isSubmitting.value) {
        stopCamera();
        emit("close");
    }
};

watch(
    () => props.show,
    async (val) => {
        if (val) {
            await loadModels();
            await startCamera();
        } else {
            stopCamera();
        }
    }
);

onBeforeUnmount(() => stopCamera());
</script>

<style scoped>
@keyframes bounce-in {
    0% { transform: scale(0.5); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); }
}
.animate-bounce-in {
    animation: bounce-in 0.5s ease-out forwards;
}
</style>
