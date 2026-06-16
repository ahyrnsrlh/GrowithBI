<template>
    <Head title="Manajemen Absensi Peserta" />

    <AdminLayout title="Manajemen Absensi">
        <AttendancePageHeader />

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
            @export="exportData"
        />

        <!--
            Pass both the original `attendances` prop (for pagination meta)
            AND the WebSocket-backed reactive state from the composable.
        -->
        <AttendanceDataTableCard
            :attendances="props.attendances"
            :attendanceList="attendanceList"
            :totalCount="totalCount"
            :isConnected="isConnected"
            :recentlyUpdatedId="recentlyUpdatedId"
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
import { Head } from '@inertiajs/vue3';
import AttendanceDataTableCard from '@/Components/Admin/Attendance/AttendanceDataTableCard.vue';
import AttendanceFiltersCard from '@/Components/Admin/Attendance/AttendanceFiltersCard.vue';
import AttendancePageHeader from '@/Components/Admin/Attendance/AttendancePageHeader.vue';
import AttendancePhotoModal from '@/Components/Admin/Attendance/AttendancePhotoModal.vue';
import AttendanceStatsCards from '@/Components/Admin/Attendance/AttendanceStatsCards.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useAdminAttendanceIndexPage } from '@/Composables/useAdminAttendanceIndexPage';

const props = defineProps({
    attendances:  Object,
    divisions:    Array,
    participants: Array,
    filters:      Object,
    stats:        Object,
});

const {
    // Filter / UI state
    isLoading,
    showFilters,
    showPhotoModal,
    selectedPhoto,
    selectedPhotoTitle,
    filterForm,
    activeFiltersCount,
    paginationLinks,
    // Actions
    openPhotoModal,
    closePhotoModal,
    applyFilters,
    debounceSearch,
    clearFilters,
    exportData,
    // Real-time state
    attendanceList,
    totalCount,
    isConnected,
    recentlyUpdatedId,
} = useAdminAttendanceIndexPage(props);
</script>
