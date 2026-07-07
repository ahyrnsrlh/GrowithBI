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

            <!--
                GATE: If face enrollment is incomplete, show the empty state instead of the
                attendance action card. The camera modal is also excluded to prevent any camera
                startup before biometric registration.
            -->
            <AttendanceEnrollmentEmptyState v-if="!faceEnrolled" />

            <template v-else>
                <LocationValidationWidget
                    :sampler="locationSampler"
                    :isProcessing="isProcessing"
                    :allowedRadius="allowedRadius"
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
            </template>

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

    <!-- Secure Camera Modal — only rendered when face is enrolled to prevent accidental camera startup -->
    <SecureCameraModal
        v-if="faceEnrolled"
        :show="showCamera"
        :title="cameraTitle"
        @close="showCamera = false"
        @photo-captured="onPhotoCaptured"
        @face-verified="onFaceVerified"
    />

</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import SecureCameraModal from "@/Components/Attendance/SecureCameraModal.vue";
import PesertaAttendanceHeader from "@/Components/Peserta/Attendance/PesertaAttendanceHeader.vue";
import PesertaAttendanceToasts from "@/Components/Peserta/Attendance/PesertaAttendanceToasts.vue";
import PesertaTodayAttendanceCard from "@/Components/Peserta/Attendance/PesertaTodayAttendanceCard.vue";
import PesertaAttendanceHistoryCard from "@/Components/Peserta/Attendance/PesertaAttendanceHistoryCard.vue";
import AttendanceEnrollmentEmptyState from "@/Components/Peserta/Attendance/AttendanceEnrollmentEmptyState.vue";
import LocationValidationWidget from "@/Components/Attendance/LocationValidationWidget.vue";
import { usePesertaAttendancePage } from "@/Composables/usePesertaAttendancePage";

const props = defineProps({
    attendances:       { type: Array,  default: () => [] },
    attendanceHistory: { type: Array,  default: () => [] },
    todayAttendance:   { type: Object, default: null },
    stats:             Object,
    officeLocation:    Object,
    allowedRadius:     { type: Number, default: 500 },
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
    onFaceVerified,
    faceEnrolled,
    locationSampler,
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

