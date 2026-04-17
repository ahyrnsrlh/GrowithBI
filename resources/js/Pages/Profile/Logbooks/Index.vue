<template>
    <AuthenticatedLayout>
        <Head title="Logbook Harian" />

        <ProfileLogbooksHeader @create="showCreateModal = true" />

        <ProfileLogbooksStatsCards :stats="stats" />

        <ProfileLogbooksFilterBar
            v-model:status="filters.status"
            :displayed-count="displayedLogbooks.length"
            :filtered-count="filteredLogbooks.length"
        />

        <ProfileLogbooksGrid
            :displayed-logbooks="displayedLogbooks"
            :filtered-count="filteredLogbooks.length"
            :displayed-count="displayedCount"
            :has-more-to-show="hasMoreToShow"
            :status-filter="filters.status"
            :format-date-short="formatDateShort"
            :get-status-class="getStatusClass"
            :get-status-text="getStatusText"
            @load-more="loadMore"
            @create="showCreateModal = true"
            @reset-filter="setStatusFilter('')"
        />

        <ProfileLogbooksCreateModal
            :show="showCreateModal"
            :form="createForm"
            @close="showCreateModal = false"
            @submit="submitLogbook"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ProfileLogbooksHeader from "@/Components/Profile/Logbooks/ProfileLogbooksHeader.vue";
import ProfileLogbooksStatsCards from "@/Components/Profile/Logbooks/ProfileLogbooksStatsCards.vue";
import ProfileLogbooksFilterBar from "@/Components/Profile/Logbooks/ProfileLogbooksFilterBar.vue";
import ProfileLogbooksGrid from "@/Components/Profile/Logbooks/ProfileLogbooksGrid.vue";
import ProfileLogbooksCreateModal from "@/Components/Profile/Logbooks/ProfileLogbooksCreateModal.vue";
import { useProfileLogbooksPage } from "@/Composables/useProfileLogbooksPage";

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
    filters: {
        type: Object,
        default: () => ({
            month: "",
            status: "",
        }),
    },
});

const {
    showCreateModal,
    displayedCount,
    filters,
    filteredLogbooks,
    displayedLogbooks,
    hasMoreToShow,
    createForm,
    loadMore,
    setStatusFilter,
    submitLogbook,
    formatDateShort,
    getStatusClass,
    getStatusText,
} = useProfileLogbooksPage(props.logbooks, props.filters);
</script>
