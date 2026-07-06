<template>
    <Head title="Absensi Online" />

    <PesertaAttendanceHeader :currentTime="currentTime" />

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <PesertaAttendanceToasts
                :successMessage="page.props.flash?.success || ''"
                :errorMessage="page.props.flash?.error || ''"
                :showSuccess="showSuccessToast"
                :showError="showErrorToast"
                @close-success="showSuccessToast = false"
                @close-error="showErrorToast = false"
            />

            <PesertaTodayAttendanceCard
                :todayAttendance="todayAttendance"
                :isProcessing="isProcessing"
                :actionType="actionType"
                :isWithinCheckInTime="isWithinCheckInTime"
                :isWithinCheckOutTime="isWithinCheckOutTime"
                :formatTime="formatTime"
                :faceEnrolled="faceEnrolled"
                @check-in="handleCheckIn"
                @check-out="handleCheckOut"
            />

            <PesertaAttendanceHistoryCard
                :paginatedAttendance="paginatedAttendance"
                :filterStatus="filterStatus"
                :currentPage="currentPage"
                :perPage="perPage"
                :totalPages="totalPages"
                :filteredAttendance="filteredAttendance"
                :monthlyStats="monthlyStats"
                :formatDate="formatDate"
                :formatDayName="formatDayName"
                :formatTime="formatTime"
                @update:filterStatus="filterStatus = $event"
                @update:currentPage="currentPage = $event"
            />
        </div>
    </div>

    <!-- Secure Camera Modal (check-in / check-out) -->
    <SecureCameraModal
        :show="showCamera"
        :title="cameraTitle"
        :locationStatus="locationStatus"
        @close="showCamera = false"
        @photo-captured="onPhotoCaptured"
    />


</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import SecureCameraModal from "@/Components/Attendance/SecureCameraModal.vue";
import PesertaAttendanceHeader from "@/Components/Peserta/Attendance/PesertaAttendanceHeader.vue";
import PesertaAttendanceToasts from "@/Components/Peserta/Attendance/PesertaAttendanceToasts.vue";
import PesertaTodayAttendanceCard from "@/Components/Peserta/Attendance/PesertaTodayAttendanceCard.vue";
import PesertaAttendanceHistoryCard from "@/Components/Peserta/Attendance/PesertaAttendanceHistoryCard.vue";
import { usePesertaAttendancePage } from "@/Composables/usePesertaAttendancePage";

const props = defineProps({
    attendances:       { type: Array,  default: () => [] },
    attendanceHistory: { type: Array,  default: () => [] },
    todayAttendance:   { type: Object, default: null },
    stats:             Object,
    officeLocation:    Object,
    currentDateTime:   String,
    face_enrolled:     { type: Boolean, default: false },
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
    locationStatus,
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
    faceEnrolled,
} = usePesertaAttendancePage(props, page);
</script>

<style scoped>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
@keyframes slideOutRight {
    from { transform: translateX(0);    opacity: 1; }
    to   { transform: translateX(100%); opacity: 0; }
}
</style>
