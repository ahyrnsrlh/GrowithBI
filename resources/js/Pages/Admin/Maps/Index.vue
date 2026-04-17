<template>
    <Head title="Maps - Dashboard Admin" />

    <AdminLayout title="Maps Real-Time">
        <AdminMapsHeader :formatted-current-date="formattedCurrentDate" />

        <AdminMapsFlashSuccess :message="$page.props.flash.success" />

        <AdminMapsStatsCards :stats="stats" />

        <AdminMapsMapCard
            :office-location="officeLocation"
            :is-refreshing="isRefreshing"
            :is-loading="isLoading"
            @refresh="refreshData"
        />

        <AdminMapsAttendanceTable
            :attendances="attendances"
            :get-initials="getInitials"
            :get-status-badge-class="getStatusBadgeClass"
            :get-status-dot-class="getStatusDotClass"
            :get-status-text="getStatusText"
        />

        <AdminMapsLeafletStyle />
    </AdminLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminMapsHeader from "@/Components/Admin/Maps/AdminMapsHeader.vue";
import AdminMapsFlashSuccess from "@/Components/Admin/Maps/AdminMapsFlashSuccess.vue";
import AdminMapsStatsCards from "@/Components/Admin/Maps/AdminMapsStatsCards.vue";
import AdminMapsMapCard from "@/Components/Admin/Maps/AdminMapsMapCard.vue";
import AdminMapsAttendanceTable from "@/Components/Admin/Maps/AdminMapsAttendanceTable.vue";
import AdminMapsLeafletStyle from "@/Components/Admin/Maps/AdminMapsLeafletStyle.vue";
import { useAdminMapsPage } from "@/Composables/useAdminMapsPage";

const props = defineProps({
    attendances: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_attendances: 0,
            valid_attendances: 0,
            invalid_attendances: 0,
            late: 0,
        }),
    },
    officeLocation: {
        type: Object,
        default: () => ({
            latitude: -5.39714,
            longitude: 105.26679,
            name: "Bank Indonesia KPw Lampung",
            radius: 100,
        }),
    },
    currentDate: {
        type: String,
        default: () => new Date().toISOString().split("T")[0],
    },
});

const {
    isLoading,
    isRefreshing,
    attendances,
    stats,
    formattedCurrentDate,
    refreshData,
    getStatusBadgeClass,
    getStatusDotClass,
    getStatusText,
    getInitials,
} = useAdminMapsPage(props);

const officeLocation = props.officeLocation;
</script>
