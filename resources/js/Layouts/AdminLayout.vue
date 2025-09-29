<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-4 left-4 z-50">
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-md bg-gradient-to-r from-blue-800 to-indigo-900 text-white hover:from-blue-900 hover:to-indigo-950 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        v-if="!sidebarOpen"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        v-else
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Mobile overlay -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="lg:hidden fixed inset-0 z-40 bg-black bg-opacity-50"
        ></div>

        <!-- Sidebar -->
        <div
            :class="[
                'sidebar fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-indigo-900 shadow-lg transform transition-transform duration-300 ease-in-out',
                sidebarOpen
                    ? 'translate-x-0'
                    : '-translate-x-full lg:translate-x-0',
            ]"
        >
            <!-- Logo -->
            <div
                class="flex h-16 items-center justify-center border-b border-blue-700 border-opacity-40 flex-shrink-0"
            >
                <div class="flex items-center space-x-2">
                    <img
                        src="/logo.png"
                        alt="GrowithBI Logo"
                        class="h-8 w-8 object-contain"
                    />
                    <span class="text-xl font-bold text-white">GrowithBI</span>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex flex-col h-full">
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <Link
                            href="/admin/dashboard"
                            :class="[
                                'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                                $page.url === '/admin/dashboard'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
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
                                    ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
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
                                    ? 'bg-blue-500 bg-opacity-30 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-500 hover:bg-opacity-30 hover:text-white',
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

                        <!-- Peserta -->
                        <Link
                            href="/admin/participants"
                            :class="[
                                'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                                $page.url.startsWith('/admin/participants')
                                    ? 'bg-blue-500 bg-opacity-30 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-500 hover:bg-opacity-30 hover:text-white',
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
                                    ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
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

                        <!-- Attendance Management -->
                        <div class="space-y-1">
                            <Link
                                href="/admin/attendance"
                                :class="[
                                    'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                                    $page.url.startsWith('/admin/attendance') && !$page.url.includes('/maps')
                                        ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                        : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Absensi Peserta
                            </Link>

                            <!-- Maps Real-Time Sub-menu -->
                            <Link
                                href="/admin/attendance/maps"
                                :class="[
                                    'flex items-center px-4 py-2 ml-4 text-sm font-medium rounded-lg transition-colors duration-200',
                                    $page.url.includes('/admin/attendance/maps')
                                        ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                        : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
                                ]"
                            >
                                <svg
                                    class="mr-3 h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                    />
                                </svg>
                                Maps Real-Time
                            </Link>
                        </div>

                        <!-- Final Reports -->
                        <Link
                            href="/admin/final-reports"
                            :class="[
                                'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                                $page.url.startsWith('/admin/final-reports')
                                    ? 'bg-blue-700 bg-opacity-40 text-white border-r-2 border-blue-300'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-40 hover:text-white',
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
                            Laporan Akhir
                        </Link>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content lg:ml-64 min-h-screen">
            <!-- Top Header -->
            <header
                class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30"
            >
                <div
                    class="flex h-16 items-center justify-between px-6 lg:px-6"
                >
                    <!-- Mobile menu space -->
                    <div class="lg:hidden w-10"></div>

                    <!-- Page Title -->
                    <div class="flex-1 lg:flex-none">
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
                        <div class="relative">
                            <button
                                @click="
                                    showNotificationMenu = !showNotificationMenu
                                "
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

                            <!-- Notification Dropdown Menu -->
                            <div
                                v-show="showNotificationMenu"
                                @click.away="showNotificationMenu = false"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                            >
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Notifikasi
                                    </h3>
                                </div>

                                <!-- Notification Items -->
                                <div class="max-h-96 overflow-y-auto">
                                    <div
                                        v-if="notifications.length === 0"
                                        class="px-4 py-8 text-center"
                                    >
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
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
                                        <p class="mt-2 text-sm text-gray-500">
                                            Belum ada notifikasi
                                        </p>
                                    </div>

                                    <div
                                        v-for="notification in notifications"
                                        :key="notification.id"
                                        class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer"
                                        @click="
                                            handleNotificationClick(
                                                notification
                                            )
                                        "
                                    >
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="w-4 h-4 text-white"
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
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ notification.title }}
                                                </p>
                                                <p
                                                    class="text-sm text-gray-500 mt-1"
                                                >
                                                    {{ notification.message }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-400 mt-1"
                                                >
                                                    {{
                                                        formatNotificationTime(
                                                            notification.created_at
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                v-if="!notification.read_at"
                                                class="w-2 h-2 bg-blue-500 rounded-full"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- View All Link -->
                                <div class="px-4 py-3 border-t border-gray-200">
                                    <Link
                                        href="/admin/notifications"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                                        @click="showNotificationMenu = false"
                                    >
                                        Lihat semua notifikasi
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button
                                @click="showProfileMenu = !showProfileMenu"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <!-- Avatar with photo or initials -->
                                <div
                                    v-if="auth.user?.profile_photo_path"
                                    class="h-8 w-8 rounded-full overflow-hidden border border-gray-200"
                                >
                                    <img
                                        :src="`/storage/${auth.user.profile_photo_path}`"
                                        :alt="auth.user?.name"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center"
                                >
                                    <span
                                        class="text-white font-medium text-sm"
                                    >
                                        {{ getInitials(auth.user?.name) }}
                                    </span>
                                </div>

                                <div class="text-left">
                                    <p
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        {{ auth.user?.name || "Admin" }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{
                                            auth.user?.position ||
                                            "Administrator"
                                        }}
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
                                <Link
                                    href="/admin/profile"
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
                                        Profile
                                    </span>
                                </Link>
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
            <main class="p-4 lg:p-6 bg-white min-h-screen">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const auth = computed(() => page.props.auth);

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

// Helper function to get user initials
const getInitials = (name) => {
    if (!name) return "A";
    return name
        .split(" ")
        .map((word) => word.charAt(0))
        .join("")
        .toUpperCase()
        .slice(0, 2);
};

const showProfileMenu = ref(false);
const showNotificationMenu = ref(false);
const sidebarOpen = ref(false);

// Sample notifications data - bisa diganti dengan data dari server
const notifications = ref([
    {
        id: 1,
        title: "Aplikasi Baru",
        message: "Ahmad Rizki Pratama telah mengirim aplikasi magang",
        created_at: "2024-09-17T10:30:00Z",
        read_at: null,
        type: "application",
    },
    {
        id: 2,
        title: "Laporan Harian",
        message: "Dewi Lestari telah mengirim laporan harian",
        created_at: "2024-09-17T09:15:00Z",
        read_at: null,
        type: "logbook",
    },
    {
        id: 3,
        title: "Status Update",
        message: "Aplikasi magang telah diperbarui",
        created_at: "2024-09-16T16:45:00Z",
        read_at: "2024-09-17T08:00:00Z",
        type: "status",
    },
]);

// Close sidebar when clicking outside on mobile
const handleClickOutside = (event) => {
    if (
        sidebarOpen.value &&
        !event.target.closest(".sidebar") &&
        !event.target.closest(".mobile-menu-button")
    ) {
        sidebarOpen.value = false;
    }

    // Close notification menu when clicking outside
    if (showNotificationMenu.value && !event.target.closest(".relative")) {
        showNotificationMenu.value = false;
    }

    // Close profile menu when clicking outside
    if (showProfileMenu.value && !event.target.closest(".relative")) {
        showProfileMenu.value = false;
    }
};

// Handle notification click
const handleNotificationClick = (notification) => {
    // Mark as read if not already read
    if (!notification.read_at) {
        notification.read_at = new Date().toISOString();
    }

    // Navigate based on notification type
    switch (notification.type) {
        case "application":
            router.visit("/admin/applications");
            break;
        case "logbook":
            router.visit("/admin/logbooks");
            break;
        case "status":
            router.visit("/admin/dashboard");
            break;
        default:
            console.log("Notification clicked:", notification);
    }

    showNotificationMenu.value = false;
};

// Format notification time
const formatNotificationTime = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;

    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return "Baru saja";
    if (minutes < 60) return `${minutes} menit yang lalu`;
    if (hours < 24) return `${hours} jam yang lalu`;
    if (days < 7) return `${days} hari yang lalu`;

    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

// Close sidebar on route change (mobile)
const handleRouteChange = () => {
    sidebarOpen.value = false;
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    // Listen for Inertia navigation
    document.addEventListener("inertia:navigate", handleRouteChange);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
    document.removeEventListener("inertia:navigate", handleRouteChange);
});

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

<style scoped>
/* Ensure the sidebar stays fixed and handles overflow properly */
.sidebar {
    position: fixed !important;
    height: 100vh !important;
    overflow-y: auto;
}

/* Smooth transitions for mobile sidebar */
@media (max-width: 1023px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .sidebar.open {
        transform: translateX(0);
    }
}

/* Ensure main content doesn't overlap sidebar on desktop */
@media (min-width: 1024px) {
    .main-content {
        margin-left: 16rem; /* 64 * 0.25rem = 16rem */
    }
}

/* Hide scrollbar for sidebar navigation while keeping functionality */
.sidebar nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar nav::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.sidebar nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>
