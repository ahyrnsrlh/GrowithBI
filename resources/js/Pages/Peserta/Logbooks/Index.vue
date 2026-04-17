<template>
    <PesertaLayout
        title="Logbook Harian"
        subtitle="Kelola catatan harian aktivitas magang Anda"
    >
        <LogbookIndexHeader />

        <LogbookIndexStatsCards :stats="stats" />

        <LogbookIndexFilters
            v-model:search-query="searchQuery"
            v-model:status-filter="statusFilter"
            v-model:month-filter="monthFilter"
        />

        <LogbookIndexGrid
            :filtered-logbooks="filteredLogbooks"
            :total-logbooks="logbooks.length"
            :format-date="formatDate"
            :get-status-class="getStatusClass"
            :get-status-text="getStatusText"
        />
    </PesertaLayout>
</template>

<script setup>
import LogbookIndexFilters from "@/Components/Peserta/Logbooks/Index/LogbookIndexFilters.vue";
import LogbookIndexGrid from "@/Components/Peserta/Logbooks/Index/LogbookIndexGrid.vue";
import LogbookIndexHeader from "@/Components/Peserta/Logbooks/Index/LogbookIndexHeader.vue";
import LogbookIndexStatsCards from "@/Components/Peserta/Logbooks/Index/LogbookIndexStatsCards.vue";
import { usePesertaLogbookIndexPage } from "@/Composables/usePesertaLogbookIndexPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

const props = defineProps({
    logbooks: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_logbooks: 0,
            pending_logbooks: 0,
            approved_logbooks: 0,
            revision_logbooks: 0,
        }),
    },
});

const logbooks = props.logbooks;
const stats = props.stats;

const {
    searchQuery,
    statusFilter,
    monthFilter,
    filteredLogbooks,
    formatDate,
    getStatusClass,
    getStatusText,
} = usePesertaLogbookIndexPage(logbooks);
</script>
