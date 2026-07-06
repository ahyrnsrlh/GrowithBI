<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="close">
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
                        <DialogPanel class="relative w-full max-w-2xl transform rounded-2xl bg-gray-950 shadow-2xl transition-all overflow-hidden">

                            <!-- Header -->
                            <div class="px-5 pt-5 pb-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-[#2F4C9E] rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <DialogTitle as="h3" class="text-base font-bold text-white">{{ title }}</DialogTitle>
                                </div>
                                <!-- Live / Captured badge -->
                                <div
                                    class="text-xs font-bold px-3 py-1 rounded-full"
                                    :class="capturedPhoto ? 'bg-emerald-600/30 text-emerald-400 border border-emerald-600/40' : 'bg-red-600/30 text-red-400 border border-red-600/40 flex items-center gap-1.5'"
                                >
                                    <span v-if="!capturedPhoto" class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse inline-block" />
                                    {{ capturedPhoto ? '✓ FOTO' : 'LIVE' }}
                                </div>
                            </div>

                            <!-- Camera View -->
                            <div class="relative mx-5 rounded-xl overflow-hidden bg-black" style="aspect-ratio: 16/9">
                                <!-- Live video -->
                                <video
                                    v-show="!capturedPhoto"
                                    ref="videoElement"
                                    autoplay
                                    playsinline
                                    class="w-full h-full object-cover mirror"
                                />
                                <!-- Canvas overlay (bounding box) -->
                                <canvas
                                    v-show="!capturedPhoto"
                                    ref="canvasElement"
                                    class="absolute inset-0 w-full h-full pointer-events-none mirror"
                                />

                                <!-- Captured photo preview -->
                                <img
                                    v-if="capturedPhoto"
                                    :src="capturedPhoto"
                                    alt="Captured"
                                    class="w-full h-full object-cover"
                                />

                                <!-- Loading models overlay -->
                                <div
                                    v-if="loadingModels"
                                    class="absolute inset-0 flex items-center justify-center bg-black/70"
                                >
                                    <div class="text-center text-white">
                                        <div class="w-12 h-12 border-3 border-blue-400 border-t-transparent rounded-full animate-spin mx-auto mb-3" />
                                        <p class="text-sm font-medium">Memuat AI Model...</p>
                                        <p class="text-xs text-white/60 mt-1">Mohon tunggu sebentar</p>
                                    </div>
                                </div>

                                <!-- Camera loading overlay -->
                                <div
                                    v-else-if="!cameraReady && !capturedPhoto"
                                    class="absolute inset-0 flex items-center justify-center bg-black/60"
                                >
                                    <div class="text-center text-white">
                                        <div class="w-10 h-10 border-3 border-blue-400 border-t-transparent rounded-full animate-spin mx-auto mb-2" />
                                        <p class="text-sm">Memuat kamera...</p>
                                    </div>
                                </div>

                                <!-- Top HUD: confidence badge + GPS -->
                                <div
                                    v-if="cameraReady && !capturedPhoto && !loadingModels"
                                    class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2"
                                >
                                    <!-- Face confidence badge -->
                                    <div
                                        class="px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-lg flex items-center gap-2 transition-all duration-300"
                                        :class="confidenceColor"
                                    >
                                        <div v-if="faceDetected" class="w-2 h-2 rounded-full bg-white animate-pulse" />
                                        <span>{{ confidenceLabel }}</span>
                                    </div>

                                    <!-- GPS status -->
                                    <div
                                        v-if="locationStatus"
                                        class="px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-sm text-white text-xs flex items-center gap-1.5 max-w-[45%]"
                                    >
                                        <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ locationStatus }}</span>
                                    </div>
                                </div>

                                <!-- Auto-capture countdown overlay -->
                                <div
                                    v-if="countdown !== null && !capturedPhoto"
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <div class="w-24 h-24 rounded-full bg-[#2F4C9E]/80 backdrop-blur-sm flex items-center justify-center">
                                        <span class="text-5xl font-black text-white countdown-number">{{ countdown }}</span>
                                    </div>
                                </div>

                                <!-- Face guide frame -->
                                <div
                                    v-if="cameraReady && !capturedPhoto"
                                    class="absolute inset-0 pointer-events-none flex items-center justify-center"
                                >
                                    <div
                                        class="w-44 h-52 border-4 rounded-full transition-all duration-500"
                                        :class="[
                                            faceDetected ? 'border-emerald-400 opacity-80' : 'border-white/30 border-dashed opacity-60'
                                        ]"
                                    />
                                </div>
                            </div>

                            <!-- Error message -->
                            <div v-if="errorMessage" class="mx-5 mt-3 p-3 bg-red-900/30 border border-red-700/50 rounded-xl">
                                <p class="text-sm text-red-300">{{ errorMessage }}</p>
                            </div>

                            <!-- Instructions -->
                            <div class="px-5 mt-3 text-center min-h-[2rem]">
                                <p v-if="capturedPhoto" class="text-sm text-emerald-400 font-medium">
                                    ✓ Foto berhasil diambil! Periksa dan konfirmasi untuk melanjutkan.
                                </p>
                                <p v-else-if="countdown !== null" class="text-sm text-blue-300 font-medium">
                                    Wajah stabil — mengambil foto otomatis dalam {{ countdown }}...
                                </p>
                                <p v-else-if="!loadingModels" class="text-sm text-gray-400">
                                    <span v-if="!faceDetected">Posisikan wajah Anda di dalam lingkaran dan pastikan pencahayaan cukup</span>
                                    <span v-else class="text-emerald-400">✓ Wajah terdeteksi — tahan posisi untuk auto-capture atau klik "Ambil Foto"</span>
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="px-5 py-5 flex gap-3">
                                <button
                                    type="button"
                                    @click="close"
                                    class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-xl transition text-sm"
                                >
                                    Batal
                                </button>

                                <!-- Retake button -->
                                <button
                                    v-if="capturedPhoto"
                                    type="button"
                                    @click="retakePhoto"
                                    class="flex-1 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-xl transition text-sm flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Ambil Ulang
                                </button>

                                <!-- Capture button -->
                                <button
                                    v-if="!capturedPhoto"
                                    type="button"
                                    @click="capturePhoto"
                                    :disabled="!cameraReady || !faceDetected || loadingModels"
                                    class="flex-1 py-3 font-semibold rounded-xl transition text-sm flex items-center justify-center gap-2"
                                    :class="cameraReady && faceDetected && !loadingModels
                                        ? 'bg-[#2F4C9E] hover:bg-[#274089] text-white shadow-lg shadow-blue-900/40'
                                        : 'bg-gray-700 cursor-not-allowed text-gray-500'"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ faceDetected && !loadingModels ? 'Ambil Foto' : 'Deteksi Wajah...' }}
                                </button>

                                <!-- Confirm button -->
                                <button
                                    v-if="capturedPhoto"
                                    type="button"
                                    @click="confirmPhoto"
                                    class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/40"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useSecureCameraModal } from "@/Composables/Attendance/useSecureCameraModal";

const props = defineProps({
    show:           { type: Boolean, default: false },
    title:          { type: String, default: "📸 Foto Absensi" },
    locationStatus: { type: String, default: null }, // e.g. "GPS Terdeteksi (±15m)"
});

const emit = defineEmits(["close", "photo-captured"]);

const {
    videoElement,
    canvasElement,
    capturedPhoto,
    cameraReady,
    errorMessage,
    modelsLoaded,
    faceDetected,
    loadingModels,
    confidence,
    confidenceLabel,
    confidenceColor,
    countdown,
    capturePhoto,
    retakePhoto,
    confirmPhoto,
    close,
} = useSecureCameraModal(props, emit);
</script>

<style scoped>
.mirror {
    transform: scaleX(-1);
}
@keyframes countdown-pop {
    0%   { transform: scale(1.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.countdown-number {
    animation: countdown-pop 0.3s ease-out;
}
</style>
