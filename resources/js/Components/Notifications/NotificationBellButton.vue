<template>
    <button
        @click="$emit('toggle')"
        data-notification-bell
        class="relative p-2 text-blue-500 hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg transition-all duration-200"
        :class="{ 'bg-white bg-opacity-10': isOpen }"
    >
        <BellIcon class="h-6 w-6" />

        <span
            v-if="unreadCount > 0"
            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full animate-pulse"
        >
            {{ unreadCount > 99 ? "99+" : unreadCount }}
        </span>

        <span
            :class="[
                'absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white',
                isWebSocketConnected
                    ? 'bg-green-500'
                    : 'bg-yellow-500 animate-pulse',
            ]"
            :title="
                isWebSocketConnected ? 'Real-time aktif' : 'Menggunakan polling'
            "
        ></span>
    </button>
</template>

<script setup>
import { BellIcon } from "@heroicons/vue/24/outline";

defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    unreadCount: {
        type: Number,
        default: 0,
    },
    isWebSocketConnected: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["toggle"]);
</script>
