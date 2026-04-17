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
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Upload Laporan Akhir
            </h1>
            <p class="text-gray-600 mt-2">
                Upload file laporan akhir magang Anda dalam format Word, Excel,
                atau PDF
            </p>
        </div>
        <Link
            :href="backHref"
            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
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
                    d="M15 19l-7-7 7-7"
                />
            </svg>
            Kembali
        </Link>
    </div>
</template>
