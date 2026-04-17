<script setup>
defineProps({
    notifications: {
        type: Array,
        required: true,
    },
    formatTime: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits([
    "notification-click",
    "mark-as-read",
    "delete-notification",
]);

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

const getTypeIconPath = (type) => {
    const iconPaths = {
        application:
            "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        logbook:
            "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
        final_report:
            "M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4",
        status: "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    };

    return iconPaths[type] || iconPaths.status;
};
</script>

<template>
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
                @click="emit('notification-click', notification)"
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="flex-shrink-0 mt-1">
                            <div
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center',
                                    getTypeColorClass(notification.type),
                                ]"
                            >
                                <svg
                                    class="w-4 h-4 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        :d="getTypeIconPath(notification.type)"
                                    />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900">
                                    {{ notification.title }}
                                </h4>
                                <div class="flex items-center space-x-2">
                                    <span
                                        v-if="!notification.read_at"
                                        class="w-2 h-2 bg-blue-500 rounded-full"
                                    ></span>
                                    <span class="text-xs text-gray-500">
                                        {{
                                            formatTime(notification.created_at)
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
                                        getTypeBadgeClass(notification.type),
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

                    <div class="flex items-center space-x-2 ml-4">
                        <button
                            v-if="!notification.read_at"
                            @click.stop="emit('mark-as-read', notification.id)"
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
                                emit('delete-notification', notification.id)
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
</template>
