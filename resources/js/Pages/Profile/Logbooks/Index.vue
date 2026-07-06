<template>
    <AuthenticatedLayout>
        <Head title="Logbook Harian" />

        <ProfileLogbooksHeader @create="showCreateModal = true" />

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
            @view="openDetailModal"
            @edit="openEditModal"
        />

        <ProfileLogbooksCreateModal
            :show="showCreateModal"
            :form="createForm"
            @close="showCreateModal = false"
            @submit="submitLogbook"
        />

        <LogbookDetailModal
            :show="showDetailModal"
            :logbook-id="selectedLogbookId"
            @close="showDetailModal = false"
        />

        <LogbookEditModal
            :show="showEditModal"
            :logbook-id="selectedLogbookId"
            @close="showEditModal = false"
            @success="handleEditSuccess"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ProfileLogbooksHeader from "@/Components/Profile/Logbooks/ProfileLogbooksHeader.vue";
import ProfileLogbooksFilterBar from "@/Components/Profile/Logbooks/ProfileLogbooksFilterBar.vue";
import ProfileLogbooksGrid from "@/Components/Profile/Logbooks/ProfileLogbooksGrid.vue";
import ProfileLogbooksCreateModal from "@/Components/Profile/Logbooks/ProfileLogbooksCreateModal.vue";
import LogbookDetailModal from "@/Components/Profile/Logbooks/LogbookDetailModal.vue";
import LogbookEditModal from "@/Components/Profile/Logbooks/LogbookEditModal.vue";
import { useProfileLogbooksPage } from "@/Composables/useProfileLogbooksPage";

const props = defineProps({
    logbooks: {
        type: Array,
        default: () => [],
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
    showDetailModal,
    showEditModal,
    selectedLogbookId,
    displayedCount,
    filters,
    filteredLogbooks,
    displayedLogbooks,
    hasMoreToShow,
    createForm,
    loadMore,
    setStatusFilter,
    openDetailModal,
    openEditModal,
    handleEditSuccess,
    submitLogbook,
    formatDateShort,
    getStatusClass,
    getStatusText,
} = useProfileLogbooksPage(props.logbooks, props.filters);
</script>
