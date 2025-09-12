<template>
    <AdminLayout title="Dashboard">
        <!-- Header Section -->
        <div class="mb-8">
            <div
                class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow-xl"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold mb-3">
                            Dashboard Analytics
                        </h1>
                        <p class="text-blue-100 text-lg">
                            Selamat datang di dashboard analitik. Lihat
                            ringkasan data dan statistik terbaru.
                        </p>
                        <div class="mt-4 flex items-center space-x-6">
                            <div class="flex items-center">
                                <div
                                    class="w-3 h-3 bg-green-400 rounded-full mr-2"
                                ></div>
                                <span class="text-blue-100 text-sm"
                                    >Sistem Online</span
                                >
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-blue-200 mr-2"
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
                                <span class="text-blue-100 text-sm"
                                    >Data Real-time</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div
                            class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-16 h-16 text-white"
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
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Status Distribution Pie Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Distribusi Status Pendaftaran
                </h3>
                <PieChart
                    :data="statusChartData"
                    :options="{ responsive: true, maintainAspectRatio: false }"
                    class="h-64"
                />
            </div>

            <!-- Applications Trend Line Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Tren Pendaftaran (6 Bulan Terakhir)
                </h3>
                <LineChart
                    :data="trendsChartData"
                    :options="{ responsive: true, maintainAspectRatio: false }"
                    class="h-64"
                />
            </div>
        </div>

        <!-- Division Capacity Bar Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Kapasitas Divisi
            </h3>
            <BarChart
                :data="divisionChartData"
                :options="{ responsive: true, maintainAspectRatio: false }"
                class="h-80"
            />
        </div>

        <!-- Recent Applications Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Pendaftaran Terbaru
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Nama
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Pemohon
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Tanggal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="app in recentApplications" :key="app.id">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                {{ app.name }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ app.email }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ app.division }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800':
                                            app.status === 'approved',
                                        'bg-yellow-100 text-yellow-800':
                                            app.status === 'pending',
                                        'bg-red-100 text-red-800':
                                            app.status === 'rejected',
                                        'bg-gray-100 text-gray-800':
                                            app.status === 'draft',
                                    }"
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ getStatusText(app.status) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ app.created_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
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

    console.log("Status Distribution Debug:", props.statusDistribution);
    const chartData = {
        labels: props.statusDistribution.map((item) => item.name),
        datasets: [
            {
                data: props.statusDistribution.map((item) => item.value),
                backgroundColor: props.statusDistribution.map(
                    (item) => item.color
                ),
                borderWidth: 2,
                borderColor: "#ffffff",
                hoverBorderWidth: 3,
                hoverBorderColor: "#ffffff",
            },
        ],
    };
    console.log("Pie Chart Data:", chartData);
    return chartData;
});

const trendsChartData = computed(() => {
    return {
        labels: props.applicationTrends.map((t) => t.month),
        datasets: [
            {
                label: "Aplikasi Baru",
                data: props.applicationTrends.map((t) => t.applications),
                borderColor: "#1E40AF",
                backgroundColor: "rgba(30, 64, 175, 0.1)",
                tension: 0.4,
                fill: true,
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
                borderWidth: 1,
            },
            {
                label: "Kuota Maksimal",
                data: props.divisionData.map((d) => d.quota),
                backgroundColor: "#E5E7EB",
                borderColor: "#9CA3AF",
                borderWidth: 1,
            },
        ],
    };
});

// Additional computed properties
const acceptanceRate = computed(() => {
    const total = props.stats.total_applications || 0;
    const accepted = props.stats.accepted_applications || 0;
    return total > 0 ? Math.round((accepted / total) * 100) : 0;
});

// Helper functions
const getStatusText = (status) => {
    const statusMap = {
        approved: "Disetujui",
        pending: "Menunggu",
        rejected: "Ditolak",
        draft: "Draft",
    };
    return statusMap[status] || status;
};
</script>
