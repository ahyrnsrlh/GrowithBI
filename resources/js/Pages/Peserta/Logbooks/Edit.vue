<template>
    <PesertaLayout
        :title="`Edit Logbook - ${form.title || 'Untitled'}`"
        subtitle="Ubah dan perbaiki logbook aktivitas magang"
    >
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Edit Logbook
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Perbaiki logbook sesuai dengan feedback pembimbing
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link
                            :href="route('peserta.logbooks.show', logbook.id)"
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
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Batal
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Alert for revision feedback -->
            <div
                v-if="logbook.status === 'revision' && lastRevisionComment"
                class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6"
            >
                <div class="flex">
                    <svg
                        class="w-5 h-5 text-yellow-600 mt-0.5 mr-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                            Feedback Revisi dari Pembimbing
                        </h3>
                        <p class="text-yellow-700 whitespace-pre-wrap">
                            {{ lastRevisionComment.comment }}
                        </p>
                        <p class="text-sm text-yellow-600 mt-2">
                            <strong>{{ lastRevisionComment.user.name }}</strong>
                            - {{ formatDate(lastRevisionComment.created_at) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm" class="space-y-8">
                <!-- Basic Information -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <svg
                            class="w-5 h-5 inline mr-2 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        Informasi Dasar
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="lg:col-span-2">
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Judul Logbook
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Masukkan judul aktivitas yang dilakukan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.title }"
                            />
                            <p
                                v-if="errors.title"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.title }}
                            </p>
                        </div>

                        <!-- Date -->
                        <div>
                            <label
                                for="date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="date"
                                v-model="form.date"
                                type="date"
                                :max="today"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.date }"
                            />
                            <p
                                v-if="errors.date"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.date }}
                            </p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label
                                for="duration"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Durasi (jam) <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="duration"
                                v-model.number="form.duration"
                                type="number"
                                min="0.5"
                                max="12"
                                step="0.5"
                                placeholder="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': errors.duration }"
                            />
                            <p
                                v-if="errors.duration"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.duration }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Maksimal 12 jam per hari
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Activities -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <svg
                            class="w-5 h-5 inline mr-2 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                            />
                        </svg>
                        Aktivitas yang Dilakukan
                    </h2>

                    <div>
                        <label
                            for="activities"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi Aktivitas
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="activities"
                            v-model="form.activities"
                            rows="6"
                            placeholder="Jelaskan secara detail aktivitas yang dilakukan, tugas yang diselesaikan, dan hasil yang dicapai..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{ 'border-red-500': errors.activities }"
                        ></textarea>
                        <p
                            v-if="errors.activities"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.activities }}
                        </p>
                        <div class="flex justify-between mt-2">
                            <p class="text-sm text-gray-500">
                                Minimal 50 karakter
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ form.activities?.length || 0 }} karakter
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Learning Points -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <svg
                            class="w-5 h-5 inline mr-2 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                            />
                        </svg>
                        Pembelajaran & Insight
                    </h2>

                    <div>
                        <label
                            for="learning_points"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Apa yang Dipelajari
                        </label>
                        <textarea
                            id="learning_points"
                            v-model="form.learning_points"
                            rows="4"
                            placeholder="Jelaskan hal-hal baru yang dipelajari, skill yang didapat, atau insight yang diperoleh dari aktivitas hari ini..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{
                                'border-red-500': errors.learning_points,
                            }"
                        ></textarea>
                        <p
                            v-if="errors.learning_points"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.learning_points }}
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ form.learning_points?.length || 0 }} karakter
                        </p>
                    </div>
                </div>

                <!-- Challenges -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <svg
                            class="w-5 h-5 inline mr-2 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                        Tantangan & Kendala
                    </h2>

                    <div>
                        <label
                            for="challenges"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Tantangan yang Dihadapi
                        </label>
                        <textarea
                            id="challenges"
                            v-model="form.challenges"
                            rows="4"
                            placeholder="Jelaskan kendala, kesulitan, atau tantangan yang dihadapi dan bagaimana cara mengatasinya..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            :class="{ 'border-red-500': errors.challenges }"
                        ></textarea>
                        <p
                            v-if="errors.challenges"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.challenges }}
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ form.challenges?.length || 0 }} karakter
                        </p>
                    </div>
                </div>

                <!-- File Attachments -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <svg
                            class="w-5 h-5 inline mr-2 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                            />
                        </svg>
                        Lampiran (Opsional)
                    </h2>

                    <!-- Existing Files -->
                    <div v-if="existingFiles.length > 0" class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            File yang Sudah Ada
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div
                                v-for="(file, index) in existingFiles"
                                :key="`existing-${index}`"
                                class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200"
                            >
                                <div class="flex-shrink-0 mr-4">
                                    <svg
                                        class="w-8 h-8 text-gray-500"
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
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate"
                                    >
                                        {{ file.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatFileSize(file.size) }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <button
                                        type="button"
                                        @click="removeExistingFile(index)"
                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200 transition-colors"
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
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New File Upload -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Tambah File Baru
                        </label>
                        <div
                            @drop="handleDrop"
                            @dragover="handleDragOver"
                            @dragleave="handleDragLeave"
                            @click="triggerFileInput"
                            :class="[
                                'border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors',
                                isDragging
                                    ? 'border-blue-500 bg-blue-50'
                                    : 'border-gray-300 hover:border-gray-400',
                            ]"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx"
                                @change="handleFileSelect"
                                class="hidden"
                            />
                            <svg
                                class="w-12 h-12 mx-auto mb-4 text-gray-400"
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
                            <p class="text-lg font-medium text-gray-900 mb-1">
                                Drag & drop file atau klik untuk pilih
                            </p>
                            <p class="text-sm text-gray-500">
                                PDF, DOC, JPG, PNG, XLS (Max. 10MB per file)
                            </p>
                        </div>

                        <!-- New Files Preview -->
                        <div v-if="newFiles.length > 0" class="mt-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div
                                    v-for="(file, index) in newFiles"
                                    :key="`new-${index}`"
                                    class="flex items-center p-4 bg-blue-50 rounded-lg border border-blue-200"
                                >
                                    <div class="flex-shrink-0 mr-4">
                                        <svg
                                            class="w-8 h-8 text-blue-500"
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
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-900 truncate"
                                        >
                                            {{ file.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ formatFileSize(file.size) }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 ml-4">
                                        <button
                                            type="button"
                                            @click="removeNewFile(index)"
                                            class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200 transition-colors"
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
                                </div>
                            </div>
                        </div>

                        <p
                            v-if="errors.attachments"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.attachments }}
                        </p>
                    </div>
                </div>

                <!-- Submit Actions -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Simpan Perubahan
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Pilih cara menyimpan logbook yang telah diubah
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <button
                                type="button"
                                @click="saveDraft"
                                :disabled="processing"
                                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg
                                    v-if="processing && submitType === 'draft'"
                                    class="w-4 h-4 mr-2 animate-spin"
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
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a1 1 0 01-1-1V3a1 1 0 011-1z"
                                    />
                                </svg>
                                Simpan Draft
                            </button>
                            <button
                                type="submit"
                                :disabled="processing"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg
                                    v-if="processing && submitType === 'submit'"
                                    class="w-4 h-4 mr-2 animate-spin"
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
                                {{
                                    logbook.status === "revision"
                                        ? "Kirim Revisi"
                                        : "Simpan & Kirim"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const fileInput = ref(null);
const newFiles = ref([]);
const existingFiles = ref([]);
const isDragging = ref(false);
const processing = ref(false);
const submitType = ref("");

const form = useForm({
    title: props.logbook.title || "",
    date: props.logbook.date || "",
    duration: props.logbook.duration || "",
    activities: props.logbook.activities || "",
    learning_points: props.logbook.learning_points || "",
    challenges: props.logbook.challenges || "",
    attachments: [],
    removed_files: [],
});

const today = computed(() => {
    return new Date().toISOString().split("T")[0];
});

const lastRevisionComment = computed(() => {
    if (!props.logbook.comments) return null;
    return props.logbook.comments
        .filter((comment) => comment.comment_type === "revision")
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))[0];
});

