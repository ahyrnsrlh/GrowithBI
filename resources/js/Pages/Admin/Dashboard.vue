<template>
    <AdminLayout title="Dashboard">
        <AdminDashboardHeader :current-date="currentDate" />

        <AdminDashboardStatsCards
            :stats="stats"
            :acceptance-rate="acceptanceRate"
            :rejection-rate="rejectionRate"
        />

        <AdminDashboardChartsSection
            :status-chart-data="statusChartData"
            :trends-chart-data="trendsChartData"
        />

        <AdminDashboardDivisionAndRecent
            :division-chart-data="divisionChartData"
            :recent-applications="recentApplications"
            :get-initials="getInitials"
            :get-status-text="getStatusText"
            :get-status-classes="getStatusClasses"
            :get-status-dot-class="getStatusDotClass"
            :format-date="formatDate"
        />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminDashboardHeader from "@/Components/Admin/Dashboard/AdminDashboardHeader.vue";
import AdminDashboardStatsCards from "@/Components/Admin/Dashboard/AdminDashboardStatsCards.vue";
import AdminDashboardChartsSection from "@/Components/Admin/Dashboard/AdminDashboardChartsSection.vue";
import AdminDashboardDivisionAndRecent from "@/Components/Admin/Dashboard/AdminDashboardDivisionAndRecent.vue";
import { useAdminDashboardPage } from "@/Composables/useAdminDashboardPage";

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    statusDistribution: {
        type: Array,
        default: () => [],
    },
    applicationTrends: {
        type: Array,
        default: () => [],
    },
    divisionData: {
        type: Array,
        default: () => [],
    },
    recentApplications: {
        type: Array,
        default: () => [],
    },
});

const {
    currentDate,
    acceptanceRate,
    rejectionRate,
    statusChartData,
    trendsChartData,
    divisionChartData,
    getInitials,
    getStatusText,
    getStatusClasses,
    getStatusDotClass,
    formatDate,
} = useAdminDashboardPage(props);

const stats = props.stats;
const recentApplications = props.recentApplications;
</script>
