<template>
    <Head :title="division.name" />
    <GuestLayout variant="public">
        <!-- Modal Notification Overlay -->
        <div
            v-if="notification.show"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="
                                    notification.type === 'success'
                                        ? 'bg-green-100'
                                        : 'bg-red-100'
                                "
                            >
                                <svg
                                    v-if="notification.type === 'success'"
                                    class="h-6 w-6 text-green-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-6 w-6 text-red-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{ notification.title }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ notification.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            @click="closeNotification"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            :class="
                                notification.type === 'success'
                                    ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                                    : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                            "
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="min-h-screen bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                <!-- Back Navigation -->
                <div class="mb-8">
                    <Link
                        href="/divisions"
                        class="group inline-flex items-center text-gray-600 hover:text-blue-600 transition-all duration-200"
                    >
                        <svg
                            class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium"
                            >Kembali ke Daftar Divisi</span
                        >
                    </Link>
                </div>

                <!-- Hero Section -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden"
                >
                    <div class="p-8 lg:p-10">
                        <div
                            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8"
                        >
                            <div class="flex-1">
                                <h1
                                    class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"
                                >
                                    {{ division.name }}
                                </h1>
                                <p
                                    class="text-gray-600 text-lg leading-relaxed mb-8"
                                >
                                    {{ division.description }}
                                </p>

                                <!-- Stats Grid -->
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="text-center">
                                        <div
                                            class="text-2xl font-bold text-blue-600 mb-1"
                                        >
                                            {{ division.quota }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Posisi
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-2xl font-bold text-green-600 mb-1"
                                        >
                                            {{ division.available_slots }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Tersedia
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-2xl font-bold text-purple-600 mb-1"
                                        >
                                            {{
                                                division.quota -
                                                division.available_slots
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Pelamar
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Apply Button -->
                            <div class="lg:flex-shrink-0">
                                <button
                                    v-if="!auth.user"
                                    @click="handleApply"
                                    :disabled="
                                        division.available_slots <= 0 ||
                                        isLoading
                                    "
                                    class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed"
                                >
                                    <span
                                        v-if="!isLoading"
                                        class="flex items-center justify-center space-x-2"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            ></path>
                                        </svg>
                                        <span>{{
                                            division.available_slots <= 0
                                                ? "Kuota Penuh"
                                                : "Lamar Sekarang"
                                        }}</span>
                                    </span>
                                    <span
                                        v-else
                                        class="flex items-center justify-center space-x-2"
                                    >
                                        <svg
                                            class="animate-spin h-5 w-5 text-white"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <span>Processing...</span>
                                    </span>
                                </button>
                                <div
                                    v-else
                                    class="text-center bg-green-50 rounded-xl p-6 border border-green-100"
                                >
                                    <p class="text-gray-600 mb-4">
                                        Login sebagai
                                        <span
                                            class="font-semibold text-gray-900"
                                            >{{ auth.user.name }}</span
                                        >
                                    </p>
                                    <Link
                                        href="/dashboard"
                                        class="inline-flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200"
                                    >
                                        <span>Dashboard</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Job Description -->
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                        >
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                Deskripsi Pekerjaan
                            </h2>
                            <div class="space-y-4">
                                <div
                                    v-for="(
                                        desc, index
                                    ) in division.job_description"
                                    :key="desc"
                                    class="flex items-start space-x-4"
                                >
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold"
                                    >
                                        {{ index + 1 }}
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ desc.replace(/^\d+\.\s*/, "") }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                        >
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                Persyaratan & Kualifikasi
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="requirement in division.requirements"
                                    :key="requirement"
                                    class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-100"
                                >
                                    <svg
                                        class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                    <span
                                        class="text-gray-700 leading-relaxed"
                                        >{{ requirement }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Company Info -->
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                        >
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                Tentang Perusahaan
                            </h2>
                            <div class="prose prose-gray max-w-none">
                                <p class="text-gray-700 leading-relaxed">
                                    PT GrowithBI (Persero) adalah perusahaan
                                    BUMN yang bergerak di bidang teknologi dan
                                    business intelligence. Kami berkomitmen
                                    untuk mengembangkan solusi teknologi
                                    terdepan yang mendukung transformasi digital
                                    Indonesia. Dengan tim yang berpengalaman dan
                                    teknologi terkini, kami membantu berbagai
                                    organisasi dalam pengambilan keputusan
                                    berbasis data.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Info Card -->
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-6"
                            >
                                Informasi Lowongan
                            </h3>

                            <div class="space-y-6">
                                <!-- Salary Info -->
                                <div
                                    class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl"
                                >
                                    <div
                                        class="text-sm text-purple-600 font-medium mb-1"
                                    >
                                        Rentang Gaji
                                    </div>
                                    <div
                                        class="text-lg font-bold text-purple-700"
                                    >
                                        {{ division.salary_range }}
                                    </div>
                                </div>

                                <!-- Timeline -->
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-2 h-2 bg-orange-400 rounded-full mt-2"
                                        ></div>
                                        <div>
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                Penutupan Lamaran
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{
                                                    division.application_deadline
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-2 h-2 bg-green-400 rounded-full mt-2"
                                        ></div>
                                        <div>
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                Pengumuman Seleksi
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{
                                                    division.selection_announcement
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Share Section -->
                                <div class="pt-6 border-t border-gray-100">
                                    <h4
                                        class="text-sm font-medium text-gray-900 mb-4"
                                    >
                                        Bagikan Lowongan
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button
                                            class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium"
                                        >
                                            Twitter
                                        </button>
                                        <button
                                            class="p-3 bg-blue-800 hover:bg-blue-900 text-white rounded-lg transition-colors duration-200 text-sm font-medium"
                                        >
                                            LinkedIn
                                        </button>
                                        <button
                                            class="p-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium"
                                        >
                                            WhatsApp
                                        </button>
                                        <button
                                            class="p-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium"
                                        >
                                            Salin Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});

// Reactive data
const isLoading = ref(false);
const notification = ref({
    show: false,
    type: "success", // 'success' or 'error'
    title: "",
    message: "",
});

// Methods
const handleApply = async () => {
    if (props.division.available_slots <= 0) {
        showNotification(
            "error",
            "Maaf",
            "Kuota untuk divisi ini sudah penuh."
        );
        return;
    }

    isLoading.value = true;

    try {
        // Simulate API call delay
        await new Promise((resolve) => setTimeout(resolve, 1000));

        // For now, just redirect to application form
        window.location.href = `/divisions/${props.division.id}/apply`;
    } catch (error) {
        showNotification(
            "error",
            "Error",
            "Terjadi kesalahan saat memproses lamaran Anda."
        );
    } finally {
        isLoading.value = false;
    }
};

const showNotification = (type, title, message) => {
    notification.value = {
        show: true,
        type,
        title,
        message,
    };
};

const closeNotification = () => {
    notification.value.show = false;
};
</script>
