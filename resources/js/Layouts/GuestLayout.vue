<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

// Props untuk kontrol layout
defineProps({
    variant: {
        type: String,
        default: "auth", // 'auth' untuk login/register, 'public' untuk halaman publik
    },
    container: {
        type: Boolean,
        default: false, // true untuk membungkus dengan container max-width
    },
});

// Modal state
const showModal = ref(false);
const modalType = ref("success"); // 'success' or 'error'
const modalTitle = ref("");
const modalMessage = ref("");
const missingData = ref([]);

// Smart registration function
const handleSmartRegistration = async (event) => {
    // Prevent any default behavior
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    console.log("Smart registration button clicked");

    try {
        // Check if user is authenticated using web route
        const authResponse = await axios.get("/auth/user");
        const user = authResponse.data;

        if (!user) {
            // User not authenticated, redirect to login
            console.log("User not authenticated, redirecting to login");
            router.visit("/login");
            return;
        }

        console.log("User authenticated, checking application status");

        // For now, we'll use the first available division (Web Development)
        const divisionId = 1;

        // Check profile completeness and existing application using web route
        const response = await axios.get(`/applications/check/${divisionId}`);

        console.log("API Response:", response.data);

        // Check if profile is complete using the new API response format
        if (response.data.canApply) {
            console.log("Profile is complete, submitting application");
            // Profile complete - auto submit application
            try {
                const submitResponse = await axios.post(
                    "/profile/create-application",
                    {
                        division_id: divisionId,
                    }
                );

                console.log("Application submitted successfully");

                modalType.value = "success";
                modalTitle.value = "Pendaftaran Berhasil!";
                modalMessage.value =
                    "Selamat! Aplikasi Anda telah berhasil disubmit. Tim kami akan meninjau aplikasi Anda dan memberikan update melalui email.";
                showModal.value = true;

                // Redirect to profile page after 3 seconds
                setTimeout(() => {
                    router.visit("/profile");
                }, 3000);
            } catch (error) {
                console.error("Error submitting application:", error);
                modalType.value = "error";
                modalTitle.value = "Pendaftaran Gagal";
                modalMessage.value =
                    "Terjadi kesalahan saat memproses aplikasi Anda. Silakan coba lagi.";
                showModal.value = true;
            }
        } else {
            console.log("Cannot apply, showing reason");
            // Cannot apply - either existing application or incomplete profile
            modalType.value = "error";
            modalTitle.value = "Pendaftaran Gagal";

            // If there are missing data fields, it's incomplete profile
            if (
                response.data.missingPersonalData?.length > 0 ||
                response.data.missingDocuments?.length > 0
            ) {
                let message =
                    "Data Anda belum lengkap. Silakan lengkapi data berikut:\n\n";

                if (
                    response.data.missingPersonalData &&
                    response.data.missingPersonalData.length > 0
                ) {
                    message += "📋 Data Pribadi:\n";
                    response.data.missingPersonalData.forEach((item) => {
                        message += `• ${item}\n`;
                    });
                    message += "\n";
                }

                if (
                    response.data.missingDocuments &&
                    response.data.missingDocuments.length > 0
                ) {
                    message += "📄 Dokumen:\n";
                    response.data.missingDocuments.forEach((item) => {
                        message += `• ${item}\n`;
                    });
                }

                message += "\nSilakan lengkapi data Anda di halaman profil.";
                modalMessage.value = message;

                // Set missing data for modal button condition
                missingData.value = {
                    personalData: response.data.missingPersonalData || [],
                    documents: response.data.missingDocuments || [],
                };
            } else {
                // User already has existing application
                modalMessage.value =
                    response.data.message ||
                    "Anda sudah memiliki aplikasi yang sedang diproses.";
                missingData.value = { personalData: [], documents: [] };
            }

            showModal.value = true;
        }
    } catch (error) {
        console.error("Smart registration error:", error);
        if (error.response?.status === 401) {
            // User not authenticated
            console.log("401 error, redirecting to login");
            router.visit("/login");
        } else {
            modalType.value = "error";
            modalTitle.value = "Pendaftaran Gagal";
            modalMessage.value = `Terjadi kesalahan: ${
                error.response?.data?.message || error.message
            }. Silakan coba lagi.`;
            showModal.value = true;
        }
    }
};

