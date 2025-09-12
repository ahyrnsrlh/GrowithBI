<template>
    <AdminLayout
        :title="`Detail Divisi - ${division.name}`"
        subtitle="Informasi lengkap divisi dan daftar peserta"
    >
        <div class="space-y-6">
            <!-- Division Info Card -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-white">
                                {{ division.name }}
                            </h2>
                            <p class="text-blue-100 text-sm">
                                {{ division.description }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="
                                    route('admin.divisions.edit', division.id)
                                "
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                            >
                                Edit Divisi
                            </Link>
                            <Link
                                :href="route('admin.divisions.index')"
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                            >
                                Kembali
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Informasi Dasar
                            </h3>

                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-500"
                                        >Nama Divisi</label
                                    >
                                    <p class="text-gray-900 font-medium">
                                        {{ division.name }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-500"
                                        >Deskripsi</label
                                    >
                                    <p class="text-gray-900">
                                        {{
                                            division.description ||
                                            "Tidak ada deskripsi"
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-500"
                                        >Kuota</label
                                    >
                                    <p class="text-gray-900 font-medium">
                                        {{ division.quota }} peserta
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-500"
                                        >Status</label
                                    >
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            division.is_active
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800',
                                        ]"
                                    >
                                        {{
                                            division.is_active
                                                ? "Aktif"
                                                : "Tidak Aktif"
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Statistik
                            </h3>

                            <div class="space-y-3">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm font-medium text-blue-700"
                                            >Total Aplikasi</span
                                        >
                                        <span
                                            class="text-lg font-bold text-blue-900"
                                            >{{
                                                division.applications_count ||
                                                applications.length
                                            }}</span
                                        >
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-lg p-4">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm font-medium text-green-700"
                                            >Sisa Kuota</span
                                        >
                                        <span
                                            class="text-lg font-bold text-green-900"
                                            >{{
                                                division.quota -
                                                (division.applications_count ||
                                                    applications.length)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Daftar Peserta
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ applications.length }} peserta terdaftar
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Peserta
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Tanggal Daftar
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="application in applications"
                                :key="application.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold"
                                        >
                                            {{
                                                application.user.name.charAt(0)
                                            }}
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ application.user.name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ application.user.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            application.status === 'approved'
                                                ? 'bg-green-100 text-green-800'
                                                : application.status ===
                                                  'pending'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800',
                                        ]"
                                    >
                                        {{
                                            application.status === "approved"
                                                ? "Diterima"
                                                : application.status ===
                                                  "pending"
                                                ? "Menunggu"
                                                : "Ditolak"
                                        }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{
                                        new Date(
                                            application.created_at
                                        ).toLocaleDateString("id-ID")
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.applications.show',
                                                application.id
                                            )
                                        "
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Lihat Detail
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="applications.length === 0">
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
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
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        ></path>
                                    </svg>
                                    <p class="text-sm">
                                        Belum ada peserta yang mendaftar ke
                                        divisi ini
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    division: {
        type: Object,
        required: true,
    },
    applications: {
        type: Array,
        default: () => [],
    },
});
</script>
