<template>
    <AdminLayout title="Dashboard">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Selamat Datang di Dashboard 👋
                    </h1>
                    <p class="mt-1 text-gray-500">
                        Pantau statistik dan kelola program internship GrowithBI
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <div
                        class="flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full"
                    >
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
                            ></span>
                            <span
                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"
                            ></span>
                        </span>
                        <span class="text-sm font-medium text-green-700"
                            >Sistem Aktif</span
                        >
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ currentDate }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Pendaftar -->
            <div
                class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white/90">
                            Total Pendaftar
                        </p>
                        <h3 class="text-3xl font-bold text-white">
                            {{ stats?.total_applications || 0 }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">
                        Total pendaftar saat ini
                    </p>
                </div>
            </div>

            <!-- Menunggu Review -->
            <div
                class="bg-gradient-to-br from-amber-500 via-orange-500 to-orange-600 rounded-2xl p-6 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white/90">
                            Menunggu Review
                        </p>
                        <h3 class="text-3xl font-bold text-white">
                            {{ stats?.pending_applications || 0 }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">Perlu ditinjau segera</p>
                </div>
            </div>

            <!-- Diterima -->
            <div
                class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white/90">
                            Diterima
                        </p>
                        <h3 class="text-3xl font-bold text-white">
                            {{ stats?.accepted_applications || 0 }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">
                        Tingkat penerimaan {{ acceptanceRate }}%
                    </p>
                </div>
            </div>

            <!-- Ditolak -->
            <div
                class="bg-gradient-to-br from-rose-500 via-red-500 to-pink-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white/90">Ditolak</p>
                        <h3 class="text-3xl font-bold text-white">
                            {{ stats?.rejected_applications || 0 }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">
                        Tingkat penolakan {{ rejectionRate }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Status Distribution Pie Chart -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300"
            >
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Distribusi Status
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Status pendaftaran saat ini
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                            />
                        </svg>
                    </div>
                </div>
                <PieChart
                    :data="statusChartData"
                    :options="{
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                    }"
                    class="h-56"
                />
                <!-- Legend Pills -->
                <div
                    class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100"
                >
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full"
                    >
                        <span
                            class="w-2 h-2 bg-emerald-500 rounded-full"
                        ></span>
                        Disetujui
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-full"
                    >
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        Menunggu
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 text-xs font-medium rounded-full"
                    >
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        Ditolak
                    </span>
                </div>
            </div>

            <!-- Applications Trend Line Chart -->
            <div
                class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300"
            >
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Tren Pendaftaran
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            6 bulan terakhir
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-50 to-cyan-100 rounded-xl flex items-center justify-center"
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
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <LineChart
                    :data="trendsChartData"
                    :options="{ responsive: true, maintainAspectRatio: false }"
                    class="h-64"
                />
            </div>
        </div>

        <!-- Division Capacity & Recent Applications -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
            <!-- Division Capacity Bar Chart -->
            <div
                class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300"
            >
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Kapasitas Divisi
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Kuota vs penerimaan
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-violet-50 to-purple-100 rounded-xl flex items-center justify-center"
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
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                    </div>
                </div>
                <BarChart
                    :data="divisionChartData"
                    :options="{ responsive: true, maintainAspectRatio: false }"
                    class="h-72"
                />
            </div>

            <!-- Recent Applications Table -->
            <div
                class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300"
            >
                <div
                    class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Pendaftaran Terbaru
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">
                                5 pendaftaran terakhir
                            </p>
                        </div>
                        <Link
                            href="/admin/applications"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors"
                        >
                            Lihat Semua
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
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </Link>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Pendaftar
                                </th>
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Divisi
                                </th>
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Tanggal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="app in recentApplications"
                                :key="app.id"
                                class="hover:bg-blue-50/30 transition-colors duration-150"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm"
                                        >
                                            {{ getInitials(app.name) }}
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ app.name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ app.email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-700"
                                    >
                                        {{ app.division }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="getStatusClasses(app.status)"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg"
                                    >
                                        <span
                                            :class="
                                                getStatusDotClass(app.status)
                                            "
                                            class="w-1.5 h-1.5 rounded-full"
                                        ></span>
                                        {{ getStatusText(app.status) }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ formatDate(app.created_at) }}
                                </td>
                            </tr>
                            <!-- Empty State -->
                            <tr
                                v-if="
                                    !recentApplications ||
                                    recentApplications.length === 0
                                "
                            >
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"
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
                                        <p class="text-gray-500 font-medium">
                                            Belum ada pendaftaran
                                        </p>
                                        <p class="text-sm text-gray-400 mt-1">
                                            Pendaftaran baru akan muncul di sini
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PieChart from "@/Components/Charts/PieChart.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import BarChart from "@/Components/Charts/BarChart.vue";

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    statusDistribution: {
        type: Array,
        default: () => [],
    },
    applicationTrends: {
        type: Array,
        default: () => [],
    },
    divisionData: {
        type: Array,
        default: () => [],
    },
    recentApplications: {
        type: Array,
        default: () => [],
    },
});

// Current date for header
const currentDate = computed(() => {
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date().toLocaleDateString("id-ID", options);
});

// Acceptance rate percentage
const acceptanceRate = computed(() => {
    const total = props.stats?.total_applications || 0;
    const accepted = props.stats?.accepted_applications || 0;
    return total > 0 ? Math.round((accepted / total) * 100) : 0;
});

// Rejection rate percentage
const rejectionRate = computed(() => {
    const total = props.stats?.total_applications || 0;
    const rejected = props.stats?.rejected_applications || 0;
    return total > 0 ? Math.round((rejected / total) * 100) : 0;
});

// Chart data computed properties
const statusChartData = computed(() => {
    // Fallback data if no applications exist
    if (!props.statusDistribution || props.statusDistribution.length === 0) {
        return {
            labels: ["Tidak ada data"],
            datasets: [
                {
                    data: [1],
                    backgroundColor: ["#E5E7EB"],
                    borderWidth: 2,
                    borderColor: "#ffffff",
                },
            ],
        };
    }

    return {
        labels: props.statusDistribution.map((item) => item.name),
        datasets: [
            {
                data: props.statusDistribution.map((item) => item.value),
                backgroundColor: ["#10B981", "#F59E0B", "#EF4444"],
                borderWidth: 3,
                borderColor: "#ffffff",
                hoverBorderWidth: 4,
                hoverBorderColor: "#ffffff",
            },
        ],
    };
});

const trendsChartData = computed(() => {
    return {
        labels: props.applicationTrends.map((t) => t.month),
        datasets: [
            {
                label: "Pendaftaran",
                data: props.applicationTrends.map((t) => t.applications),
                borderColor: "#3B82F6",
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                tension: 0.4,
                fill: true,
                pointBackgroundColor: "#3B82F6",
                pointBorderColor: "#ffffff",
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            },
        ],
    };
});

const divisionChartData = computed(() => {
    return {
        labels: props.divisionData.map((d) => d.name),
        datasets: [
            {
                label: "Diterima",
                data: props.divisionData.map((d) => d.accepted),
                backgroundColor: "#10B981",
                borderColor: "#10B981",
                borderWidth: 0,
                borderRadius: 6,
            },
            {
                label: "Kuota",
                data: props.divisionData.map((d) => d.quota),
                backgroundColor: "#E5E7EB",
                borderColor: "#D1D5DB",
                borderWidth: 0,
                borderRadius: 6,
            },
        ],
    };
});

// Helper functions
const getInitials = (name) => {
    if (!name) return "?";
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
};

const getStatusText = (status) => {
    const statusMap = {
        approved: "Disetujui",
        diterima: "Diterima",
        pending: "Menunggu",
        menunggu: "Menunggu",
        rejected: "Ditolak",
        ditolak: "Ditolak",
        draft: "Draft",
    };
    return statusMap[status] || status;
};

const getStatusClasses = (status) => {
    const classMap = {
        approved: "bg-emerald-50 text-emerald-700 border border-emerald-200",
        diterima: "bg-emerald-50 text-emerald-700 border border-emerald-200",
        pending: "bg-amber-50 text-amber-700 border border-amber-200",
        menunggu: "bg-amber-50 text-amber-700 border border-amber-200",
        rejected: "bg-red-50 text-red-700 border border-red-200",
        ditolak: "bg-red-50 text-red-700 border border-red-200",
        draft: "bg-gray-50 text-gray-700 border border-gray-200",
    };
    return (
        classMap[status] || "bg-gray-50 text-gray-700 border border-gray-200"
    );
};

const getStatusDotClass = (status) => {
    const dotMap = {
        approved: "bg-emerald-500",
        diterima: "bg-emerald-500",
        pending: "bg-amber-500",
        menunggu: "bg-amber-500",
        rejected: "bg-red-500",
        ditolak: "bg-red-500",
        draft: "bg-gray-500",
    };
    return dotMap[status] || "bg-gray-500";
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};
</script>
