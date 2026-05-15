<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Filter Data</h3>
                <p class="text-sm text-gray-500">Gunakan filter untuk mempersempit laporan harian.</p>
            </div>

            <Link
                :href="route('admin.logbooks.export')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200"
            >
                <svg
                    class="w-4 h-4"
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
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Pencarian</label
                >
                <input
                    v-model="searchModel"
                    type="text"
                    placeholder="Cari peserta atau aktivitas..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Status</label
                >
                <select
                    v-model="statusModel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="submitted">Menunggu Review</option>
                    <option value="approved">Disetujui</option>
                    <option value="revision">Perlu Revisi</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Divisi</label
                >
                <select
                    v-model="divisionModel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    >Bulan</label
                >
                <input
                    v-model="monthModel"
                    type="month"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
            <div class="flex items-end">
                <button
                    @click="$emit('reset')"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

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
        type: String,
        default: "",
    },
    divisionFilter: {
        type: [String, Number],
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
    "update:divisionFilter",
    "update:monthFilter",
    "reset",
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

const monthModel = computed({
    get: () => props.monthFilter,
    set: (value) => emit("update:monthFilter", value),
});
</script>
