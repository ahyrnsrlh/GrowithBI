<template>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <button
                @click="$emit('check-in')"
                :disabled="
                    isProcessing ||
                    (todayAttendance && todayAttendance.check_in)
                "
                class="w-full group relative px-8 py-4 rounded-xl font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                :class="
                    todayAttendance?.check_in
                        ? 'bg-gray-400'
                        : 'bg-[#2F4C9E] hover:bg-[#274089]'
                "
            >
                <svg
                    v-if="isProcessing && actionType === 'check-in'"
                    class="animate-spin h-5 w-5 mr-2"
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
                <svg
                    v-else
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                    />
                </svg>
                <span>
                    {{
                        isProcessing && actionType === "check-in"
                            ? "Memproses..."
                            : todayAttendance?.check_in
                              ? "Sudah Check-in"
                              : "Check-in Sekarang"
                    }}
                </span>
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

        <div>
            <button
                @click="$emit('check-out')"
                :disabled="
                    isProcessing ||
                    !todayAttendance?.check_in ||
                    todayAttendance?.check_out
                "
                class="w-full group relative px-8 py-4 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                :class="
                    todayAttendance?.check_out
                        ? 'bg-gray-400 text-white'
                        : 'bg-[#DAE3F3] hover:bg-[#C5D9F1] text-[#2F4C9E]'
                "
            >
                <svg
                    v-if="isProcessing && actionType === 'check-out'"
                    class="animate-spin h-5 w-5 mr-2"
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
                <svg
                    v-else
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                    />
                </svg>
                <span>
                    {{
                        isProcessing && actionType === "check-out"
                            ? "Memproses..."
                            : todayAttendance?.check_out
                              ? "Sudah Check-out"
                              : "Check-out Sekarang"
                    }}
                </span>
            </button>
            <div
                v-if="
                    todayAttendance?.check_in &&
                    !todayAttendance?.check_out &&
                    !isWithinCheckOutTime
                "
                class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg"
            >
                <p class="text-xs text-yellow-800 text-center">
                    ⚠️ Check-out hanya diperbolehkan antara 16:00 - 18:00 WIB
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    todayAttendance: { type: Object, default: null },
    isProcessing: { type: Boolean, default: false },
    actionType: { type: String, default: "" },
    isWithinCheckInTime: { type: Boolean, default: false },
    isWithinCheckOutTime: { type: Boolean, default: false },
});

defineEmits(["check-in", "check-out"]);
</script>
