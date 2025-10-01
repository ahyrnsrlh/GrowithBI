<template>
    <div class="relative">
        <!-- Notification Bell Button -->
        <button
            @click="toggleDropdown"
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg transition-all duration-200"
            :class="{ 'bg-indigo-50': isOpen }"
        >
            <BellIcon class="h-6 w-6" />

            <!-- Unread Badge -->
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full animate-pulse"
            >
                {{ unreadCount > 99 ? "99+" : unreadCount }}
            </span>
        </button>

        <!-- Dropdown Panel -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                v-click-outside="closeDropdown"
                class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 max-h-[600px] flex flex-col"
            >
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-2xl"
                >
                    <h3 class="text-lg font-bold text-gray-900">Notifikasi</h3>
                    <div class="flex items-center space-x-2">
                        <button
                            v-if="unreadCount > 0"
                            @click="markAllAsRead"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors"
                        >
                            Tandai Semua
                        </button>
                        <button
                            @click="closeDropdown"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="overflow-y-auto flex-1 custom-scrollbar">
                    <div
                        v-if="loading"
                        class="flex items-center justify-center py-12"
                    >
                        <div
                            class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"
                        ></div>
                    </div>

                    <div
                        v-else-if="notifications.length === 0"
                        class="text-center py-12"
                    >
                        <BellIcon
                            class="h-16 w-16 text-gray-300 mx-auto mb-4"
                        />
                        <p class="text-gray-500 font-medium">
                            Tidak ada notifikasi
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            Notifikasi akan muncul di sini
                        </p>
                    </div>

                    <div v-else class="divide-y divide-gray-100">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            @click="handleNotificationClick(notification)"
                            class="px-6 py-4 hover:bg-gray-50 cursor-pointer transition-colors relative group"
                            :class="{ 'bg-indigo-50': !notification.read_at }"
                        >
                            <!-- Unread Indicator -->
                            <div
                                v-if="!notification.read_at"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 w-2 h-2 bg-indigo-600 rounded-full"
                            ></div>

                            <div class="flex items-start space-x-3 ml-4">
                                <!-- Icon -->
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                                    :class="
                                        getIconColor(notification.data.color)
                                    "
                                >
                                    <component
                                        :is="getIcon(notification.data.icon)"
                                        class="h-5 w-5 text-white"
                                    />
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        {{ notification.data.title }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ notification.data.message }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{
                                            formatTime(notification.created_at)
                                        }}
                                    </p>
                                </div>

                                <!-- Delete Button -->
                                <button
                                    @click.stop="
                                        deleteNotification(notification.id)
                                    "
                                    class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-red-600"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    v-if="notifications.length > 0"
                    class="px-6 py-3 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
                >
                    <button
                        @click="viewAllNotifications"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors"
                    >
                        Lihat Semua Notifikasi
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { router } from "@inertiajs/vue3";
import {
    BellIcon,
    XMarkIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    ClockIcon,
    DocumentTextIcon,
    MegaphoneIcon,
} from "@heroicons/vue/24/outline";
import axios from "axios";

const isOpen = ref(false);
const notifications = ref([]);
const unreadCount = ref(0);
const loading = ref(false);
const echoChannel = ref(null);

const props = defineProps({
    userId: {
        type: Number,
        required: true,
    },
});

onMounted(() => {
    fetchUnreadCount();
    setupEcho();
});

onUnmounted(() => {
    if (echoChannel.value) {
        window.Echo.leave(`App.User.${props.userId}`);
    }
});

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && notifications.value.length === 0) {
        fetchNotifications();
    }
};

const closeDropdown = () => {
    isOpen.value = false;
};

const fetchNotifications = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/notifications?limit=20");
        notifications.value = response.data.notifications;
        unreadCount.value = response.data.unread_count;
    } catch (error) {
        console.error("Error fetching notifications:", error);
    } finally {
        loading.value = false;
    }
};

