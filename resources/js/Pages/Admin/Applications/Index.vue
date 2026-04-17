<template>
    <AdminLayout
        title="Manajemen Pendaftaran"
        subtitle="Kelola dan review semua pendaftaran magang"
        :pendingCount="stats.pending"
        :notificationCount="stats.pending"
    >
        <AdminApplicationsStatsCards :stats="stats" />

        <AdminApplicationsFilters
            :divisions="divisions"
            :status-options="statusOptions"
            v-model:search-query="searchQuery"
            v-model:selected-status="selectedStatus"
            v-model:selected-division="selectedDivision"
            :selected-count="selectedApplications.length"
            @search="search"
            @filter-by-status="filterByStatus"
            @filter-by-division="filterByDivision"
            @open-bulk="showBulkModal = true"
        />

        <AdminApplicationsTable
            :applications="applications"
            v-model:selected-applications="selectedApplications"
            :format-date="formatDate"
            @toggle-all="toggleAllApplications"
            @open-status-modal="openStatusModal"
        />

        <AdminApplicationsStatusModal
            :show="showStatusModal"
            :status-options="statusOptions"
            :status-form="statusForm"
            @close="closeStatusModal"
            @submit="updateStatus"
        />

        <AdminApplicationsBulkModal
            :show="showBulkModal"
            :selected-count="selectedApplications.length"
            :status-options="statusOptions"
            :bulk-form="bulkForm"
            @close="showBulkModal = false"
            @submit="bulkUpdateStatus"
        />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminApplicationsStatsCards from "@/Components/Admin/Applications/Index/AdminApplicationsStatsCards.vue";
import AdminApplicationsFilters from "@/Components/Admin/Applications/Index/AdminApplicationsFilters.vue";
import AdminApplicationsTable from "@/Components/Admin/Applications/Index/AdminApplicationsTable.vue";
import AdminApplicationsStatusModal from "@/Components/Admin/Applications/Index/AdminApplicationsStatusModal.vue";
import AdminApplicationsBulkModal from "@/Components/Admin/Applications/Index/AdminApplicationsBulkModal.vue";
import { useAdminApplicationsPage } from "@/Composables/useAdminApplicationsPage";

const props = defineProps({
    applications: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            accepted: 0,
            rejected: 0,
        }),
    },
    statusOptions: {
        type: Array,
        default: () => [
            { value: "menunggu", label: "Menunggu Review" },
            { value: "in_review", label: "Dalam Proses Seleksi" },
            { value: "interview_scheduled", label: "Wawancara Dijadwalkan" },
            { value: "accepted", label: "Diterima" },
            { value: "rejected", label: "Ditolak" },
            { value: "letter_ready", label: "Surat Penerimaan Tersedia" },
            { value: "expired", label: "Kedaluwarsa" },
        ],
    },
});

const {
    searchQuery,
    selectedStatus,
    selectedDivision,
    selectedApplications,
    showStatusModal,
    showBulkModal,
    statusForm,
    bulkForm,
    formatDate,
    search,
    filterByStatus,
    filterByDivision,
    toggleAllApplications,
    openStatusModal,
    closeStatusModal,
    updateStatus,
    bulkUpdateStatus,
} = useAdminApplicationsPage(props);
</script>
