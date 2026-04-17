<template>
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
    >
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Harian</h1>
            <p class="text-gray-600 mt-2">
                Review dan berikan feedback pada logbook peserta magang
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-4">
            <button
                @click="$emit('bulk-approve')"
                :disabled="selectedCount === 0"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
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
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                Setujui ({{ selectedCount }})
            </button>

            <Link
                :href="route('admin.logbooks.export')"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all duration-200"
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
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                Export
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    selectedCount: {
        type: Number,
        default: 0,
    },
});

defineEmits(["bulk-approve"]);
</script>
