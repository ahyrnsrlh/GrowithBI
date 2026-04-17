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
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Divisi</h2>
            <p class="text-gray-600">
                Kelola semua divisi magang yang tersedia
            </p>
        </div>
        <Link
            :href="createHref"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2"
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
