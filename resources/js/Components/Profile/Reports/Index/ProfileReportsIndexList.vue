<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    reports: {
        type: Array,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    formatFileSize: {
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
    onDeleteReport: {
        type: Function,
        required: true,
    },
    createHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                Laporan Akhir Saya
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Daftar laporan yang telah Anda upload
            </p>
        </div>

        <div
            v-if="reports && reports.length > 0"
            class="divide-y divide-gray-200"
        >
            <div
                v-for="report in reports"
                :key="report.id"
                class="p-6 hover:bg-gray-50 transition-colors"
            >
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-sm font-medium text-gray-900 truncate"
                                >
                                    {{ report.title }}
                                </h3>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ report.file_name }}
                                </p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-xs text-gray-500">{{
                                        formatFileSize(report.file_size)
                                    }}</span>
                                    <span class="text-xs text-gray-500">{{
                                        formatDate(report.created_at)
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <p
                            v-if="report.description"
                            class="mt-2 text-sm text-gray-600"
                        >
                            {{ report.description }}
                        </p>
                        <div
                            v-if="
                                report.feedback && report.status === 'revision'
                            "
                            class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md"
                        >
                            <p class="text-sm text-yellow-800">
                                <strong>Feedback:</strong> {{ report.feedback }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-4">
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                getStatusClass(report.status),
                            ]"
                        >
                            {{ getStatusText(report.status) }}
                        </span>
                        <div class="flex space-x-2">
                            <a
                                :href="
                                    route('profile.reports.download', report.id)
                                "
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                title="Download"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 10v6m0 0l-4-4m4 4l4-4m-4-4V2"
                                    />
                                </svg>
                            </a>
                            <Link
                                v-if="
                                    report.status === 'draft' ||
                                    report.status === 'revision'
                                "
                                :href="route('profile.reports.edit', report.id)"
                                class="text-yellow-600 hover:text-yellow-700 text-sm font-medium"
                                title="Edit"
                            >
                                <svg
                                    class="w-4 h-4"
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
                            </Link>
                            <button
                                v-if="
                                    report.status === 'draft' ||
                                    report.status === 'revision'
                                "
                                @click="onDeleteReport(report.id)"
                                class="text-red-600 hover:text-red-700 text-sm font-medium"
                                title="Hapus"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="p-12 text-center">
            <div
                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <svg
                    class="w-8 h-8 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Belum Ada Laporan
            </h3>
            <p class="text-gray-600 mb-6">
                Upload laporan akhir magang Anda untuk mendapatkan review dari
                pembimbing.
            </p>
            <Link
                :href="createHref"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
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
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Upload Laporan Pertama
            </Link>
        </div>
    </div>
</template>
