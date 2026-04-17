<script setup>
import ProfileReportsIndexHeader from "@/Components/Profile/Reports/Index/ProfileReportsIndexHeader.vue";
import ProfileReportsIndexList from "@/Components/Profile/Reports/Index/ProfileReportsIndexList.vue";
import ProfileReportsIndexStatsCards from "@/Components/Profile/Reports/Index/ProfileReportsIndexStatsCards.vue";
import { useProfileReportsIndexPage } from "@/Composables/useProfileReportsIndexPage";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    acceptedApplication: {
        type: Object,
        default: null,
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
    reports: {
        type: Array,
        default: () => [],
    },
    recentLogbooks: {
        type: Array,
        default: () => [],
    },
});

const {
    formatDate,
    formatFileSize,
    deleteReport,
    getStatusClass,
    getStatusText,
} = useProfileReportsIndexPage();
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Laporan Akhir" />

        <ProfileReportsIndexHeader
            :create-href="route('profile.reports.create')"
        />

        <ProfileReportsIndexStatsCards :stats="stats" />

        <ProfileReportsIndexList
            :reports="reports"
            :format-date="formatDate"
            :format-file-size="formatFileSize"
            :get-status-class="getStatusClass"
            :get-status-text="getStatusText"
            :on-delete-report="deleteReport"
            :create-href="route('profile.reports.create')"
        />
    </AuthenticatedLayout>
</template>
