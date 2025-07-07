<template>
    <Head title="Status Aplikasi" />

    <GuestLayout>
        <div
            class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12"
        >
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Status Aplikasi Magang
                    </h1>
                    <p class="text-lg text-gray-600">
                        ID Aplikasi:
                        <span class="font-mono font-medium text-blue-600">{{
                            application.id
                        }}</span>
                    </p>
                </div>

                <!-- Status Card -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8">
                    <!-- Status Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Status Pendaftaran
                            </h2>
                            <span
                                :class="statusBadgeClass"
                                class="px-3 py-1 rounded-full text-sm font-medium"
                            >
                                {{ statusText }}
                            </span>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-3">
                                    Informasi Pelamar
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <p>
                                        <span class="text-gray-600">Nama:</span>
                                        <span class="font-medium">{{
                                            application.name
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Email:</span
                                        >
                                        <span class="font-medium">{{
                                            application.email
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Universitas:</span
                                        >
                                        <span class="font-medium">{{
                                            application.university
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Jurusan:</span
                                        >
                                        <span class="font-medium">{{
                                            application.major
                                        }}</span>
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-3">
                                    Detail Magang
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <p>
                                        <span class="text-gray-600"
                                            >Divisi:</span
                                        >
                                        <span class="font-medium">{{
                                            application.division.name
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Tanggal Mulai:</span
                                        >
                                        <span class="font-medium">{{
                                            formatDate(application.start_date)
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Tanggal Selesai:</span
                                        >
                                        <span class="font-medium">{{
                                            formatDate(application.end_date)
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600"
                                            >Tanggal Daftar:</span
                                        >
                                        <span class="font-medium">{{
                                            formatDate(application.applied_at)
                                        }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div class="px-6 py-4 bg-gray-50">
                        <h3 class="font-medium text-gray-900 mb-4">
                            Timeline Proses
                        </h3>
                        <div class="space-y-4">
                            <!-- Submitted -->
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
                                        Aplikasi Diterima
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ formatDate(application.applied_at) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Under Review -->
                            <div class="flex items-center">
                                <div
                                    :class="
                                        application.status === 'pending'
                                            ? 'bg-yellow-500 animate-pulse'
                                            : 'bg-green-500'
                                    "
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        v-if="application.status !== 'pending'"
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <div
                                        v-else
                                        class="w-2 h-2 bg-white rounded-full"
                                    ></div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
                                        Sedang Direview
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{
                                            application.status === "pending"
                                                ? "Dalam proses review..."
                                                : "Review selesai"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Final Decision -->
                            <div class="flex items-center">
                                <div
                                    :class="getFinalStepClass()"
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        v-if="application.status === 'approved'"
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <svg
                                        v-else-if="
                                            application.status === 'rejected'
                                        "
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <div
                                        v-else
                                        class="w-2 h-2 bg-white rounded-full opacity-50"
                                    ></div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
                                        Keputusan Akhir
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span
                                            v-if="
                                                application.status ===
                                                'approved'
                                            "
                                            >Selamat! Aplikasi Anda
                                            diterima</span
                                        >
                                        <span
                                            v-else-if="
                                                application.status ===
                                                'rejected'
                                            "
                                            >Aplikasi tidak dapat diproses</span
                                        >
                                        <span v-else
                                            >Menunggu keputusan...</span
                                        >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div
                        v-if="application.status === 'approved'"
                        class="px-6 py-4 bg-green-50 border-t border-green-200"
                    >
                        <div class="flex items-start">
                            <svg
                                class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5 mr-3"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <div>
                                <p class="font-medium text-green-800 mb-1">
                                    Langkah Selanjutnya
                                </p>
                                <p class="text-sm text-green-700">
                                    Tim HR akan menghubungi Anda dalam 2-3 hari
                                    kerja untuk proses onboarding dan penjelasan
                                    lebih lanjut mengenai program magang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else-if="application.status === 'rejected'"
                        class="px-6 py-4 bg-red-50 border-t border-red-200"
                    >
                        <div class="flex items-start">
                            <svg
                                class="flex-shrink-0 w-5 h-5 text-red-600 mt-0.5 mr-3"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <div>
                                <p class="font-medium text-red-800 mb-1">
                                    Informasi
                                </p>
                                <p class="text-sm text-red-700">
                                    Terima kasih atas minat Anda. Sayangnya,
                                    saat ini kami tidak dapat melanjutkan
                                    aplikasi Anda. Silakan coba lagi di periode
                                    pendaftaran berikutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center space-y-4">
                    <Link
                        :href="route('public.check.status')"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors mr-4"
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
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        Cek Status Lain
                    </Link>

                    <Link
                        :href="route('home')"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
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
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { computed } from "vue";

const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
});

const statusText = computed(() => {
    switch (props.application.status) {
        case "pending":
            return "Menunggu Review";
        case "approved":
            return "Diterima";
        case "rejected":
            return "Ditolak";
        default:
            return "Unknown";
    }
});

const statusBadgeClass = computed(() => {
    switch (props.application.status) {
        case "pending":
            return "bg-yellow-100 text-yellow-800";
        case "approved":
            return "bg-green-100 text-green-800";
        case "rejected":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
});

const getFinalStepClass = () => {
    switch (props.application.status) {
        case "approved":
            return "bg-green-500";
        case "rejected":
            return "bg-red-500";
        default:
            return "bg-gray-300";
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
