<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    logbooks: {
        type: Array,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
    createLogbookHref: {
        type: String,
        required: true,
    },
    logbooksIndexHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">
                Logbook Terbaru
            </h4>

            <div v-if="logbooks.length === 0" class="text-center py-8">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    Belum ada logbook
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Mulai dengan membuat logbook aktivitas harian pertama Anda.
                </p>
                <div class="mt-6">
                    <Link
                        :href="createLogbookHref"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent text-sm font-medium rounded-md text-white hover:bg-blue-700"
                    >
                        Buat Logbook Pertama
                    </Link>
                </div>
            </div>

            <div v-else class="space-y-4">
                <div
                    v-for="logbook in logbooks.slice(0, 5)"
                    :key="logbook.id"
                    class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
                >
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">
                            {{ logbook.title || "Aktivitas Harian" }}
                        </h5>
                        <p class="text-sm text-gray-500">
                            {{ formatDate(logbook.date) }} •
                            {{ logbook.duration }} jam
                        </p>
                    </div>
                    <span
                        :class="getStatusClass(logbook.status)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                        {{ getStatusText(logbook.status) }}
                    </span>
                </div>

                <div class="text-center pt-4">
                    <Link
                        :href="logbooksIndexHref"
                        class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                    >
                        Lihat Semua Logbook →
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
