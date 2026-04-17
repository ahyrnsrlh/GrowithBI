<template>
    <PesertaLayout
        :title="`Edit Logbook - ${form.title || 'Untitled'}`"
        subtitle="Ubah dan perbaiki logbook aktivitas magang"
    >
        <div class="max-w-4xl mx-auto">
            <LogbookEditHeader @cancel="handleCancel" />

            <LogbookEditRevisionAlert
                :logbook-status="logbook.status"
                :last-revision-comment="lastRevisionComment"
                :format-date="formatDate"
            />

            <form class="space-y-8" @submit.prevent="submitForm">
                <LogbookEditBasicInfoCard
                    :form="form"
                    :errors="errors"
                    :today="today"
                />

                <LogbookEditActivitiesCard
                    :form="form"
                    :errors="errors"
                />

                <LogbookEditLearningCard
                    :form="form"
                    :errors="errors"
                />

                <LogbookEditChallengesCard
                    :form="form"
                    :errors="errors"
                />

                <LogbookEditAttachmentsCard
                    :existing-files="existingFiles"
                    :new-files="newFiles"
                    :errors="errors"
                    :format-file-size="formatFileSize"
                    @add-files="addFiles"
                    @remove-new-file="removeNewFile"
                    @remove-existing-file="removeExistingFile"
                />

                <LogbookEditSubmitActionsCard
                    :processing="processing"
                    :submit-type="submitType"
                    :logbook-status="logbook.status"
                    @save-draft="saveDraft"
                />
            </form>
        </div>
    </PesertaLayout>
</template>

<script setup>
import LogbookEditActivitiesCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditActivitiesCard.vue";
import LogbookEditAttachmentsCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditAttachmentsCard.vue";
import LogbookEditBasicInfoCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditBasicInfoCard.vue";
import LogbookEditChallengesCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditChallengesCard.vue";
import LogbookEditHeader from "@/Components/Peserta/Logbooks/Edit/LogbookEditHeader.vue";
import LogbookEditLearningCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditLearningCard.vue";
import LogbookEditRevisionAlert from "@/Components/Peserta/Logbooks/Edit/LogbookEditRevisionAlert.vue";
import LogbookEditSubmitActionsCard from "@/Components/Peserta/Logbooks/Edit/LogbookEditSubmitActionsCard.vue";
import { usePesertaLogbookEditPage } from "@/Composables/usePesertaLogbookEditPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

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

const {
    form,
    newFiles,
    existingFiles,
    processing,
    submitType,
    today,
    lastRevisionComment,
    formatDate,
    formatFileSize,
    addFiles,
    removeNewFile,
    removeExistingFile,
    handleCancel,
    submitForm,
    saveDraft,
} = usePesertaLogbookEditPage(props.logbook);
</script>
