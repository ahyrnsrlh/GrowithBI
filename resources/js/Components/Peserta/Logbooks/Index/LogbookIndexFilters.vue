<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Pencarian</label
                >
                <input
                    v-model="searchModel"
                    type="text"
                    placeholder="Cari berdasarkan judul atau aktivitas..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
            <div class="sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Status</label
                >
                <select
                    v-model="statusModel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="submitted">Menunggu Review</option>
                    <option value="approved">Disetujui</option>
                    <option value="revision">Perlu Revisi</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div class="sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Bulan</label
                >
                <input
                    v-model="monthModel"
                    type="month"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    searchQuery: {
        type: String,
        default: "",
    },
    statusFilter: {
        type: String,
        default: "",
    },
    monthFilter: {
        type: String,
        default: "",
    },
});

const emit = defineEmits([
    "update:searchQuery",
    "update:statusFilter",
    "update:monthFilter",
]);

const searchModel = computed({
    get: () => props.searchQuery,
    set: (value) => emit("update:searchQuery", value),
});

const statusModel = computed({
    get: () => props.statusFilter,
    set: (value) => emit("update:statusFilter", value),
});

const monthModel = computed({
    get: () => props.monthFilter,
    set: (value) => emit("update:monthFilter", value),
});
</script>
