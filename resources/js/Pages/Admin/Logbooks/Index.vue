<template>
    <AdminLayout
        title="Laporan Harian Peserta"
        subtitle="Review dan kelola laporan harian dari peserta magang"
    >
        <AdminLogbooksHeader
            :selected-count="selectedLogbooks.length"
            @bulk-approve="bulkAction('approve')"
        />

        <AdminLogbooksStatsCards :stats="stats" />

        <AdminLogbooksFilters
            :divisions="divisions"
            v-model:search-query="searchQuery"
            v-model:status-filter="statusFilter"
            v-model:division-filter="divisionFilter"
            v-model:month-filter="monthFilter"
            @reset="resetFilters"
        />

        <AdminLogbooksTable
            :filtered-logbooks="filteredLogbooks"
            :paginated-logbooks="paginatedLogbooks"
            v-model:selected-logbooks="selectedLogbooks"
            :is-all-selected="isAllSelected"
            :current-page="currentPage"
            :total-pages="totalPages"
            :items-per-page="itemsPerPage"
            :pagination-range="paginationRange"
            :format-date="formatDate"
            :get-status-class="getStatusClass"
            :get-status-text="getStatusText"
            @toggle-select-all="toggleSelectAll"
            @quick-approve="quickApprove"
            @go-to-page="goToPage"
            @change-items-per-page="changeItemsPerPage"
        />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminLogbooksHeader from "@/Components/Admin/Logbooks/AdminLogbooksHeader.vue";
import AdminLogbooksStatsCards from "@/Components/Admin/Logbooks/AdminLogbooksStatsCards.vue";
import AdminLogbooksFilters from "@/Components/Admin/Logbooks/AdminLogbooksFilters.vue";
import AdminLogbooksTable from "@/Components/Admin/Logbooks/AdminLogbooksTable.vue";
import { useAdminLogbooksPage } from "@/Composables/useAdminLogbooksPage";

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
            average_hours: 0,
        }),
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

const {
    searchQuery,
    statusFilter,
    divisionFilter,
    monthFilter,
    selectedLogbooks,
    currentPage,
    itemsPerPage,
    filteredLogbooks,
    paginatedLogbooks,
    totalPages,
    paginationRange,
    isAllSelected,
    goToPage,
    changeItemsPerPage,
    toggleSelectAll,
    resetFilters,
    formatDate,
    getStatusClass,
    getStatusText,
    quickApprove,
    bulkAction,
} = useAdminLogbooksPage(props.logbooks);
</script>
