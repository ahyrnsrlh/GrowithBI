<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    report: {
        type: Object,
        required: true,
    },
    editHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        v-if="report.status === 'draft' || report.status === 'revision'"
        class="flex space-x-3"
    >
        <Link
            :href="editHref"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
        >
            <svg
                class="w-4 h-4 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
            </svg>
            Edit Laporan
        </Link>
    </div>
</template>
