<template>
    <Head title="Pendaftaran Berhasil - GrowithBI" />

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div
                    class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6"
                >
                    <svg
                        class="w-10 h-10 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>

                <Link href="/" class="inline-flex items-center mb-6">
                    <img
                        src="/logo.png"
                        alt="GrowithBI Logo"
                        class="w-12 h-12 mr-3"
                    />
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                    >
                        GrowithBI
                    </h1>
                </Link>
            </div>

            <!-- Success Card -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-8 text-center"
            >
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    🎉 Pendaftaran Berhasil!
                </h2>

                <p class="text-gray-600 mb-6 leading-relaxed">
                    Selamat! Akun Anda telah berhasil dibuat. Silakan login
                    menggunakan email dan password yang telah Anda daftarkan
                    untuk mengakses dashboard GrowithBI.
                </p>

                <div
                    class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6"
                >
                    <div class="flex items-start space-x-3">
                        <svg
                            class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-left">
                            <h3
                                class="text-sm font-semibold text-blue-900 mb-1"
                            >
                                Langkah Selanjutnya:
                            </h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>✓ Login dengan akun yang baru dibuat</li>
                                <li>✓ Lengkapi profil dan data pribadi</li>
                                <li>✓ Pilih program magang yang diminati</li>
                                <li>✓ Submit aplikasi magang</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">
                        Detail Akun Anda:
                    </h3>
                    <div class="text-sm text-gray-600">
                        <p><strong>Email:</strong> {{ email }}</p>
                        <p><strong>Nama:</strong> {{ name }}</p>
                        <p><strong>Status:</strong> Peserta Magang</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <Link
                        :href="route('login') + '?registered=1'"
                        class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 shadow-lg shadow-blue-600/30"
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
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            />
                        </svg>
                        Login Sekarang
                    </Link>

                    <Link
                        href="/"
                        class="w-full inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
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
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500">
                    Butuh bantuan?
                    <a
                        href="mailto:growithbi@bi.go.id"
                        class="text-blue-600 hover:text-blue-700 font-medium"
                    >
                        Hubungi kami
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    email: String,
    name: String,
});
</script>
