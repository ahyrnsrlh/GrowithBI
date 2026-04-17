<template>
    <PesertaLayout
        title="Tambah Logbook"
        subtitle="Buat catatan aktivitas harian magang baru"
    >
        <div class="max-w-4xl mx-auto">
            <LogbookCreateHeader />

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
                    <LogbookCreateBasicFields :form="form" :today="today" />

                    <LogbookCreateTextAreas :form="form" />

                    <LogbookCreateAttachments
                        :selected-files="selectedFiles"
                        :format-file-size="formatFileSize"
                        :attachments-error="form.errors.attachments"
                        @file-upload="handleFileUpload"
                        @remove-file="removeFile"
                    />

                    <LogbookCreateActions
                        :processing="form.processing"
                        :is-form-valid="isFormValid"
                        @cancel="handleCancel"
                        @draft="saveAsDraft"
                    />
                </form>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import LogbookCreateActions from "@/Components/Peserta/Logbooks/Create/LogbookCreateActions.vue";
import LogbookCreateAttachments from "@/Components/Peserta/Logbooks/Create/LogbookCreateAttachments.vue";
import LogbookCreateBasicFields from "@/Components/Peserta/Logbooks/Create/LogbookCreateBasicFields.vue";
import LogbookCreateHeader from "@/Components/Peserta/Logbooks/Create/LogbookCreateHeader.vue";
import LogbookCreateTextAreas from "@/Components/Peserta/Logbooks/Create/LogbookCreateTextAreas.vue";
import { usePesertaLogbookCreatePage } from "@/Composables/usePesertaLogbookCreatePage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

defineProps({
    division: {
        type: Object,
        required: true,
    },
});

const {
    form,
    today,
    isFormValid,
    selectedFiles,
    handleFileUpload,
    removeFile,
    formatFileSize,
    handleCancel,
    submit,
    saveAsDraft,
} = usePesertaLogbookCreatePage();
</script>
