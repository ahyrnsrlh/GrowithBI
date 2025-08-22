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
        <div class="min-h-screen bg-white/60 backdrop-blur-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                <!-- Back Navigation -->
                <div class="mb-4 lg:mb-6">
                    <Link
                        href="/divisions"
                        class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200"
                    >
                        <svg
                            class="w-4 h-4 mr-2"
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

                <!-- Header Section -->
                <div
                    class="glass-strong rounded-xl border border-white/30 p-6 lg:p-8 mb-6 shadow-xl"
                >
                    <div
                        class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6"
                    >
                        <div class="flex-1">
                            <h1
                                class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2"
                            >
                                {{ division.name }}
                            </h1>
                            <p class="text-gray-600 mb-4">
                                PT GrowithBI (Persero)
                            </p>

                            <!-- Location and Type Info -->
                            <div class="flex flex-wrap items-center gap-4 mb-4">
                                <div class="flex items-center text-gray-600">
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                    </svg>
                                    <span class="text-sm">Kota Bandung</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        ></path>
                                    </svg>
                                    <span class="text-sm">Onsite</span>
                                </div>
                            </div>

                            <!-- Status Badges -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                >
                                    Umum
                                </span>
                                <div
                                    class="flex items-center text-sm text-gray-600"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
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
                                    <span class="mr-1 font-medium">{{
                                        division.quota
                                    }}</span>
                                    <span class="mr-3">Posisi</span>
                                    <svg
                                        class="w-4 h-4 mr-1"
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
                                    <span class="font-medium">6</span>
                                    <span>bulan</span>
                                </div>
                                <button
                                    class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-700 transition-colors duration-200 hover:bg-blue-50 rounded-lg"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                        ></path>
                                    </svg>
                                    Bagikan Lowongan
                                </button>
                            </div>
                        </div>

                        <!-- Apply Button -->
                        <div class="lg:flex-shrink-0 lg:ml-6 mt-4 lg:mt-0">
                            <button
                                @click="handleApply"
                                :disabled="
                                    division.available_slots <= 0 || isLoading
                                "
                                class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl disabled:cursor-not-allowed disabled:shadow-none"
                            >
                                <span
                                    v-if="!isLoading"
                                    class="flex items-center justify-center"
                                >
                                    <span class="text-base">
                                        {{
                                            division.available_slots <= 0
                                                ? "Kuota Penuh"
                                                : "Daftar Sekarang"
                                        }}
                                    </span>
                                </span>
                                <span
                                    v-else
                                    class="flex items-center justify-center"
                                >
                                    <svg
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
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
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="w-full">
                    <!-- Tab Navigation -->
                    <div
                        class="glass rounded-lg border border-white/20 mb-6 shadow-lg overflow-hidden"
                    >
                        <div class="border-b border-white/20">
                            <nav class="flex" aria-label="Tabs">
                                <button
                                    @click="activeTab = 'description'"
                                    :class="
                                        activeTab === 'description'
                                            ? 'border-blue-500 text-blue-600 bg-blue-50/50 backdrop-blur-sm'
                                            : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-white/30'
                                    "
                                    class="flex-1 py-3 px-6 border-b-2 font-medium text-sm lg:text-base transition-all duration-300 text-center"
                                >
                                    Deskripsi Lowongan
                                </button>
                                <button
                                    @click="activeTab = 'requirements'"
                                    :class="
                                        activeTab === 'requirements'
                                            ? 'border-blue-500 text-blue-600 bg-blue-50/50 backdrop-blur-sm'
                                            : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-white/30'
                                    "
                                    class="flex-1 py-3 px-6 border-b-2 font-medium text-sm lg:text-base transition-all duration-300 text-center"
                                >
                                    Persyaratan
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div
                        class="glass-strong rounded-lg border border-white/30 p-6 lg:p-8 shadow-lg"
                    >
                        <!-- Description Tab -->
                        <div
                            v-if="activeTab === 'description'"
                            class="animate-fadeIn"
                        >
                            <!-- Education Section -->
                            <div class="mb-6">
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-3 flex items-center"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                        ></path>
                                    </svg>
                                    Pendidikan
                                </h3>
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-700"
                                >
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <strong class="text-gray-900"
                                            >Jenjang pendidikan:</strong
                                        ><br />
                                        S1, D3, D4
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <strong class="text-gray-900"
                                            >Jurusan:</strong
                                        ><br />
                                        Farmasi, Farmasi Industri, Teknik Kimia,
                                        Kimia
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <strong class="text-gray-900"
                                            >IPK minimal:</strong
                                        ><br />
                                        2.0
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div class="mb-6">
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-3 flex items-center"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2 text-blue-600"
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
                                    Persyaratan Dokumen
                                </h3>
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"
                                >
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            CV
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            KTP
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            Transkrip Nilai
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 p-3 rounded-lg col-span-full"
                                    >
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            Ijazah/Surat Keterangan Lulus (bagi
                                            Fresh Graduate)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline Section -->
                            <div class="mb-6">
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-3 flex items-center"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    Tanggal Penting
                                </h3>
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-3 gap-3"
                                >
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            Durasi
                                        </div>
                                        <div class="text-gray-600 text-sm">
                                            6 bulan
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            Penutupan lamaran
                                        </div>
                                        <div class="text-gray-600 text-sm">
                                            {{
                                                division.application_deadline ||
                                                "19 Agustus 2025"
                                            }}
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div
                                            class="font-medium text-gray-900 text-sm"
                                        >
                                            Pengumuman lolos seleksi
                                        </div>
                                        <div class="text-gray-600 text-sm">
                                            {{
                                                division.selection_announcement ||
                                                "14 September 2025"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements Tab -->
                        <!-- Requirements Tab -->
                        <div
                            v-else-if="activeTab === 'requirements'"
                            class="animate-fadeIn"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Rincian Lowongan
                            </h3>

                            <div class="mb-6">
                                <h4
                                    class="font-medium text-gray-900 mb-3 text-base"
                                >
                                    Deskripsi Kerja :
                                </h4>
                                <div class="space-y-2">
                                    <div
                                        v-for="(
                                            desc, index
                                        ) in division.job_description"
                                        :key="desc"
                                        class="flex items-start"
                                    >
                                        <span
                                            class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-1"
                                        >
                                            {{ index + 1 }}
                                        </span>
                                        <p
                                            class="text-gray-700 leading-relaxed text-sm"
                                        >
                                            {{ desc.replace(/^\d+\.\s*/, "") }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4
                                    class="font-medium text-gray-900 mb-3 text-base"
                                >
                                    Persyaratan :
                                </h4>
                                <div class="space-y-2">
                                    <div
                                        v-for="(
                                            requirement, index
                                        ) in division.requirements"
                                        :key="requirement"
                                        class="flex items-start"
                                    >
                                        <span
                                            class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-1"
                                        >
                                            {{ index + 1 }}
                                        </span>
                                        <p
                                            class="text-gray-700 leading-relaxed text-sm"
                                        >
                                            {{ requirement }}
                                        </p>
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
const activeTab = ref("description");
const isLoading = ref(false);
const notification = ref({
    show: false,
    type: "success", // 'success' or 'error'
    title: "",
    message: "",
});

// Methods
const handleApply = async () => {
    console.log('=== HANDLE APPLY START ===');
    console.log('Division:', props.division);
    console.log('Auth user:', props.auth?.user);
    
    if (props.division.available_slots <= 0) {
        showNotification(
            "error",
            "Maaf",
            "Kuota untuk divisi ini sudah penuh."
        );
        return;
    }

    // Check if user is authenticated
    if (!props.auth?.user) {
        console.log('User not authenticated, showing login option');
        showNotification(
            "error",
            "Login Diperlukan",
            "Silakan login terlebih dahulu untuk mendaftar magang."
        );
        
        // Redirect to login after 2 seconds
        setTimeout(() => {
            window.location.href = `/login`;
        }, 2000);
        return;
    }

    console.log('User authenticated, starting validation process');
    isLoading.value = true;

    try {
        // Check if user already has application for this division
        console.log('Fetching user data...');
        const checkResponse = await window.axios.get('/api/user');
        const user = checkResponse.data;
        console.log('User data:', user);
        
        // Check if user has existing application for this division
        console.log('Checking existing application...');
        const existingApplicationResponse = await window.axios.get(`/api/applications/check/${props.division.id}`);
        console.log('Existing application check:', existingApplicationResponse.data);
        
        if (existingApplicationResponse.data.hasApplication) {
            showNotification(
                "error",
                "Lamaran Sudah Ada",
                "Anda sudah memiliki lamaran aktif untuk divisi ini."
            );
            isLoading.value = false;
            return;
        }

        // Validate required documents
        console.log('Validating documents...');
        const requiredDocuments = [
            { field: 'surat_pengantar_path', name: 'Surat Pengantar' },
            { field: 'cv_path', name: 'Curriculum Vitae (CV)' },
            { field: 'motivation_letter_path', name: 'Motivation Letter' },
            { field: 'transkrip_path', name: 'Transkrip Nilai' },
            { field: 'ktp_path', name: 'KTP' },
            { field: 'buku_rekening_path', name: 'Buku Rekening Tabungan' },
            { field: 'pas_foto_path', name: 'Pas Foto 3x4 atau 4x6' }
        ];

        const missingDocuments = requiredDocuments.filter(doc => !user[doc.field]);
        console.log('Missing documents:', missingDocuments);
        
        if (missingDocuments.length > 0) {
            const missingNames = missingDocuments.map(doc => doc.name).join(', ');
            showNotification(
                "error",
                "Dokumen Belum Lengkap",
                `Silakan lengkapi dokumen berikut terlebih dahulu: ${missingNames}`
            );
            isLoading.value = false;
            
            // Show popup for 5 seconds before allowing any action
            console.log('Showing document validation error for 5 seconds...');
            setTimeout(() => {
                console.log('Document error timeout completed');
            }, 5000);
            
            return;
        }

        // Validate profile completion
        console.log('Validating profile...');
        const requiredProfile = [
            { field: 'name', name: 'Nama Lengkap' },
            { field: 'phone', name: 'Nomor Telepon' },
            { field: 'address', name: 'Alamat' },
            { field: 'university', name: 'Universitas' },
            { field: 'major', name: 'Jurusan' }
        ];

        const missingProfile = requiredProfile.filter(field => !user[field.field]);
        console.log('Missing profile:', missingProfile);
        
        if (missingProfile.length > 0) {
            const missingProfileNames = missingProfile.map(field => field.name).join(', ');
            showNotification(
                "error",
                "Profil Belum Lengkap",
                `Silakan lengkapi data profil berikut: ${missingProfileNames}. Anda akan diarahkan ke halaman profil.`
            );
            setTimeout(() => {
                window.location.href = `/profile?division_id=${props.division.id}`;
            }, 3000);
            isLoading.value = false;
            return;
        }

        // All requirements met, submit application automatically
        console.log('All requirements met, submitting application...');
        const applicationResponse = await window.axios.post('/profile/create-application', {
            division_id: props.division.id,
            motivation: `Saya tertarik bergabung dengan program magang di divisi ${props.division.name}. Saya berkomitmen untuk memberikan kontribusi terbaik dan belajar secara maksimal selama program magang berlangsung.`
        });

        console.log('Application submitted successfully:', applicationResponse.data);
        showNotification(
            "success",
            "Lamaran Berhasil Dikirim!",
            `Selamat! Lamaran Anda untuk posisi magang di divisi ${props.division.name} telah berhasil dikirim. Tim HRD akan menghubungi Anda segera melalui email atau telepon.`
        );

        // Redirect to profile after 3 seconds to give user time to read success message
        setTimeout(() => {
            window.location.href = '/profile';
        }, 3000);

    } catch (error) {
        console.error('Application error:', error);
        
        if (error.response?.status === 422) {
            // Validation error
            const errorMsg = error.response.data.message || 'Ada kesalahan validasi data.';
            showNotification("error", "Gagal Mengirim Lamaran", errorMsg);
        } else if (error.response?.status === 401) {
            // Not authenticated
            console.log('Authentication error, redirecting to login');
            window.location.href = `/login?redirect=/divisi/${props.division.id}`;
        } else {
            showNotification(
                "error",
                "Error",
                "Terjadi kesalahan saat memproses lamaran Anda. Silakan coba lagi."
            );
        }
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
