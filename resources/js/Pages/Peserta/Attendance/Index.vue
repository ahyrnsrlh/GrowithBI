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

    <SimpleCameraModal
        :show="showCamera"
        :title="cameraTitle"
        @close="showCamera = false"
        @photo-captured="onPhotoCaptured"
    />
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import SimpleCameraModal from "@/Components/SimpleCameraModal.vue";
import PesertaAttendanceHeader from "@/Components/Peserta/Attendance/PesertaAttendanceHeader.vue";
import PesertaAttendanceToasts from "@/Components/Peserta/Attendance/PesertaAttendanceToasts.vue";
import PesertaTodayAttendanceCard from "@/Components/Peserta/Attendance/PesertaTodayAttendanceCard.vue";
import PesertaAttendanceHistoryCard from "@/Components/Peserta/Attendance/PesertaAttendanceHistoryCard.vue";
import { usePesertaAttendancePage } from "@/Composables/usePesertaAttendancePage";

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
    stats: Object,
    officeLocation: Object,
    currentDateTime: String,
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
} = usePesertaAttendancePage(props, page);
</script>

<style scoped>
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.bg-gradient-to-r.from-blue-700.to-blue-600 {
    position: relative;
}

.bg-gradient-to-r.from-blue-700.to-blue-600::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
    pointer-events: none;
}
</style>
