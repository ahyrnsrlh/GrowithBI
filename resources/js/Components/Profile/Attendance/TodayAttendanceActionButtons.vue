<template>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <button
                @click="$emit('check-in')"
                :disabled="
                    isProcessing ||
                    (todayAttendance && todayAttendance.check_in)
                "
                class="w-full h-14 rounded-xl font-semibold text-white transition-all duration-200 flex items-center justify-center gap-2 disabled:cursor-not-allowed"
                :class="
                    todayAttendance?.check_in
                        ? 'bg-gray-300 text-gray-500'
                        : 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40'
                "
            >
                <svg
                    v-if="isProcessing && actionType === 'check-in'"
                    class="animate-spin h-5 w-5"
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
                class="mt-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg"
            >
                <p
                    class="text-xs text-amber-700 text-center flex items-center justify-center gap-1"
                >
                    <svg
                        class="w-3.5 h-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    Check-in hanya diperbolehkan antara 07:30 - 08:00 WIB
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
                class="w-full h-14 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 disabled:cursor-not-allowed"
                :class="
                    todayAttendance?.check_out
                        ? 'bg-gray-300 text-gray-500'
                        : !todayAttendance?.check_in
                          ? 'bg-gray-100 text-gray-400 border-2 border-dashed border-gray-300'
                          : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40'
                "
            >
                <svg
                    v-if="isProcessing && actionType === 'check-out'"
                    class="animate-spin h-5 w-5"
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
                class="mt-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg"
            >
                <p
                    class="text-xs text-amber-700 text-center flex items-center justify-center gap-1"
                >
                    <svg
                        class="w-3.5 h-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    Check-out hanya diperbolehkan antara 16:00 - 18:00 WIB
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    todayAttendance: {
        type: Object,
        default: null,
    },
    isProcessing: {
        type: Boolean,
        default: false,
    },
    actionType: {
        type: String,
        default: "",
    },
    isWithinCheckInTime: {
        type: Boolean,
        default: false,
    },
    isWithinCheckOutTime: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["check-in", "check-out"]);
</script>
