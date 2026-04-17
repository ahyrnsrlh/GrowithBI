<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    createHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="text-center py-16">
        <svg
            class="w-16 h-16 text-gray-400 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            Belum ada divisi
        </h3>
        <p class="text-gray-600 mb-4">
            Mulai dengan membuat divisi magang pertama
        </p>
        <Link
            :href="createHref"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>
            <span>Tambah Divisi</span>
        </Link>
    </div>
</template>
