<script setup>
defineProps({
    filters: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["apply-filters"]);
</script>

<template>
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div
            class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4"
        >
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <select
                    v-model="filters.filter"
                    @change="emit('apply-filters')"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">Semua</option>
                    <option value="unread">Belum dibaca</option>
                    <option value="read">Sudah dibaca</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe
                </label>
                <select
                    v-model="filters.type"
                    @change="emit('apply-filters')"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">Semua Tipe</option>
                    <option value="application">Aplikasi</option>
                    <option value="logbook">Laporan Harian</option>
                    <option value="final_report">Laporan Akhir</option>
                    <option value="status">Update Status</option>
                </select>
            </div>
        </div>
    </div>
</template>
