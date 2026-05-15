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
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
