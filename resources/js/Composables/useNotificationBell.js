import { onMounted, onUnmounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import {
    BellIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    ClockIcon,
    DocumentTextIcon,
    MegaphoneIcon,
} from "@heroicons/vue/24/outline";

export function useNotificationBell(props) {
    const isOpen = ref(false);
    const notifications = ref([]);
    const unreadCount = ref(0);
    const loading = ref(false);
    const echoChannel = ref(null);
    const isWebSocketConnected = ref(false);
    let pollingInterval = null;

    const handleClickOutside = (event) => {
        const bellButton = event.target.closest("[data-notification-bell]");
        const dropdownPanel = event.target.closest(
            "[data-notification-dropdown]",
        );

        if (!bellButton && !dropdownPanel && isOpen.value) {
            closeDropdown();
        }
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

    const toggleDropdown = () => {
        isOpen.value = !isOpen.value;

        if (isOpen.value && notifications.value.length === 0) {
            fetchNotifications();
        }
    };

    const closeDropdown = () => {
        isOpen.value = false;
    };

    const markAsRead = async (notificationId) => {
        try {
            await axios.post(
                `/api/notifications/${notificationId}/mark-as-read`,
            );

            const notification = notifications.value.find(
                (n) => n.id === notificationId,
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

            const index = notifications.value.findIndex(
                (n) => n.id === notificationId,
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
        if (!notification.read_at) {
            await markAsRead(notification.id);
        }

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
            "arrow-right-on-rectangle": CheckCircleIcon,
            "arrow-left-on-rectangle": CheckCircleIcon,
            "map-pin": ExclamationCircleIcon,
            "user-circle": CheckCircleIcon,
            "chat-bubble-left-right": MegaphoneIcon,
            "book-open": DocumentTextIcon,
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

        if (diffInSeconds < 60) {
            return "Baru saja";
        }

        if (diffInSeconds < 3600) {
            return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
        }

        if (diffInSeconds < 86400) {
            return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
        }

        if (diffInSeconds < 604800) {
            return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
        }

        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    };

    const getDisplayMessage = (notification) => {
        if (props.userRole !== "admin") {
            return notification.data.message;
        }

        const data = notification.data;
        const message = data.message;

        if (!message.includes("Anda")) {
            return message;
        }

        const userName =
            data.user_name ||
            data.attendance_user_name ||
            data.logbook_user_name ||
            "User";

        let transformedMessage = message;

        if (message.includes("Anda telah berhasil check-in")) {
            const time = message.match(/pada (\d{2}:\d{2}:\d{2})/)?.[1] || "";
            transformedMessage = `${userName} telah check-in pada ${time}`;
        } else if (message.includes("Anda telah berhasil check-out")) {
            const time = message.match(/pada (\d{2}:\d{2}:\d{2})/)?.[1] || "";
            transformedMessage = `${userName} telah check-out pada ${time}`;
        } else if (message.includes("Logbook Anda untuk tanggal")) {
            const dateMatch = message.match(/tanggal (\d{2}\/\d{2}\/\d{4})/);
            const date = dateMatch ? dateMatch[1] : "";
            if (message.includes("telah berhasil dikirim")) {
                transformedMessage = `${userName} submit logbook tanggal ${date}`;
            } else if (message.includes("telah disetujui")) {
                transformedMessage = `Logbook ${userName} tanggal ${date} telah disetujui`;
            } else if (message.includes("perlu direvisi")) {
                transformedMessage = `Logbook ${userName} tanggal ${date} perlu direvisi`;
            }
        } else if (message.includes("Pendaftaran Anda untuk posisi")) {
            const positionMatch = message.match(/posisi ([^t]+) telah/);
            const position = positionMatch ? positionMatch[1].trim() : "";
            if (message.includes("telah berhasil dikirim")) {
                transformedMessage = `${userName} mendaftar untuk posisi ${position}`;
            }
        } else if (message.includes("Laporan akhir Anda")) {
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

    const handleRealtimeNotification = (notification) => {
        notifications.value.unshift({
            id: notification.id,
            type: notification.type,
            data: notification,
            read_at: null,
            created_at: new Date().toISOString(),
        });

        unreadCount.value += 1;

        if ("Notification" in window && Notification.permission === "granted") {
            // eslint-disable-next-line no-new
            new Notification(notification.title || "Notifikasi Baru", {
                body: notification.message || "",
                icon: "/logo.png",
            });
        }
    };

    const stopPolling = () => {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    };

    const startPolling = () => {
        if (pollingInterval) {
            return;
        }

        pollingInterval = setInterval(async () => {
            try {
                await fetchUnreadCount();
                if (isOpen.value) {
                    await fetchNotifications();
                }
            } catch (error) {
                console.error("Polling error:", error);
            }
        }, 30000);
    };

    const setupEcho = () => {
        if (!window.Echo || !props.userId) {
            startPolling();
            return;
        }

        try {
            const channelName = `App.Models.User.${props.userId}`;

            echoChannel.value = window.Echo.private(channelName)
                .notification((notification) => {
                    handleRealtimeNotification(notification);
                })
                .error(() => {
                    if (!isWebSocketConnected.value) {
                        startPolling();
                    }
                });

            if (window.Echo.connector?.pusher?.connection) {
                window.Echo.connector.pusher.connection.bind(
                    "connected",
                    () => {
                        isWebSocketConnected.value = true;
                        stopPolling();
                    },
                );

                window.Echo.connector.pusher.connection.bind(
                    "disconnected",
                    () => {
                        isWebSocketConnected.value = false;
                        startPolling();
                    },
                );

                window.Echo.connector.pusher.connection.bind(
                    "unavailable",
                    () => {
                        isWebSocketConnected.value = false;
                        startPolling();
                    },
                );
            }
        } catch (error) {
            console.error("Echo setup failed:", error);
            startPolling();
        }
    };

    onMounted(() => {
        if (!props.userId) {
            return;
        }

        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission();
        }

        fetchUnreadCount();
        setupEcho();
        document.addEventListener("click", handleClickOutside);
    });

    onUnmounted(() => {
        stopPolling();
        if (echoChannel.value) {
            window.Echo.leave(`App.Models.User.${props.userId}`);
        }
        document.removeEventListener("click", handleClickOutside);
    });

    return {
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
    };
}
