<template>
    <Head :title="division.name || 'Detail Program'" />

    <PublicNavbar :auth="auth" />

    <div class="min-h-screen bg-gray-50 pt-24">
        <DivisionDetailHero
            :auth="auth"
            :available-slots="availableSlots"
            :division="division"
            :existing-application="existingApplication"
            :handle-apply="handleApply"
            :is-loading="isLoading"
        />

        <DivisionDetailContentGrid
            :available-slots="availableSlots"
            :clean-task-text="cleanTaskText"
            :division="division"
            :format-available-slots="formatAvailableSlots"
            :format-date="formatDate"
            :format-quota="formatQuota"
            :formatted-duration="formattedDuration"
            :has-benefits="hasBenefits"
            :has-criteria="hasCriteria"
            :has-job-description="hasJobDescription"
            :has-requirements="hasRequirements"
            :normalized-benefits="normalizedBenefits"
            :normalized-criteria="normalizedCriteria"
            :normalized-job-description="normalizedJobDescription"
            :normalized-requirements="normalizedRequirements"
        />
    </div>

    <DivisionDetailNotificationModal
        :notification="notification"
        :redirect-countdown="redirectCountdown"
        :should-redirect-to-profile="shouldRedirectToProfile"
        @close="closeNotification"
    />
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicNavbar from "@/Components/PublicNavbar.vue";
import DivisionDetailContentGrid from "@/Components/Public/DivisionDetail/DivisionDetailContentGrid.vue";
import DivisionDetailHero from "@/Components/Public/DivisionDetail/DivisionDetailHero.vue";
import DivisionDetailNotificationModal from "@/Components/Public/DivisionDetail/DivisionDetailNotificationModal.vue";
import { useDivisionDetailPage } from "@/Composables/useDivisionDetailPage";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
    existingApplication: {
        type: Object,
        default: null,
    },
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});

const {
    availableSlots,
    cleanTaskText,
    closeNotification,
    formatAvailableSlots,
    formatDate,
    formatQuota,
    formattedDuration,
    handleApply,
    hasBenefits,
    hasCriteria,
    hasJobDescription,
    hasRequirements,
    isLoading,
    normalizedBenefits,
    normalizedCriteria,
    normalizedJobDescription,
    normalizedRequirements,
    notification,
    redirectCountdown,
    shouldRedirectToProfile,
} = useDivisionDetailPage(props);
</script>
