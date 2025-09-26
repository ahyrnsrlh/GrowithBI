<template>
    <Head title="Detail Laporan" />

    <PesertaLayout
        title="Detail Laporan"
        subtitle="Lihat detail laporan akhir magang Anda"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ report.title || "Detail Laporan" }}
                    </h1>
                    <p class="text-gray-600">
                        Divisi
                        {{ report.application?.division?.name || "Unknown" }}
                    </p>
                </div>
                <Link
                    :href="route('peserta.reports.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                    Kembali
                </Link>
            </div>

            <!-- Report Details Card -->
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
                        <dt class="text-sm font-medium text-gray-500">
                            Deskripsi
                        </dt>
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
                        <dt class="text-sm font-medium text-gray-500">
                            Reviewer
                        </dt>
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

            <!-- Actions -->
            <div
                v-if="report.status === 'draft' || report.status === 'revision'"
                class="flex space-x-3"
            >
                <Link
                    :href="route('peserta.reports.edit', report.id)"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg
                        class="w-4 h-4 mr-2"
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
                    Edit Laporan
                </Link>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

defineProps({
    report: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
    };
    return texts[status] || "Unknown";
};
</script>
