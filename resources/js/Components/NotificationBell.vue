<template>
    <div class="relative">
        <!-- Notification Bell Button -->
        <button
            @click="toggleDropdown"
            data-notification-bell
            class="relative p-2 text-blue-500 hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg transition-all duration-200"
            :class="{ 'bg-white bg-opacity-10': isOpen }"
        >
            <BellIcon class="h-6 w-6" />

            <!-- Unread Badge -->
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full animate-pulse"
            >
                {{ unreadCount > 99 ? "99+" : unreadCount }}
            </span>

            <!-- Connection Status Indicator -->
            <span
                :class="[
                    'absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white',
                    isWebSocketConnected
                        ? 'bg-green-500'
                        : 'bg-yellow-500 animate-pulse',
                ]"
                :title="
                    isWebSocketConnected
                        ? 'Real-time aktif'
                        : 'Menggunakan polling'
                "
            ></span>
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
                data-notification-dropdown
                class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 max-h-[600px] flex flex-col"
            >
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50 to-blue-100 rounded-t-2xl"
                >
                    <div class="flex items-center space-x-3">
                        <h3 class="text-lg font-bold text-gray-900">
                            Notifikasi
                        </h3>
                        <!-- Connection Status Badge -->
                        <div
                            :class="[
                                'px-2 py-1 rounded-full text-xs font-medium flex items-center space-x-1',
                                isWebSocketConnected
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-1.5 h-1.5 rounded-full',
                                    isWebSocketConnected
                                        ? 'bg-green-600'
                                        : 'bg-yellow-600',
                                ]"
                            ></div>
                            <span>
                                {{ isWebSocketConnected ? "Live" : "Polling" }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            v-if="unreadCount > 0"
                            @click="markAllAsRead"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
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
                            class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"
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
                            :class="{ 'bg-blue-50': !notification.read_at }"
                        >
                            <!-- Unread Indicator -->
                            <div
                                v-if="!notification.read_at"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 w-2 h-2 bg-blue-600 rounded-full"
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
                                        {{ getDisplayMessage(notification) }}
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
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
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
const dropdownRef = ref(null);
const isWebSocketConnected = ref(false);
let pollingInterval = null;

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

onMounted(() => {
    if (!props.userId) {
        console.warn(
            "📢 NotificationBell: userId is required but not provided"
        );
        return;
    }
    console.log("📢 NotificationBell mounted for userId:", props.userId);

    // Request notification permission
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }

    fetchUnreadCount();
    setupEcho();
    // Add click outside listener
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    stopPolling();
    if (echoChannel.value) {
        // Must match the channel name used in setupEcho
        window.Echo.leave(`App.Models.User.${props.userId}`);
    }
    // Remove click outside listener
    document.removeEventListener("click", handleClickOutside);
});

const handleClickOutside = (event) => {
    // Check if click is outside the dropdown component
    const bellButton = event.target.closest("[data-notification-bell]");
    const dropdownPanel = event.target.closest("[data-notification-dropdown]");

    // Close dropdown if click is outside both button and dropdown
    if (!bellButton && !dropdownPanel && isOpen.value) {
        closeDropdown();
    }
};

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
    console.log("🔍 Fetching notifications for userId:", props.userId);
    try {
        const response = await axios.get("/api/notifications?limit=20");
        console.log("✅ Notifications fetched:", response.data);
        notifications.value = response.data.notifications;
        unreadCount.value = response.data.unread_count;
    } catch (error) {
        console.error("❌ Error fetching notifications:", {
            status: error.response?.status,
            statusText: error.response?.statusText,
            data: error.response?.data,
            message: error.message,
        });
    } finally {
        loading.value = false;
    }
};

