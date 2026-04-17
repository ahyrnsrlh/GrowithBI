<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    backHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-center space-x-4 mb-8">
        <Link :href="backHref" class="text-blue-600 hover:text-blue-700">
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
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>
        </Link>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Divisi Baru</h1>
            <p class="text-gray-600">Lengkapi informasi divisi magang</p>
        </div>
    </div>
</template>