const closeModal = () => {
    showModal.value = false;
};

const goToProfile = () => {
    closeModal();
    router.visit("/profile");
};
</script>

<template>
    <div>
        <!-- Layout untuk halaman auth (login/register) -->
        <template v-if="variant === 'auth'">
            <div
                class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
            >
                <div class="mb-6">
                    <Link href="/">
                        <ApplicationLogo
                            class="h-16 w-16 sm:h-20 sm:w-20 fill-current text-gray-500"
                        />
                    </Link>
                </div>

                <div
                    class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
                >
                    <slot />
                </div>
            </div>
        </template>

        <!-- Layout untuk halaman publik (full-width responsive) -->
        <template v-else>
            <div class="min-h-screen bg-white relative overflow-hidden">
                <!-- Grid Background Pattern -->
                <div class="absolute inset-0 opacity-30">
                    <div
                        class="absolute inset-0"
                        style="
                            background-image: linear-gradient(
                                    rgba(59, 130, 246, 0.1) 1px,
                                    transparent 1px
                                ),
                                linear-gradient(
                                    90deg,
                                    rgba(59, 130, 246, 0.1) 1px,
                                    transparent 1px
                                );
                            background-size: 50px 50px;
                        "
                    ></div>
                </div>

                <!-- Header Navigation -->
                <header
                    class="relative z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200"
                >
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center py-4">
                            <!-- Logo Section -->
                            <div class="flex items-center">
                                <Link href="/" class="flex items-center">
                                    <ApplicationLogo
                                        class="h-8 w-8 sm:h-10 sm:w-10 fill-current text-blue-600"
                                    />
                                    <span
                                        class="ml-3 text-xl sm:text-2xl font-bold text-gray-900"
                                    >
                                        GrowithBI
                                    </span>
                                </Link>
                            </div>

                            <!-- Navigation Menu -->
                            <nav class="hidden md:flex items-center space-x-8">
                                <Link
                                    href="/divisions"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Kriteria
                                </Link>
                                <Link
                                    href="/about"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Benefit
                                </Link>
                                <Link
                                    href="/requirements"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Persyaratan
                                </Link>
                                <Link
                                    href="/faq"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    FAQ
                                </Link>
                            </nav>

                            <!-- CTA Button -->
                            <div class="flex items-center">
                                <button
                                    type="button"
                                    @click.prevent="handleSmartRegistration"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                >
                                    Daftar Sekarang
                                </button>
                            </div>

                            <!-- Mobile Menu Button -->
                            <div class="md:hidden">
                                <button
                                    class="text-gray-700 hover:text-blue-600 focus:outline-none"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main content area -->
                <main class="relative z-10">
                    <template v-if="container">
                        <!-- Dengan container max-width -->
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <slot />
                        </div>
                    </template>
                    <template v-else>
                        <!-- Full width tanpa container -->
                        <slot />
                    </template>
                </main>

                <!-- Smart Registration Modal -->
                <div
                    v-if="showModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                >
                    <div
                        class="bg-white rounded-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-lg font-semibold"
                                :class="{
                                    'text-green-600': modalType === 'success',
                                    'text-red-600': modalType === 'error',
                                }"
                            >
                                <span
                                    v-if="modalType === 'success'"
                                    class="inline-flex items-center"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    {{ modalTitle }}
                                </span>
                                <span v-else class="inline-flex items-center">
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    {{ modalTitle }}
                                </span>
                            </h3>
                            <button
                                @click="closeModal"
                                class="text-gray-400 hover:text-gray-600"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div class="mb-6">
                            <p
                                class="whitespace-pre-line text-gray-700 leading-relaxed"
                            >
                                {{ modalMessage }}
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                @click="closeModal"
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Tutup
                            </button>
                            <button
                                v-if="
                                    modalType === 'error' &&
                                    (missingData.personalData?.length > 0 ||
                                        missingData.documents?.length > 0)
                                "
                                @click="goToProfile"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Lengkapi Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
