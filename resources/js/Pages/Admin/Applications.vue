<template>
    <Head title="Kelola Aplikasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Aplikasi Magang
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <AdminApplicationsLegacyStatsCards :stats="stats" />

                <AdminApplicationsLegacyFilters
                    :filters="filters"
                    :divisions="divisions"
                    @apply-filters="applyFilters"
                    @reset-filters="resetFilters"
                />

                <AdminApplicationsLegacyTable
                    :applications="applications"
                    :format-date="formatDate"
                    :get-status-badge-class="getStatusBadgeClass"
                    :get-status-label="getStatusLabel"
                    @view="viewApplication"
                    @approve="approveApplication"
                    @reject="rejectApplication"
                />
            </div>
        </div>

        <AdminApplicationsLegacyDetailModal
            :selected-application="selectedApplication"
            @close="closeModal"
            @approve="approveApplication"
            @reject="rejectApplication"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AdminApplicationsLegacyStatsCards from "@/Components/Admin/Applications/Legacy/AdminApplicationsLegacyStatsCards.vue";
import AdminApplicationsLegacyFilters from "@/Components/Admin/Applications/Legacy/AdminApplicationsLegacyFilters.vue";
import AdminApplicationsLegacyTable from "@/Components/Admin/Applications/Legacy/AdminApplicationsLegacyTable.vue";
import AdminApplicationsLegacyDetailModal from "@/Components/Admin/Applications/Legacy/AdminApplicationsLegacyDetailModal.vue";
import { useAdminApplicationsLegacyPage } from "@/Composables/useAdminApplicationsLegacyPage";

defineProps({
    applications: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            approved: 0,
            rejected: 0,
        }),
    },
});

const {
    filters,
    selectedApplication,
    getStatusBadgeClass,
    getStatusLabel,
    formatDate,
    applyFilters,
    resetFilters,
    viewApplication,
    closeModal,
    approveApplication,
    rejectApplication,
} = useAdminApplicationsLegacyPage();
</script>
