<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Riwayat Aplikasi
            </h2>
            <span class="text-sm text-gray-500"
                >{{ applications.length }} aplikasi</span
            >
        </div>

        <div v-if="applications && applications.length > 0" class="space-y-4">
            <div
                v-for="application in applications"
                :key="application.id"
                class="border border-gray-200 rounded-lg p-4"
            >
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-medium text-gray-900">
                            {{ application.division.name }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{
                                application.division.description ||
                                "Tidak ada deskripsi"
                            }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="getStatusClass(application.status)"
                        >
                            {{ getStatusText(application.status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ formatDate(application.created_at) }}
                        </p>
                    </div>
                </div>

                <div
                    v-if="application.notes"
                    class="mt-3 p-3 bg-gray-50 rounded-lg"
                >
                    <label class="block text-xs font-medium text-gray-700 mb-1"
                        >Catatan</label
                    >
                    <p class="text-sm text-gray-600">{{ application.notes }}</p>
                </div>

                <div class="mt-3 flex justify-end">
                    <Link
                        :href="route('admin.applications.show', application.id)"
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        Lihat Detail →
                    </Link>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12">
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
            <p class="mt-2 text-sm text-gray-500">
                Belum ada aplikasi yang diajukan
            </p>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    applications: {
        type: Array,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});
</script>
