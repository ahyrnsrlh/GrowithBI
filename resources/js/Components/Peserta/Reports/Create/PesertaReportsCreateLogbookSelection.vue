<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    logbooks: {
        type: Array,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div v-if="logbooks.length > 0" class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            Pilih Logbook yang Termasuk
        </h3>
        <p class="text-sm text-gray-600 mb-4">
            Pilih logbook yang akan disertakan dalam laporan ini
        </p>

        <div class="space-y-2 max-h-64 overflow-y-auto">
            <label
                v-for="logbook in logbooks"
                :key="logbook.id"
                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer"
            >
                <input
                    v-model="form.selected_logbooks"
                    :value="logbook.id"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <div class="ml-3 flex-1">
                    <div class="text-sm font-medium text-gray-900">
                        {{ logbook.title || "Aktivitas Harian" }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ formatDate(logbook.date) }} •
                        {{ logbook.duration }} jam
                    </div>
                </div>
            </label>
        </div>
    </div>
</template>
