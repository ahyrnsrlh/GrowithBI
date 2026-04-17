<template>
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <nav class="flex items-center space-x-2 text-sm mb-6">
                <Link
                    href="/"
                    class="text-gray-500 hover:text-blue-600 transition-colors"
                >
                    Beranda
                </Link>
                <svg
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
                <Link
                    href="/#divisions"
                    class="text-gray-500 hover:text-blue-600 transition-colors"
                >
                    Divisi
                </Link>
                <svg
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
                <span class="text-gray-900 font-medium">
                    {{ division.name || "Detail Program" }}
                </span>
            </nav>

            <div
                class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6"
            >
                <div class="flex items-start gap-5">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-white border-2 border-gray-100 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0"
                    >
                        <img
                            :src="division.logo || '/logo.png'"
                            :alt="division.institution || 'Bank Indonesia'"
                            class="w-12 h-12 sm:w-14 sm:h-14 object-contain"
                        />
                    </div>
                    <div>
                        <h1
                            class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"
                        >
                            {{ division.name || "Program Magang" }}
                        </h1>
                        <div
                            class="flex flex-wrap items-center gap-3 text-gray-600"
                        >
                            <span class="font-medium">{{
                                division.institution || "Bank Indonesia"
                            }}</span>
                            <span class="hidden sm:inline text-gray-300"
                                >•</span
                            >
                            <div class="flex items-center gap-1.5">
                                <svg
                                    class="w-4 h-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span>{{
                                    division.location ||
                                    "Bandar Lampung, Indonesia"
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0 flex gap-3">
                    <Link
                        v-if="auth?.user && existingApplication"
                        :href="route('profile.edit')"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2"
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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                        <span>Cek Status</span>
                    </Link>

                    <button
                        v-if="!existingApplication"
                        :disabled="availableSlots <= 0 || isLoading"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl disabled:shadow-none disabled:cursor-not-allowed flex items-center gap-2"
                        @click="handleApply"
                    >
                        <template v-if="!isLoading">
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            <span>
                                {{
                                    availableSlots <= 0
                                        ? "Kuota Penuh"
                                        : "Daftar Sekarang"
                                }}
                            </span>
                        </template>
                        <template v-else>
                            <svg
                                class="animate-spin w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <span>Memproses...</span>
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
    availableSlots: {
        type: Number,
        required: true,
    },
    division: {
        type: Object,
        required: true,
    },
    existingApplication: {
        type: Object,
        default: null,
    },
    handleApply: {
        type: Function,
        required: true,
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});
</script>
