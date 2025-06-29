<template>
    <AdminLayout
        title="Detail Divisi"
        :subtitle="`Detail divisi ${division.name}`"
    >
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
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
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ division.name }}
                            </h1>
                            <div class="flex items-center space-x-4 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        division.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    "
                                >
                                    {{
                                        division.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Dibuat {{ formatDate(division.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="route('admin.divisions.edit', division.id)"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center"
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Edit
                        </Link>
                        <Link
                            :href="route('admin.divisions.index')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center"
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
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                            Kembali
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Info Umum -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Umum
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Deskripsi</label
                                >
                                <p class="text-gray-900 leading-relaxed">
                                    {{
                                        division.description ||
                                        "Belum ada deskripsi"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Persyaratan</label
                                >
                                <p
                                    class="text-gray-900 leading-relaxed whitespace-pre-line"
                                >
                                    {{
                                        division.requirements ||
                                        "Tidak ada persyaratan khusus"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Peserta Magang -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Peserta Magang
                            </h2>
                            <span class="text-sm text-gray-500">
                                {{ division.applications_count || 0 }} dari
                                {{ division.quota }} kuota
                            </span>
                        </div>

                        <div
                            v-if="applications && applications.length > 0"
                            class="space-y-3"
                        >
                            <div
                                v-for="application in applications"
                                :key="application.id"
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-600"
                                        >
                                            {{
                                                application.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">
                                            {{ application.user.name }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            {{ application.user.email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="
                                            getStatusClass(application.status)
                                        "
                                    >
                                        {{ getStatusText(application.status) }}
                                    </span>
                                    <Link
                                        :href="
                                            route(
                                                'admin.applications.show',
                                                application.id
                                            )
                                        "
                                        class="text-blue-600 hover:text-blue-700 text-sm"
                                    >
                                        Detail
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">
                                Belum ada peserta magang
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Statistik -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Statistik
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Total Kuota</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ division.quota }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Terisi</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        division.applications_count || 0
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Tersisa</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        division.quota -
                                        (division.applications_count || 0)
                                    }}</span
                                >
                            </div>
                            <div class="pt-2 border-t border-gray-200">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <span class="text-sm text-gray-600"
                                        >Progress</span
                                    >
                                    <span
                                        class="text-sm font-medium text-gray-900"
                                        >{{
                                            Math.round(
                                                ((division.applications_count ||
                                                    0) /
                                                    division.quota) *
                                                    100
                                            )
                                        }}%</span
                                    >
                                </div>
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2"
                                >
                                    <div
                                        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                        :style="{
                                            width: `${Math.min(
                                                ((division.applications_count ||
                                                    0) /
                                                    division.quota) *
                                                    100,
                                                100
                                            )}%`,
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pembimbing -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Pembimbing
                        </h3>
                        <div
                            v-if="division.supervisor"
                            class="flex items-center space-x-3"
                        >
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <span class="text-lg font-medium text-blue-600">
                                    {{
                                        division.supervisor.name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    {{ division.supervisor.name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ division.supervisor.email }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ division.supervisor.phone }}
                                </p>
                            </div>
                        </div>
                        <div v-else class="text-center py-4">
                            <p class="text-sm text-gray-500">
                                Belum ada pembimbing ditugaskan
                            </p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <Link
                                :href="
                                    route('admin.applications.index', {
                                        division: division.id,
                                    })
                                "
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center"
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
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                Lihat Semua Aplikasi
                            </Link>
                            <Link
                                :href="
                                    route('admin.divisions.edit', division.id)
                                "
                                class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center"
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
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                                Edit Divisi
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";

// Import route helper
const route = window.route;

const props = defineProps({
    division: Object,
    applications: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getStatusClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        accepted: "bg-green-100 text-green-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        pending: "Menunggu",
        accepted: "Diterima",
        rejected: "Ditolak",
    };
    return texts[status] || status;
};
</script>
