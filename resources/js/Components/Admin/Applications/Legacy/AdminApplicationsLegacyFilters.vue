<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Filter Status</label
                    >
                    <select
                        v-model="filters.status"
                        @change="$emit('apply-filters')"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Review</option>
                        <option value="approved">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Filter Divisi</label
                    >
                    <select
                        v-model="filters.division_id"
                        @change="$emit('apply-filters')"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Cari</label
                    >
                    <input
                        v-model="filters.search"
                        @input="$emit('apply-filters')"
                        type="text"
                        placeholder="Nama atau email..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                </div>
                <div class="flex items-end">
                    <button
                        @click="$emit('reset-filters')"
                        class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    filters: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

defineEmits(["apply-filters", "reset-filters"]);
</script>
