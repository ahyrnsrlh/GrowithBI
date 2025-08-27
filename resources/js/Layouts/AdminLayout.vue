<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
            <!-- Logo -->
            <div
                class="flex h-16 items-center justify-center border-b border-gray-200"
            >
                <div class="flex items-center space-x-2">
                    <img
                        src="/logo.png"
                        alt="GrowithBI Logo"
                        class="h-8 w-8 object-contain"
                    />
                    <span class="text-xl font-bold text-blue-900"
                        >GrowithBI</span
                    >
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-4">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <Link
                        href="/admin/dashboard"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url === '/admin/dashboard'
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"
                            />
                        </svg>
                        Dashboard
                    </Link>

                    <!-- Pendaftaran -->
                    <Link
                        href="/admin/applications"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/applications')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
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
                        Pendaftaran
                        <span
                            v-if="pendingCount > 0"
                            class="ml-auto bg-red-100 text-red-600 text-xs font-medium px-2.5 py-0.5 rounded-full"
                        >
                            {{ pendingCount }}
                        </span>
                    </Link>

                    <!-- Divisi -->
                    <Link
                        href="/admin/divisions"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/divisions')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
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
                        Divisi
                    </Link>

                    <!-- Pembimbing -->
                    <Link
                        href="/admin/supervisors"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/supervisors')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
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
                        Manajemen Pembimbing
                    </Link>

                    <!-- Peserta -->
                    <Link
                        href="/admin/participants"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/participants')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            />
                        </svg>
                        Manajemen Peserta
                    </Link>

                    <!-- Logbook/Laporan Harian -->
                    <Link
                        href="/admin/logbooks"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/logbooks')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
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
                        Laporan Harian
                    </Link>

                    <!-- Laporan -->
                    <Link
                        href="/admin/reports"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                            $page.url.startsWith('/admin/reports')
                                ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
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
                        Laporan
                    </Link>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex h-16 items-center justify-between px-6">
                    <!-- Page Title -->
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ title }}
                        </h1>
                        <p v-if="subtitle" class="text-sm text-gray-500">
                            {{ subtitle }}
                        </p>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button
                            class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-5 5v-5zM4 17h8a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <span
                                v-if="notificationCount > 0"
                                class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs font-medium flex items-center justify-center rounded-full"
                            >
                                {{
                                    notificationCount > 9
                                        ? "9+"
                                        : notificationCount
                                }}
                            </span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button
                                @click="showProfileMenu = !showProfileMenu"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <div
                                    class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center"
                                >
                                    <span class="text-white font-medium text-sm"
                                        >A</span
                                    >
                                </div>
                                <div class="text-left">
                                    <p
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        Admin
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Administrator
                                    </p>
                                </div>
                                <svg
                                    class="h-4 w-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                v-show="showProfileMenu"
                                @click.away="showProfileMenu = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                            >
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="mr-2 h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                        Profil
                                    </span>
                                </a>
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="mr-2 h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        Pengaturan
                                    </span>
                                </a>
                                <hr class="my-1" />
                                <button
                                    @click="logout"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="mr-2 h-4 w-4"
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
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    title: {
        type: String,
        default: "Dashboard",
    },
    subtitle: {
        type: String,
        default: null,
    },
    pendingCount: {
        type: Number,
        default: 0,
    },
    notificationCount: {
        type: Number,
        default: 0,
    },
});

const showProfileMenu = ref(false);

const logout = () => {
    router.post(
        route("logout"),
        {},
        {
            onSuccess: () => {
                // Redirect ke halaman login setelah logout berhasil
                window.location.href = '/login';
            },
            onError: (errors) => {
                console.error("Logout error:", errors);
                // Jika ada error, tetap redirect ke login
                window.location.href = '/login';
            },
            onFinish: () => {
                // Pastikan redirect ke login dalam semua kasus
                setTimeout(() => {
                    if (window.location.pathname !== '/login') {
                        window.location.href = '/login';
                    }
                }, 100);
            }
        }
    );
};
</script>
