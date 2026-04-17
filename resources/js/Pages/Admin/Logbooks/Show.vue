<template>
    <AdminLayout
        title="Review Logbook"
        subtitle="Detail dan review laporan harian peserta"
    >
        <div class="max-w-6xl mx-auto">
            <AdminLogbookShowHeader
                :logbook="logbook"
                :format-date="formatDate"
                :get-status-class="getStatusClass"
                :get-status-text="getStatusText"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <AdminLogbookShowDetailsCard :logbook="logbook" />

                    <AdminLogbookShowCommentsCard
                        :logbook="logbook"
                        :review-form="reviewForm"
                        :processing="processing"
                        :format-date="formatDate"
                        :get-comment-type-class="getCommentTypeClass"
                        :get-comment-type-text="getCommentTypeText"
                        @submit-status="submitWithStatus"
                    />
                </div>

                <div class="lg:col-span-1">
                    <AdminLogbookShowParticipantCard :logbook="logbook" />

                    <AdminLogbookShowTimelineCard
                        :logbook="logbook"
                        :format-date-time="formatDateTime"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useAdminLogbookShowPage } from "@/Composables/useAdminLogbookShowPage";
import AdminLogbookShowHeader from "@/Components/Admin/Logbooks/Show/AdminLogbookShowHeader.vue";
import AdminLogbookShowDetailsCard from "@/Components/Admin/Logbooks/Show/AdminLogbookShowDetailsCard.vue";
import AdminLogbookShowCommentsCard from "@/Components/Admin/Logbooks/Show/AdminLogbookShowCommentsCard.vue";
import AdminLogbookShowParticipantCard from "@/Components/Admin/Logbooks/Show/AdminLogbookShowParticipantCard.vue";
import AdminLogbookShowTimelineCard from "@/Components/Admin/Logbooks/Show/AdminLogbookShowTimelineCard.vue";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const logbook = props.logbook;
const {
    processing,
    reviewForm,
    submitWithStatus,
    formatDate,
    formatDateTime,
    getStatusClass,
    getStatusText,
    getCommentTypeClass,
    getCommentTypeText,
} = useAdminLogbookShowPage(logbook);
</script>
