<template>
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div
            class="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200 transition-all duration-300 hover:shadow-lg"
        >
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg
                            class="w-5 h-5 text-green-600"
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
                        <span class="text-sm font-medium text-green-800"
                            >Check-in</span
                        >
                    </div>
                    <div
                        class="text-3xl font-bold transition-colors duration-300"
                        :class="
                            todayAttendance?.check_in
                                ? 'text-green-700'
                                : 'text-gray-400'
                        "
                    >
                        {{
                            todayAttendance?.check_in
                                ? formatTime(todayAttendance.check_in)
                                : "--:--"
                        }}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div
                        v-if="todayAttendance?.check_in"
                        class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-white"
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
                    </div>
                    <div
                        v-else
                        class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="relative bg-gradient-to-br from-[#E8EEF7] to-[#D6E3F5] rounded-xl p-6 border-2 border-[#B4C7E7] transition-all duration-300 hover:shadow-lg"
        >
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg
                            class="w-5 h-5 text-[#2F4C9E]"
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
                        <span class="text-sm font-medium text-[#2F4C9E]"
                            >Check-out</span
                        >
                    </div>
                    <div
                        class="text-3xl font-bold transition-colors duration-300"
                        :class="
                            todayAttendance?.check_out
                                ? 'text-[#2F4C9E]'
                                : 'text-gray-400'
                        "
                    >
                        {{
                            todayAttendance?.check_out
                                ? formatTime(todayAttendance.check_out)
                                : "--:--"
                        }}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div
                        v-if="todayAttendance?.check_out"
                        class="w-12 h-12 bg-[#2F4C9E] rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-white"
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
                    </div>
                    <div
                        v-else
                        class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    todayAttendance: { type: Object, default: null },
    formatTime: { type: Function, required: true },
});
</script>
