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
                                    <div
                                        class="text-sm text-gray-500 whitespace-pre-line max-h-60 overflow-y-auto"
                                    >
                                        {{ notification.message }}
                                    </div>
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

        <!-- Main Content with Optimized Size -->
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
                <!-- Compact Back Navigation -->
                <div class="mb-6">
                    <Link
                        href="/divisions"
                        class="group inline-flex items-center text-gray-600 hover:text-blue-600 transition-all duration-200 bg-white px-3 py-2 rounded-lg shadow-sm hover:shadow-md"
                    >
                        <svg
                            class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200"
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
                        <span class="text-sm font-semibold"
                            >Kembali ke Daftar Divisi</span
                        >
                    </Link>
                </div>

                <!-- Compact Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Compact Left Sidebar -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-lg p-6 sticky top-4"
                        >
                            <!-- Compact Company Logo and Info -->
                            <div class="text-center mb-6">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg"
                                    style="
                                        background: linear-gradient(
                                            135deg,
                                            #002f6c 0%,
                                            #1e40af 100%
                                        );
                                    "
                                >
                                    <img
                                        src="/logo2.png"
                                        alt="Bank Indonesia Logo"
                                        class="w-10 h-10 object-contain"
                                    />
                                </div>
                                <h3
                                    class="text-base font-bold text-gray-900 mb-1"
                                    style="color: #002f6c"
                                >
                                    Bank Indonesia
                                </h3>
                                <p class="text-xs text-gray-600">
                                    Bank Sentral RI
                                </p>
                            </div>

                            <!-- Compact Job Title Section -->
                            <div class="mb-6">
                                <h1
                                    class="text-xl font-bold text-gray-900 mb-3 leading-tight"
                                    style="color: #002f6c"
                                >
                                    Program Magang {{ division.name }}
                                </h1>

                                <!-- Compact Location -->
                                <div
                                    class="flex items-center text-gray-600 mb-4 bg-gray-50 p-3 rounded-xl"
                                >
                                    <div
                                        class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mr-2"
                                    >
                                        <svg
                                            class="w-4 h-4 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium"
                                        >Bandar Lampung, Indonesia</span
                                    >
                                </div>

                                <!-- Quick Program Info -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6"
                                >
                                    <h4
                                        class="text-sm font-bold text-blue-800 mb-3"
                                    >
                                        Info Program
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span class="text-xs text-gray-600"
                                                >Durasi</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-blue-700"
                                                >{{
                                                    formatDuration(
                                                        division.start_date,
                                                        division.end_date
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span class="text-xs text-gray-600"
                                                >Kuota</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-blue-700"
                                                >{{
                                                    division.quota
                                                }}
                                                orang</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span class="text-xs text-gray-600"
                                                >Deadline</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-blue-700"
                                                >{{
                                                    formatDate(
                                                        division.application_deadline
                                                    )
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Apply Button -->
                            <button
                                v-if="!auth.user"
                                @click="handleApply"
                                :disabled="
                                    division.available_slots <= 0 || isLoading
                                "
                                class="w-full group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold text-base px-5 py-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed overflow-hidden mb-4"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #002f6c 0%,
                                        #1e40af 100%
                                    );
                                "
                            >
                                <!-- Gradient overlay on hover -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>

                                <span
                                    v-if="!isLoading"
                                    class="relative flex items-center justify-center space-x-2"
                                >
                                    <svg
                                        class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"
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
                                            : "Daftar Sekarang"
                                    }}</span>
                                </span>
                                <span
                                    v-else
                                    class="relative flex items-center justify-center space-x-2"
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
                                class="text-center bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-4 border border-green-200 shadow-lg mb-4"
                            >
                                <div
                                    class="w-12 h-12 bg-green-500 rounded-full mx-auto mb-3 flex items-center justify-center shadow-lg"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
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
                                <p class="text-gray-600 mb-3 text-sm">
                                    Login sebagai
                                    <span class="font-bold text-gray-900">{{
                                        auth.user.name
                                    }}</span>
                                </p>
                                <Link
                                    href="/dashboard"
                                    class="inline-flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm"
                                >
                                    <span>Dashboard</span>
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 7l5 5-5 5M6 12h12"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content Area - Simplified -->
                    <div class="lg:col-span-3">
                        <!-- Main Content Container -->
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-lg p-6"
                        >
                            <!-- Job Description -->
                            <div class="mb-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg"
                                        style="
                                            background: linear-gradient(
                                                135deg,
                                                #002f6c 0%,
                                                #1e40af 100%
                                            );
                                        "
                                    >
                                        <svg
                                            class="w-5 h-5 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-900"
                                        style="color: #002f6c"
                                    >
                                        Deskripsi Program
                                    </h3>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-xl p-6 border border-gray-100"
                                >
                                    <p
                                        class="text-gray-700 leading-relaxed mb-6 text-base"
                                    >
                                        {{ division.description }}
                                    </p>
                                    <div
                                        class="space-y-4"
                                        v-if="
                                            division.job_description &&
                                            division.job_description.length > 0
                                        "
                                    >
                                        <h4
                                            class="text-base font-bold text-gray-800"
                                        >
                                            Tugas dan Tanggung Jawab:
                                        </h4>
                                        <div class="space-y-3">
                                            <div
                                                v-for="(
                                                    desc, index
                                                ) in division.job_description"
                                                :key="index"
                                                class="flex items-start gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300"
                                            >
                                                <div
                                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5 flex-shrink-0"
                                                >
                                                    {{ index + 1 }}
                                                </div>
                                                <p
                                                    class="text-gray-700 text-sm leading-relaxed flex-1"
                                                >
                                                    {{
                                                        desc.replace(
                                                            /^\d+\.\s*/,
                                                            ""
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Requirements Section -->
                            <div
                                class="mb-8"
                                v-if="
                                    division.requirements &&
                                    division.requirements.length > 0
                                "
                            >
                                <div class="flex items-center space-x-3 mb-6">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg"
                                    >
                                        <svg
                                            class="w-5 h-5 text-white"
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
                                    <h3
                                        class="text-xl font-bold text-gray-900"
                                        style="color: #002f6c"
                                    >
                                        Persyaratan
                                    </h3>
                                </div>
                                <div
                                    class="bg-white rounded-xl border border-gray-200 p-6"
                                >
                                    <div class="space-y-3">
                                        <div
                                            v-for="(
                                                requirement, index
                                            ) in division.requirements"
                                            :key="index"
                                            class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200"
                                        >
                                            <div
                                                class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5 flex-shrink-0"
                                            >
                                                {{ index + 1 }}
                                            </div>
                                            <p
                                                class="text-gray-700 text-sm leading-relaxed flex-1"
                                            >
                                                {{ requirement }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Schedule Section -->
                            <div class="mb-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
                                    >
                                        <svg
                                            class="w-5 h-5 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-900"
                                        style="color: #002f6c"
                                    >
                                        Jadwal Program
                                    </h3>
                                </div>
                                <div
                                    class="bg-white rounded-xl border border-gray-200 p-6"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <div
                                            class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-200"
                                        >
                                            <span
                                                class="text-gray-700 text-sm font-medium"
                                                >Mulai Program</span
                                            >
                                            <span
                                                class="font-bold text-blue-700"
                                                >{{
                                                    formatDate(
                                                        division.start_date
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-200"
                                        >
                                            <span
                                                class="text-gray-700 text-sm font-medium"
                                                >Selesai Program</span
                                            >
                                            <span
                                                class="font-bold text-green-700"
                                                >{{
                                                    formatDate(
                                                        division.end_date
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-4 bg-orange-50 rounded-lg border border-orange-200"
                                        >
                                            <span
                                                class="text-gray-700 text-sm font-medium"
                                                >Deadline Lamaran</span
                                            >
                                            <span
                                                class="font-bold text-orange-700"
                                                >{{
                                                    formatDate(
                                                        division.application_deadline
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-4 bg-purple-50 rounded-lg border border-purple-200"
                                        >
                                            <span
                                                class="text-gray-700 text-sm font-medium"
                                                >Pengumuman Seleksi</span
                                            >
                                            <span
                                                class="font-bold text-purple-700"
                                                >{{
                                                    formatDate(
                                                        division.selection_announcement
                                                    )
                                                }}</span
                                            >
                                        </div>
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
import { Head, Link, router } from "@inertiajs/vue3";
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
const activeTab = ref("description");
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

    // Check if user is logged in
    if (!props.auth.user) {
        showNotification(
            "error",
            "Login Required",
            "Silakan login terlebih dahulu untuk mendaftar magang."
        );
        // Redirect to login with return URL
        setTimeout(() => {
            router.visit("/login", {
                method: "get",
                preserveState: false,
                data: { redirect: window.location.pathname },
            });
        }, 2000);
        return;
    }

    isLoading.value = true;

    try {
        // Check user profile completeness and documents
        const response = await fetch(
            `/applications/check/${props.division.id}`,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );

        const result = await response.json();

        if (response.ok && result.canApply) {
            // All data complete, proceed with application
            const applyResponse = await fetch("/profile/create-application", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({
                    division_id: props.division.id,
                }),
            });

            const applyResult = await applyResponse.json();

            if (applyResponse.ok) {
                showNotification(
                    "success",
                    "Pendaftaran Berhasil!",
                    `Anda telah berhasil mendaftar untuk divisi ${props.division.name}. Silakan tunggu konfirmasi dari admin.`
                );

                // Refresh page to update UI
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                showNotification(
                    "error",
                    "Gagal Mendaftar",
                    applyResult.message || "Terjadi kesalahan saat mendaftar."
                );
            }
        } else {
            // Show what's missing
            const missingItems = [];

            if (
                result.missingPersonalData &&
                result.missingPersonalData.length > 0
            ) {
                missingItems.push(
                    `Data Pribadi: ${result.missingPersonalData.join(", ")}`
                );
            }

            if (result.missingDocuments && result.missingDocuments.length > 0) {
                missingItems.push(
                    `Dokumen: ${result.missingDocuments.join(", ")}`
                );
            }

            const missingMessage =
                missingItems.length > 0
                    ? `Harap lengkapi terlebih dahulu:\n\n${missingItems.join(
                          "\n\n"
                      )}`
                    : "Harap lengkapi profil Anda terlebih dahulu.";

            showNotification("error", "Data Belum Lengkap", missingMessage);

            // Redirect to profile after 3 seconds
            setTimeout(() => {
                router.visit("/profile", {
                    method: "get",
                    preserveState: false,
                });
            }, 4000);
        }
    } catch (error) {
        console.error("Application error:", error);
        showNotification(
            "error",
            "Error",
            "Terjadi kesalahan saat memproses lamaran Anda. Silakan coba lagi."
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

// Helper functions
const formatDate = (date) => {
    if (!date) return "-";

    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };

    return new Date(date).toLocaleDateString("id-ID", options);
};

const formatDuration = (startDate, endDate) => {
    if (!startDate || !endDate) return "-";

    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const diffMonths = Math.round(diffDays / 30);

    if (diffMonths >= 1) {
        return `${diffMonths} bulan`;
    } else {
        return `${diffDays} hari`;
    }
};
</script>
