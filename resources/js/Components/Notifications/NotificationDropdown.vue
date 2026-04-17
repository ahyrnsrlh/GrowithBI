<template>
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
            <div
                class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50 to-blue-100 rounded-t-2xl"
            >
                <div class="flex items-center space-x-3">
                    <h3 class="text-lg font-bold text-gray-900">Notifikasi</h3>
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
                        <span>{{
                            isWebSocketConnected ? "Live" : "Polling"
                        }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        v-if="unreadCount > 0"
                        @click="$emit('mark-all')"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                    >
                        Tandai Semua
                    </button>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </div>
            </div>

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
                    <BellIcon class="h-16 w-16 text-gray-300 mx-auto mb-4" />
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
                        @click="$emit('click-notification', notification)"
                        class="px-6 py-4 hover:bg-gray-50 cursor-pointer transition-colors relative group"
                        :class="{ 'bg-blue-50': !notification.read_at }"
                    >
                        <div
                            v-if="!notification.read_at"
                            class="absolute left-2 top-1/2 transform -translate-y-1/2 w-2 h-2 bg-blue-600 rounded-full"
                        ></div>

                        <div class="flex items-start space-x-3 ml-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                                :class="getIconColor(notification.data.color)"
                            >
                                <component
                                    :is="getIcon(notification.data.icon)"
                                    class="h-5 w-5 text-white"
                                />
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ notification.data.title }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ getDisplayMessage(notification) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    {{ formatTime(notification.created_at) }}
                                </p>
                            </div>

                            <button
                                @click.stop="
                                    $emit(
                                        'delete-notification',
                                        notification.id,
                                    )
                                "
                                class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-red-600"
                            >
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="notifications.length > 0"
                class="px-6 py-3 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
            >
                <button
                    @click="$emit('view-all')"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                >
                    Lihat Semua Notifikasi
                </button>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";

defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    notifications: {
        type: Array,
        default: () => [],
    },
    unreadCount: {
        type: Number,
        default: 0,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    isWebSocketConnected: {
        type: Boolean,
        default: false,
    },
    getIcon: {
        type: Function,
        required: true,
    },
    getIconColor: {
        type: Function,
        required: true,
    },
    formatTime: {
        type: Function,
        required: true,
    },
    getDisplayMessage: {
        type: Function,
        required: true,
    },
});

defineEmits([
    "mark-all",
    "close",
    "click-notification",
    "delete-notification",
    "view-all",
]);
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
