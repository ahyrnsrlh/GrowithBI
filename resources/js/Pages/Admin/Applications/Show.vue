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
                @confirm-delete="openDeleteModal"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <ApplicationMainInformation
                        :application="application"
                        :formatDate="formatDate"
                    />

                    <!-- Selection Scorecard Card -->
                    <SelectionScorecardCard
                        :evaluation="evaluation"
                        :selectionWeights="selectionWeights"
                        :application="application"
                        @edit-evaluation="openEvaluationModal(evaluation)"
                    />
                </div>

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
            :current-status="application.status"
            :division="application.division"
            :evaluation="evaluation"
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

        <EvaluationFormModal
            :show="showEvaluationModal"
            :form="evaluationForm"
            :weights="selectionWeights"
            @close="showEvaluationModal = false"
            @submit="submitEvaluation"
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
import SelectionScorecardCard from "@/Components/Admin/Applications/Show/SelectionScorecardCard.vue";
import EvaluationFormModal from "@/Components/Admin/Applications/Show/EvaluationFormModal.vue";
import { useAdminApplicationShowPage } from "@/Composables/useAdminApplicationShowPage";

const props = defineProps({
    application: Object,
    evaluation: Object,
    selectionWeights: Object,
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
    openDeleteModal,
    showEvaluationModal,
    evaluationForm,
    openEvaluationModal,
    submitEvaluation,
} = useAdminApplicationShowPage(props.application, page);
</script>
