<template>
    <AdminLayout
        title="Detail Peserta"
        :subtitle="`Detail peserta ${participant.name}`"
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
                                {{ participant.name.charAt(0).toUpperCase() }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ participant.name }}
                            </h1>
                            <div class="flex items-center space-x-4 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        participant.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    "
                                >
                                    {{
                                        participant.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Bergabung
                                    {{ formatDate(participant.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button
                            @click="toggleStatus"
                            :class="[
                                'px-4 py-2 rounded-lg transition-colors duration-200 flex items-center',
                                participant.is_active
                                    ? 'bg-red-600 text-white hover:bg-red-700'
                                    : 'bg-green-600 text-white hover:bg-green-700',
                            ]"
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
                                    :d="
                                        participant.is_active
                                            ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636'
                                            : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    "
                                />
                            </svg>
                            {{
                                participant.is_active
                                    ? "Nonaktifkan"
                                    : "Aktifkan"
                            }}
                        </button>
                        <Link
                            :href="route('admin.participants.index')"
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
                                    {{ participant.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Nomor Telepon</label
                                >
                                <p class="text-gray-900">
                                    {{ participant.phone || "-" }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Alamat</label
                                >
                                <p class="text-gray-900">
                                    {{ participant.address || "-" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Aplikasi -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Riwayat Aplikasi
                            </h2>
                            <span class="text-sm text-gray-500">
                                {{ applications.length }} aplikasi
                            </span>
                        </div>

                        <div
                            v-if="applications && applications.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="application in applications"
                                :key="application.id"
                                class="border border-gray-200 rounded-lg p-4"
                            >
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <div>
                                        <h3 class="font-medium text-gray-900">
                                            {{ application.division.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{
                                                application.division
                                                    .description ||
                                                "Tidak ada deskripsi"
                                            }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="
                                                getStatusClass(
                                                    application.status
                                                )
                                            "
                                        >
                                            {{
                                                getStatusText(
                                                    application.status
                                                )
                                            }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{
                                                formatDate(
                                                    application.created_at
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="application.notes"
                                    class="mt-3 p-3 bg-gray-50 rounded-lg"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 mb-1"
                                        >Catatan</label
                                    >
                                    <p class="text-sm text-gray-600">
                                        {{ application.notes }}
                                    </p>
                                </div>

                                <div class="mt-3 flex justify-end">
                                    <Link
                                        :href="
                                            route(
                                                'admin.applications.show',
                                                application.id
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
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">
                                Belum ada aplikasi yang diajukan
                            </p>
                        </div>
                    </div>

                    <!-- Progress Magang (if accepted) -->
                    <div
                        v-if="getAcceptedApplication()"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Progress Magang
                        </h2>
                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center"
                        >
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">
                                    0
                                </p>
                                <p class="text-xs text-gray-500">Logbook</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">
                                    0
                                </p>
                                <p class="text-xs text-gray-500">Disetujui</p>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg">
                                <p class="text-2xl font-bold text-yellow-600">
                                    0
                                </p>
                                <p class="text-xs text-gray-500">Pending</p>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-lg">
                                <p class="text-2xl font-bold text-purple-600">
                                    0%
                                </p>
                                <p class="text-xs text-gray-500">Kehadiran</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center text-sm text-gray-500">
                            Fitur tracking progress akan segera tersedia
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Aplikasi Aktif -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Status Saat Ini
                        </h3>
                        <div v-if="getAcceptedApplication()" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Divisi</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        getAcceptedApplication().division.name
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Status</span
                                >
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                >
                                    Diterima
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Mulai</span>
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{
                                        formatDate(
                                            getAcceptedApplication().created_at
                                        )
                                    }}</span
                                >
                            </div>
                        </div>
                        <div v-else class="text-center py-4">
                            <p class="text-sm text-gray-500">
                                Belum ada aplikasi yang diterima
                            </p>
                        </div>
                    </div>

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
                                    >Total Aplikasi</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ applications.length }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Diterima</span
                                >
                                <span
                                    class="text-sm font-medium text-green-600"
                                    >{{
                                        applications.filter((app) =>
                                            [
                                                "accepted",
                                                "letter_ready",
                                                "diterima",
                                            ].includes(app.status)
                                        ).length
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"
                                    >Ditolak</span
                                >
                                <span
                                    class="text-sm font-medium text-red-600"
                                    >{{
                                        applications.filter(
                                            (app) => app.status === "rejected"
                                        ).length
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
                                        participant.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{
                                        participant.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
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
                            <button
                                @click="toggleStatus"
                                :class="[
                                    'w-full px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center',
                                    participant.is_active
                                        ? 'bg-red-600 text-white hover:bg-red-700'
                                        : 'bg-green-600 text-white hover:bg-green-700',
                                ]"
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
                                        :d="
                                            participant.is_active
                                                ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636'
                                                : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                        "
                                    />
                                </svg>
                                {{
                                    participant.is_active
                                        ? "Nonaktifkan Peserta"
                                        : "Aktifkan Peserta"
                                }}
                            </button>
                            <Link
                                v-if="getAcceptedApplication()"
                                :href="
                                    route(
                                        'admin.divisions.show',
                                        getAcceptedApplication().division.id
                                    )
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
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                Lihat Divisi
                            </Link>
                            <Link
                                href="#"
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
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    />
                                </svg>
                                Lihat Logbook
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
import { Link, router } from "@inertiajs/vue3";

// Import route helper
const route = window.route;

const props = defineProps({
    participant: Object,
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
        menunggu: "bg-yellow-100 text-yellow-800",
        in_review: "bg-blue-100 text-blue-800",
        interview_scheduled: "bg-purple-100 text-purple-800",
        accepted: "bg-green-100 text-green-800",
        rejected: "bg-red-100 text-red-800",
        expired: "bg-gray-100 text-gray-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        menunggu: "Menunggu",
        in_review: "Dalam Review",
        interview_scheduled: "Wawancara",
        accepted: "Diterima",
        rejected: "Ditolak",
        expired: "Kedaluwarsa",
    };
    return texts[status] || status;
};

const getAcceptedApplication = () => {
    return props.applications.find((app) =>
        ["accepted", "letter_ready", "diterima"].includes(app.status)
    );
};

const toggleStatus = () => {
    router.put(
        route("admin.participants.update-status", props.participant.id),
        {
            is_active: !props.participant.is_active,
        }
    );
};
</script>
