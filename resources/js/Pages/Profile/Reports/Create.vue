<template>
    <AuthenticatedLayout>
        <Head title="Upload Laporan" />

        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Upload Laporan Akhir
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Upload file laporan akhir magang Anda dalam format Word,
                        Excel, atau PDF
                    </p>
                </div>
                <Link
                    :href="route('profile.reports.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
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
                        />
                    </svg>
                    Kembali
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Informasi Laporan
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Isi detail laporan dan upload file Anda
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.title }"
                            placeholder="Contoh: Laporan Akhir Magang Divisi IT"
                            required
                        />
                        <p
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                            <span class="text-gray-500">(Opsional)</span>
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{
                                'border-red-500': form.errors.description,
                            }"
                            placeholder="Deskripsi singkat tentang isi laporan..."
                        ></textarea>
                        <p
                            v-if="form.errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            File Laporan <span class="text-red-500">*</span>
                        </label>
                        <div
                            @drop.prevent="handleDrop"
                            @dragover.prevent
                            @dragenter.prevent
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors"
                            :class="{
                                'border-red-500': form.errors.report_file,
                                'border-blue-400 bg-blue-50': isDragging,
                            }"
                        >
                            <div v-if="!selectedFile">
                                <svg
                                    class="w-12 h-12 text-gray-400 mx-auto mb-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    />
                                </svg>
                                <p
                                    class="text-lg font-medium text-gray-900 mb-2"
                                >
                                    Upload file laporan
                                </p>
                                <p class="text-sm text-gray-500 mb-4">
                                    Drag dan drop file atau klik untuk browse
                                </p>
                                <button
                                    type="button"
                                    @click="$refs.fileInput.click()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
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
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                    Pilih File
                                </button>
                            </div>
                            <div v-else class="space-y-4">
                                <div
                                    class="flex items-center justify-center space-x-3"
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6 text-blue-600"
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
                                    <div class="text-left">
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ selectedFile.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{
                                                formatFileSize(
                                                    selectedFile.size
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="removeFile"
                                    class="text-sm text-red-600 hover:text-red-700 font-medium"
                                >
                                    Hapus File
                                </button>
                            </div>
                        </div>
                        <input
                            ref="fileInput"
                            type="file"
                            @change="handleFileSelect"
                            accept=".pdf,.doc,.docx,.xls,.xlsx"
                            class="hidden"
                        />
                        <p class="mt-2 text-xs text-gray-500">
                            Format yang didukung: PDF, Word (.doc, .docx), Excel
                            (.xls, .xlsx). Maksimal 10MB.
                        </p>
                        <p
                            v-if="form.errors.report_file"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.report_file }}
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div
                        class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <Link
                            :href="route('profile.reports.index')"
                            class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="
                                uploading ||
                                form.processing ||
                                !selectedFile ||
                                !form.title
                            "
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center"
                        >
                            <svg
                                v-if="uploading || form.processing"
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                            {{
                                uploading || form.processing
                                    ? "Mengupload..."
                                    : "Upload Laporan"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    application: Object,
    division: Object,
});

const selectedFile = ref(null);
const isDragging = ref(false);
const uploading = ref(false);

const form = useForm({
    title: "",
    description: "",
    report_file: null,
});

// Refresh CSRF token on mount to ensure it's fresh
onMounted(() => {
    // Get fresh CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        window.axios.defaults.headers.common["X-CSRF-TOKEN"] =
            csrfToken.getAttribute("content");
    }
});

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];
        if (!allowedTypes.includes(file.type)) {
            alert(
                "File type tidak didukung. Silakan pilih file PDF, Word (.doc/.docx), atau Excel (.xls/.xlsx)"
            );
            event.target.value = "";
            return;
        }

        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            alert("Ukuran file terlalu besar. Maksimal 10MB.");
            event.target.value = "";
            return;
        }

        selectedFile.value = file;
        form.report_file = file;
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    const file = event.dataTransfer.files[0];
    if (file) {
        // Validate file type
        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];
        if (!allowedTypes.includes(file.type)) {
            alert(
                "File type tidak didukung. Silakan pilih file PDF, Word (.doc/.docx), atau Excel (.xls/.xlsx)"
            );
            return;
        }

        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            alert("Ukuran file terlalu besar. Maksimal 10MB.");
            return;
        }

        selectedFile.value = file;
        form.report_file = file;
    }
};

const removeFile = () => {
    selectedFile.value = null;
    form.report_file = null;
    // Reset the file input
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = "";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const submit = () => {
    if (uploading.value) return;

    uploading.value = true;

    form.post(route("profile.reports.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            uploading.value = false;
            // Redirect handled by controller
        },
        onError: (errors) => {
            uploading.value = false;
            console.error("Upload error:", errors);

            // Handle CSRF token mismatch (419 error)
            if (
                errors.message &&
                (errors.message.includes("419") ||
                    errors.message.includes("CSRF"))
            ) {
                alert(
                    "Session telah expired. Halaman akan di-refresh untuk memperbarui session."
                );
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }

            // Handle other errors
            if (errors.report_file) {
                alert("Error file upload: " + errors.report_file);
            } else if (errors.message) {
                alert("Error: " + errors.message);
            } else {
                alert("Terjadi kesalahan saat upload. Silakan coba lagi.");
            }
        },
        onBefore: () => {
            // Validate before submit
            if (!form.title) {
                alert("Judul laporan harus diisi");
                return false;
            }
            if (!form.report_file) {
                alert("File laporan harus dipilih");
                return false;
            }
            // Reset any previous errors
            form.clearErrors();
            return true;
        },
        onFinish: () => {
            uploading.value = false;
        },
    });
};
</script>
