<template>
    <AdminLayout
        title="Laporan Harian Peserta"
        subtitle="Review dan kelola laporan harian dari peserta magang"
    >
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
        >
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Harian</h1>
                <p class="text-gray-600 mt-2">
                    Review dan berikan feedback pada logbook peserta magang
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-4">
                <button
                    @click="bulkAction('approve')"
                    :disabled="selectedLogbooks.length === 0"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    Setujui ({{ selectedLogbooks.length }})
                </button>

                <Link
                    :href="route('admin.logbooks.export')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all duration-200"
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
                </Link>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Logbook
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            Perlu Review
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.pending_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Disetujui
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.approved_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Rata-rata Jam/Hari
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.average_hours || 0 }}
                        </p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Pencarian</label
                    >
                    <input
                        v-model="searchQuery"
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
                        v-model="statusFilter"
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
                        v-model="divisionFilter"
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
                        v-model="monthFilter"
                        type="month"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div class="flex items-end">
                    <button
                        @click="resetFilters"
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Logbooks Table -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Daftar Logbook
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Menampilkan {{ paginatedLogbooks.length }} dari
                            {{ filteredLogbooks.length }} logbook
                        </p>
                    </div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            :checked="isAllSelected"
                            @change="toggleSelectAll"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-700"
                            >Pilih Semua</span
                        >
                    </label>
                </div>
            </div>

            <div v-if="filteredLogbooks.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                <input
                                    type="checkbox"
                                    :checked="isAllSelected"
                                    @change="toggleSelectAll"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                />
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Peserta & Tanggal
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Judul & Aktivitas
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Duration
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="logbook in paginatedLogbooks"
                            :key="logbook.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="checkbox"
                                    :value="logbook.id"
                                    v-model="selectedLogbooks"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-semibold text-blue-600"
                                        >
                                            {{
                                                logbook.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ logbook.user.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ formatDate(logbook.date) }}
                                        </div>
                                        <div
                                            v-if="logbook.user.division"
                                            class="text-xs text-gray-400"
                                        >
                                            {{ logbook.user.division.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div
                                        class="text-sm font-medium text-gray-900 truncate"
                                    >
                                        {{ logbook.title }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-500 line-clamp-2"
                                    >
                                        {{ logbook.activities }}
                                    </div>
                                    <div
                                        v-if="
                                            logbook.attachments &&
                                            logbook.attachments.length > 0
                                        "
                                        class="flex items-center mt-1"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                            />
                                        </svg>
                                        <span class="text-xs text-gray-400"
                                            >{{
                                                logbook.attachments.length
                                            }}
                                            file</span
                                        >
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 mr-1 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <span class="text-sm text-gray-900"
                                        >{{ logbook.duration || 0 }} jam</span
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="getStatusClass(logbook.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                >
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <div class="flex space-x-2">
                                    <Link
                                        :href="
                                            route(
                                                'admin.logbooks.show',
                                                logbook.id
                                            )
                                        "
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-100 transition-colors"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        Review
                                    </Link>

                                    <button
                                        v-if="logbook.status === 'submitted'"
                                        @click="quickApprove(logbook.id)"
                                        class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 text-sm font-medium rounded-md hover:bg-green-100 transition-colors"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                        Setujui
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="filteredLogbooks.length > 0"
                class="px-6 py-4 border-t border-gray-200 bg-gray-50"
            >
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4"
                >
                    <!-- Items per page -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700">Tampilkan:</span>
                        <select
                            v-model="itemsPerPage"
                            @change="changeItemsPerPage(itemsPerPage)"
                            class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                        </select>
                        <span class="text-sm text-gray-700">data</span>
                    </div>

                    <!-- Page info -->
                    <div class="text-sm text-gray-700">
                        Halaman {{ currentPage }} dari {{ totalPages }}
                        <span class="text-gray-500"
                            >({{ filteredLogbooks.length }} total)</span
                        >
                    </div>

                    <!-- Pagination buttons -->
                    <div class="flex items-center gap-1">
                        <!-- Previous button -->
                        <button
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="px-3 py-1 rounded-md text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 text-gray-700"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>

                        <!-- Page numbers -->
                        <template
                            v-for="(page, index) in paginationRange"
                            :key="index"
                        >
                            <button
                                v-if="page !== '...'"
                                @click="goToPage(page)"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                                    currentPage === page
                                        ? 'bg-blue-600 text-white'
                                        : 'hover:bg-gray-200 text-gray-700',
                                ]"
                            >
                                {{ page }}
                            </button>
                            <span v-else class="px-2 text-gray-500">...</span>
                        </template>

                        <!-- Next button -->
                        <button
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            class="px-3 py-1 rounded-md text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 text-gray-700"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Tidak ada logbook ditemukan
                </h3>
                <p class="text-gray-500">
                    Coba ubah kriteria pencarian atau filter yang Anda gunakan
                </p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    logbooks: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_logbooks: 0,
            pending_logbooks: 0,
            approved_logbooks: 0,
            average_hours: 0,
        }),
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref("");
const statusFilter = ref("");
const divisionFilter = ref("");
const monthFilter = ref("");
const selectedLogbooks = ref([]);

