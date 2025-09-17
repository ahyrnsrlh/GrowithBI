<template>
    <AdminLayout
        title="Notifikasi"
        subtitle="Kelola dan lihat semua notifikasi sistem"
    >
        <!-- Header Actions -->
        <div
            class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="mb-4 sm:mb-0">
                <div class="flex items-center space-x-4">
                    <!-- Stats -->
                    <div
                        class="flex items-center space-x-6 text-sm text-gray-600"
                    >
                        <span class="flex items-center">
                            <div
                                class="w-3 h-3 bg-blue-500 rounded-full mr-2"
                            ></div>
                            Total: {{ stats.total }}
                        </span>
                        <span class="flex items-center">
                            <div
                                class="w-3 h-3 bg-red-500 rounded-full mr-2"
                            ></div>
                            Belum dibaca: {{ stats.unread }}
                        </span>
                        <span class="flex items-center">
                            <div
                                class="w-3 h-3 bg-green-500 rounded-full mr-2"
                            ></div>
                            Sudah dibaca: {{ stats.read }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <button
                    @click="markAllAsRead"
                    :disabled="stats.unread === 0"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <div
                class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4"
            >
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select
                        v-model="filters.filter"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="all">Semua</option>
                        <option value="unread">Belum dibaca</option>
                        <option value="read">Sudah dibaca</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipe
                    </label>
                    <select
                        v-model="filters.type"
                        @change="applyFilters"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="all">Semua Tipe</option>
                        <option value="application">Aplikasi</option>
                        <option value="logbook">Laporan Harian</option>
                        <option value="final_report">Laporan Akhir</option>
                        <option value="status">Update Status</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div v-if="notifications.length === 0" class="text-center py-12">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-5 5v-5zM4 17h8a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    Tidak ada notifikasi
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Belum ada notifikasi yang sesuai dengan filter.
                </p>
            </div>

            <div v-else class="divide-y divide-gray-200">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    :class="[
                        'px-6 py-4 hover:bg-gray-50 cursor-pointer transition-colors',
                        !notification.read_at
                            ? 'bg-blue-50 border-l-4 border-blue-500'
                            : '',
                    ]"
                    @click="handleNotificationClick(notification)"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Type Icon -->
                            <div class="flex-shrink-0 mt-1">
                                <div
                                    :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center',
                                        getTypeColorClass(notification.type),
                                    ]"
                                >
                                    <component
                                        :is="getTypeIcon(notification.type)"
                                        class="w-4 h-4 text-white"
                                    />
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ notification.title }}
                                    </h4>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            v-if="!notification.read_at"
                                            class="w-2 h-2 bg-blue-500 rounded-full"
                                        ></span>
                                        <span class="text-xs text-gray-500">
                                            {{
                                                formatTime(
                                                    notification.created_at
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ notification.message }}
                                </p>
                                <div class="mt-2 flex items-center space-x-4">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            getTypeBadgeClass(
                                                notification.type
                                            ),
                                        ]"
                                    >
                                        {{ getTypeLabel(notification.type) }}
                                    </span>
                                    <span
                                        v-if="notification.read_at"
                                        class="text-xs text-gray-500"
                                    >
                                        Dibaca
                                        {{ formatTime(notification.read_at) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2 ml-4">
                            <button
                                v-if="!notification.read_at"
                                @click.stop="markAsRead(notification.id)"
                                class="p-1 text-gray-400 hover:text-blue-600 rounded"
                                title="Tandai sebagai dibaca"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </button>
                            <button
                                @click.stop="
                                    deleteNotification(notification.id)
                                "
                                class="p-1 text-gray-400 hover:text-red-600 rounded"
                                title="Hapus notifikasi"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            v-if="pagination.last_page > 1"
            class="mt-6 flex items-center justify-between"
        >
            <div class="text-sm text-gray-700">
                Menampilkan
                {{
                    (pagination.current_page - 1) * pagination.per_page + 1
                }}
                sampai
                {{
                    Math.min(
                        pagination.current_page * pagination.per_page,
                        pagination.total
                    )
                }}
                dari {{ pagination.total }} notifikasi
            </div>
            <div class="flex items-center space-x-2">
                <button
                    :disabled="pagination.current_page === 1"
                    @click="changePage(pagination.current_page - 1)"
                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                >
                    Sebelumnya
                </button>
                <span class="text-sm text-gray-700">
                    Halaman {{ pagination.current_page }} dari
                    {{ pagination.last_page }}
                </span>
                <button
                    :disabled="pagination.current_page === pagination.last_page"
                    @click="changePage(pagination.current_page + 1)"
                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                >
                    Selanjutnya
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, defineComponent } from "vue";

// Icons
const DocumentTextIcon = defineComponent({
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
});

const BookOpenIcon = defineComponent({
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>`,
});

const ClipboardCheckIcon = defineComponent({
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>`,
});

const InformationCircleIcon = defineComponent({
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
});

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

const filters = ref({ ...props.filters });

// Helper functions
const getTypeIcon = (type) => {
    const icons = {
        application: DocumentTextIcon,
        logbook: BookOpenIcon,
        final_report: ClipboardCheckIcon,
        status: InformationCircleIcon,
    };
    return icons[type] || InformationCircleIcon;
};

const getTypeColorClass = (type) => {
    const colors = {
        application: "bg-blue-500",
        logbook: "bg-green-500",
        final_report: "bg-purple-500",
        status: "bg-yellow-500",
    };
    return colors[type] || "bg-gray-500";
};

const getTypeBadgeClass = (type) => {
    const colors = {
        application: "bg-blue-100 text-blue-800",
        logbook: "bg-green-100 text-green-800",
        final_report: "bg-purple-100 text-purple-800",
        status: "bg-yellow-100 text-yellow-800",
    };
    return colors[type] || "bg-gray-100 text-gray-800";
};

const getTypeLabel = (type) => {
    const labels = {
        application: "Aplikasi",
        logbook: "Laporan Harian",
        final_report: "Laporan Akhir",
        status: "Status Update",
    };
    return labels[type] || "Lainnya";
};

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

// Event handlers
const handleNotificationClick = (notification) => {
    // Mark as read if not already read
    if (!notification.read_at) {
        markAsRead(notification.id);
    }

    // Navigate based on notification type
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
    }
};

const markAsRead = async (notificationId) => {
    try {
        await router.put(
            `/admin/notifications/${notificationId}/mark-read`,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    // Refresh page to update notification status
                    router.reload({ only: ["notifications", "stats"] });
                },
            }
        );
    } catch (error) {
        console.error("Error marking notification as read:", error);
    }
};

const markAllAsRead = async () => {
    try {
        await router.post(
            "/admin/notifications/mark-all-read",
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ["notifications", "stats"] });
                },
            }
        );
    } catch (error) {
        console.error("Error marking all notifications as read:", error);
    }
};

const deleteNotification = async (notificationId) => {
    if (!confirm("Apakah Anda yakin ingin menghapus notifikasi ini?")) {
        return;
    }

    try {
        await router.delete(`/admin/notifications/${notificationId}`, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ["notifications", "stats"] });
            },
        });
    } catch (error) {
        console.error("Error deleting notification:", error);
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
        }
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
        }
    );
};
</script>