const fetchUnreadCount = async () => {
    try {
        const response = await axios.get("/api/notifications/unread-count");
        unreadCount.value = response.data.count;
    } catch (error) {
        console.error("Error fetching unread count:", error);
    }
};

const markAsRead = async (notificationId) => {
    try {
        await axios.post(`/api/notifications/${notificationId}/mark-as-read`);

        // Update local state
        const notification = notifications.value.find(
            (n) => n.id === notificationId
        );
        if (notification) {
            notification.read_at = new Date().toISOString();
            unreadCount.value = Math.max(0, unreadCount.value - 1);
        }
    } catch (error) {
        console.error("Error marking notification as read:", error);
    }
};

const markAllAsRead = async () => {
    try {
        await axios.post("/api/notifications/mark-all-as-read");

        // Update local state
        notifications.value.forEach((n) => {
            n.read_at = new Date().toISOString();
        });
        unreadCount.value = 0;
    } catch (error) {
        console.error("Error marking all notifications as read:", error);
    }
};

const deleteNotification = async (notificationId) => {
    try {
        await axios.delete(`/api/notifications/${notificationId}`);

        // Remove from local state
        const index = notifications.value.findIndex(
            (n) => n.id === notificationId
        );
        if (index > -1) {
            const wasUnread = !notifications.value[index].read_at;
            notifications.value.splice(index, 1);
            if (wasUnread) {
                unreadCount.value = Math.max(0, unreadCount.value - 1);
            }
        }
    } catch (error) {
        console.error("Error deleting notification:", error);
    }
};

const handleNotificationClick = async (notification) => {
    // Mark as read if unread
    if (!notification.read_at) {
        await markAsRead(notification.id);
    }

    // Navigate to action URL if exists
    if (notification.data.action_url) {
        router.visit(notification.data.action_url);
        closeDropdown();
    }
};

const viewAllNotifications = () => {
    router.visit("/notifications");
    closeDropdown();
};

const getIcon = (iconName) => {
    const iconMap = {
        "check-circle": CheckCircleIcon,
        "x-circle": ExclamationCircleIcon,
        clock: ClockIcon,
        document: DocumentTextIcon,
        megaphone: MegaphoneIcon,
    };
    return iconMap[iconName] || BellIcon;
};

const getIconColor = (color) => {
    const colorMap = {
        blue: "bg-gradient-to-br from-blue-500 to-indigo-600",
        green: "bg-gradient-to-br from-emerald-500 to-green-600",
        red: "bg-gradient-to-br from-rose-500 to-red-600",
        emerald: "bg-gradient-to-br from-emerald-500 to-green-600",
        amber: "bg-gradient-to-br from-amber-500 to-orange-600",
        indigo: "bg-gradient-to-br from-indigo-500 to-purple-600",
    };
    return colorMap[color] || "bg-gradient-to-br from-gray-500 to-gray-600";
};

const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) return "Baru saja";
    if (diffInSeconds < 3600)
        return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
    if (diffInSeconds < 86400)
        return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
    if (diffInSeconds < 604800)
        return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;

    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

// Setup real-time notifications with Laravel Echo
const setupEcho = () => {
    // Listen to private notification channel
    if (window.Echo && props.userId) {
        echoChannel.value = window.Echo.private(
            `App.User.${props.userId}`
        ).notification((notification) => {
            console.log("Received real-time notification:", notification);

            // Add to notifications list
            notifications.value.unshift({
                id: notification.id,
                type: notification.type,
                data: notification,
                read_at: null,
                created_at: new Date().toISOString(),
            });

            // Increment unread count
            unreadCount.value++;

            // Show browser notification if permitted
            if (
                "Notification" in window &&
                Notification.permission === "granted"
            ) {
                new Notification(notification.title, {
                    body: notification.message,
                    icon: "/logo.png",
                });
            }
        });
    }

    // Request notification permission
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }
};

// Click outside directive
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.addEventListener("click", el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener("click", el.clickOutsideEvent);
    },
};
</script>

<style scoped>
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(99, 102, 241, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.3);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(99, 102, 241, 0.5);
}
</style>
