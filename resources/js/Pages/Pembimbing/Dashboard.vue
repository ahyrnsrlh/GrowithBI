<template>
    <PembimbingLayout
        title="Dashboard Pembimbing"
        subtitle="Kelola peserta magang dan pantau perkembangan mereka"
    >
        <!-- Hero Section -->
        <div class="relative mb-8 overflow-hidden">
            <div
                class="bg-gradient-to-br from-green-600 via-green-600 to-green-700 rounded-2xl p-8 border-2 border-green-600 shadow-xl"
            >
                <div class="relative z-10">
                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div>
                            <h1
                                class="text-3xl lg:text-4xl font-bold mb-2 text-white"
                            >
                                Selamat datang, Pembimbing! 👨‍🏫
                            </h1>
                            <p class="text-white text-lg mb-4 font-medium">
                                Bimbing dan pantau perkembangan peserta magang
                                Anda
                            </p>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >{{
                                            stats.total_participants
                                        }}
                                        Peserta</span
                                    >
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-yellow-300 rounded-full mr-2"
                                        :class="{
                                            'animate-pulse':
                                                stats.pending_applications > 0,
                                        }"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >{{ stats.pending_applications }} Perlu
                                        Review</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 lg:mt-0">
                            <div class="flex space-x-3">
                                <button
                                    class="inline-flex items-center px-6 py-3 bg-white text-green-700 border-2 border-green-200 rounded-xl hover:bg-green-50 hover:border-green-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg"
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
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                    Kelola Peserta
                                </button>
                                <button
                                    class="inline-flex items-center px-6 py-3 bg-white text-green-700 border-2 border-green-200 rounded-xl hover:bg-green-50 hover:border-green-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg"
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Review Logbook
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
            <!-- Total Participants -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Total Peserta
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_participants }}
                        </p>
                        <p class="text-green-100 text-xs mt-1">
                            {{ stats.active_participants }} aktif
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
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div
                class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">
                            Perlu Review
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.pending_applications }}
                        </p>
                        <p class="text-yellow-100 text-xs mt-1">
                            Aplikasi masuk
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

            <!-- Supervised Divisions -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Divisi Dibimbing
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_divisions }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">Divisi aktif</p>
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

            <!-- Recent Logbooks -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Logbook Terbaru
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.recent_logbooks }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">Minggu ini</p>
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
            <!-- Participants List -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50"
                >
                    <h3 class="text-xl font-bold text-gray-900">
                        Peserta Bimbingan
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Daftar peserta yang Anda bimbing
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4" v-if="participants.length">
                        <div
                            v-for="participant in participants"
                            :key="participant.id"
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200"
                        >
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg"
                                >
                                    <span
                                        class="text-white font-bold text-lg"
                                        >{{ participant.name.charAt(0) }}</span
                                    >
                                </div>
                                <div>
                                    <p
                                        class="text-base font-semibold text-gray-900"
                                    >
                                        {{ participant.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ participant.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                        participant.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{
                                        participant.is_active
                                            ? "Aktif"
                                            : "Non-aktif"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <p class="text-gray-500">
                            Belum ada peserta yang dibimbing
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50"
                >
                    <h3 class="text-xl font-bold text-gray-900">
                        Aplikasi Terbaru
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Aplikasi untuk divisi yang Anda awasi
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4" v-if="recentApplications.length">
                        <div
                            v-for="application in recentApplications"
                            :key="application.id"
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200"
                        >
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg"
                                >
                                    <span
                                        class="text-white font-bold text-lg"
                                        >{{
                                            application.user.name.charAt(0)
                                        }}</span
                                    >
                                </div>
                                <div>
                                    <p
                                        class="text-base font-semibold text-gray-900"
                                    >
                                        {{ application.user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ application.division.name }}
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
                        <p class="text-gray-500">Belum ada aplikasi terbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </PembimbingLayout>
</template>

<script setup>
import PembimbingLayout from "@/Layouts/PembimbingLayout.vue";
import { router } from "@inertiajs/vue3";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_participants: 0,
            active_participants: 0,
            total_divisions: 0,
            pending_applications: 0,
            recent_logbooks: 0,
        }),
    },
    participants: {
        type: Array,
        default: () => [],
    },
    recentApplications: {
        type: Array,
        default: () => [],
    },
    supervisedDivisions: {
        type: Array,
        default: () => [],
    },
});

const logout = () => {
    router.post(
        route("logout"),
        {},
        {
            onSuccess: () => {
                window.location.href = route("login");
            },
            onError: (errors) => {
                console.error("Logout error:", errors);
            },
        }
    );
};
</script>