onMounted(() => {
    if (props.logbook.attachments) {
        existingFiles.value = [...props.logbook.attachments];
    }
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
        return "-";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const handleDragOver = (e) => {
    e.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (e) => {
    e.preventDefault();
    isDragging.value = false;
};

const handleDrop = (e) => {
    e.preventDefault();
    isDragging.value = false;
    const files = Array.from(e.dataTransfer.files);
    processFiles(files);
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileSelect = (e) => {
    const files = Array.from(e.target.files);
    processFiles(files);
};

const processFiles = (files) => {
    const validFiles = files.filter((file) => {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "image/jpeg",
            "image/jpg",
            "image/png",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];

        return file.size <= maxSize && allowedTypes.includes(file.type);
    });

    newFiles.value = [...newFiles.value, ...validFiles];
};

const removeNewFile = (index) => {
    newFiles.value.splice(index, 1);
};

const removeExistingFile = (index) => {
    const removedFile = existingFiles.value[index];
    form.removed_files.push(removedFile.id);
    existingFiles.value.splice(index, 1);
};

const validateForm = () => {
    const errors = {};

    if (!form.title?.trim()) {
        errors.title = "Judul logbook harus diisi";
    }

    if (!form.date) {
        errors.date = "Tanggal harus diisi";
    }

    if (!form.duration || form.duration < 0.5 || form.duration > 12) {
        errors.duration = "Durasi harus antara 0.5 - 12 jam";
    }

    if (!form.activities?.trim() || form.activities.length < 50) {
        errors.activities = "Deskripsi aktivitas minimal 50 karakter";
    }

    return errors;
};

const submitForm = () => {
    submitType.value = "submit";
    updateLogbook("submitted");
};

const saveDraft = () => {
    submitType.value = "draft";
    updateLogbook("draft");
};

const updateLogbook = (status) => {
    const formErrors = validateForm();
    if (Object.keys(formErrors).length > 0 && status === "submitted") {
        // Show validation errors
        return;
    }

    processing.value = true;

    // Prepare form data
    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("title", form.title);
    formData.append("date", form.date);
    formData.append("duration", form.duration);
    formData.append("activities", form.activities);
    formData.append("learning_points", form.learning_points || "");
    formData.append("challenges", form.challenges || "");
    formData.append("status", status);

    // Add new files
    newFiles.value.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file);
    });

    // Add removed files
    form.removed_files.forEach((fileId, index) => {
        formData.append(`removed_files[${index}]`, fileId);
    });

    router.post(route("peserta.logbooks.update", props.logbook.id), formData, {
        forceFormData: true,
        onSuccess: () => {
            router.visit(route("peserta.logbooks.show", props.logbook.id));
        },
        onError: (errors) => {
            console.error("Update errors:", errors);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<style scoped>
.prose {
    max-width: none;
}
</style>
