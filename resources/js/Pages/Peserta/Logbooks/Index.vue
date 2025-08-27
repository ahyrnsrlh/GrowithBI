<template>
    <PesertaLayout
        title="Logbook Harian"
        subtitle="Kelola catatan harian aktivitas magang Anda"
    >
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
        >
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Logbook Harian</h1>
                <p class="text-gray-600 mt-2">
                    Catat perkembangan dan kegiatan harian selama masa magang
                </p>
            </div>
            <Link
                :href="route('peserta.logbooks.create')"
                class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
            >
                <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Tambah Logbook
            </Link>
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
                class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">
                            Pending
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.pending_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
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
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
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
                class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Revisi</p>
                        <p class="text-3xl font-bold">
                            {{ stats.revision_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
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
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
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
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Pencarian</label
                    >
                    <input
                        v-model="searchQuery"
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
                        v-model="statusFilter"
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
                        v-model="monthFilter"
                        type="month"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
            </div>
        </div>

        <!-- Logbooks List -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    Daftar Logbook
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    Menampilkan {{ filteredLogbooks.length }} dari
                    {{ logbooks.length }} logbook
                </p>
            </div>

            <!-- Logbooks Grid -->
            <div v-if="filteredLogbooks.length > 0" class="p-6">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="logbook in filteredLogbooks"
                        :key="logbook.id"
                        class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"
                    >
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4
                                        class="text-lg font-semibold text-gray-900 mb-2"
                                    >
                                        {{ logbook.title }}
                                    </h4>
                                    <div
                                        class="flex items-center text-sm text-gray-500 mb-2"
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
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                        {{ formatDate(logbook.date) }}
                                    </div>
                                </div>
                                <span
                                    :class="getStatusClass(logbook.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                >
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </div>

                            <!-- Content Preview -->
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm line-clamp-3">
                                    {{
                                        logbook.activities ||
                                        "Tidak ada deskripsi aktivitas"
                                    }}
                                </p>
                            </div>

                            <!-- Duration and Attachments -->
                            <div
                                class="flex items-center justify-between text-sm text-gray-500 mb-4"
                            >
                                <div class="flex items-center">
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
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    {{ logbook.duration || 0 }} jam
                                </div>
                                <div
                                    v-if="
                                        logbook.attachments &&
                                        logbook.attachments.length > 0
                                    "
                                    class="flex items-center"
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
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                        />
                                    </svg>
                                    {{ logbook.attachments.length }} file
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <Link
                                        :href="
                                            route(
                                                'peserta.logbooks.show',
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
                                        Detail
                                    </Link>

                                    <Link
                                        v-if="
                                            logbook.status === 'draft' ||
                                            logbook.status === 'revision'
                                        "
                                        :href="
                                            route(
                                                'peserta.logbooks.edit',
                                                logbook.id
                                            )
                                        "
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-50 text-yellow-700 text-sm font-medium rounded-md hover:bg-yellow-100 transition-colors"
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
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                        Edit
                                    </Link>
                                </div>

                                <!-- Comments Count -->
                                <div
                                    v-if="logbook.comments_count > 0"
                                    class="flex items-center text-sm text-gray-500"
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
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                        />
                                    </svg>
                                    {{ logbook.comments_count }}
                                </div>
                            </div>
                        </div>
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
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                        />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Belum ada logbook
                </h3>
                <p class="text-gray-500 mb-6">
                    Mulai catat aktivitas harian magang Anda
                </p>
                <Link
                    :href="route('peserta.logbooks.create')"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                    </svg>
                    Tambah Logbook Pertama
                </Link>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Link } from "@inertiajs/vue3";
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
            revision_logbooks: 0,
        }),
    },
});

const searchQuery = ref("");
const statusFilter = ref("");
const monthFilter = ref("");

const filteredLogbooks = computed(() => {
    return props.logbooks.filter((logbook) => {
        const matchesSearch =
            !searchQuery.value ||
            logbook.title
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            (logbook.activities &&
                logbook.activities
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()));

        const matchesStatus =
            !statusFilter.value || logbook.status === statusFilter.value;

        const matchesMonth =
            !monthFilter.value ||
            (logbook.date && logbook.date.startsWith(monthFilter.value));

        return matchesSearch && matchesStatus && matchesMonth;
    });
});

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
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
