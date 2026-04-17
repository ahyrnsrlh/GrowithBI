<script setup>
import ProfileReportCreateActions from "@/Components/Profile/Reports/Create/ProfileReportCreateActions.vue";
import ProfileReportCreateFormIntro from "@/Components/Profile/Reports/Create/ProfileReportCreateFormIntro.vue";
import ProfileReportCreateHeader from "@/Components/Profile/Reports/Create/ProfileReportCreateHeader.vue";
import ProfileReportCreateMetaFields from "@/Components/Profile/Reports/Create/ProfileReportCreateMetaFields.vue";
import ProfileReportCreateUploadField from "@/Components/Profile/Reports/Create/ProfileReportCreateUploadField.vue";
import { useProfileReportCreatePage } from "@/Composables/useProfileReportCreatePage";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    application: {
        type: Object,
        default: null,
    },
    division: {
        type: Object,
        default: null,
    },
});

const {
    form,
    selectedFile,
    isDragging,
    uploading,
    setFileInputRef,
    openFilePicker,
    handleFileSelect,
    handleDrop,
    handleDragEnter,
    handleDragLeave,
    removeFile,
    formatFileSize,
    submit,
} = useProfileReportCreatePage();
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Upload Laporan" />

        <div class="max-w-4xl mx-auto">
            <ProfileReportCreateHeader
                :back-href="route('profile.reports.index')"
            />

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <ProfileReportCreateFormIntro />

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <ProfileReportCreateMetaFields :form="form" />

                    <ProfileReportCreateUploadField
                        :form="form"
                        :selected-file="selectedFile"
                        :is-dragging="isDragging"
                        :set-file-input-ref="setFileInputRef"
                        :open-file-picker="openFilePicker"
                        :handle-file-select="handleFileSelect"
                        :handle-drop="handleDrop"
                        :handle-drag-enter="handleDragEnter"
                        :handle-drag-leave="handleDragLeave"
                        :remove-file="removeFile"
                        :format-file-size="formatFileSize"
                    />

                    <ProfileReportCreateActions
                        :cancel-href="route('profile.reports.index')"
                        :processing="form.processing"
                        :uploading="uploading"
                        :disabled="
                            uploading ||
                            form.processing ||
                            !selectedFile ||
                            !form.title
                        "
                    />
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
