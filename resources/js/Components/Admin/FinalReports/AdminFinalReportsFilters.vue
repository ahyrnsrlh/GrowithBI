<template>
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Laporan</label>
                <input
                    v-model="searchForm.search"
                    type="text"
                    placeholder="Cari berdasarkan judul atau nama peserta..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div class="min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                    v-model="searchForm.status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Status</option>
                    <option value="submitted">Menunggu Review</option>
                    <option value="approved">Disetujui</option>
                    <option value="revision">Perlu Revisi</option>
                </select>
            </div>

            <div class="min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                <select
                    v-model="searchForm.division"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Divisi</option>
                    <option
                        v-for="division in divisions"
                        :key="division.id"
                        :value="division.id"
                    >
                        {{ division.name }}
                    </option>
                </select>
            </div>

            <div class="flex items-end">
                <button
                    @click="$emit('filter')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Filter
                </button>
            </div>

            <div class="flex items-end">
                <button
                    @click="$emit('reset')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Reset
                </button>
            </div>

            <div class="flex items-end">
                <button
                    @click="$emit('export')"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    Export CSV
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    searchForm: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

defineEmits(["filter", "reset", "export"]);
</script>
