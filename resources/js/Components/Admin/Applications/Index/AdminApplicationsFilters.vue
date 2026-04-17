<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
        >
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <div class="relative">
                    <input
                        v-model="searchModel"
                        @input="$emit('search')"
                        type="text"
                        placeholder="Cari nama atau email..."
                        class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <svg
                        class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>

                <select
                    v-model="statusModel"
                    @change="$emit('filter-by-status')"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option
                        v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>

                <select
                    v-model="divisionModel"
                    @change="$emit('filter-by-division')"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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

            <div v-if="selectedCount > 0" class="flex items-center gap-2">
                <span class="text-sm text-gray-600"
                    >{{ selectedCount }} terpilih</span
                >
                <button
                    @click="$emit('open-bulk')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                >
                    Aksi Bulk
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    searchQuery: {
        type: String,
        default: "",
    },
    selectedStatus: {
        type: [String, Number],
        default: "",
    },
    selectedDivision: {
        type: [String, Number],
        default: "",
    },
    selectedCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits([
    "update:searchQuery",
    "update:selectedStatus",
    "update:selectedDivision",
    "search",
    "filter-by-status",
    "filter-by-division",
    "open-bulk",
]);

const searchModel = computed({
    get: () => props.searchQuery,
    set: (value) => emit("update:searchQuery", value),
});

const statusModel = computed({
    get: () => props.selectedStatus,
    set: (value) => emit("update:selectedStatus", value),
});

const divisionModel = computed({
    get: () => props.selectedDivision,
    set: (value) => emit("update:selectedDivision", value),
});
</script>
