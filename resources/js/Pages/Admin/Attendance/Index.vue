<template>
    <Head title="Manajemen Absensi Peserta" />

    <AdminLayout title="Manajemen Absensi">
        <AttendancePageHeader @export="exportData" />

        <AttendanceStatsCards :stats="props.stats" />

        <AttendanceFiltersCard
            :showFilters="showFilters"
            :filterForm="filterForm"
            :divisions="props.divisions"
            :activeFiltersCount="activeFiltersCount"
            @toggle-filters="showFilters = !showFilters"
            @apply-filters="applyFilters"
            @debounce-search="debounceSearch"
            @clear-filters="clearFilters"
        />

        <AttendanceDataTableCard
            :attendances="props.attendances"
            :isLoading="isLoading"
            :paginationLinks="paginationLinks"
            @open-photo="openPhotoModal"
            @clear-filters="clearFilters"
        />

        <AttendancePhotoModal
            :show="showPhotoModal"
            :photo="selectedPhoto"
            :title="selectedPhotoTitle"
            @close="closePhotoModal"
        />
    </AdminLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AttendanceDataTableCard from "@/Components/Admin/Attendance/AttendanceDataTableCard.vue";
import AttendanceFiltersCard from "@/Components/Admin/Attendance/AttendanceFiltersCard.vue";
import AttendancePageHeader from "@/Components/Admin/Attendance/AttendancePageHeader.vue";
import AttendancePhotoModal from "@/Components/Admin/Attendance/AttendancePhotoModal.vue";
import AttendanceStatsCards from "@/Components/Admin/Attendance/AttendanceStatsCards.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useAdminAttendanceIndexPage } from "@/Composables/useAdminAttendanceIndexPage";

const props = defineProps({
    attendances: Object,
    divisions: Array,
    participants: Array,
    filters: Object,
    stats: Object,
});

const {
    isLoading,
    showFilters,
    showPhotoModal,
    selectedPhoto,
    selectedPhotoTitle,
    filterForm,
    activeFiltersCount,
    paginationLinks,
    openPhotoModal,
    closePhotoModal,
    applyFilters,
    debounceSearch,
    clearFilters,
    exportData,
} = useAdminAttendanceIndexPage(props);
</script>
