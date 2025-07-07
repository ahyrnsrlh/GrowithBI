<template>
    <Head title="GrowithBI - Sistem Manajemen Magang" />

    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50"
    >
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <div
                                class="h-8 w-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center"
                            >
                                <span class="text-white font-bold text-sm"
                                    >GB</span
                                >
                            </div>
                            <span class="ml-2 text-xl font-bold text-gray-900"
                                >GrowithBI</span
                            >
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="text-gray-600 hover:text-blue-600 font-medium transition-colors"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Daftar
                        </Link>
                        <Link
                            :href="route('public.apply')"
                            class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:scale-105"
                        >
                            Daftar Magang
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1
                        class="text-4xl md:text-6xl font-bold text-gray-900 mb-6"
                    >
                        Sistem Manajemen
                        <span
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            Magang GrowithBI
                        </span>
                    </h1>
                    <p
                        class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto"
                    >
                        Platform terpadu untuk mengelola program magang, logbook
                        harian, dan monitoring perkembangan peserta magang
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            :href="route('public.apply')"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all transform hover:scale-105 shadow-lg"
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
                            Daftar Magang Sekarang
                        </Link>

                        <Link
                            :href="route('public.check.status')"
                            class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-gray-300 transition-all"
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
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                            Cek Status Pendaftaran
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Background decoration -->
            <div
                class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            >
                <div
                    class="w-96 h-96 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"
                ></div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Fitur Unggulan
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Sistem yang dirancang khusus untuk kemudahan pengelolaan
                        program magang
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <!-- Feature 1 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
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
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Pendaftaran Online
                        </h3>
                        <p class="text-gray-600">
                            Proses pendaftaran magang yang mudah dan cepat
                            secara online
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-green-50 to-green-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
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
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Logbook Digital
                        </h3>
                        <p class="text-gray-600">
                            Catat dan kelola aktivitas harian magang secara
                            digital
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
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
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Manajemen Pembimbing
                        </h3>
                        <p class="text-gray-600">
                            Sistem monitoring dan bimbingan yang terstruktur
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-orange-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
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
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Dashboard Analytics
                        </h3>
                        <p class="text-gray-600">
                            Laporan dan analisis perkembangan magang real-time
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Evaluasi & Rating
                        </h3>
                        <p class="text-gray-600">
                            Sistem penilaian dan feedback untuk peserta magang
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div
                        class="text-center p-6 rounded-xl bg-gradient-to-br from-red-50 to-red-100 hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-5 5v-5zM4 19l5-5 5 5m-1.485-1.485L16 15l-3.485-3.485a1.485 1.485 0 012.97-2.97L18 12l-2.485 2.485a1.485 1.485 0 01-2.97-2.97z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Notifikasi Real-time
                        </h3>
                        <p class="text-gray-600">
                            Pemberitahuan otomatis untuk semua aktivitas penting
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-20">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Siap Memulai Karir Anda?
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    Bergabunglah dengan program magang GrowithBI dan dapatkan
                    pengalaman kerja yang berharga
                </p>
                <Link
                    :href="route('public.apply')"
                    class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-100 text-blue-600 font-semibold rounded-xl transition-all transform hover:scale-105 shadow-lg"
                >
                    Daftar Sekarang
                    <svg
                        class="w-5 h-5 ml-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"
                        />
                    </svg>
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <div
                                class="h-8 w-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center"
                            >
                                <span class="text-white font-bold text-sm"
                                    >GB</span
                                >
                            </div>
                            <span class="ml-2 text-xl font-bold"
                                >GrowithBI</span
                            >
                        </div>
                        <p class="text-gray-400">
                            Sistem manajemen magang yang modern dan efisien
                            untuk mendukung perkembangan karir Anda.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <Link
                                    :href="route('public.apply')"
                                    class="hover:text-white transition-colors"
                                    >Daftar Magang</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('public.check.status')"
                                    class="hover:text-white transition-colors"
                                    >Cek Status</Link
                                >
                            </li>
                            <li v-if="canLogin">
                                <Link
                                    :href="route('login')"
                                    class="hover:text-white transition-colors"
                                    >Masuk</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-4">Kontak</h3>
                        <div class="text-gray-400 space-y-2">
                            <p>Email: info@growithbi.com</p>
                            <p>Telepon: (021) 1234-5678</p>
                            <p>Alamat: Jakarta, Indonesia</p>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }} GrowithBI. All
                        rights reserved.
                    </p>
                    <p class="mt-2 text-sm">
                        Built with Laravel {{ laravelVersion }} & Vue.js
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>
