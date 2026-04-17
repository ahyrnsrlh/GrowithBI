<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    title: {
        type: String,
        required: true,
    },
    backHref: {
        type: String,
        required: true,
    },
    backLabel: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">{{ title }}</h2>
        <Link
            :href="backHref"
            class="text-sm text-gray-600 hover:text-gray-900 flex items-center"
        >
            <svg
                class="w-4 h-4 mr-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
            {{ backLabel }}
        </Link>
    </div>
</template>
