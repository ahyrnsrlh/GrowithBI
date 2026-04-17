<template>
    <PesertaLayout>
        <LogbookShowHeader
            :logbook="logbook"
            :can-edit="canEdit"
            :format-date-short="formatDateShort"
            :get-status-badge-class="getStatusBadgeClass"
            :get-status-dot-class="getStatusDotClass"
            :get-status-text="getStatusText"
            :get-progress-percentage="getProgressPercentage"
        />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <LogbookShowContentCard
                        :logbook="logbook"
                        :format-date="formatDate"
                        :format-file-size="formatFileSize"
                        :get-status-badge-class="getStatusBadgeClass"
                        :get-status-text="getStatusText"
                    />

                    <LogbookShowCommentsCard
                        :all-comments="allComments"
                        :comment-form="commentForm"
                        :format-date-short="formatDateShort"
                        :add-comment="addComment"
                    />
                </div>

                <LogbookShowSidebar
                    :logbook="logbook"
                    :can-edit="canEdit"
                    :format-date-short="formatDateShort"
                    :get-status-badge-class="getStatusBadgeClass"
                    :get-status-dot-class="getStatusDotClass"
                    :get-status-text="getStatusText"
                />
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import LogbookShowCommentsCard from "@/Components/Peserta/Logbooks/Show/LogbookShowCommentsCard.vue";
import LogbookShowContentCard from "@/Components/Peserta/Logbooks/Show/LogbookShowContentCard.vue";
import LogbookShowHeader from "@/Components/Peserta/Logbooks/Show/LogbookShowHeader.vue";
import LogbookShowSidebar from "@/Components/Peserta/Logbooks/Show/LogbookShowSidebar.vue";
import { usePesertaLogbookShowPage } from "@/Composables/usePesertaLogbookShowPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const {
    commentForm,
    canEdit,
    allComments,
    formatDate,
    formatDateShort,
    formatFileSize,
    getStatusBadgeClass,
    getStatusDotClass,
    getStatusText,
    getProgressPercentage,
    addComment,
} = usePesertaLogbookShowPage(props.logbook);
</script>
