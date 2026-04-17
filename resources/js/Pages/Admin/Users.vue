<script setup>
import AdminUsersFilters from "@/Components/Admin/Users/AdminUsersFilters.vue";
import AdminUsersHeaderCard from "@/Components/Admin/Users/AdminUsersHeaderCard.vue";
import AdminUsersTable from "@/Components/Admin/Users/AdminUsersTable.vue";
import { useAdminUsersPage } from "@/Composables/useAdminUsersPage";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { toRef } from "vue";

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const {
    filters,
    showCreateModal,
    getRoleBadgeClass,
    getRoleLabel,
    formatDate,
    pageNumbers,
    getPageUrl,
    applyFilters,
    resetFilters,
    openCreateModal,
    editUser,
    toggleUserStatus,
} = useAdminUsersPage(toRef(props, "users"));
</script>

<template>
    <Head title="Kelola Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Pengguna
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <AdminUsersHeaderCard @create-user="openCreateModal" />

                <AdminUsersFilters
                    :filters="filters"
                    @apply-filters="applyFilters"
                    @reset-filters="resetFilters"
                />

                <AdminUsersTable
                    :users="users"
                    :get-role-badge-class="getRoleBadgeClass"
                    :get-role-label="getRoleLabel"
                    :format-date="formatDate"
                    :page-numbers="pageNumbers"
                    :get-page-url="getPageUrl"
                    @edit-user="editUser"
                    @toggle-user-status="toggleUserStatus"
                />
            </div>
        </div>

        <div v-if="showCreateModal" class="hidden"></div>
    </AuthenticatedLayout>
</template>
