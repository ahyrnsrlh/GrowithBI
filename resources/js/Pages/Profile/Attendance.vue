<script setup>
import { usePage } from "@inertiajs/vue3";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import SimpleCameraModal from "@/Components/SimpleCameraModal.vue";
import AttendanceToasts from "@/Components/Profile/Attendance/AttendanceToasts.vue";
import AttendancePageHeader from "@/Components/Profile/Attendance/AttendancePageHeader.vue";
import AttendanceStatsCards from "@/Components/Profile/Attendance/AttendanceStatsCards.vue";
import TodayAttendanceStatusCard from "@/Components/Profile/Attendance/TodayAttendanceStatusCard.vue";
import AttendanceHistoryCard from "@/Components/Profile/Attendance/AttendanceHistoryCard.vue";
import { useProfileAttendancePage } from "@/Composables/useProfileAttendancePage";

const props = defineProps({
    attendances: {
        type: Array,
        default: () => [],
    },
    attendanceHistory: {
        type: Array,
        default: () => [],
    },
    todayAttendance: {
        type: Object,
        default: null,
    },
    hasAcceptedApplication: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();

const {
    showCamera,
    actionType,
    isProcessing,
    showSuccessToast,
    showErrorToast,
    currentTime,
    filterStatus,
    currentPage,
    perPage,
    cameraTitle,
    filteredAttendance,
    paginatedAttendance,
    totalPages,
    monthlyStats,
    isWithinCheckInTime,
    isWithinCheckOutTime,
    formatDate,
    formatDayName,
    formatTime,
    handleCheckIn,
    handleCheckOut,
    onPhotoCaptured,
} = useProfileAttendancePage(props, page);
</script>

<template>
    <ProfileLayout
        title="Absensi Online"
        :hasAcceptedApplication="hasAcceptedApplication"
    >
        <AttendanceToasts
            :successMessage="page.props.flash?.success"
            :errorMessage="page.props.flash?.error"
            :showSuccess="showSuccessToast"
            :showError="showErrorToast"
            @close-success="showSuccessToast = false"
            @close-error="showErrorToast = false"
        />

        <AttendancePageHeader :currentTime="currentTime" />

        <AttendanceStatsCards :stats="monthlyStats" />

        <TodayAttendanceStatusCard
            :todayAttendance="todayAttendance"
            :isProcessing="isProcessing"
            :actionType="actionType"
            :isWithinCheckInTime="isWithinCheckInTime"
            :isWithinCheckOutTime="isWithinCheckOutTime"
            :formatTime="formatTime"
            @check-in="handleCheckIn"
            @check-out="handleCheckOut"
        />

        <AttendanceHistoryCard
            :paginatedAttendance="paginatedAttendance"
            :filterStatus="filterStatus"
            :currentPage="currentPage"
            :perPage="perPage"
            :totalPages="totalPages"
            :filteredAttendance="filteredAttendance"
            :formatDate="formatDate"
            :formatDayName="formatDayName"
            :formatTime="formatTime"
            @update:filterStatus="filterStatus = $event"
            @update:currentPage="currentPage = $event"
        />

        <SimpleCameraModal
            :show="showCamera"
            :title="cameraTitle"
            @close="showCamera = false"
            @photo-captured="onPhotoCaptured"
        />
    </ProfileLayout>
</template>
