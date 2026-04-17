<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0"
        >
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="h-5 w-5 text-gray-400"
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
                    <input
                        v-model="searchModel"
                        @input="$emit('search')"
                        type="text"
                        placeholder="Cari peserta berdasarkan nama, email, atau telepon..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
            </div>

            <div
                class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4"
            >
                <select
                    v-model="statusModel"
                    @change="$emit('filter')"
                    class="block w-full sm:w-40 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>

                <select
                    v-model="divisionModel"
                    @change="$emit('filter')"
                    class="block w-full sm:w-48 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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

                <button
                    @click="$emit('export')"
                    class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
    searchQuery: {
        type: String,
        default: "",
    },
    statusFilter: {
        type: [String, Number],
        default: "",
    },
    divisionFilter: {
        type: [String, Number],
        default: "",
    },
});

const emit = defineEmits([
    "update:searchQuery",
    "update:statusFilter",
    "update:divisionFilter",
    "search",
    "filter",
    "export",
]);

const searchModel = computed({
    get: () => props.searchQuery,
    set: (value) => emit("update:searchQuery", value),
});

const statusModel = computed({
    get: () => props.statusFilter,
    set: (value) => emit("update:statusFilter", value),
});

const divisionModel = computed({
    get: () => props.divisionFilter,
    set: (value) => emit("update:divisionFilter", value),
});
</script>
