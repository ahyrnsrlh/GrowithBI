<template>
    <AdminLayout
        title="Detail Pendaftaran"
        :subtitle="`Pendaftaran ${application.user?.name || 'N/A'} - ${
            application.division?.name || 'N/A'
        }`"
    >
        <div class="max-w-4xl mx-auto">
            <ApplicationHeaderCard
                :application="application"
                :getStatusBadgeClass="getStatusBadgeClass"
                @open-status-modal="showStatusModal = true"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <ApplicationMainInformation
                    :application="application"
                    :formatDate="formatDate"
                />

                <ApplicationSidebar
                    :application="application"
                    :formatDateTime="formatDateTime"
                    :getDaysAgo="getDaysAgo"
                    :isAcceptedStatus="isAcceptedStatus"
                    :getStatusTextClass="getStatusTextClass"
                    @open-letter-upload="showLetterUpload = true"
                />
            </div>
        </div>

        <StatusUpdateModal
            :show="showStatusModal"
            :statusForm="statusForm"
            @close="showStatusModal = false"
            @submit="updateStatus"
        />

        <AcceptanceLetterUploadModal
            :show="showLetterUpload"
            :uploadError="uploadError"
            :isUploading="isUploading"
            :selectedFile="selectedFile"
            :inputKey="uploadInputKey"
            @close="closeLetterUpload"
            @submit="uploadAcceptanceLetter"
            @file-change="handleFileChange"
        />

        <UploadSuccessModal
            :show="showSuccessModal"
            :message="successMessage"
            @close="showSuccessModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ApplicationHeaderCard from "@/Components/Admin/Applications/Show/ApplicationHeaderCard.vue";
import ApplicationMainInformation from "@/Components/Admin/Applications/Show/ApplicationMainInformation.vue";
import ApplicationSidebar from "@/Components/Admin/Applications/Show/ApplicationSidebar.vue";
import StatusUpdateModal from "@/Components/Admin/Applications/Show/StatusUpdateModal.vue";
import AcceptanceLetterUploadModal from "@/Components/Admin/Applications/Show/AcceptanceLetterUploadModal.vue";
import UploadSuccessModal from "@/Components/Admin/Applications/Show/UploadSuccessModal.vue";
import { useAdminApplicationShowPage } from "@/Composables/useAdminApplicationShowPage";

const props = defineProps({
    application: Object,
});

const page = usePage();

const {
    showStatusModal,
    showLetterUpload,
    showSuccessModal,
    successMessage,
    selectedFile,
    isUploading,
    uploadError,
    uploadInputKey,
    statusForm,
    formatDate,
    formatDateTime,
    getDaysAgo,
    isAcceptedStatus,
    getStatusBadgeClass,
    getStatusTextClass,
    updateStatus,
    handleFileChange,
    uploadAcceptanceLetter,
    closeLetterUpload,
} = useAdminApplicationShowPage(props.application, page);
</script>
