<template>
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
    >
        <div>
            <div class="flex items-center space-x-4 mb-2">
                <Link
                    :href="route('admin.logbooks.index')"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Kembali ke Daftar
                </Link>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ logbook.title }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ logbook.user.name }} - {{ formatDate(logbook.date) }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span
                :class="getStatusClass(logbook.status)"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            >
                {{ getStatusText(logbook.status) }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});
</script>
