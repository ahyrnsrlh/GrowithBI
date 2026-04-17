<script setup>
import DivisionDeleteModal from "@/Components/Admin/Divisions/DivisionDeleteModal.vue";
import DivisionIndexEmptyState from "@/Components/Admin/Divisions/DivisionIndexEmptyState.vue";
import DivisionIndexGrid from "@/Components/Admin/Divisions/DivisionIndexGrid.vue";
import DivisionIndexHeader from "@/Components/Admin/Divisions/DivisionIndexHeader.vue";
import DivisionIndexStatsCards from "@/Components/Admin/Divisions/DivisionIndexStatsCards.vue";
import { useAdminDivisionsIndexPage } from "@/Composables/useAdminDivisionsIndexPage";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    divisions: {
        type: Array,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
});

const {
    showDeleteModal,
    divisionToDelete,
    formatDate,
    getProgressPercent,
    confirmDelete,
    closeDeleteModal,
    deleteDivision,
} = useAdminDivisionsIndexPage();
</script>

<template>
    <AdminLayout
        title="Manajemen Divisi"
        subtitle="Kelola divisi magang dan kuota"
    >
        <DivisionIndexStatsCards :stats="stats" />

        <DivisionIndexHeader create-href="/admin/divisions/create" />

        <DivisionIndexGrid
            v-if="divisions.length"
            :divisions="divisions"
            :format-date="formatDate"
            :get-progress-percent="getProgressPercent"
            @confirm-delete="confirmDelete"
        />

        <DivisionIndexEmptyState v-else create-href="/admin/divisions/create" />

        <DivisionDeleteModal
            :show="showDeleteModal"
            :division="divisionToDelete"
            @close="closeDeleteModal"
            @confirm="deleteDivision"
        />
    </AdminLayout>
</template>