// Pagination state
const currentPage = ref(1);
const itemsPerPage = ref(10);

const filteredLogbooks = computed(() => {
    return props.logbooks.filter((logbook) => {
        const matchesSearch =
            !searchQuery.value ||
            logbook.user.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            logbook.title
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            (logbook.activities &&
                logbook.activities
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()));

        const matchesStatus =
            !statusFilter.value || logbook.status === statusFilter.value;

        const matchesDivision =
            !divisionFilter.value ||
            (logbook.user.division &&
                logbook.user.division.id === parseInt(divisionFilter.value));

        const matchesMonth =
            !monthFilter.value ||
            (logbook.date && logbook.date.startsWith(monthFilter.value));

        return (
            matchesSearch && matchesStatus && matchesDivision && matchesMonth
        );
    });
});

// Pagination computed
const totalPages = computed(() => {
    return Math.ceil(filteredLogbooks.value.length / itemsPerPage.value);
});

const paginatedLogbooks = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredLogbooks.value.slice(start, end);
});

const paginationRange = computed(() => {
    const range = [];
    const delta = 2;
    const left = currentPage.value - delta;
    const right = currentPage.value + delta;

    for (let i = 1; i <= totalPages.value; i++) {
        if (i === 1 || i === totalPages.value || (i >= left && i <= right)) {
            range.push(i);
        } else if (range[range.length - 1] !== "...") {
            range.push("...");
        }
    }

    return range;
});

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
};

const changeItemsPerPage = (value) => {
    itemsPerPage.value = value;
    currentPage.value = 1;
};

const isAllSelected = computed(() => {
    return (
        paginatedLogbooks.value.length > 0 &&
        selectedLogbooks.value.length === paginatedLogbooks.value.length
    );
});

const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedLogbooks.value = [];
    } else {
        selectedLogbooks.value = paginatedLogbooks.value.map(
            (logbook) => logbook.id
        );
    }
};

const resetFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "";
    divisionFilter.value = "";
    monthFilter.value = "";
    selectedLogbooks.value = [];
    currentPage.value = 1;
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            weekday: "short",
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    } catch (error) {
        return "-";
    }
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
        rejected: "Ditolak",
    };
    return texts[status] || "Unknown";
};

const quickApprove = (logbookId) => {
    router.put(
        route("admin.logbooks.approve", logbookId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Remove from selected if it was selected
                selectedLogbooks.value = selectedLogbooks.value.filter(
                    (id) => id !== logbookId
                );
            },
        }
    );
};

const bulkAction = (action) => {
    if (selectedLogbooks.value.length === 0) return;

    router.post(
        route("admin.logbooks.bulk-update"),
        {
            logbook_ids: selectedLogbooks.value,
            action: action,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                selectedLogbooks.value = [];
            },
        }
    );
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
