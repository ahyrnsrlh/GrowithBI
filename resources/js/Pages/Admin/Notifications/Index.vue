<script setup>
import AdminNotificationsFilters from "@/Components/Admin/Notifications/AdminNotificationsFilters.vue";
import AdminNotificationsHeader from "@/Components/Admin/Notifications/AdminNotificationsHeader.vue";
import AdminNotificationsList from "@/Components/Admin/Notifications/AdminNotificationsList.vue";
import AdminNotificationsPagination from "@/Components/Admin/Notifications/AdminNotificationsPagination.vue";
import { useAdminNotificationsIndexPage } from "@/Composables/useAdminNotificationsIndexPage";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    notifications: {
        type: Array,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const {
    filters,
    formatTime,
    markAsRead,
    markAllAsRead,
    deleteNotification,
    handleNotificationClick,
    applyFilters,
    changePage,
} = useAdminNotificationsIndexPage(props.filters);
</script>

<template>
    <AdminLayout
        title="Notifikasi"
        subtitle="Kelola dan lihat semua notifikasi sistem"
    >
        <AdminNotificationsHeader
            :stats="stats"
            @mark-all-as-read="markAllAsRead"
        />

        <AdminNotificationsFilters
            :filters="filters"
            @apply-filters="applyFilters"
        />

        <AdminNotificationsList
            :notifications="notifications"
            :format-time="formatTime"
            @notification-click="handleNotificationClick"
            @mark-as-read="markAsRead"
            @delete-notification="deleteNotification"
        />

        <AdminNotificationsPagination
            :pagination="pagination"
            @change-page="changePage"
        />
    </AdminLayout>
</template>
