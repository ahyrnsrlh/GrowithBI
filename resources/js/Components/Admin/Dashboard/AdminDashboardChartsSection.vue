<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- ── Kehadiran per Divisi (real-time) — menggantikan Distribusi Status ── -->
        <div class="lg:col-span-1">
            <AttendanceDivisionChart />
        </div>

        <!-- ── Tren Pendaftaran (6 bulan terakhir) ──────────────────────────── -->
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
import AttendanceDivisionChart from "@/Components/Admin/Dashboard/AttendanceDivisionChart.vue";
import LineChart from "@/Components/Charts/LineChart.vue";

defineProps({
    trendsChartData: {
        type: Object,
        required: true,
    },
});
</script>
