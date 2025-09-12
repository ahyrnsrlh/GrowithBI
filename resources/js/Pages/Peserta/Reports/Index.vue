<template>
    <Head title="Laporan Magang" />

    <PesertaLayout
        title="Laporan Magang"
        subtitle="Kelola dan buat laporan progress magang Anda"
    >
        <div class="space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
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
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Logbook
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_logbooks }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                            >
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
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Logbook Disetujui
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.approved_logbooks }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-yellow-600"
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
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Jam
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_hours || 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-purple-600"
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
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Durasi Magang
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.internship_duration || 0 }} hari
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internship Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Informasi Magang
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Divisi
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ stats.division }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">
                            Tanggal Mulai
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ formatDate(stats.start_date) }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">
                        Laporan Magang
                    </h3>
                    <p class="text-sm text-gray-500">
                        Buat dan kelola laporan progress magang Anda
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button
                        @click="generateReport"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Generate Laporan
                    </button>
                    <Link
                        :href="route('peserta.reports.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        Buat Laporan Baru
                    </Link>
                </div>
            </div>

            <!-- Progress Summary -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        Ringkasan Progress
                    </h4>

                    <!-- Progress Chart/Summary -->
                    <div class="space-y-4">
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                        >
                            <div>
                                <h5 class="font-medium text-gray-900">
                                    Tingkat Penyelesaian Logbook
                                </h5>
                                <p class="text-sm text-gray-500">
                                    {{ stats.approved_logbooks }} dari
                                    {{ stats.total_logbooks }} logbook disetujui
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{
                                        Math.round(
                                            (stats.approved_logbooks /
                                                Math.max(
                                                    stats.total_logbooks,
                                                    1
                                                )) *
                                                100
                                        )
                                    }}%
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                        >
                            <div>
                                <h5 class="font-medium text-gray-900">
                                    Total Jam Magang
                                </h5>
                                <p class="text-sm text-gray-500">
                                    Waktu yang telah dihabiskan untuk kegiatan
                                    magang
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ stats.total_hours || 0 }} jam
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Logbooks Summary -->
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
                            Mulai dengan membuat logbook aktivitas harian
                            pertama Anda.
                        </p>
                        <div class="mt-6">
                            <Link
                                :href="route('peserta.logbooks.create')"
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
                                :href="route('peserta.logbooks.index')"
                                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                            >
                                Lihat Semua Logbook →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

defineProps({
    stats: Object,
    logbooks: Array,
    application: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
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

const generateReport = () => {
    // Future implementation for generating PDF/Excel reports
    alert("Fitur generate laporan akan segera tersedia!");
};
</script>
