<template>
    <Head title="Status Lamaran" />

    <GuestLayout variant="public">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Status Lamaran Magang
                    </h1>
                    <p class="text-lg text-gray-600">
                        Pantau progress lamaran magang Anda di PT GrowithBI
                    </p>
                </div>

                <!-- Search Form -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <form @submit.prevent="checkStatus" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Email yang Terdaftar *
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="contoh@email.com"
                                />
                            </div>
                            <div>
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nomor Telepon *
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="08123456789"
                                />
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <button
                                type="submit"
                                :disabled="processing"
                                class="px-8 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
                            >
                                {{ processing ? "Mencari..." : "Cek Status" }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Status Result -->
                <div
                    v-if="application"
                    class="bg-white rounded-lg shadow-lg p-8"
                >
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-8 h-8 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-2"
                            >
                                {{ application.name }}
                            </h3>
                            <p class="text-gray-600">
                                {{ application.division_name }}
                            </p>
                            <div class="mt-4">
                                <span
                                    :class="statusClasses[application.status]"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ statusLabels[application.status] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            Timeline Seleksi
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-white"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Pendaftaran Diterima
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(application.created_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        :class="
                                            application.status === 'menunggu'
                                                ? 'bg-yellow-500'
                                                : 'bg-green-500'
                                        "
                                        class="w-8 h-8 rounded-full flex items-center justify-center"
                                    >
                                        <svg
                                            v-if="
                                                application.status !==
                                                'menunggu'
                                            "
                                            class="w-5 h-5 text-white"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            ></path>
                                        </svg>
                                        <div
                                            v-else
                                            class="w-3 h-3 bg-white rounded-full animate-pulse"
                                        ></div>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Verifikasi Dokumen
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            application.status === "menunggu"
                                                ? "Sedang diproses..."
                                                : "Selesai"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Not Found Message -->
                <div
                    v-else-if="searched && !application"
                    class="bg-white rounded-lg shadow-lg p-8 text-center"
                >
                    <div
                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Lamaran Tidak Ditemukan
                    </h3>
                    <p class="text-gray-600">
                        Pastikan email dan nomor telepon yang Anda masukkan
                        sesuai dengan data saat mendaftar.
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ref } from "vue";

// Data
const application = ref(null);
const searched = ref(false);

// Form
const form = useForm({
    email: "",
    phone: "",
});

// Status configuration
const statusLabels = {
    menunggu: "Menunggu Verifikasi",
    in_review: "Dalam Review",
    interview_scheduled: "Wawancara Dijadwalkan",
    accepted: "Diterima",
    rejected: "Ditolak",
    expired: "Kedaluwarsa",
};

const statusClasses = {
    menunggu: "bg-yellow-100 text-yellow-800",
    in_review: "bg-blue-100 text-blue-800",
    interview_scheduled: "bg-purple-100 text-purple-800",
    accepted: "bg-green-100 text-green-800",
    rejected: "bg-red-100 text-red-800",
    expired: "bg-gray-100 text-gray-800",
};

// Methods
const checkStatus = () => {
    form.post(route("application.check-status"), {
        onSuccess: (response) => {
            application.value = response.props.application;
            searched.value = true;
        },
        onError: () => {
            application.value = null;
            searched.value = true;
        },
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
