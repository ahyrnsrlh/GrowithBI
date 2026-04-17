<template>
    <div class="mb-8">
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
        >
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol
                        class="inline-flex items-center space-x-1 text-sm text-gray-500"
                    >
                        <li class="inline-flex items-center">
                            <Link
                                href="/admin/dashboard"
                                class="hover:text-blue-600 transition-colors"
                            >
                                Dashboard
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span class="text-gray-700 font-medium"
                                    >Absensi</span
                                >
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Manajemen Absensi
                </h1>
                <p class="mt-1 text-gray-500">
                    Kelola dan pantau absensi peserta magang secara real-time
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="$emit('export')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 border-2 border-blue-600 text-blue-600 font-medium rounded-xl hover:bg-blue-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineEmits(["export"]);
</script>
