<template>
    <div class="relative">
        <NotificationBellButton
            :is-open="isOpen"
            :unread-count="unreadCount"
            :is-web-socket-connected="isWebSocketConnected"
            @toggle="toggleDropdown"
        />

        <NotificationDropdown
            :is-open="isOpen"
            :notifications="notifications"
            :unread-count="unreadCount"
            :loading="loading"
            :is-web-socket-connected="isWebSocketConnected"
            :get-icon="getIcon"
            :get-icon-color="getIconColor"
            :format-time="formatTime"
            :get-display-message="getDisplayMessage"
            @mark-all="markAllAsRead"
            @close="closeDropdown"
            @click-notification="handleNotificationClick"
            @delete-notification="deleteNotification"
            @view-all="viewAllNotifications"
        />
    </div>
</template>

<script setup>
import NotificationBellButton from "@/Components/Notifications/NotificationBellButton.vue";
import NotificationDropdown from "@/Components/Notifications/NotificationDropdown.vue";
import { useNotificationBell } from "@/Composables/useNotificationBell";

const props = defineProps({
    userId: {
        type: Number,
        required: false,
        default: null,
    },
    userRole: {
        type: String,
        required: false,
        default: "user",
    },
});

const {
    isOpen,
    notifications,
    unreadCount,
    loading,
    isWebSocketConnected,
    toggleDropdown,
    closeDropdown,
    markAllAsRead,
    deleteNotification,
    handleNotificationClick,
    viewAllNotifications,
    getIcon,
    getIconColor,
    formatTime,
    getDisplayMessage,
} = useNotificationBell(props);
</script>
