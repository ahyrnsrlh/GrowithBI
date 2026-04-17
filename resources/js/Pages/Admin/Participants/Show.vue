<template>
    <AdminLayout
        title="Detail Peserta"
        :subtitle="`Detail peserta ${participant.name}`"
    >
        <div class="max-w-6xl mx-auto space-y-6">
            <AdminParticipantShowHeader
                :participant="participant"
                :format-date="formatDate"
                @toggle-status="toggleStatus"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <AdminParticipantShowPersonalInfoCard
                        :participant="participant"
                    />

                    <AdminParticipantShowApplicationsCard
                        :applications="applications"
                        :format-date="formatDate"
                        :get-status-class="getStatusClass"
                        :get-status-text="getStatusText"
                    />

                    <AdminParticipantShowProgressCard
                        :accepted-application="acceptedApplication"
                    />
                </div>

                <div class="space-y-6">
                    <AdminParticipantShowStatusCard
                        :accepted-application="acceptedApplication"
                        :format-date="formatDate"
                    />

                    <AdminParticipantShowStatsCard
                        :participant="participant"
                        :total-applications="applications.length"
                        :accepted-count="acceptedCount"
                        :rejected-count="rejectedCount"
                    />

                    <AdminParticipantShowQuickActionsCard
                        :participant="participant"
                        :accepted-application="acceptedApplication"
                        @toggle-status="toggleStatus"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useAdminParticipantShowPage } from "@/Composables/useAdminParticipantShowPage";
import AdminParticipantShowHeader from "@/Components/Admin/Participants/Show/AdminParticipantShowHeader.vue";
import AdminParticipantShowPersonalInfoCard from "@/Components/Admin/Participants/Show/AdminParticipantShowPersonalInfoCard.vue";
import AdminParticipantShowApplicationsCard from "@/Components/Admin/Participants/Show/AdminParticipantShowApplicationsCard.vue";
import AdminParticipantShowProgressCard from "@/Components/Admin/Participants/Show/AdminParticipantShowProgressCard.vue";
import AdminParticipantShowStatusCard from "@/Components/Admin/Participants/Show/AdminParticipantShowStatusCard.vue";
import AdminParticipantShowStatsCard from "@/Components/Admin/Participants/Show/AdminParticipantShowStatsCard.vue";
import AdminParticipantShowQuickActionsCard from "@/Components/Admin/Participants/Show/AdminParticipantShowQuickActionsCard.vue";

const props = defineProps({
    participant: {
        type: Object,
        required: true,
    },
    applications: {
        type: Array,
        required: true,
    },
});

const participant = props.participant;
const applications = props.applications;

const {
    formatDate,
    getStatusClass,
    getStatusText,
    acceptedApplication,
    acceptedCount,
    rejectedCount,
    toggleStatus,
} = useAdminParticipantShowPage(participant, applications);
</script>
