<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    formatDateShort: {
        type: Function,
        required: true,
    },
    getStatusBadgeClass: {
        type: Function,
        required: true,
    },
    getStatusDotClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
    getProgressPercentage: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="p-6 sm:p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1
                        class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"
                    >
                        {{ logbook.title }}
                    </h1>
                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-1.5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDateShort(logbook.date) }}
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-1.5 text-gray-400"
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
                            {{ logbook.duration }} jam
                        </div>
                    </div>
                </div>
                <span
                    :class="getStatusBadgeClass(logbook.status)"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap"
                >
                    <span
                        class="w-2 h-2 rounded-full mr-2"
                        :class="getStatusDotClass(logbook.status)"
                    ></span>
                    {{ getStatusText(logbook.status) }}
                </span>
            </div>

            <div v-if="logbook.status !== 'draft'" class="mb-6">
                <div
                    class="flex items-center justify-between text-xs text-gray-500 mb-2"
                >
                    <span>Progress</span>
                    <span class="font-semibold"
                        >{{ getProgressPercentage(logbook.status) }}%</span
                    >
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div
                        class="bg-blue-600 h-1.5 rounded-full transition-all duration-700"
                        :style="{
                            width: getProgressPercentage(logbook.status) + '%',
                        }"
                    ></div>
                </div>
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <div class="space-y-6">
                <div>
                    <div class="flex items-center mb-3">
                        <div
                            class="w-1 h-5 bg-blue-600 rounded-full mr-3"
                        ></div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Aktivitas yang Dilakukan
                        </h2>
                    </div>
                    <p
                        class="text-gray-700 leading-relaxed whitespace-pre-line"
                    >
                        {{ logbook.activities }}
                    </p>
                </div>

                <div v-if="logbook.learning_points">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-1 h-5 bg-emerald-600 rounded-full mr-3"
                        ></div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Poin Pembelajaran
                        </h2>
                    </div>
                    <p
                        class="text-gray-700 leading-relaxed whitespace-pre-line"
                    >
                        {{ logbook.learning_points }}
                    </p>
                </div>

                <div v-if="logbook.challenges">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-1 h-5 bg-amber-600 rounded-full mr-3"
                        ></div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Tantangan yang Dihadapi
                        </h2>
                    </div>
                    <p
                        class="text-gray-700 leading-relaxed whitespace-pre-line"
                    >
                        {{ logbook.challenges }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
