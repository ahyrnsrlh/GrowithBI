<template>
    <PesertaLayout
        title="Tambah Logbook"
        subtitle="Buat catatan aktivitas harian magang baru"
    >
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Tambah Logbook Harian
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Catat aktivitas dan pencapaian hari ini
                    </p>
                </div>
                <Link
                    :href="route('peserta.logbooks.index')"
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
                        Informasi Logbook
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Isi informasi lengkap tentang aktivitas harian Anda
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Date and Title Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.date"
                                type="date"
                                :max="today"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-300': form.errors.date }"
                                required
                            />
                            <p
                                v-if="form.errors.date"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.date }}
                            </p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Durasi (jam) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.duration"
                                type="number"
                                min="0"
                                max="24"
                                step="0.5"
                                placeholder="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{
                                    'border-red-300': form.errors.duration,
                                }"
                                required
                            />
                            <p
                                v-if="form.errors.duration"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.duration }}
                            </p>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Judul Aktivitas <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Contoh: Pengembangan Fitur Login System"
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            :class="{ 'border-red-300': form.errors.title }"
                            required
                        />
                        <p
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Activities -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi Aktivitas
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.activities"
                            rows="6"
                            placeholder="Jelaskan secara detail aktivitas yang telah dilakukan hari ini, termasuk tugas yang diselesaikan, kendala yang dihadapi, dan hasil yang dicapai..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{
                                'border-red-300': form.errors.activities,
                            }"
                            required
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p
                                v-if="form.errors.activities"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.activities }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ form.activities.length }} karakter
                            </p>
                        </div>
                    </div>

                    <!-- Learning Points -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Pembelajaran & Refleksi
                        </label>
                        <textarea
                            v-model="form.learning_points"
                            rows="4"
                            placeholder="Apa yang dipelajari hari ini? Skill baru, pengetahuan, atau insight yang didapat..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{
                                'border-red-300': form.errors.learning_points,
                            }"
                        ></textarea>
                        <p
                            v-if="form.errors.learning_points"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.learning_points }}
                        </p>
                    </div>

                    <!-- Challenges -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Kendala & Solusi
                        </label>
                        <textarea
                            v-model="form.challenges"
                            rows="4"
                            placeholder="Kendala yang dihadapi dan bagaimana cara mengatasinya..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{
                                'border-red-300': form.errors.challenges,
                            }"
                        ></textarea>
                        <p
                            v-if="form.errors.challenges"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.challenges }}
                        </p>
                    </div>

                    <!-- File Attachments -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Lampiran (Opsional)
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                @change="handleFileUpload"
                                class="hidden"
                            />
                            <div v-if="selectedFiles.length === 0">
                                <svg
                                    class="w-12 h-12 mx-auto text-gray-400 mb-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    />
                                </svg>
                                <button
                                    type="button"
                                    @click="$refs.fileInput.click()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors"
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
                                <p class="text-sm text-gray-500 mt-2">
                                    PDF, DOC, DOCX, JPG, PNG (Max: 5MB per file)
                                </p>
                            </div>
                            <div v-else class="space-y-2">
                                <div
                                    v-for="(file, index) in selectedFiles"
                                    :key="index"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            class="w-5 h-5 text-gray-400 mr-2"
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
                                        <span class="text-sm text-gray-700">{{
                                            file.name
                                        }}</span>
                                        <span class="text-xs text-gray-500 ml-2"
                                            >({{
                                                formatFileSize(file.size)
                                            }})</span
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeFile(index)"
                                        class="text-red-500 hover:text-red-700"
                                    >
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
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="$refs.fileInput.click()"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    + Tambah File Lain
                                </button>
                            </div>
                        </div>
                        <p
                            v-if="form.errors.attachments"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.attachments }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="button"
                            @click="handleCancel"
                            class="inline-flex justify-center items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
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
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Batalkan
                        </button>

                        <button
                            type="button"
                            @click="saveAsDraft"
                            :disabled="form.processing"
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg
                                v-if="form.processing"
                                class="w-4 h-4 mr-2 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    class="opacity-25"
                                ></circle>
                                <path
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    class="opacity-75"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-4 h-4 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4"
                                />
                            </svg>
                            Simpan sebagai Draft
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing || !isFormValid"
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg
                                v-if="form.processing"
                                class="w-4 h-4 mr-2 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    class="opacity-25"
                                ></circle>
                                <path
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    class="opacity-75"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-4 h-4 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                />
                            </svg>
                            Kirim untuk Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
});

const fileInput = ref(null);
const selectedFiles = ref([]);

const form = useForm({
    date: new Date().toISOString().split("T")[0],
    title: "",
    activities: "",
    learning_points: "",
    challenges: "",
    duration: "",
    attachments: [],
    status: "submitted", // Will be overridden by saveAsDraft
});

const today = new Date().toISOString().split("T")[0];

const isFormValid = computed(() => {
    return form.date && form.title && form.activities && form.duration;
});

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    const maxSize = 5 * 1024 * 1024; // 5MB

    files.forEach((file) => {
        if (file.size > maxSize) {
            alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
            return;
        }

        if (!selectedFiles.value.find((f) => f.name === file.name)) {
            selectedFiles.value.push(file);
        }
    });

    // Reset input
    event.target.value = "";
};

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const handleCancel = () => {
    // Check if form has any data
    const hasData =
        form.title.trim() !== "" ||
        form.activities.trim() !== "" ||
        form.learning_points.trim() !== "" ||
        form.challenges.trim() !== "" ||
        form.duration !== "" ||
        form.date !== new Date().toISOString().split("T")[0] ||
        selectedFiles.value.length > 0;

    if (hasData) {
        if (
            confirm(
                "Anda memiliki data yang belum disimpan. Yakin ingin membatalkan?"
            )
        ) {
            router.visit(route("peserta.logbooks.index"));
        }
    } else {
        router.visit(route("peserta.logbooks.index"));
    }
};

const submit = () => {
    // Add files to form data
    form.attachments = selectedFiles.value;
    form.status = "submitted";

    form.post(route("peserta.logbooks.store"), {
        forceFormData: true,
        onSuccess: () => {
            // Reset form and files
            selectedFiles.value = [];
        },
    });
};

const saveAsDraft = () => {
    // Add files to form data
    form.attachments = selectedFiles.value;
    form.status = "draft";

    form.post(route("peserta.logbooks.store"), {
        forceFormData: true,
        onSuccess: () => {
            // Reset form and files
            selectedFiles.value = [];
        },
    });
};
</script>
