<template>
    <Transition
        enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
    >
        <div
            v-if="visible"
            class="relative overflow-hidden rounded-2xl mb-6 shadow-lg border border-amber-200"
            role="alert"
            aria-live="polite"
        >
            <div class="absolute inset-0 bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50" />
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer pointer-events-none" />

            <div class="relative px-5 py-4 sm:px-6 sm:py-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <div class="w-12 h-12 bg-amber-100 border-2 border-amber-300 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 flex">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75" />
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500" />
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="text-sm font-bold text-amber-900 leading-tight">Lengkapi Profil Anda</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-200 text-amber-800">Wajib</span>
                        </div>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            Untuk menggunakan fitur <strong>Presensi Online</strong>, Anda wajib melakukan registrasi
                            foto profil menggunakan kamera. Foto ini akan digunakan sebagai referensi verifikasi wajah saat presensi.
                        </p>
                    </div>

                    <div class="flex-shrink-0 flex items-center gap-2">
                        <button
                            @click="$emit('go-to-profile')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-amber-600/30 hover:shadow-amber-600/50 hover:-translate-y-0.5 active:translate-y-0"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Daftarkan Wajah
                        </button>
                        <button
                            @click="visible = false"
                            class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-100 rounded-lg transition-colors"
                            aria-label="Tutup"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: true },
});

defineEmits(['go-to-profile']);

const visible = ref(props.show);
watch(() => props.show, (val) => { visible.value = val; });
</script>

<style scoped>
@keyframes shimmer {
    0%   { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}
.animate-shimmer { animation: shimmer 2.5s infinite; }
</style>