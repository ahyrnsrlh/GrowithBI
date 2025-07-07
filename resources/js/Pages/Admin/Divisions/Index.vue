<template>
    <AdminLayout
        title="Manajemen Divisi"
        subtitle="Kelola divisi magang dan kuota"
    >
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Divisi -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Divisi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_divisions }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">
                            Divisi yang tersedia
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
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Divisi Aktif -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Divisi Aktif
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.active_divisions }}
                        </p>
                        <p class="text-green-100 text-xs mt-1">
                            Sedang berjalan
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

            <!-- Total Kuota -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Total Kuota
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_quota }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">
                            Kapasitas tersedia
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
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Aplikasi -->
            <div
                class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            Total Aplikasi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_applications }}
                        </p>
                        <p class="text-orange-100 text-xs mt-1">
                            Pendaftaran masuk
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header with Add Button -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Divisi</h2>
                <p class="text-gray-600">
                    Kelola semua divisi magang yang tersedia
                </p>
            </div>
            <Link
                href="/admin/divisions/create"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>
                <span>Tambah Divisi</span>
            </Link>
        </div>

        <!-- Divisions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="division in divisions"
                :key="division.id"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
            >
                <!-- Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2"
                            >
                                {{ division.name }}
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-2">
                                {{ division.description }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <span
                                :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    division.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-gray-100 text-gray-800',
                                ]"
                            >
                                {{ division.is_active ? "Aktif" : "Nonaktif" }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">
                                {{ division.applications_count }}
                            </p>
                            <p class="text-xs text-gray-500">Pendaftar</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">
                                {{ division.accepted_count }}
                            </p>
                            <p class="text-xs text-gray-500">Diterima</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-600">
                                {{ division.max_interns }}
                            </p>
                            <p class="text-xs text-gray-500">Kuota</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div
                            class="flex justify-between text-xs text-gray-500 mb-1"
                        >
                            <span>Progress</span>
                            <span
                                >{{
                                    Math.round(
                                        (division.accepted_count /
                                            division.max_interns) *
                                            100
                                    )
                                }}%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-blue-600 h-2 rounded-full"
                                :style="`width: ${Math.min(
                                    (division.accepted_count /
                                        division.max_interns) *
                                        100,
                                    100
                                )}%`"
                            ></div>
                        </div>
                    </div>

                    <!-- Supervisor -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">Pembimbing:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{
                                division.supervisor
                                    ? division.supervisor.name
                                    : "Belum ditentukan"
                            }}
                        </span>
                    </div>

                    <!-- Period -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">Periode:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ formatDate(division.start_date) }} -
                            {{ formatDate(division.end_date) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex items-center justify-between pt-4 border-t border-gray-100"
                    >
                        <Link
                            :href="`/admin/divisions/${division.id}`"
                            class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                        >
                            Detail
                        </Link>
                        <div class="flex items-center space-x-2">
                            <Link
                                :href="`/admin/divisions/${division.id}/edit`"
                                class="text-indigo-600 hover:text-indigo-700 text-sm"
                            >
                                Edit
                            </Link>
                            <button
                                @click="confirmDelete(division)"
                                class="text-red-600 hover:text-red-700 text-sm"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="divisions.length === 0" class="text-center py-16">
            <svg
                class="w-16 h-16 text-gray-400 mx-auto mb-4"
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
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                Belum ada divisi
            </h3>
            <p class="text-gray-600 mb-4">
                Mulai dengan membuat divisi magang pertama
            </p>
            <Link
                href="/admin/divisions/create"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>
                <span>Tambah Divisi</span>
            </Link>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4"
                    >
                        <svg
                            class="w-6 h-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.863-.833-2.633 0L4.181 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-medium text-gray-900 text-center mb-2"
                    >
                        Hapus Divisi
                    </h3>
                    <p class="text-sm text-gray-600 text-center mb-4">
                        Apakah Anda yakin ingin menghapus divisi "{{
                            divisionToDelete?.name
                        }}"? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex justify-center space-x-2">
                        <button
                            @click="showDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            Batal
                        </button>
                        <button
                            @click="deleteDivision"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    divisions: Array,
    stats: Object,
});

const showDeleteModal = ref(false);
const divisionToDelete = ref(null);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const confirmDelete = (division) => {
    divisionToDelete.value = division;
    showDeleteModal.value = true;
};

const deleteDivision = () => {
    router.delete(`/admin/divisions/${divisionToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            divisionToDelete.value = null;
        },
    });
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
