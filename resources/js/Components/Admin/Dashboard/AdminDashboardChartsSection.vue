<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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
            <div
                class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100"
            >
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full"
                >
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
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

        <div
            class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300"
        >
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Tren Pendaftaran
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5">6 bulan terakhir</p>
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
</template>

<script setup>
import PieChart from "@/Components/Charts/PieChart.vue";
import LineChart from "@/Components/Charts/LineChart.vue";

defineProps({
    statusChartData: {
        type: Object,
        required: true,
    },
    trendsChartData: {
        type: Object,
        required: true,
    },
});
</script>