const fetchUnreadCount = async () => {
    console.log("🔢 Fetching unread count for userId:", props.userId);
    try {
        const response = await axios.get("/api/notifications/unread-count");
        console.log("✅ Unread count fetched:", response.data.count);
        unreadCount.value = response.data.count;
    } catch (error) {
        console.error("❌ Error fetching unread count:", {
            status: error.response?.status,
            statusText: error.response?.statusText,
            data: error.response?.data,
            message: error.message,
            userId: props.userId,
        });
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
        "check-badge": CheckCircleIcon,
        "x-circle": ExclamationCircleIcon,
        "exclamation-triangle": ExclamationCircleIcon,
        "exclamation-circle": ExclamationCircleIcon,
        clock: ClockIcon,
        document: DocumentTextIcon,
        "document-text": DocumentTextIcon,
        "document-check": DocumentTextIcon,
        "document-arrow-up": DocumentTextIcon,
        "paper-airplane": DocumentTextIcon,
        megaphone: MegaphoneIcon,
        bell: BellIcon,
        "bell-alert": BellIcon,
        // Attendance icons (use available alternatives)
        "arrow-right-on-rectangle": CheckCircleIcon,
        "arrow-left-on-rectangle": CheckCircleIcon,
        "map-pin": ExclamationCircleIcon,
        "user-circle": CheckCircleIcon,
        // Logbook icons
        "chat-bubble-left-right": MegaphoneIcon,
        "book-open": DocumentTextIcon,
        // Report icons
        "magnifying-glass": ClockIcon,
        "arrow-path": ClockIcon,
        "academic-cap": CheckCircleIcon,
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

// Transform notification message berdasarkan role user
const getDisplayMessage = (notification) => {
    // Jika role bukan admin, return message original
    if (props.userRole !== "admin") {
        return notification.data.message;
    }

    // Untuk admin, extract user name dari notification data jika ada
    const data = notification.data;
    const message = data.message;

    // Jika message sudah mengandung nama user (bukan "Anda"), return as is
    if (!message.includes("Anda")) {
        return message;
    }

    // Pattern untuk extract info dari notification
    // Default: jika ada user_name di data, gunakan itu
    const userName =
        data.user_name ||
        data.attendance_user_name ||
        data.logbook_user_name ||
        "User";

    // Transform message "Anda" menjadi nama user untuk admin
    let transformedMessage = message;

    // Check-in/Check-out attendance
    if (message.includes("Anda telah berhasil check-in")) {
        const time = message.match(/pada (\d{2}:\d{2}:\d{2})/)?.[1] || "";
        transformedMessage = `${userName} telah check-in pada ${time}`;
    } else if (message.includes("Anda telah berhasil check-out")) {
        const time = message.match(/pada (\d{2}:\d{2}:\d{2})/)?.[1] || "";
        transformedMessage = `${userName} telah check-out pada ${time}`;
    }
    // Logbook
    else if (message.includes("Logbook Anda untuk tanggal")) {
        const dateMatch = message.match(/tanggal (\d{2}\/\d{2}\/\d{4})/);
        const date = dateMatch ? dateMatch[1] : "";
        if (message.includes("telah berhasil dikirim")) {
            transformedMessage = `${userName} submit logbook tanggal ${date}`;
        } else if (message.includes("telah disetujui")) {
            transformedMessage = `Logbook ${userName} tanggal ${date} telah disetujui`;
        } else if (message.includes("perlu direvisi")) {
            transformedMessage = `Logbook ${userName} tanggal ${date} perlu direvisi`;
        }
    }
    // Application/Pendaftaran
    else if (message.includes("Pendaftaran Anda untuk posisi")) {
        const positionMatch = message.match(/posisi ([^t]+) telah/);
        const position = positionMatch ? positionMatch[1].trim() : "";
        if (message.includes("telah berhasil dikirim")) {
            transformedMessage = `${userName} mendaftar untuk posisi ${position}`;
        }
    }
    // Report
    else if (message.includes("Laporan akhir Anda")) {
        if (message.includes("telah berhasil dikirim")) {
            transformedMessage = `${userName} telah mengirim laporan akhir`;
        } else if (message.includes("telah disetujui")) {
            transformedMessage = `Laporan akhir ${userName} telah disetujui`;
        } else if (message.includes("memerlukan revisi")) {
            transformedMessage = `Laporan akhir ${userName} memerlukan revisi`;
        }
    }

    return transformedMessage;
};

// Setup real-time notifications with Laravel Echo
const setupEcho = () => {
    if (!window.Echo || !props.userId) {
        console.warn("⚠️ Laravel Echo not available, falling back to polling");
        startPolling();
        return;
    }

    try {
        // Listen to private notification channel
        // IMPORTANT: Laravel's notification system broadcasts to 'App.Models.User.{id}'
        const channelName = `App.Models.User.${props.userId}`;
        console.log("📡 Subscribing to channel:", channelName);

        echoChannel.value = window.Echo.private(channelName)
            .notification((notification) => {
                console.log(
                    "✅ Real-time notification received:",
                    notification
                );
                handleRealtimeNotification(notification);
            })
            .error((error) => {
                console.error("❌ Echo channel error:", error);
                if (!isWebSocketConnected.value) {
                    startPolling();
                }
            });

        // Monitor connection status
        if (window.Echo.connector?.pusher?.connection) {
            window.Echo.connector.pusher.connection.bind("connected", () => {
                console.log("✅ WebSocket connected");
                isWebSocketConnected.value = true;
                stopPolling();
            });

            window.Echo.connector.pusher.connection.bind("disconnected", () => {
                console.log("⚠️ WebSocket disconnected, starting polling");
                isWebSocketConnected.value = false;
                startPolling();
            });

            window.Echo.connector.pusher.connection.bind("unavailable", () => {
                console.log("⚠️ WebSocket unavailable, using polling");
                isWebSocketConnected.value = false;
                startPolling();
            });
        }
    } catch (error) {
        console.error("❌ Echo setup failed:", error);
        startPolling();
    }
};

// Handle realtime notification
const handleRealtimeNotification = (notification) => {
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
    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(notification.title || "Notifikasi Baru", {
            body: notification.message || "",
            icon: "/logo.png",
        });
    }
};

// Start polling as fallback
const startPolling = () => {
    if (pollingInterval) return; // Prevent multiple intervals

    console.log("🔄 Starting polling mode (fallback)");

    // Poll every 30 seconds
    pollingInterval = setInterval(async () => {
        try {
            await fetchUnreadCount();

            // If dropdown is open, refresh notifications too
            if (isOpen.value) {
                await fetchNotifications();
            }
        } catch (error) {
            console.error("Polling error:", error);
        }
    }, 30000); // 30 seconds
};

// Stop polling
const stopPolling = () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
        console.log("⏹️ Stopped polling mode");
    }
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
