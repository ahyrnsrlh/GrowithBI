<template>
    <BaseModal
        :show="showPreview"
        :title="documentName"
        :maxWidth="maxWidth"
        @close="$emit('close')"
    >
        <DocumentPreview
            :url="previewUrl || ''"
            :file-name="documentName"
        />

        <template #footer>
            <div class="flex items-center justify-between w-full">
                <!-- Left aligned document format info -->
                <div class="text-xs text-gray-500 font-medium">
                    <span v-if="isPDF">Format: PDF</span>
                    <span v-else>Format: Gambar (JPG/PNG)</span>
                </div>

                <!-- Right aligned action buttons -->
                <div class="flex items-center space-x-3">
                    <button
                        @click="$emit('download')"
                        :disabled="isDownloading"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50"
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
                        @click="$emit('open-new-tab')"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
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
                        @click="$emit('close')"
                        class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </template>
    </BaseModal>
</template>

<script setup>
import BaseModal from "@/Components/BaseModal.vue";
import DocumentPreview from "@/Components/DocumentPreview.vue";

defineProps({
    maxWidth: {
        type: String,
        default: "max-w-2xl",
    },
    showPreview: {
        type: Boolean,
        default: false,
    },
    documentName: {
        type: String,
        required: true,
    },
    previewLoading: {
        type: Boolean,
        default: false,
    },
    previewError: {
        type: String,
        default: null,
    },
    previewUrl: {
        type: String,
        default: null,
    },
    isPDF: {
        type: Boolean,
        default: false,
    },
    isDownloading: {
        type: Boolean,
        default: false,
    },
});

defineEmits([
    "close",
    "retry-preview",
    "download",
    "open-new-tab",
    "preview-load",
    "preview-error",
]);
</script>
