<template>
    <AuthenticatedLayout>
        <Head title="Logbook Harian" />

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
            <button
                @click="showCreateModal = true"
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
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total</p>
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
                            Pending
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
                            Perlu Revisi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.revision_logbooks || 0 }}
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
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div
            class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100"
        >
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label
                        for="month"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Filter Bulan</label
                    >
                    <input
                        type="month"
                        id="month"
                        v-model="filters.month"
                        @change="applyFilters"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div>
                    <label
                        for="status"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Status</label
                    >
                    <select
                        id="status"
                        v-model="filters.status"
                        @change="applyFilters"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="submitted">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="revision">Revisi</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button
                        @click="resetFilters"
                        class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Logbooks List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">
                    Daftar Logbook
                </h2>
            </div>

            <div v-if="logbooks.length === 0" class="p-12 text-center">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400 mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Belum ada logbook
                </h3>
                <p class="text-gray-500 mb-4">
                    Mulai catat kegiatan harian Anda dengan membuat logbook
                    pertama.
                </p>
                <button
                    @click="showCreateModal = true"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                    </svg>
                    Buat Logbook
                </button>
            </div>

            <div v-else class="divide-y divide-gray-100">
                <div
                    v-for="logbook in logbooks"
                    :key="logbook.id"
                    class="p-6 hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ formatDate(logbook.date) }}
                                </h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="getStatusClass(logbook.status)"
                                >
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-3 line-clamp-2">
                                {{ logbook.description }}
                            </p>
                            <div
                                class="flex items-center space-x-4 text-sm text-gray-500"
                            >
                                <span class="flex items-center">
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
                                    {{ logbook.hours }} jam
                                </span>
                                <span class="flex items-center">
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
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        />
                                    </svg>
                                    {{
                                        logbook.division
                                            ? logbook.division.name
                                            : "Tidak ada divisi"
                                    }}
                                </span>
                                <span
                                    v-if="
                                        logbook.comments &&
                                        logbook.comments.length > 0
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
                                            d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"
                                        />
                                    </svg>
                                    {{ logbook.comments.length }} komentar
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <Link
                                :href="
                                    route('profile.logbooks.show', logbook.id)
                                "
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors"
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
                                v-if="logbook.status !== 'approved'"
                                :href="
                                    route('profile.logbooks.edit', logbook.id)
                                "
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-200 transition-colors"
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Logbook Modal -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="showCreateModal = false"
        >
            <div
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center"
            >
                <div
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                ></div>

                <div
                    class="relative inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                >
                    <!-- Modal Header -->
                    <div
                        class="flex items-center justify-between mb-6 pb-4 border-b"
                    >
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Tambah Logbook Harian
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Catat aktivitas dan pencapaian hari ini
                            </p>
                        </div>
                        <button
                            @click="showCreateModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form @submit.prevent="submitLogbook" class="space-y-6">
                        <!-- Date and Hours Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="date"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date"
                                    v-model="createForm.date"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div
                                    v-if="createForm.errors.date"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createForm.errors.date }}
                                </div>
                            </div>

                            <div>
                                <label
                                    for="duration"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Durasi (jam)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="duration"
                                    v-model="createForm.duration"
                                    min="1"
                                    max="12"
                                    step="0.5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="8"
                                    required
                                />
                                <div
                                    v-if="createForm.errors.duration"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createForm.errors.duration }}
                                </div>
                            </div>
                        </div>

                        <!-- Activity Title -->
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Judul Aktivitas
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                v-model="createForm.title"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: Pengembangan Fitur Login System"
                                required
                            />
                            <div
                                v-if="createForm.errors.title"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createForm.errors.title }}
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div>
                            <label
                                for="activities"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Deskripsi Aktivitas
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="activities"
                                v-model="createForm.activities"
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan secara detail aktivitas yang telah dilakukan hari ini, termasuk tugas yang diselesaikan, kendala yang dihadapi, dan hasil yang dicapai..."
                                required
                            ></textarea>
                            <div
                                v-if="createForm.errors.activities"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createForm.errors.activities }}
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button
                                type="button"
                                @click="showCreateModal = false"
                                class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                :disabled="createForm.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                            >
                                <span v-if="createForm.processing"
                                    >Menyimpan...</span
                                >
                                <span v-else>Simpan Logbook</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    logbooks: Array,
    stats: Object,
    filters: {
        type: Object,
        default: () => ({
            month: "",
            status: "",
        }),
    },
});

const showCreateModal = ref(false);

const filters = ref({
    month: props.filters.month || "",
    status: props.filters.status || "",
});

// Create form for modal
const createForm = useForm({
    date: new Date().toISOString().split("T")[0], // Today's date
    duration: 8,
    title: "",
    activities: "", // Changed from description to activities
    learning_points: "",
    challenges: "",
    status: "submitted", // Default to submitted
});

const submitLogbook = () => {
    createForm.post(route("profile.logbooks.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
            // Refresh the page data
            router.reload({ only: ["logbooks", "stats"] });
        },
        onError: () => {
            // Form errors are handled automatically by Inertia
        },
    });
};

const applyFilters = () => {
    router.get(route("profile.logbooks.index"), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filters.value = {
        month: "",
        status: "",
    };
    applyFilters();
};

const formatDate = (date) => {
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const getStatusClass = (status) => {
    const classes = {
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
        draft: "bg-gray-100 text-gray-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        submitted: "Pending",
        approved: "Disetujui",
        revision: "Revisi",
        draft: "Draft",
    };
    return texts[status] || "Unknown";
};
</script>
