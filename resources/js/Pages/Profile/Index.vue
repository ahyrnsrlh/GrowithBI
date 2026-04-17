<template>
    <Head title="Profil Saya" />

    <div>
        <ToastNotification
            :show="showNotification"
            :type="notificationType"
            :message="notificationMessage"
        />

        <div class="min-h-screen bg-gray-50">
            <ProfileHeader :user="$page.props.auth?.user" />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <SidebarNavigation
                        :activeTab="activeTab"
                        :applicationsCount="props.applications.length"
                        :hasAcceptedApplication="hasAcceptedApplication"
                        @update:activeTab="activeTab = $event"
                    />

                    <div class="lg:col-span-3">
                        <ProfileInfoTab
                            v-if="activeTab === 'profile'"
                            :user="user"
                            :profileForm="profileForm"
                            :editMode="editMode"
                            @toggle-edit="editMode = !editMode"
                            @cancel-edit="editMode = false"
                            @submit-profile="updateProfile"
                            @upload-photo="uploadPhoto"
                        />

                        <DocumentsTab
                            v-if="activeTab === 'documents'"
                            :user="user"
                            :documentProgress="documentProgress"
                            @upload-document="uploadDocument"
                        />

                        <ApplicationsTab
                            v-if="activeTab === 'applications'"
                            :applications="props.applications"
                        />

                        <div v-if="activeTab === 'attendance'">
                            <AttendanceTab
                                :attendanceHistory="props.attendanceHistory"
                                :todayAttendance="props.todayAttendance"
                                @show-toast="(type, msg) => showToast(type, msg)"
                            />
                        </div>

                        <LogbookTab
                            v-if="activeTab === 'logbook'"
                            :logbooks="props.logbooks"
                            :logbookStats="props.logbookStats"
                            :formatDate="formatDate"
                            @create-logbook="showCreateLogbookModal = true"
                            @view-logbook="viewLogbookDetail"
                            @edit-logbook="editLogbook"
                        />

                        <ReportsTab
                            v-if="activeTab === 'reports'"
                            :reports="props.reports"
                            :reportStats="props.reportStats"
                            :formatDate="formatDate"
                            @create-report="showCreateReportModal = true"
                        />
                    </div>
                </div>
            </div>

            <CreateLogbookModal
                :show="showCreateLogbookModal"
                :form="createLogbookForm"
                @close="showCreateLogbookModal = false"
                @submit="submitLogbook"
            />

            <CreateReportModal
                :show="showCreateReportModal"
                :form="reportForm"
                @close="showCreateReportModal = false"
                @submit="submitReport"
                @file-change="handleFileChange"
            />
        </div>
    </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AttendanceTab from "@/Components/AttendanceTab.vue";
import ApplicationsTab from "@/Components/Profile/ApplicationsTab.vue";
import CreateLogbookModal from "@/Components/Profile/CreateLogbookModal.vue";
import CreateReportModal from "@/Components/Profile/CreateReportModal.vue";
import DocumentsTab from "@/Components/Profile/DocumentsTab.vue";
import LogbookTab from "@/Components/Profile/Logbook/LogbookTab.vue";
import ProfileHeader from "@/Components/Profile/ProfileHeader.vue";
import ProfileInfoTab from "@/Components/Profile/ProfileInfoTab.vue";
import ReportsTab from "@/Components/Profile/Reports/ReportsTab.vue";
import SidebarNavigation from "@/Components/Profile/SidebarNavigation.vue";
import ToastNotification from "@/Components/Profile/ToastNotification.vue";
import { useProfilePage } from "@/Composables/useProfilePage";

const props = defineProps({
    user: Object,
    applications: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    logbooks: { type: Array, default: () => [] },
    logbookStats: { type: Object, default: () => ({}) },
    reports: { type: Array, default: () => [] },
    reportStats: { type: Object, default: () => ({}) },
    attendanceHistory: { type: Array, default: () => [] },
    todayAttendance: { type: Object, default: null },
    profileCompletion: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

const {
    user,
    activeTab,
    editMode,
    showNotification,
    notificationType,
    notificationMessage,
    showCreateLogbookModal,
    showCreateReportModal,
    hasAcceptedApplication,
    documentProgress,
    profileForm,
    createLogbookForm,
    reportForm,
    formatDate,
    showToast,
    viewLogbookDetail,
    editLogbook,
    updateProfile,
    uploadPhoto,
    submitLogbook,
    submitReport,
    handleFileChange,
    uploadDocument,
} = useProfilePage(props);
</script>
