<template>
    <div
        class="bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200 hover:shadow-md"
    >
        <div class="p-4">
            <!-- Header: Icon + Name + Required -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-start space-x-3">
                    <!-- Document Icon -->
                    <div
                        class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                        :class="
                            documentPath
                                ? 'bg-green-50 text-green-600'
                                : 'bg-gray-100 text-gray-400'
                        "
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                v-if="documentPath"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                            <path
                                v-else
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            />
                        </svg>
                    </div>

                    <!-- Document Name -->
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-sm font-medium text-gray-900 leading-tight"
                        >
                            {{ documentName }}
                            <span
                                v-if="isRequired"
                                class="text-red-500 ml-0.5"
                                title="Dokumen wajib"
                                >*</span
                            >
                        </h3>
                        <!-- Status Badge -->
                        <div class="mt-1">
                            <span
                                v-if="documentPath"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200"
                            >
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Tersimpan
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200"
                            >
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Belum Upload
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="mt-3 pt-3 border-t border-gray-100">
                <!-- When file exists: View, Download, Replace -->
                <div v-if="documentPath" class="flex items-center space-x-2">
                    <!-- View Button -->
                    <button
                        type="button"
                        @click="viewDocument"
                        :disabled="isLoading"
                        class="flex-1 inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        title="Lihat dokumen"
                    >
                        <svg
                            v-if="!isLoading"
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 mr-1 animate-spin"
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
                        Lihat
                    </button>

                    <!-- Download Button -->
                    <button
                        type="button"
                        @click="downloadDocument"
                        :disabled="isDownloading"
                        class="flex-1 inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        title="Unduh dokumen"
                    >
                        <svg
                            v-if="!isDownloading"
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 mr-1 animate-spin"
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
                        Unduh
                    </button>

                    <!-- Replace File Button -->
                    <button
                        type="button"
                        @click="triggerFileInput"
                        class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors"
                        title="Ganti file"
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
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </div>

                <!-- When no file: Upload Button -->
                <button
                    v-else
                    type="button"
                    @click="triggerFileInput"
                    class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
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
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                        />
                    </svg>
                    Pilih File
                </button>
            </div>

            <!-- Hidden File Input -->
            <input
                ref="fileInput"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                @change="handleFileChange"
                class="hidden"
            />
        </div>

        <!-- Preview Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showPreview"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    @keydown.esc="closePreview"
                >
                    <div
                        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20"
                    >
                        <!-- Backdrop -->
                        <div
                            class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                            @click="closePreview"
                        ></div>

                        <!-- Modal Content -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="showPreview"
                                class="relative bg-white rounded-lg max-w-4xl w-full shadow-xl"
                                @click.stop
                            >
                                <!-- Header -->
                                <div
                                    class="flex items-center justify-between p-4 border-b border-gray-200"
                                >
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ documentName }}
                                    </h3>
                                    <button
                                        @click="closePreview"
                                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100"
                                        title="Tutup (ESC)"
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

                                <!-- Preview Content -->
                                <div
                                    class="p-4 bg-gray-50 overflow-auto"
                                    style="max-height: 70vh"
                                >
                                    <!-- Loading State -->
                                    <div
                                        v-if="previewLoading"
                                        class="flex items-center justify-center min-h-[60vh]"
                                    >
                                        <div class="text-center">
                                            <svg
                                                class="w-12 h-12 mx-auto text-blue-600 animate-spin"
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
                                            <p class="mt-4 text-gray-600">
                                                Memuat dokumen...
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Error State -->
                                    <div
                                        v-else-if="previewError"
                                        class="flex items-center justify-center min-h-[60vh]"
                                    >
                                        <div class="text-center">
                                            <svg
                                                class="w-16 h-16 mx-auto text-red-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                                />
                                            </svg>
                                            <p class="mt-4 text-gray-600">
                                                Gagal memuat dokumen
                                            </p>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ previewError }}
                                            </p>
                                            <button
                                                @click="retryPreview"
                                                class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors"
                                            >
                                                Coba Lagi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- PDF Preview -->
                                    <iframe
                                        v-else-if="isPDF"
                                        :src="previewUrl"
                                        class="w-full min-h-[60vh] rounded border border-gray-200"
                                        @load="onPreviewLoad"
                                        @error="onPreviewError"
                                    ></iframe>

                                    <!-- Image Preview -->
                                    <div v-else class="flex justify-center">
                                        <img
                                            :src="previewUrl"
                                            :alt="documentName"
                                            class="max-w-full h-auto rounded shadow-sm"
                                            @load="onPreviewLoad"
                                            @error="onPreviewError"
                                        />
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div
                                    class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50"
                                >
                                    <div class="text-sm text-gray-500">
                                        <span v-if="isPDF">Dokumen PDF</span>
                                        <span v-else>Dokumen Gambar</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button
                                            @click="downloadDocument"
                                            :disabled="isDownloading"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50"
                                        >
                                            <svg
                                                v-if="!isDownloading"
                                                class="w-4 h-4 mr-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                />
                                            </svg>
                                            <svg
                                                v-else
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
                                            Unduh
                                        </button>
                                        <button
                                            @click="openInNewTab"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors"
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
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                            Buka Tab Baru
                                        </button>
                                        <button
                                            @click="closePreview"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors"
                                        >
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

