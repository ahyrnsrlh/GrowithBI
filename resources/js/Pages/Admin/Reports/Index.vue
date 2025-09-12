<template>
    <AdminLayout
        title="Laporan Akhir"
        subtitle="Manajemen dan evaluasi laporan akhir peserta magang"
    >
        <div class="space-y-6">
            <!-- Key Statistics for Final Reports -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Final Reports -->
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">
                                Total Laporan
                            </p>
                            <p class="text-3xl font-bold">
                                {{ stats.total_final_reports || 0 }}
                            </p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                            <svg
                                class="w-8 h-8"
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
                </div>

                <!-- Reports Submitted -->
                <div
                    class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">
                                Sudah Diserahkan
                            </p>
                            <p class="text-3xl font-bold">
                                {{ stats.submitted_reports || 0 }}
                            </p>
                        </div>
                        <div
                            class="bg-green-400 bg-opacity-30 rounded-full p-3"
                        >
                            <svg
                                class="w-8 h-8"
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
                </div>

                <!-- Pending Review -->
                <div
                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">
                                Menunggu Review
                            </p>
                            <p class="text-3xl font-bold">
                                {{ stats.pending_review || 0 }}
                            </p>
                        </div>
                        <div
                            class="bg-yellow-400 bg-opacity-30 rounded-full p-3"
                        >
                            <svg
                                class="w-8 h-8"
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

                <!-- Completed Internships -->
                <div
                    class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">
                                Magang Selesai
                            </p>
                            <p class="text-3xl font-bold">
                                {{ stats.completed_internships || 0 }}
                            </p>
                        </div>
                        <div
                            class="bg-purple-400 bg-opacity-30 rounded-full p-3"
                        >
                            <svg
                                class="w-8 h-8"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z M12 14v6.5"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions for Final Reports -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Aksi Laporan Akhir
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Link
                        :href="route('admin.final-reports.index')"
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
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
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                        Review Laporan
                    </Link>

                    <button
                        @click="exportFinalReports"
                        class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200"
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
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Export Laporan Akhir
                    </button>

                    <Link
                        :href="route('admin.internship.statistics')"
                        class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200"
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
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                        Statistik Magang
                    </Link>
                </div>
            </div>

            <!-- Final Reports Status Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Status Laporan Akhir per Divisi
                    </h3>
                    <p class="text-sm text-gray-500">
                        Overview penyelesaian laporan akhir peserta magang
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Divisi
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Peserta Aktif
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Laporan Selesai
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Tingkat Penyelesaian
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="division in finalReportsStatus"
                                :key="division.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ division.name }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ division.active_participants || 0 }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ division.completed_reports || 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-1 bg-gray-200 rounded-full h-2 mr-2"
                                        >
                                            <div
                                                class="bg-green-500 h-2 rounded-full"
                                                :style="{
                                                    width:
                                                        (division.completion_rate ||
                                                            0) + '%',
                                                }"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-900 font-medium"
                                            >{{
                                                division.completion_rate || 0
                                            }}%</span
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'bg-green-100 text-green-800':
                                                (division.completion_rate ||
                                                    0) >= 80,
                                            'bg-yellow-100 text-yellow-800':
                                                (division.completion_rate ||
                                                    0) >= 50 &&
                                                (division.completion_rate ||
                                                    0) < 80,
                                            'bg-red-100 text-red-800':
                                                (division.completion_rate ||
                                                    0) < 50,
                                        }"
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                    >
                                        <template
                                            v-if="
                                                (division.completion_rate ||
                                                    0) >= 80
                                            "
                                            >Excellent</template
                                        >
                                        <template
                                            v-else-if="
                                                (division.completion_rate ||
                                                    0) >= 50
                                            "
                                            >Good</template
                                        >
                                        <template v-else
                                            >Needs Attention</template
                                        >
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.final-reports.division',
                                                division.id
                                            )
                                        "
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Detail
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Final Report Submissions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Pengumpulan Laporan Terbaru
                            </h3>
                            <p class="text-sm text-gray-500">
                                Laporan akhir yang baru diserahkan
                            </p>
                        </div>
                        <Link
                            :href="route('admin.final-reports.index')"
                            class="text-sm text-blue-600 hover:text-blue-900 font-medium"
                        >
                            Lihat Semua →
                        </Link>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    <div
                        v-for="report in recentReports"
                        :key="report.id"
                        class="px-6 py-4 hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"
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
                                        <p
                                            class="text-sm font-medium text-gray-900 truncate"
                                        >
                                            {{ report.participant_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ report.division_name }} •
                                            {{
                                                formatDate(report.submitted_at)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    :class="{
                                        'bg-yellow-100 text-yellow-800':
                                            report.status === 'pending',
                                        'bg-green-100 text-green-800':
                                            report.status === 'approved',
                                        'bg-red-100 text-red-800':
                                            report.status === 'revision',
                                    }"
                                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                >
                                    {{ getStatusText(report.status) }}
                                </span>
                                <Link
                                    :href="
                                        route(
                                            'admin.final-reports.show',
                                            report.id
                                        )
                                    "
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                >
                                    Review
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="!recentReports || recentReports.length === 0"
                        class="px-6 py-8 text-center"
                    >
                        <svg
                            class="w-12 h-12 text-gray-400 mx-auto mb-4"
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
                        <p class="text-gray-500">
                            Belum ada laporan akhir yang diserahkan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";

defineProps({
    stats: {
        type: Object,
        required: true,
    },
    finalReportsStatus: {
        type: Array,
        default: () => [],
    },
    recentReports: {
        type: Array,
        default: () => [],
    },
});

const exportFinalReports = () => {
    window.location.href = route("admin.reports.final-reports.export");
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    } catch (error) {
        return "-";
    }
};

const getStatusText = (status) => {
    const statusMap = {
        pending: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
    };
    return statusMap[status] || status;
};
</script>
