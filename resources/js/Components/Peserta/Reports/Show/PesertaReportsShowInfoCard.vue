<script setup>
defineProps({
    report: {
        type: Object,
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
});
</script>

<template>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Informasi Laporan
                </h3>
                <span
                    :class="getStatusClass(report.status)"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                    {{ getStatusText(report.status) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6 space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Judul</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ report.title || "-" }}
                </dd>
            </div>

            <div v-if="report.description">
                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ report.description }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">File</dt>
                <dd class="mt-1">
                    <a
                        :href="route('reports.download', report.id)"
                        class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm"
                    >
                        <svg
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        {{ report.file_name || "Download File" }}
                    </a>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">
                    Tanggal Upload
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ formatDate(report.created_at) }}
                </dd>
            </div>

            <div v-if="report.reviewer">
                <dt class="text-sm font-medium text-gray-500">Reviewer</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ report.reviewer.name }}
                </dd>
            </div>

            <div v-if="report.review_notes">
                <dt class="text-sm font-medium text-gray-500">
                    Catatan Review
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ report.review_notes }}
                </dd>
            </div>
        </div>
    </div>
</template>
