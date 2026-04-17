<template>
    <AdminLayout
        title="Manajemen Peserta"
        subtitle="Kelola data peserta magang yang telah diterima"
    >
        <AdminParticipantsStatsCards :stats="safeStats" />

        <AdminParticipantsFilters
            :divisions="safeDivisions"
            v-model:search-query="searchQuery"
            v-model:status-filter="statusFilter"
            v-model:division-filter="divisionFilter"
            @search="search"
            @filter="filter"
            @export="exportData"
        />

        <AdminParticipantsTable
            :participants="participants"
            :participants-data="safeParticipants"
            :format-date="formatDate"
            :get-accepted-application="getAcceptedApplication"
            :get-last-application-status-class="getLastApplicationStatusClass"
            :get-last-application-status-text="getLastApplicationStatusText"
            :safe-route="safeRoute"
            @toggle-status="toggleStatus"
        />
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminParticipantsStatsCards from "@/Components/Admin/Participants/AdminParticipantsStatsCards.vue";
import AdminParticipantsFilters from "@/Components/Admin/Participants/AdminParticipantsFilters.vue";
import AdminParticipantsTable from "@/Components/Admin/Participants/AdminParticipantsTable.vue";
import { useAdminParticipantsPage } from "@/Composables/useAdminParticipantsPage";

const props = defineProps({
    participants: {
        type: Object,
        default: () => ({ data: [], links: [], from: 0, to: 0, total: 0 }),
    },
    stats: {
        type: Object,
        default: () => ({
            total_participants: 0,
            active_participants: 0,
            total_applications: 0,
            completed_participants: 0,
        }),
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: "", status: "", division: "" }),
    },
});

const {
    safeParticipants,
    safeStats,
    safeDivisions,
    searchQuery,
    statusFilter,
    divisionFilter,
    search,
    filter,
    formatDate,
    getAcceptedApplication,
    getLastApplicationStatusClass,
    getLastApplicationStatusText,
    toggleStatus,
    exportData,
    safeRoute,
} = useAdminParticipantsPage(props);

const participants = props.participants;
</script>
