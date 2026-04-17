<template>
    <div
        class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-100"
    >
        <div class="p-6 border-b border-gray-100 bg-white rounded-t-xl">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex items-start gap-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"
                    >
                        <svg
                            class="w-7 h-7 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-blue-600">
                            Laporan Akhir
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Kelola laporan dan statistik magang Anda
                        </p>
                    </div>
                </div>
                <button
                    @click="$emit('create-report')"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
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
                    Buat Laporan
                </button>
            </div>
        </div>

        <div class="p-6 border-b border-gray-100 bg-white">
            <ReportStatsCards :reportStats="reportStats" />
        </div>

        <div class="p-6 bg-gray-50">
            <ReportGrid
                :reports="reports"
                :formatDate="formatDate"
                @create="$emit('create-report')"
            />
        </div>
    </div>
</template>

<script setup>
import ReportStatsCards from "@/Components/Profile/Reports/ReportStatsCards.vue";
import ReportGrid from "@/Components/Profile/Reports/ReportGrid.vue";

defineProps({
    reports: { type: Array, default: () => [] },
    reportStats: { type: Object, default: () => ({}) },
    formatDate: { type: Function, required: true },
});

defineEmits(["create-report"]);
</script>
