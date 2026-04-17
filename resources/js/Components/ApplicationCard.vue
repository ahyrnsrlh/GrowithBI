<template>
    <div class="group relative">
        <div
            class="bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 hover:border-gray-200"
        >
            <ApplicationCardHeader
                :application="application"
                :get-accent-gradient="getAccentGradient"
                :get-status-badge-class="getStatusBadgeClass"
                :get-status-dot-class="getStatusDotClass"
                :get-status-text="getStatusText"
                :format-date="formatDate"
            />

            <ApplicationCardTimeline
                :application="application"
                :get-progress-bar-color="getProgressBarColor"
                :get-progress-width-adjusted="getProgressWidthAdjusted"
                :is-step-complete="isStepComplete"
                :is-step-active="isStepActive"
                :is-accepted="isAccepted"
                :show-letter-step="showLetterStep"
                :format-short-date="formatShortDate"
            />

            <ApplicationCardActions
                :application="application"
                :downloading="downloading"
                @view-details="viewDetails"
                @download-offer="downloadOffer"
            />
        </div>

        <ApplicationCardDetailModal
            :show="showModal"
            :application="application"
            :downloading="downloading"
            :get-accent-gradient="getAccentGradient"
            :get-status-badge-class="getStatusBadgeClass"
            :get-status-dot-class="getStatusDotClass"
            :get-status-text="getStatusText"
            :format-date="formatDate"
            @close="closeModal"
            @download-offer="downloadOffer"
        />
    </div>
</template>

<script setup>
import ApplicationCardActions from "@/Components/ApplicationCard/ApplicationCardActions.vue";
import ApplicationCardDetailModal from "@/Components/ApplicationCard/ApplicationCardDetailModal.vue";
import ApplicationCardHeader from "@/Components/ApplicationCard/ApplicationCardHeader.vue";
import ApplicationCardTimeline from "@/Components/ApplicationCard/ApplicationCardTimeline.vue";
import { useApplicationCard } from "@/Composables/useApplicationCard";

const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["status-updated"]);

const {
    showModal,
    downloading,
    isAccepted,
    isStepComplete,
    isStepActive,
    showLetterStep,
    getProgressWidthAdjusted,
    getProgressBarColor,
    getAccentGradient,
    getStatusBadgeClass,
    getStatusDotClass,
    getStatusText,
    formatDate,
    formatShortDate,
    viewDetails,
    closeModal,
    downloadOffer,
} = useApplicationCard(props.application, emit);
</script>
