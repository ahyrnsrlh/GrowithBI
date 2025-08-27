<template>
    <Head title="Pendaftaran Berhasil" />

    <GuestLayout variant="public">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Card -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
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
                            ></path>
                        </svg>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Pendaftaran Berhasil!
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Terima kasih telah mendaftar untuk program magang di PT
                        GrowithBI. Lamaran Anda telah berhasil dikirim dan akan
                        segera diproses oleh tim kami.
                    </p>

                    <!-- Application Details -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Detail Lamaran
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium text-gray-900">{{
                                    application.name
                                }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900">{{
                                    application.email
                                }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Divisi:</span>
                                <span class="font-medium text-gray-900">{{
                                    application.division_name
                                }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"
                                    >Tanggal Daftar:</span
                                >
                                <span class="font-medium text-gray-900">{{
                                    formatDate(application.created_at)
                                }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
                                >
                                    Menunggu Verifikasi
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-blue-50 rounded-lg p-6 mb-8 text-left">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">
                            Langkah Selanjutnya
                        </h3>
                        <ul class="space-y-3 text-blue-800">
                            <li class="flex items-start space-x-3">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-sm font-medium"
                                    >1</span
                                >
                                <span
                                    >Tim kami akan melakukan verifikasi dokumen
                                    yang Anda kirimkan</span
                                >
                            </li>
                            <li class="flex items-start space-x-3">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-sm font-medium"
                                    >2</span
                                >
                                <span
                                    >Anda akan dihubungi melalui email atau
                                    telepon untuk interview</span
                                >
                            </li>
                            <li class="flex items-start space-x-3">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-sm font-medium"
                                    >3</span
                                >
                                <span
                                    >Pengumuman hasil seleksi akan dikirim
                                    maksimal 7 hari kerja</span
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <svg
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <span class="text-gray-600"
                                    >hr@growithbi.com</span
                                >
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    ></path>
                                </svg>
                                <span class="text-gray-600"
                                    >(021) 1234-5678</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            :href="route('application.status')"
                            class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center"
                        >
                            Cek Status Lamaran
                        </Link>
                        <Link
                            :href="route('public.divisions')"
                            class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-center"
                        >
                            Lihat Divisi Lain
                        </Link>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-center text-gray-600">
                    <p class="text-sm">
                        Email konfirmasi telah dikirim ke alamat email Anda.
                        Jika tidak menerima email, silakan periksa folder
                        spam/junk.
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

// Props
defineProps({
    application: {
        type: Object,
        required: true,
    },
});

// Methods
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
