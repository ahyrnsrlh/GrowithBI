<template>
    <div class="grid md:grid-cols-2 gap-4 mb-6">
        <div
            class="relative rounded-xl p-5 border-2 transition-all duration-300"
            :class="
                todayAttendance?.check_in
                    ? 'bg-emerald-50/50 border-emerald-200'
                    : 'bg-gray-50 border-gray-200'
            "
        >
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center"
                            :class="
                                todayAttendance?.check_in
                                    ? 'bg-emerald-100'
                                    : 'bg-gray-200'
                            "
                        >
                            <svg
                                class="w-4 h-4"
                                :class="
                                    todayAttendance?.check_in
                                        ? 'text-emerald-600'
                                        : 'text-gray-500'
                                "
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
                        </div>
                        <span
                            class="text-sm font-medium"
                            :class="
                                todayAttendance?.check_in
                                    ? 'text-emerald-700'
                                    : 'text-gray-600'
                            "
                        >
                            Check-in
                        </span>
                    </div>
                    <p
                        class="text-3xl font-bold tracking-tight"
                        :class="
                            todayAttendance?.check_in
                                ? 'text-emerald-700'
                                : 'text-gray-300'
                        "
                    >
                        {{
                            todayAttendance?.check_in
                                ? formatTime(todayAttendance.check_in)
                                : "--:--"
                        }}
                    </p>
                </div>
                <div
                    class="w-14 h-14 rounded-full flex items-center justify-center"
                    :class="
                        todayAttendance?.check_in
                            ? 'bg-emerald-500'
                            : 'bg-gray-200'
                    "
                >
                    <svg
                        v-if="todayAttendance?.check_in"
                        class="w-7 h-7 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-7 h-7 text-gray-400"
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

        <div
            class="relative rounded-xl p-5 border-2 transition-all duration-300"
            :class="
                todayAttendance?.check_out
                    ? 'bg-blue-50/50 border-blue-200'
                    : 'bg-gray-50 border-gray-200'
            "
        >
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center"
                            :class="
                                todayAttendance?.check_out
                                    ? 'bg-blue-100'
                                    : 'bg-gray-200'
                            "
                        >
                            <svg
                                class="w-4 h-4"
                                :class="
                                    todayAttendance?.check_out
                                        ? 'text-blue-600'
                                        : 'text-gray-500'
                                "
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
                        </div>
                        <span
                            class="text-sm font-medium"
                            :class="
                                todayAttendance?.check_out
                                    ? 'text-blue-700'
                                    : 'text-gray-600'
                            "
                        >
                            Check-out
                        </span>
                    </div>
                    <p
                        class="text-3xl font-bold tracking-tight"
                        :class="
                            todayAttendance?.check_out
                                ? 'text-blue-700'
                                : 'text-gray-300'
                        "
                    >
                        {{
                            todayAttendance?.check_out
                                ? formatTime(todayAttendance.check_out)
                                : "--:--"
                        }}
                    </p>
                </div>
                <div
                    class="w-14 h-14 rounded-full flex items-center justify-center"
                    :class="
                        todayAttendance?.check_out
                            ? 'bg-blue-500'
                            : 'bg-gray-200'
                    "
                >
                    <svg
                        v-if="todayAttendance?.check_out"
                        class="w-7 h-7 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-7 h-7 text-gray-400"
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
</template>

<script setup>
defineProps({
    todayAttendance: {
        type: Object,
        default: null,
    },
    formatTime: {
        type: Function,
        required: true,
    },
});
</script>
