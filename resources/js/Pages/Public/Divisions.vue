<template>
    <Head title="Daftar Divisi" />
    <GuestLayout variant="public">
        <!-- Header Section - Full Width Responsive -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20"
            >
                <div class="text-center">
                    <h1
                        class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 sm:mb-6"
                    >
                        Daftar Divisi Magang
                    </h1>
                    <p
                        class="text-lg sm:text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed"
                    >
                        Temukan program magang yang sesuai dengan minat dan
                        keahlian Anda di berbagai divisi PT GrowithBI
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content - Responsive Container -->
        <div class="bg-gray-50 min-h-screen">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16"
            >
                <!-- Search & Filter Section - Responsive -->
                <div class="mb-8 sm:mb-12">
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-6 sm:p-8"
                    >
                        <div
                            class="flex flex-col space-y-6 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-6"
                        >
                            <!-- Search Input - Full width on mobile, constrained on desktop -->
                            <div class="relative flex-1 lg:max-w-2xl">
                                <input
                                    v-model="q"
                                    type="text"
                                    placeholder="Cari divisi, supervisor, atau deskripsi..."
                                    class="w-full rounded-lg sm:rounded-xl border border-gray-300 bg-white px-4 sm:px-6 py-3 sm:py-4 pr-12 sm:pr-14 text-base sm:text-lg placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                />
                                <div
                                    class="pointer-events-none absolute right-3 sm:right-4 top-1/2 -translate-y-1/2"
                                >
                                    <svg
                                        class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Controls - Stack on mobile, inline on desktop -->
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6"
                            >
                                <label
                                    class="inline-flex items-center gap-2 sm:gap-3 text-base sm:text-lg text-gray-700 font-medium"
                                >
                                    <input
                                        type="checkbox"
                                        v-model="onlyAvailable"
                                        class="h-4 w-4 sm:h-5 sm:w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span class="whitespace-nowrap"
                                        >Hanya yang tersedia</span
                                    >
                                </label>
                                <select
                                    v-model="sortBy"
                                    class="w-full sm:w-auto rounded-lg sm:rounded-xl border border-gray-300 bg-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-medium focus:border-blue-500 focus:outline-none"
                                >
                                    <option value="name">Urutkan: Nama</option>
                                    <option value="available_slots">
                                        Urutkan: Kuota
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Division Cards Grid - Responsive Grid System -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-6 sm:gap-8"
                >
                    <div
                        v-for="division in sortedFiltered"
                        :key="division.id"
                        class="group bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 sm:hover:-translate-y-2 flex flex-col h-full"
                    >
                        <!-- Card Header -->
                        <div class="p-6 sm:p-8 flex-1">
                            <!-- Division Icon & Title -->
                            <div
                                class="flex items-start space-x-3 sm:space-x-4 mb-4 sm:mb-6"
                            >
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white text-lg sm:text-2xl font-bold group-hover:scale-105 transition-transform"
                                    >
                                        {{ (division.name || "D")[0] }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="text-lg sm:text-xl font-bold text-gray-900 mb-1 sm:mb-2 group-hover:text-blue-600 transition-colors leading-tight"
                                    >
                                        {{ division.name }}
                                    </h3>
                                    <p
                                        class="text-xs sm:text-sm text-gray-600 font-medium"
                                    >
                                        {{
                                            division.supervisor ||
                                            "Supervisor: Belum ditentukan"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status Badges -->
                            <div class="flex flex-wrap gap-2 mb-4 sm:mb-6">
                                <span
                                    class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-green-100 text-green-800"
                                >
                                    <span
                                        class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-500 rounded-full mr-1 sm:mr-2"
                                    ></span>
                                    Tersedia
                                </span>
                                <span
                                    class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-50 text-blue-700"
                                >
                                    6 bulan
                                </span>
                                <span
                                    class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700"
                                >
                                    Jakarta
                                </span>
                            </div>

                            <!-- Description -->
                            <p
                                class="text-gray-600 text-sm sm:text-base leading-relaxed mb-6 sm:mb-8 line-clamp-3"
                            >
                                {{ division.description }}
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div
                            class="px-6 sm:px-8 py-4 sm:py-6 bg-gray-50 border-t border-gray-100 mt-auto"
                        >
                            <div
                                class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0"
                            >
                                <!-- Stats -->
                                <div
                                    class="flex items-center space-x-4 sm:space-x-6 text-xs sm:text-sm text-gray-600"
                                >
                                    <div
                                        class="flex items-center gap-1 sm:gap-2"
                                    >
                                        <svg
                                            class="w-3 h-3 sm:w-4 sm:h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            ></path>
                                        </svg>
                                        <span class="font-medium">{{
                                            division.quota || 10
                                        }}</span>
                                        <span>posisi</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-1 sm:gap-2"
                                    >
                                        <svg
                                            class="w-3 h-3 sm:w-4 sm:h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        <span
                                            class="font-medium text-green-600"
                                            >{{
                                                division.available_slots ||
                                                division.quota ||
                                                10
                                            }}</span
                                        >
                                        <span>tersisa</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <Link
                                    :href="`/divisi/${division.id}`"
                                    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg sm:rounded-xl transition-all duration-200 group-hover:scale-105 w-full sm:w-auto"
                                >
                                    Lihat Detail
                                    <svg
                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        ></path>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="sortedFiltered.length === 0"
                    class="text-center py-16 sm:py-24"
                >
                    <div
                        class="mx-auto w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-full flex items-center justify-center mb-6 sm:mb-8"
                    >
                        <svg
                            class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            ></path>
                        </svg>
                    </div>
                    <h3
                        class="text-xl sm:text-2xl font-semibold text-gray-900 mb-3 sm:mb-4"
                    >
                        Tidak ada divisi ditemukan
                    </h3>
                    <p
                        class="text-gray-600 max-w-md mx-auto text-base sm:text-lg"
                    >
                        Coba ubah filter pencarian Anda atau gunakan kata kunci
                        yang berbeda
                    </p>
                </div>

                <!-- Loading State -->
                <div
                    v-if="!divisions.length"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-6 sm:gap-8"
                >
                    <div
                        v-for="n in 8"
                        :key="n"
                        class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 overflow-hidden animate-pulse"
                    >
                        <div class="p-8">
                            <div class="flex items-start space-x-4 mb-6">
                                <div
                                    class="w-16 h-16 bg-gray-200 rounded-2xl"
                                ></div>
                                <div class="flex-1">
                                    <div
                                        class="h-6 bg-gray-200 rounded mb-2"
                                    ></div>
                                    <div
                                        class="h-4 bg-gray-200 rounded w-3/4"
                                    ></div>
                                </div>
                            </div>
                            <div class="flex gap-2 mb-6">
                                <div
                                    class="h-8 bg-gray-200 rounded-full w-20"
                                ></div>
                                <div
                                    class="h-8 bg-gray-200 rounded-full w-16"
                                ></div>
                            </div>
                            <div class="space-y-3 mb-8">
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div
                                    class="h-4 bg-gray-200 rounded w-2/3"
                                ></div>
                            </div>
                        </div>
                        <div
                            class="px-8 py-6 bg-gray-50 border-t border-gray-100"
                        >
                            <div class="flex items-center justify-between">
                                <div class="h-4 bg-gray-200 rounded w-32"></div>
                                <div
                                    class="h-10 bg-gray-200 rounded w-24"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
});

const q = ref("");
const onlyAvailable = ref(false);
const sortBy = ref("name");

const sortedFiltered = computed(() => {
    let filtered = [...(props.divisions || [])];

    if (q.value) {
        const query = q.value.toLowerCase();
        filtered = filtered.filter(
            (division) =>
                (division.name || "").toLowerCase().includes(query) ||
                (division.description || "").toLowerCase().includes(query) ||
                (division.supervisor || "").toLowerCase().includes(query)
        );
    }

    if (onlyAvailable.value) {
        filtered = filtered.filter(
            (division) => (division.available_slots || division.quota || 0) > 0
        );
    }

    return filtered.sort((a, b) => {
        if (sortBy.value === "available_slots") {
            return (
                (b.available_slots || b.quota || 0) -
                (a.available_slots || a.quota || 0)
            );
        }
        return String(a.name || "").localeCompare(String(b.name || ""));
    });
});
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
