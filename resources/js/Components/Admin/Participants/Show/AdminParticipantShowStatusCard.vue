<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Status Saat Ini
        </h3>
        <div v-if="acceptedApplication" class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Divisi</span>
                <span class="text-sm font-medium text-gray-900">{{
                    acceptedApplication.division.name
                }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Status</span>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                >
                    Diterima
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Mulai</span>
                <span class="text-sm font-medium text-gray-900">{{
                    formatDate(acceptedApplication.created_at)
                }}</span>
            </div>
        </div>
        <div v-else class="text-center py-4">
            <p class="text-sm text-gray-500">
                Belum ada aplikasi yang diterima
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    acceptedApplication: {
        type: Object,
        default: null,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>
