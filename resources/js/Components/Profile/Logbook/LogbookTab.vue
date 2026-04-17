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
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-blue-600">
                            Logbook Harian
                        </h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Catat perkembangan dan kegiatan harian selama masa
                            magang
                        </p>
                    </div>
                </div>
                <button
                    @click="$emit('create-logbook')"
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
                    Tambah Logbook
                </button>
            </div>
        </div>

        <div class="p-6 border-b border-gray-100 bg-white">
            <LogbookStatsCards :logbookStats="logbookStats" />
        </div>

        <div class="p-6 bg-gray-50">
            <LogbookGrid
                :logbooks="logbooks"
                :formatDate="formatDate"
                @create="$emit('create-logbook')"
                @view="$emit('view-logbook', $event)"
                @edit="$emit('edit-logbook', $event)"
            />
        </div>
    </div>
</template>

<script setup>
import LogbookStatsCards from "@/Components/Profile/Logbook/LogbookStatsCards.vue";
import LogbookGrid from "@/Components/Profile/Logbook/LogbookGrid.vue";

defineProps({
    logbooks: { type: Array, default: () => [] },
    logbookStats: { type: Object, default: () => ({}) },
    formatDate: { type: Function, required: true },
});

defineEmits(["create-logbook", "view-logbook", "edit-logbook"]);
</script>
