<template>
    <PesertaLayout
        title="Dashboard Peserta"
        subtitle="Pantau perkembangan magang dan kelola aktivitas harian Anda"
    >
        <!-- Hero Section -->
        <div class="relative mb-8 overflow-hidden">
            <div
                class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 border-2 border-blue-600 shadow-xl"
            >
                <div class="relative z-10">
                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div>
                            <h1
                                class="text-3xl lg:text-4xl font-bold mb-2 text-white"
                            >
                                Selamat datang, {{ user.name }}! 🎓
                            </h1>
                            <p class="text-white text-lg mb-4 font-medium">
                                Mari tingkatkan pengalaman magang Anda
                            </p>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >{{
                                            stats.total_applications
                                        }}
                                        Aplikasi</span
                                    >
                                </div>
                                <div
                                    class="flex items-center"
                                    v-if="stats.current_division"
                                >
                                    <div
                                        class="w-3 h-3 bg-green-300 rounded-full mr-2"
                                    ></div>
                                    <span
                                        class="text-white text-sm font-medium"
                                        >{{ stats.current_division }}</span
                                    >
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-yellow-300 rounded-full mr-2"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >{{
                                            stats.total_logbooks
                                        }}
                                        Logbook</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 lg:mt-0">
                            <div class="flex space-x-3">
                                <button
                                    class="inline-flex items-center px-6 py-3 bg-white text-blue-700 border-2 border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg"
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
                                    Buat Logbook
                                </button>
                                <button
                                    v-if="!stats.current_division"
                                    class="inline-flex items-center px-6 py-3 bg-white text-blue-700 border-2 border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg"
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
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                    Daftar Magang
                                </button>
                                <button
                                    @click="logout"
                                    class="inline-flex items-center px-6 py-3 bg-red-600 text-white border-2 border-red-600 rounded-xl hover:bg-red-700 hover:border-red-700 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg"
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
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Aplikasi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_applications }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">
                            Sepanjang waktu
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div
                class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">
                            Menunggu Review
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.pending_applications }}
                        </p>
                        <p class="text-yellow-100 text-xs mt-1">Dalam proses</p>
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

            <!-- Accepted Applications -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Diterima
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.accepted_applications }}
                        </p>
                        <p class="text-green-100 text-xs mt-1">
                            Aplikasi sukses
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

            <!-- Total Logbooks -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Total Logbook
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_logbooks }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">
                            Aktivitas tercatat
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Application Status -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50"
                >
                    <h3 class="text-xl font-bold text-gray-900">
                        Status Aplikasi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Riwayat pendaftaran magang Anda
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4" v-if="applications.length">
                        <div
                            v-for="application in applications"
                            :key="application.id"
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200"
                        >
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg"
                                >
                                    <span
                                        class="text-white font-bold text-lg"
                                        >{{
                                            application.division.name.charAt(0)
                                        }}</span
                                    >
                                </div>
                                <div>
                                    <p
                                        class="text-base font-semibold text-gray-900"
                                    >
                                        {{ application.division.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(application.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                        application.status === 'menunggu'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : application.status === 'diterima'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ application.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"
                        >
                            <svg
                                class="w-8 h-8 text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-4">
                            Belum ada aplikasi yang dibuat
                        </p>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200"
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
                            Buat Aplikasi Baru
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Logbooks -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50"
                >
                    <h3 class="text-xl font-bold text-gray-900">
                        Logbook Terbaru
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Aktivitas harian terbaru Anda
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4" v-if="recentLogbooks.length">
                        <div
                            v-for="logbook in recentLogbooks"
                            :key="logbook.id"
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200"
                        >
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
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
                                <div>
                                    <p
                                        class="text-base font-semibold text-gray-900"
                                    >
                                        {{
                                            logbook.title || "Aktivitas Harian"
                                        }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            formatDate(
                                                logbook.date ||
                                                    logbook.created_at
                                            )
                                        }}
                                        · {{ logbook.duration || 0 }} jam
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span
                                    :class="
                                        getLogbookStatusClass(logbook.status)
                                    "
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold"
                                >
                                    {{ getLogbookStatusText(logbook.status) }}
                                </span>
                                <Link
                                    :href="
                                        route(
                                            'peserta.logbooks.show',
                                            logbook.id
                                        )
                                    "
                                    class="text-blue-600 hover:text-blue-700"
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
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <div
                                class="flex flex-col sm:flex-row gap-3 justify-center"
                            >
                                <Link
                                    :href="route('peserta.logbooks.index')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
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
                                            d="M4 6h16M4 12h16M4 18h7"
                                        />
                                    </svg>
                                    Lihat Semua Logbook
                                </Link>
                                <Link
                                    :href="route('peserta.logbooks.create')"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors"
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
                                    Tambah Logbook Baru
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3"
                        >
                            <svg
                                class="w-8 h-8 text-purple-400"
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
                        <p class="text-gray-500 mb-4">
                            Belum ada logbook yang dibuat
                        </p>
                        <Link
                            :href="route('peserta.logbooks.create')"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200"
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
                            Buat Logbook Hari Ini
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div
            class="mt-8 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-200"
        >
            <div class="flex items-start space-x-4">
                <div
                    class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center shadow-lg"
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        Informasi Penting
                    </h3>
                    <p class="text-gray-700">
                        Untuk pertanyaan atau bantuan mengenai magang, silakan
                        hubungi admin melalui email atau kontak yang tersedia.
                    </p>
                </div>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_applications: 0,
            pending_applications: 0,
            accepted_applications: 0,
            total_logbooks: 0,
            current_division: null,
            supervisor_name: null,
        }),
    },
    applications: {
        type: Array,
        default: () => [],
    },
    recentLogbooks: {
        type: Array,
        default: () => [],
    },
    availableDivisions: {
        type: Array,
        default: () => [],
    },
    user: {
        type: Object,
        required: true,
    },
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
        return "-";
    }
};

const getLogbookStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getLogbookStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
        rejected: "Ditolak",
    };
    return texts[status] || "Unknown";
};

const logout = () => {
    router.post(
        route("logout"),
        {},
        {
            onSuccess: () => {
                // Redirect ke halaman login setelah logout berhasil
                window.location.href = "/login";
            },
            onError: (errors) => {
                console.error("Logout error:", errors);
                // Jika ada error, tetap redirect ke login
                window.location.href = "/login";
            },
            onFinish: () => {
                // Pastikan redirect ke login dalam semua kasus
                setTimeout(() => {
                    if (window.location.pathname !== "/login") {
                        window.location.href = "/login";
                    }
                }, 100);
            },
        }
    );
};
</script>
