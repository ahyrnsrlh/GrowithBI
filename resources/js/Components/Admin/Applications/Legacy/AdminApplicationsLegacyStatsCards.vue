<template>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <DocumentTextIcon class="h-6 w-6 text-blue-600" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">
                        Total Aplikasi
                    </p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ stats.total }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <ClockIcon class="h-6 w-6 text-yellow-600" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">
                        Menunggu Review
                    </p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ stats.pending }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <CheckCircleIcon class="h-6 w-6 text-green-600" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Diterima</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ stats.approved }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <XCircleIcon class="h-6 w-6 text-red-600" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ stats.rejected }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    DocumentTextIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            approved: 0,
            rejected: 0,
        }),
    },
});
</script>
