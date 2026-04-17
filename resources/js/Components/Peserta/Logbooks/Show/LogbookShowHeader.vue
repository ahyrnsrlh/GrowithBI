<template>
    <div
        class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 -mt-12 mb-8"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-6">
                <Link
                    :href="route('peserta.logbooks.index')"
                    class="inline-flex items-center text-blue-100 hover:text-white transition-colors group"
                >
                    <svg
                        class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    <span class="font-medium">Kembali ke Daftar Logbook</span>
                </Link>
            </div>

            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div
                            class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center"
                        >
                            <svg
                                class="w-7 h-7 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-1">
                                {{ logbook.title }}
                            </h1>
                            <p class="text-blue-100 text-sm font-medium">
                                Laporan kegiatan harian peserta magang
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <div class="flex items-center text-white/90">
                            <svg
                                class="w-5 h-5 mr-2"
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
                            <span class="text-sm font-medium">{{
                                formatDateShort(logbook.date)
                            }}</span>
                        </div>
                        <div class="flex items-center text-white/90">
                            <svg
                                class="w-5 h-5 mr-2"
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
                            <span class="text-sm font-medium"
                                >{{ logbook.duration }} jam</span
                            >
                        </div>
                        <div>
                            <span
                                :class="getStatusBadgeClass(logbook.status)"
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm"
                            >
                                <span
                                    class="w-2 h-2 rounded-full mr-2"
                                    :class="getStatusDotClass(logbook.status)"
                                ></span>
                                {{ getStatusText(logbook.status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="hidden md:block">
                    <Link
                        :href="route('peserta.logbooks.edit', logbook.id)"
                        class="inline-flex items-center px-5 py-2.5 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl font-semibold"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                        Edit Logbook
                    </Link>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-blue-100"
                        >Progress Review</span
                    >
                    <span class="text-xs font-semibold text-blue-100"
                        >{{ getProgressPercentage(logbook.status) }}%</span
                    >
                </div>
                <div
                    class="w-full bg-blue-900/30 rounded-full h-2 overflow-hidden"
                >
                    <div
                        class="bg-gradient-to-r from-blue-400 to-blue-300 h-2 rounded-full transition-all duration-500"
                        :style="{
                            width: getProgressPercentage(logbook.status) + '%',
                        }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    logbook: { type: Object, required: true },
    canEdit: { type: Boolean, default: false },
    formatDateShort: { type: Function, required: true },
    getStatusBadgeClass: { type: Function, required: true },
    getStatusDotClass: { type: Function, required: true },
    getStatusText: { type: Function, required: true },
    getProgressPercentage: { type: Function, required: true },
});
</script>
