<template>
    <AdminLayout>
        <Head title="Manajemen Laporan Akhir" />

        <AdminFinalReportsHeader />

        <AdminFinalReportsStatsCards :stats="stats" />

        <AdminFinalReportsFilters
            :search-form="searchForm"
            :divisions="divisions"
            @filter="applyFilters"
            @reset="resetFilters"
            @export="exportReports"
        />

        <AdminFinalReportsTable
            v-if="hasReports"
            :reports="reports"
            :get-status-label="getStatusLabel"
            :get-status-badge-class="getStatusBadgeClass"
            :format-date="formatDate"
        />

        <AdminFinalReportsEmptyState v-else />
    </AdminLayout>
</template>

<script setup>
import AdminFinalReportsEmptyState from "@/Components/Admin/FinalReports/AdminFinalReportsEmptyState.vue";
import AdminFinalReportsFilters from "@/Components/Admin/FinalReports/AdminFinalReportsFilters.vue";
import AdminFinalReportsHeader from "@/Components/Admin/FinalReports/AdminFinalReportsHeader.vue";
import AdminFinalReportsStatsCards from "@/Components/Admin/FinalReports/AdminFinalReportsStatsCards.vue";
import AdminFinalReportsTable from "@/Components/Admin/FinalReports/AdminFinalReportsTable.vue";
import { useAdminFinalReportsIndexPage } from "@/Composables/useAdminFinalReportsIndexPage";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    reports: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const reports = props.reports;
const divisions = props.divisions;
const stats = props.stats;

const hasReports = computed(() => {
    return (reports?.data || []).length > 0;
});

const {
    searchForm,
    applyFilters,
    resetFilters,
    exportReports,
    getStatusLabel,
    getStatusBadgeClass,
    formatDate,
} = useAdminFinalReportsIndexPage(props.filters);
</script>
