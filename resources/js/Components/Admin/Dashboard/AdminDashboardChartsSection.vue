<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- ── Kehadiran per Divisi (real-time) — menggantikan Distribusi Status ── -->
        <div class="lg:col-span-1">
            <Suspense>
                <template #default>
                    <AttendanceDivisionChart />
                </template>
                <template #fallback>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-3">
                        <div class="h-5 bg-gray-100 rounded-lg animate-pulse w-2/3"></div>
                        <div class="h-4 bg-gray-50 rounded animate-pulse w-1/2"></div>
                        <div class="h-48 bg-gray-50 rounded-xl animate-pulse mt-2"></div>
                    </div>
                </template>
            </Suspense>
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
            <Suspense>
                <template #default>
                    <LineChart
                        :data="trendsChartData"
                        :options="{ responsive: true, maintainAspectRatio: false }"
                        class="h-64"
                    />
                </template>
                <template #fallback>
                    <div class="h-64 bg-gray-50 rounded-xl animate-pulse"></div>
                </template>
            </Suspense>
        </div>
    </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";

// Lazy-loaded — these are below the fold so they must not block first paint
const AttendanceDivisionChart = defineAsyncComponent(() =>
    import("@/Components/Admin/Dashboard/AttendanceDivisionChart.vue")
);
const LineChart = defineAsyncComponent(() =>
    import("@/Components/Charts/LineChart.vue")
);

defineProps({
    trendsChartData: {
        type: Object,
        required: true,
    },
});
</script>

