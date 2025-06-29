<template>
    <AdminLayout
        title="Detail Pembimbing"
        :subtitle="`Detail pembimbing ${supervisor.name}`"
    >
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"
                        >
                            <span class="text-2xl font-bold text-blue-600">
                                {{ supervisor.name.charAt(0).toUpperCase() }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ supervisor.name }}
                            </h1>
                            <div class="flex items-center space-x-4 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        supervisor.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    "
                                >
                                    {{
                                        supervisor.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Bergabung
                                    {{ formatDate(supervisor.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="
                                route('admin.supervisors.edit', supervisor.id)
                            "
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
                            :href="route('admin.supervisors.index')"
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
                    <!-- Informasi Personal -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Personal
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Email</label
                                >
                                <p class="text-gray-900">
                                    {{ supervisor.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Nomor Telepon</label
                                >
                                <p class="text-gray-900">
                                    {{ supervisor.phone || "-" }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Alamat</label
                                >
                                <p class="text-gray-900">
                                    {{ supervisor.address || "-" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Divisi yang Dibimbing -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Divisi yang Dibimbing
                            </h2>
                            <span class="text-sm text-gray-500">
                                {{ divisions.length }} divisi
                            </span>
                        </div>

                        <div
                            v-if="divisions && divisions.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="division in divisions"
                                :key="division.id"
                                class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200"
                            >
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <div>
                                        <h3 class="font-medium text-gray-900">
                                            {{ division.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{
                                                division.description ||
                                                "Tidak ada deskripsi"
                                            }}
                                        </p>
                                    </div>
                                    <div class="text-right">
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
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <p
                                            class="text-lg font-bold text-blue-600"
                                        >
                                            {{
                                                division.applications_count || 0
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Pendaftar
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-lg font-bold text-green-600"
                                        >
                                            {{ division.quota }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Kuota
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-lg font-bold text-gray-600"
                                        >
                                            {{
                                                Math.max(
                                                    0,
                                                    division.quota -
                                                        (division.applications_count ||
                                                            0)
                                                )
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Tersisa
                                        </p>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div
                                        class="flex justify-between text-xs text-gray-500 mb-1"
                                    >
                                        <span>Progress</span>
                                        <span
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

                                <div class="mt-3 flex justify-end">
                                    <Link
                                        :href="
                                            route(
                                                'admin.divisions.show',
                                                division.id
                                            )
                                        "
                                        class="text-sm text-blue-600 hover:text-blue-700"
                                    >
                                        Lihat Detail →
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
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">
                                Belum ada divisi yang dibimbing
                            </p>
                            <div class="mt-4">
                                <Link
                                    href="/admin/divisions/create"
                                    class="text-sm text-blue-600 hover:text-blue-700"
                                >
                                    Buat divisi baru →
                                </Link>
                            </div>
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
                                    >Total Divisi</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        supervisor.supervised_divisions_count ||
                                        0
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Total Peserta</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        supervisor.supervised_applications_count ||
                                        0
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Status Akun</span
                                >
                                <span
                                    class="text-sm font-medium"
                                    :class="
                                        supervisor.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{
                                        supervisor.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Aktivitas Terbaru
                        </h3>
                        <div class="space-y-3">
                            <div class="text-center py-8">
                                <svg
                                    class="mx-auto h-8 w-8 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">
                                    Belum ada aktivitas
                                </p>
                            </div>
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
                                    route(
                                        'admin.supervisors.edit',
                                        supervisor.id
                                    )
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
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                                Edit Pembimbing
                            </Link>
                            <Link
                                href="/admin/divisions/create"
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
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                                Buat Divisi Baru
                            </Link>
                            <Link
                                :href="
                                    route('admin.applications.index', {
                                        supervisor: supervisor.id,
                                    })
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
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                Lihat Aplikasi
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
    supervisor: Object,
    divisions: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
