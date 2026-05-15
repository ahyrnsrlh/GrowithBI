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
    <div class="flex items-start justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <Link
                :href="backHref"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-200"
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
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>
            </Link>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Tambah Divisi Baru
                </h1>
                <p class="text-sm text-gray-500">
                    Lengkapi informasi divisi magang
                </p>
            </div>
        </div>
    </div>
</template>
