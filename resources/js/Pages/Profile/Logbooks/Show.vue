<script setup>
import ProfileLogbookShowFeedbackCard from "@/Components/Profile/Logbooks/Show/ProfileLogbookShowFeedbackCard.vue";
import ProfileLogbookShowInfoCard from "@/Components/Profile/Logbooks/Show/ProfileLogbookShowInfoCard.vue";
import ProfileLogbookShowMainCard from "@/Components/Profile/Logbooks/Show/ProfileLogbookShowMainCard.vue";
import ProfileLogbookShowTopBar from "@/Components/Profile/Logbooks/Show/ProfileLogbookShowTopBar.vue";
import { useProfileLogbookShowPage } from "@/Composables/useProfileLogbookShowPage";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const {
    formatDate,
    formatDateShort,
    getStatusText,
    getStatusBadgeClass,
    getStatusDotClass,
    getProgressPercentage,
} = useProfileLogbookShowPage();
</script>

<template>
    <div>
        <Head :title="`Detail Logbook - ${logbook.title}`" />

        <div class="min-h-screen bg-gray-50">
            <ProfileLogbookShowTopBar
                :home-href="route('home')"
                :back-href="route('profile.edit')"
            />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <ProfileLogbookShowMainCard
                            :logbook="logbook"
                            :format-date-short="formatDateShort"
                            :get-status-badge-class="getStatusBadgeClass"
                            :get-status-dot-class="getStatusDotClass"
                            :get-status-text="getStatusText"
                            :get-progress-percentage="getProgressPercentage"
                        />

                        <ProfileLogbookShowFeedbackCard
                            :logbook="logbook"
                            :format-date="formatDate"
                        />
                    </div>

                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">
                            <ProfileLogbookShowInfoCard
                                :logbook="logbook"
                                :format-date="formatDate"
                                :format-date-short="formatDateShort"
                                :get-status-badge-class="getStatusBadgeClass"
                                :get-status-dot-class="getStatusDotClass"
                                :get-status-text="getStatusText"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
