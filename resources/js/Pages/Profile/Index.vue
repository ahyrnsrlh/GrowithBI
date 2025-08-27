<template>
    <Head title="Profil Saya" />

    <AuthenticatedLayout>
        <!-- Toast Notifications -->
        <div v-if="showNotification" class="fixed top-4 right-4 z-50">
            <div
                :class="[
                    'p-4 rounded-lg shadow-lg border transition-all duration-300',
                    notificationType === 'success'
                        ? 'bg-green-50 border-green-200 text-green-800'
                        : 'bg-red-50 border-red-200 text-red-800',
                ]"
            >
                <div class="flex items-center">
                    <svg
                        v-if="notificationType === 'success'"
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
                    <svg
                        v-else
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
                    {{ notificationMessage }}
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-50">
            <!-- Header Section -->
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Profil Saya
                            </h1>
                            <p class="text-gray-600">
                                Kelola informasi profil dan lamaran magang Anda
                            </p>
                        </div>
                        <!-- Progress Badge -->
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">
                                    Kelengkapan Profil
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div
                                        class="w-24 bg-gray-200 rounded-full h-2"
                                    >
                                        <div
                                            :class="[
                                                'h-2 rounded-full transition-all duration-300',
                                                profileCompletion.percentage >=
                                                80
                                                    ? 'bg-green-500'
                                                    : profileCompletion.percentage >=
                                                      50
                                                    ? 'bg-yellow-500'
                                                    : 'bg-red-500',
                                            ]"
                                            :style="`width: ${profileCompletion.percentage}%`"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium"
                                        >{{
                                            profileCompletion.percentage
                                        }}%</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <nav
                            class="bg-blue-600 rounded-lg shadow-sm border border-blue-600 p-6"
                        >
                            <div class="space-y-2">
                                <button
                                    @click="activeTab = 'profile'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'profile'
                                            ? 'bg-blue-800 text-white border border-blue-800'
                                            : 'text-white hover:bg-blue-700',
                                    ]"
                                >
                                    <i class="fas fa-user mr-3"></i>
                                    Informasi Pribadi
                                </button>
                                <button
                                    @click="activeTab = 'documents'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'documents'
                                            ? 'bg-blue-800 text-white border border-blue-800'
                                            : 'text-white hover:bg-blue-700',
                                    ]"
                                >
                                    <i class="fas fa-file-alt mr-3"></i>
                                    Dokumen Persyaratan
                                </button>
                                <button
                                    @click="activeTab = 'applications'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'applications'
                                            ? 'bg-blue-800 text-white border border-blue-800'
                                            : 'text-white hover:bg-blue-700',
                                    ]"
                                >
                                    <i class="fas fa-briefcase mr-3"></i>
                                    Status Lamaran
                                    <span
                                        v-if="applications.length"
                                        class="ml-2 bg-white text-blue-600 text-xs px-2 py-1 rounded-full"
                                    >
                                        {{ applications.length }}
                                    </span>
                                </button>
                                <a
                                    :href="route('home') + '#divisions'"
                                    class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-white hover:bg-blue-700 block"
                                >
                                    <i
                                        class="fas fa-external-link-alt mr-3"
                                    ></i>
                                    Daftar Magang
                                </a>
                            </div>

                            <!-- Quick Links -->
                            <div class="mt-8 pt-6 border-t border-blue-500">
                                <h3 class="text-sm font-medium text-white mb-3">
                                    Navigasi Cepat
                                </h3>
                                <div class="space-y-2">
                                    <Link
                                        href="/"
                                        class="block text-sm text-white hover:text-blue-200"
                                    >
                                        <i class="fas fa-home mr-2"></i>
                                        Beranda
                                    </Link>
                                    <Link
                                        href="/divisi"
                                        class="block text-sm text-white hover:text-blue-200"
                                    >
                                        <i class="fas fa-building mr-2"></i>
                                        Lowongan Magang
                                    </Link>
                                    <Link
                                        href="/cek-status"
                                        class="block text-sm text-white hover:text-blue-200"
                                    >
                                        <i class="fas fa-search mr-2"></i>
                                        Cek Status
                                    </Link>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <!-- Informasi Pribadi Tab -->
                        <div
                            v-if="activeTab === 'profile'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">
                                    Informasi Pribadi
                                </h2>
                                <button
                                    @click="editMode = !editMode"
                                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                >
                                    {{ editMode ? "Batal" : "Edit Profil" }}
                                </button>
                            </div>

                            <form @submit.prevent="updateProfile">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- Profile Photo -->
                                    <div
                                        class="md:col-span-2 flex items-center space-x-6"
                                    >
                                        <div class="relative">
                                            <img
                                                :src="
                                                    user.profile_photo_path
                                                        ? `/storage/${user.profile_photo_path}`
                                                        : '/images/default-avatar.png'
                                                "
                                                alt="Profile"
                                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                                            />
                                            <label
                                                v-if="editMode"
                                                class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 transition-colors"
                                            >
                                                <i
                                                    class="fas fa-camera text-xs"
                                                ></i>
                                                <input
                                                    type="file"
                                                    @change="uploadPhoto"
                                                    accept="image/*"
                                                    class="hidden"
                                                />
                                            </label>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                            </h3>
                                            <p class="text-gray-500">
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Nama Lengkap</label
                                        >
                                        <input
                                            v-model="profileForm.name"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Masukkan nama lengkap"
                                        />
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Email</label
                                        >
                                        <input
                                            v-model="profileForm.email"
                                            :disabled="!editMode"
                                            type="email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="email@example.com"
                                        />
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Nomor Telepon</label
                                        >
                                        <input
                                            v-model="profileForm.phone"
                                            :disabled="!editMode"
                                            type="tel"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="08xxxxxxxxxx"
                                        />
                                    </div>

                                    <!-- University -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Universitas</label
                                        >
                                        <input
                                            v-model="profileForm.university"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Nama universitas"
                                        />
                                    </div>

                                    <!-- Major -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Jurusan</label
                                        >
                                        <input
                                            v-model="profileForm.major"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Jurusan/Program studi"
                                        />
                                    </div>

                                    <!-- Semester -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Semester</label
                                        >
                                        <select
                                            v-model="profileForm.semester"
                                            :disabled="!editMode"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                        >
                                            <option value="">
                                                Pilih semester
                                            </option>
                                            <option
                                                v-for="i in 14"
                                                :key="i"
                                                :value="i"
                                            >
                                                Semester {{ i }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Alamat Lengkap</label
                                        >
                                        <textarea
                                            v-model="profileForm.address"
                                            :disabled="!editMode"
                                            rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Alamat lengkap sesuai KTP"
                                        ></textarea>
                                    </div>
                                </div>

                                <div
                                    v-if="editMode"
                                    class="mt-6 flex justify-end space-x-4"
                                >
                                    <button
                                        type="button"
                                        @click="editMode = false"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="profileForm.processing"
                                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                                    >
                                        {{
                                            profileForm.processing
                                                ? "Menyimpan..."
                                                : "Simpan Perubahan"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Dokumen Persyaratan Tab -->
                        <div
                            v-if="activeTab === 'documents'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                        >
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-6"
                            >
                                Dokumen Persyaratan
                            </h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Surat Pengantar -->
                                <DocumentUpload
                                    title="Surat Pengantar"
                                    type="surat_pengantar"
                                    :current-file="user.surat_pengantar_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- CV -->
                                <DocumentUpload
                                    title="Curriculum Vitae (CV)"
                                    type="cv"
                                    :current-file="user.cv_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Motivation Letter -->
                                <DocumentUpload
                                    title="Motivation Letter"
                                    type="motivation_letter"
                                    :current-file="user.motivation_letter_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Transkrip -->
                                <DocumentUpload
                                    title="Transkrip Nilai"
                                    type="transkrip"
                                    :current-file="user.transkrip_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- KTP -->
                                <DocumentUpload
                                    title="KTP"
                                    type="ktp"
                                    :current-file="user.ktp_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- NPWP -->
                                <DocumentUpload
                                    title="NPWP"
                                    type="npwp"
                                    :current-file="user.npwp_path"
                                    @upload="uploadDocument"
                                />

                                <!-- Buku Rekening -->
                                <DocumentUpload
                                    title="Buku Rekening Tabungan"
                                    type="buku_rekening"
                                    :current-file="user.buku_rekening_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Pas Foto -->
                                <DocumentUpload
                                    title="Pas Foto 3x4 atau 4x6"
                                    type="pas_foto"
                                    :current-file="user.pas_foto_path"
                                    @upload="uploadDocument"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Status Lamaran Tab -->
                        <div
                            v-if="activeTab === 'applications'"
                            class="space-y-6"
                        >
                            <div
                                v-if="applications.length === 0"
                                class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center"
                            >
                                <div
                                    class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
                                >
                                    <i
                                        class="fas fa-briefcase text-2xl text-gray-400"
                                    ></i>
                                </div>
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-2"
                                >
                                    Belum Ada Lamaran
                                </h3>
                                <p class="text-gray-500 mb-6">
                                    Anda belum memiliki lamaran magang. Mulai
                                    lamar sekarang!
                                </p>
                                <a
                                    :href="route('home') + '#divisions'"
                                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Daftar Magang Sekarang
                                </a>
                            </div>

                            <div v-else class="space-y-4">
                                <ApplicationCard
                                    v-for="application in applications"
                                    :key="application.id"
                                    :application="application"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DocumentUpload from "@/Components/DocumentUpload.vue";
import ApplicationCard from "@/Components/ApplicationCard.vue";

const props = defineProps({
    user: Object,
    applications: Array,
    divisions: Array,
    profileCompletion: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

// Reactive user data to handle updates
const user = reactive({ ...props.user });

// State
const activeTab = ref("profile");
const editMode = ref(false);
const showNotification = ref(false);
const notificationType = ref("success");
const notificationMessage = ref("");

// Forms
const profileForm = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone,
    address: user.address,
    university: user.university,
    major: user.major,
    semester: user.semester,
});

// Methods
const showToast = (type, message) => {
    notificationType.value = type;
    notificationMessage.value = message;
    showNotification.value = true;
    setTimeout(() => {
        showNotification.value = false;
    }, 5000);
};

const updateProfile = () => {
    profileForm.patch(route("profile.update"), {
        onSuccess: (page) => {
            editMode.value = false;
            // Update reactive user data with the latest data from server
            Object.assign(user, page.props.user);
            // Update form data as well
            profileForm.data = {
                name: user.name,
                email: user.email,
                phone: user.phone,
                address: user.address,
                university: user.university,
                major: user.major,
                semester: user.semester,
            };
            showToast("success", "Profil berhasil diperbarui!");
        },
        onError: (errors) => {
            console.error("Profile update errors:", errors);
            showToast("error", "Gagal memperbarui profil!");
        },
    });
};

const uploadPhoto = (event) => {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append("photo", file);

        window.axios
            .post(route("profile.upload-photo"), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then(() => {
                window.location.reload();
            })
            .catch(() => {
                showToast("error", "Gagal mengunggah foto!");
            });
    }
};

const uploadDocument = (type, file) => {
    const formData = new FormData();
    formData.append("document_type", type);
    formData.append("document", file);

    window.axios
        .post(route("profile.upload-document"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Accept: "application/json",
            },
        })
        .then((response) => {
            // Update reactive user data in-place without page reload
            if (response.data.user) {
                Object.assign(user, response.data.user);
            }
            showToast(
                "success",
                response.data.message || "Dokumen berhasil diunggah!"
            );
        })
        .catch((error) => {
            const message =
                error.response?.data?.message || "Gagal mengunggah dokumen!";
            showToast("error", message);
        });
};

// Helper function to get status text
const getStatusText = (status) => {
    const statusMap = {
        menunggu: "Menunggu Review",
        dalam_review: "Sedang Direview",
        wawancara: "Tahap Wawancara",
        diterima: "Diterima",
        ditolak: "Ditolak",
    };
    return statusMap[status] || status;
};

// Check for flash messages and handle navigation
onMounted(() => {
    if (props.status) {
        showToast("success", props.status);
    }

    // Check for missing fields from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const missingFields = urlParams.get("missing_fields");
    if (missingFields) {
        const fieldNames = missingFields.split(",");
        const fieldLabels = {
            phone: "Nomor Telepon",
            address: "Alamat",
            university: "Universitas",
            major: "Jurusan",
            semester: "Semester",
        };
        const missingLabels = fieldNames
            .map((field) => fieldLabels[field] || field)
            .join(", ");
        showToast("error", `Harap lengkapi: ${missingLabels}`);

        // Auto switch to profile tab and enable edit mode
        activeTab.value = "profile";
        editMode.value = true;
    }

    // Check if user came from successful application submission
    if (
        urlParams.get("from") === "application" ||
        urlParams.get("success") === "application"
    ) {
        // Switch to applications tab automatically
        activeTab.value = "applications";
        showToast(
            "success",
            "Selamat! Lamaran Anda telah berhasil dikirim. Anda dapat memantau statusnya di sini."
        );
    }

    // Check if there are new applications to highlight
    if (props.applications && props.applications.length > 0) {
        const latestApplication = props.applications[0];
        const applicationTime = new Date(latestApplication.created_at);
        const currentTime = new Date();
        const timeDiff = (currentTime - applicationTime) / (1000 * 60); // minutes

        // If application was submitted in the last 5 minutes, highlight it
        if (timeDiff < 5) {
            setTimeout(() => {
                activeTab.value = "applications";
                showToast(
                    "info",
                    `Status lamaran terbaru: ${getStatusText(
                        latestApplication.status
                    )}`
                );
            }, 1000);
        }
    }
});
</script>
