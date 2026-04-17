<template>
    <div class="bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200 hover:shadow-md">
        <div class="p-4">
            <DocumentCardHeader
                :document-name="documentName"
                :is-required="isRequired"
                :has-document="hasDocument"
            />

            <DocumentCardActions
                :has-document="hasDocument"
                :is-loading="isLoading"
                :is-downloading="isDownloading"
                @view="viewDocument"
                @download="downloadDocument"
                @trigger-upload="triggerFileInput"
            />

            <input
                ref="fileInput"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                @change="handleFileChange"
                class="hidden"
            />
        </div>

        <DocumentCardPreviewModal
            :show-preview="showPreview"
            :document-name="documentName"
            :preview-loading="previewLoading"
            :preview-error="previewError"
            :preview-url="previewUrl"
            :is-pdf="isPDF"
            :is-downloading="isDownloading"
            @close="closePreview"
            @retry-preview="retryPreview"
            @download="downloadDocument"
            @open-new-tab="openInNewTab"
            @preview-load="onPreviewLoad"
            @preview-error="onPreviewError"
        />
    </div>
</template>

<script setup>
import DocumentCardHeader from "@/Components/Documents/DocumentCardHeader.vue";
import DocumentCardActions from "@/Components/Documents/DocumentCardActions.vue";
import DocumentCardPreviewModal from "@/Components/Documents/DocumentCardPreviewModal.vue";
import { useDocumentCard } from "@/Composables/useDocumentCard";

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

const {
    showPreview,
    fileInput,
    isLoading,
    isDownloading,
    previewLoading,
    previewError,
    previewUrl,
    hasDocument,
    isPDF,
    triggerFileInput,
    viewDocument,
    onPreviewLoad,
    onPreviewError,
    retryPreview,
    closePreview,
    openInNewTab,
    downloadDocument,
    handleFileChange,
} = useDocumentCard(props, emit);
</script>
