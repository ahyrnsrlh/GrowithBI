<template>
    <div>
        <!-- ═══════════════════════════════════════════════════════════════════
             FACE NOT ENROLLED — show enrollment CTA instead of action buttons
        ════════════════════════════════════════════════════════════════════ -->
        <div
            v-if="!faceEnrolled"
            class="rounded-xl border-2 border-dashed border-blue-300 bg-blue-50 p-5 text-center"
        >
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="font-bold text-blue-900 text-sm mb-1">Foto Profil & Biometrik Belum Lengkap</h4>
            <p class="text-xs text-blue-700 mb-4 leading-relaxed">
                Silakan lengkapi foto profil Anda terlebih dahulu menggunakan Live Camera untuk mengaktifkan fitur absensi biometrik.
            </p>
            <Link
                href="/profile"
                class="w-full py-3 px-4 bg-[#2F4C9E] hover:bg-[#274089] text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Lengkapi Foto Profil
            </Link>
        </div>

        <!-- ═══════════════════════════════════════════════════════════════════
             FACE ENROLLED — normal check-in / check-out buttons
        ════════════════════════════════════════════════════════════════════ -->
        <div v-else class="grid md:grid-cols-2 gap-4">
            <!-- Check-in -->
            <div>
                <button
                    @click="$emit('check-in')"
                    :disabled="isProcessing || (todayAttendance && todayAttendance.check_in)"
                    class="w-full group relative px-8 py-4 rounded-xl font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                    :class="todayAttendance?.check_in ? 'bg-gray-400' : 'bg-[#2F4C9E] hover:bg-[#274089]'"
                >
                    <svg
                        v-if="isProcessing && actionType === 'check-in'"
                        class="animate-spin h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>{{
                        isProcessing && actionType === 'check-in'
                            ? 'Memproses...'
                            : todayAttendance?.check_in
                              ? 'Sudah Check-in'
                              : 'Check-in Sekarang'
                    }}</span>
                </button>
                <div
                    v-if="!todayAttendance?.check_in && !isWithinCheckInTime"
                    class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg"
                >
                    <p class="text-xs text-yellow-800 text-center">
                        ⚠️ Check-in hanya diperbolehkan antara 07:30 - 08:00 WIB
                    </p>
                </div>
            </div>

            <!-- Check-out -->
            <div>
                <button
                    @click="$emit('check-out')"
                    :disabled="isProcessing || !todayAttendance?.check_in || todayAttendance?.check_out"
                    class="w-full group relative px-8 py-4 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                    :class="todayAttendance?.check_out
                        ? 'bg-gray-400 text-white'
                        : 'bg-[#DAE3F3] hover:bg-[#C5D9F1] text-[#2F4C9E]'"
                >
                    <svg
                        v-if="isProcessing && actionType === 'check-out'"
                        class="animate-spin h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{
                        isProcessing && actionType === 'check-out'
                            ? 'Memproses...'
                            : todayAttendance?.check_out
                              ? 'Sudah Check-out'
                              : 'Check-out Sekarang'
                    }}</span>
                </button>
                <div
                    v-if="todayAttendance?.check_in && !todayAttendance?.check_out && !isWithinCheckOutTime"
                    class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg"
                >
                    <p class="text-xs text-yellow-800 text-center">
                        ⚠️ Check-out hanya diperbolehkan antara 16:00 - 18:00 WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    todayAttendance:    { type: Object,  default: null },
    isProcessing:       { type: Boolean, default: false },
    actionType:         { type: String,  default: "" },
    isWithinCheckInTime:  { type: Boolean, default: false },
    isWithinCheckOutTime: { type: Boolean, default: false },
    faceEnrolled:       { type: Boolean, default: true },
});

defineEmits(["check-in", "check-out", "enroll-face"]);
</script>
