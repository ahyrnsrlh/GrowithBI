<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>

        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Dibuat</p>
                    <p class="text-xs text-gray-500">
                        {{ formatDateTime(logbook.created_at) }}
                    </p>
                </div>
            </div>

            <div
                v-if="logbook.updated_at !== logbook.created_at"
                class="flex items-start space-x-3"
            >
                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Diperbarui</p>
                    <p class="text-xs text-gray-500">
                        {{ formatDateTime(logbook.updated_at) }}
                    </p>
                </div>
            </div>

            <div v-if="logbook.approved_at" class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Disetujui</p>
                    <p class="text-xs text-gray-500">
                        {{ formatDateTime(logbook.approved_at) }}
                    </p>
                    <p v-if="logbook.approver" class="text-xs text-gray-400">
                        oleh {{ logbook.approver.name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    formatDateTime: {
        type: Function,
        required: true,
    },
});
</script>
