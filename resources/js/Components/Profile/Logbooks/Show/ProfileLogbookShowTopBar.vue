<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    homeHref: {
        type: String,
        required: true,
    },
    backHref: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="sticky top-0 z-50 bg-gradient-to-r from-blue-800 to-indigo-900 border-b border-blue-600 shadow-lg"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <div class="flex items-center space-x-3">
                    <Link :href="homeHref">
                        <img
                            src="/logo.png"
                            alt="Bank Indonesia"
                            class="h-10 w-auto"
                        />
                    </Link>
                    <div class="hidden sm:block">
                        <h1 class="text-white font-bold text-lg">GrowithBI</h1>
                        <p class="text-blue-200 text-xs">
                            Bank Indonesia Lampung
                        </p>
                    </div>
                </div>

                <Link
                    :href="backHref"
                    class="inline-flex items-center text-sm text-blue-200 hover:text-white transition-colors group"
                >
                    <svg
                        class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-0.5 transition-transform"
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
                    <span class="font-medium">Kembali</span>
                </Link>
            </div>
        </div>
    </div>
</template>