const props = defineProps({
    documentType: {
        type: String,
        required: true,
    },
    documentName: {
        type: String,
        required: true,
    },
    documentPath: {
        type: String,
        default: null,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["upload"]);

// State
const showPreview = ref(false);
const fileInput = ref(null);
const isLoading = ref(false);
const isDownloading = ref(false);
const previewLoading = ref(false);
const previewError = ref(null);
const previewUrl = ref(null);

/**
 * Generate the full URL for a document
 * Handles both relative paths and absolute URLs
 */
const getDocumentUrl = computed(() => {
    if (!props.documentPath) return null;
    
    // If already a full URL, return as is
    if (props.documentPath.startsWith('http://') || props.documentPath.startsWith('https://')) {
        return props.documentPath;
    }
    
    // If starts with /storage, return as is
    if (props.documentPath.startsWith('/storage/')) {
        return props.documentPath;
    }
    
    // Otherwise, prepend /storage/ for Laravel public disk
    return `/storage/${props.documentPath}`;
});

/**
 * Check if document is PDF based on file extension
 */
const isPDF = computed(() => {
    if (!props.documentPath) return false;
    return props.documentPath.toLowerCase().endsWith(".pdf");
});

/**
 * Trigger hidden file input
 */
const triggerFileInput = () => {
    if (fileInput.value) {
        fileInput.value.click();
    }
};

/**
 * View document in modal
 */
const viewDocument = async () => {
    if (!props.documentPath) return;
    
    isLoading.value = true;
    previewLoading.value = true;
    previewError.value = null;
    previewUrl.value = getDocumentUrl.value;
    showPreview.value = true;
    
    // Add keyboard listener for ESC
    document.addEventListener('keydown', handleKeydown);
    
    isLoading.value = false;
};

/**
 * Handle preview load success
 */
const onPreviewLoad = () => {
    previewLoading.value = false;
    previewError.value = null;
};

/**
 * Handle preview load error
 */
const onPreviewError = () => {
    previewLoading.value = false;
    previewError.value = 'File tidak ditemukan atau tidak dapat ditampilkan';
};

/**
 * Retry loading preview
 */
const retryPreview = () => {
    previewLoading.value = true;
    previewError.value = null;
    // Force reload by adding timestamp
    previewUrl.value = `${getDocumentUrl.value}?t=${Date.now()}`;
};

/**
 * Close preview modal
 */
const closePreview = () => {
    showPreview.value = false;
    previewUrl.value = null;
    previewError.value = null;
    document.removeEventListener('keydown', handleKeydown);
};

/**
 * Handle keyboard events
 */
const handleKeydown = (event) => {
    if (event.key === 'Escape') {
        closePreview();
    }
};

/**
 * Open document in new tab
 */
const openInNewTab = () => {
    if (getDocumentUrl.value) {
        window.open(getDocumentUrl.value, '_blank', 'noopener,noreferrer');
    }
};

/**
 * Download document
 * Uses fetch to properly trigger download with correct filename
 */
const downloadDocument = async () => {
    if (!props.documentPath || isDownloading.value) return;
    
    isDownloading.value = true;
    
    try {
        const url = getDocumentUrl.value;
        
        // Fetch the file as blob
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error('File tidak ditemukan');
        }
        
        const blob = await response.blob();
        
        // Extract filename from path
        const filename = props.documentPath.split('/').pop() || `${props.documentType}.pdf`;
        
        // Create download link
        const downloadUrl = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = filename;
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Cleanup
        window.URL.revokeObjectURL(downloadUrl);
    } catch (error) {
        console.error('Download error:', error);
        alert('Gagal mengunduh dokumen. ' + error.message);
    } finally {
        isDownloading.value = false;
    }
};

/**
 * Handle file selection
 */
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size (5MB max)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        alert("Ukuran file terlalu besar. Maksimal 5MB.");
        event.target.value = "";
        return;
    }

    // Validate file type
    const allowedTypes = [
        "application/pdf",
        "image/jpeg",
        "image/jpg",
        "image/png",
    ];
    if (!allowedTypes.includes(file.type)) {
        alert("Format file tidak didukung. Gunakan PDF, JPG, atau PNG.");
        event.target.value = "";
        return;
    }

    // Confirm replacement if file already exists
    if (props.documentPath) {
        if (!confirm("Apakah Anda yakin ingin mengganti file yang sudah ada?")) {
            event.target.value = "";
            return;
        }
    }

    // Emit upload event
    emit("upload", props.documentType, file);

    // Reset input
    event.target.value = "";
};

// Cleanup on unmount
onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});
</script>
