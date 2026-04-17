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
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
    >
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Akhir</h1>
            <p class="text-gray-600 mt-2">
                Kelola laporan dan statistik magang Anda
            </p>
        </div>
        <Link
            :href="createHref"
            class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
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
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
            </svg>
            Buat Laporan
        </Link>
    </div>
</template>
