<template>
    <Head title="Pendaftaran Berhasil" />

    <GuestLayout>
        <div
            class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center py-12"
        >
            <div class="max-w-lg mx-auto px-4">
                <div class="bg-white shadow-xl rounded-2xl p-8 text-center">
                    <!-- Success Icon -->
                    <div
                        class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6"
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

                    <!-- Success Message -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Pendaftaran Berhasil!
                    </h1>

                    <p class="text-lg text-gray-600 mb-6">
                        {{ message }}
                    </p>

                    <!-- Additional Info -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-blue-900 mb-2">
                            Langkah Selanjutnya:
                        </h3>
                        <ul class="text-sm text-blue-800 space-y-1 text-left">
                            <li>
                                • Tim HR akan meninjau aplikasi Anda dalam 3-5
                                hari kerja
                            </li>
                            <li>
                                • Anda akan menerima email konfirmasi jika
                                diterima
                            </li>
                            <li>
                                • Cek status pendaftaran Anda secara berkala
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <Link
                            :href="route('public.check.status')"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Cek Status Pendaftaran
                        </Link>

                        <Link
                            :href="route('home')"
                            class="block w-full border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Kembali ke Beranda
                        </Link>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-2">
                            Ada pertanyaan? Hubungi kami:
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Email:</strong> hr@growithbi.com<br />
                            <strong>WhatsApp:</strong> (021) 1234-5678
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    message: {
        type: String,
        required: true,
    },
});
</script>
