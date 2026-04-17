import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export function useAdminNotificationsIndexPage(initialFilters) {
    const filters = ref({ ...initialFilters });

    const formatTime = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;

        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return "Baru saja";
        if (minutes < 60) return `${minutes} menit yang lalu`;
        if (hours < 24) return `${hours} jam yang lalu`;
        if (days < 7) return `${days} hari yang lalu`;

        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    };

    const markAsRead = (notificationId) => {
        router.put(
            `/admin/notifications/${notificationId}/mark-read`,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ["notifications", "stats"] });
                },
            },
        );
    };

    const markAllAsRead = () => {
        router.post(
            "/admin/notifications/mark-all-read",
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ["notifications", "stats"] });
                },
            },
        );
    };

    const deleteNotification = (notificationId) => {
        if (!confirm("Apakah Anda yakin ingin menghapus notifikasi ini?")) {
            return;
        }

        router.delete(`/admin/notifications/${notificationId}`, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ["notifications", "stats"] });
            },
        });
    };

    const handleNotificationClick = (notification) => {
        if (!notification.read_at) {
            markAsRead(notification.id);
        }

        switch (notification.type) {
            case "application":
                router.visit("/admin/applications");
                break;
            case "logbook":
                router.visit("/admin/logbooks");
                break;
            case "final_report":
                router.visit("/admin/final-reports");
                break;
            case "status":
                router.visit("/admin/dashboard");
                break;
            default:
                break;
        }
    };

    const applyFilters = () => {
        router.get(
            "/admin/notifications",
            {
                filter: filters.value.filter,
                type: filters.value.type,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const changePage = (page) => {
        router.get(
            "/admin/notifications",
            {
                page,
                filter: filters.value.filter,
                type: filters.value.type,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    return {
        filters,
        formatTime,
        markAsRead,
        markAllAsRead,
        deleteNotification,
        handleNotificationClick,
        applyFilters,
        changePage,
    };
}
