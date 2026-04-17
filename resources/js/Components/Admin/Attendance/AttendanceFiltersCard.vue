<template>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg flex items-center justify-center"
                >
                    <svg
                        class="w-4 h-4 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Filter Data</h3>
            </div>
            <button
                @click="$emit('toggle-filters')"
                class="md:hidden inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900"
            >
                <span>{{ showFilters ? "Sembunyikan" : "Tampilkan" }}</span>
                <svg
                    :class="[
                        'w-4 h-4 transition-transform',
                        showFilters ? 'rotate-180' : '',
                    ]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </button>
        </div>

        <div
            :class="[
                'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4',
                { 'hidden md:grid': !showFilters },
            ]"
        >
            <div class="xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5"
                    >Cari Peserta</label
                >
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
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
                        v-model="filterForm.search"
                        @input="$emit('debounce-search')"
                        type="text"
                        placeholder="Nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                    />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5"
                    >Tanggal Mulai</label
                >
                <input
                    v-model="filterForm.date_from"
                    @change="$emit('apply-filters')"
                    type="date"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5"
                    >Tanggal Akhir</label
                >
                <input
                    v-model="filterForm.date_to"
                    @change="$emit('apply-filters')"
                    type="date"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5"
                    >Divisi</label
                >
                <select
                    v-model="filterForm.division_id"
                    @change="$emit('apply-filters')"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none cursor-pointer"
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
                <label class="block text-sm font-medium text-gray-700 mb-1.5"
                    >Status</label
                >
                <div class="flex items-center gap-2">
                    <select
                        v-model="filterForm.status"
                        @change="$emit('apply-filters')"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none cursor-pointer"
                    >
                        <option value="">Semua Status</option>
                        <option value="On-Time">Tepat Waktu</option>
                        <option value="Late">Terlambat</option>
                        <option value="Absent">Tidak Hadir</option>
                        <option value="Not-Checked-Out">Belum Check-out</option>
                    </select>
                    <button
                        @click="$emit('clear-filters')"
                        :disabled="activeFiltersCount === 0"
                        :class="[
                            'inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap',
                            activeFiltersCount > 0
                                ? 'text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200'
                                : 'text-gray-400 bg-gray-50 border border-gray-100 cursor-not-allowed',
                        ]"
                        :title="
                            activeFiltersCount > 0
                                ? activeFiltersCount + ' filter aktif'
                                : 'Tidak ada filter aktif'
                        "
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
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        <span class="hidden sm:inline">Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    showFilters: { type: Boolean, default: true },
    filterForm: { type: Object, required: true },
    divisions: { type: Array, default: () => [] },
    activeFiltersCount: { type: Number, default: 0 },
});

defineEmits([
    "toggle-filters",
    "apply-filters",
    "debounce-search",
    "clear-filters",
]);
</script>
