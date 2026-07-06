<script setup>
import { computed, ref, watch } from "vue";
import SecureCameraModal from "@/Components/Attendance/SecureCameraModal.vue";
import FaceEnrollmentModal from "@/Components/Attendance/FaceEnrollmentModal.vue";
import AttendanceHistoryCard from "@/Components/Profile/Attendance/AttendanceHistoryCard.vue";
import AttendancePageHeader from "@/Components/Profile/Attendance/AttendancePageHeader.vue";
import TodayAttendanceStatusCard from "@/Components/Profile/Attendance/TodayAttendanceStatusCard.vue";
import { useAttendanceCapture } from "@/Composables/Attendance/useAttendanceCapture";
import { useAttendanceFormatters } from "@/Composables/Attendance/useAttendanceFormatters";
import { useAttendanceServerClock } from "@/Composables/Attendance/useAttendanceServerClock";

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
    faceEnrolled: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["show-toast"]);

const attendanceList = computed(
    () => props.attendanceHistory || props.attendances || [],
);

const {
    showCamera,
    actionType,
    isProcessing,
    cameraTitle,
    handleCheckIn,
    handleCheckOut,
    onPhotoCaptured,
} = useAttendanceCapture({
    onNotify: (type, message) => {
        emit("show-toast", type, message);
    },
    onSuccess: (action) => {
        const successMessage =
            action === "check-in"
                ? "Check-in berhasil!"
                : "Check-out berhasil!";
        emit("show-toast", "success", successMessage);
    },
});

const { currentTime, isWithinCheckInTime, isWithinCheckOutTime } =
    useAttendanceServerClock();
const { formatDate, formatDayName, formatTime } = useAttendanceFormatters();

// Face enrollment (Profile tab also supports enrollment)
const showEnrollment  = ref(false);
const localFaceEnrolled = ref(props.faceEnrolled);

watch(() => props.faceEnrolled, (val) => { localFaceEnrolled.value = val; });

const onEnrolled = () => {
    localFaceEnrolled.value = true;
    showEnrollment.value = false;
    emit("show-toast", "success", "Wajah berhasil didaftarkan!");
};

const filterStatus = ref("all");
const currentPage = ref(1);
const perPage = 5;

const filteredAttendance = computed(() => {
    if (filterStatus.value === "all") {
        return attendanceList.value;
    }
    return attendanceList.value.filter(
        (attendance) => attendance.status === filterStatus.value,
    );
});

const paginatedAttendance = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredAttendance.value.slice(start, end);
});

const totalPages = computed(() =>
    Math.ceil(filteredAttendance.value.length / perPage),
);

watch(filterStatus, () => {
    currentPage.value = 1;
});
</script>

<template>
    <div>
        <AttendancePageHeader :currentTime="currentTime" />

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

        <!-- Secure Camera Modal (check-in / check-out) -->
        <SecureCameraModal
            :show="showCamera"
            :title="cameraTitle"
            @close="showCamera = false"
            @photo-captured="onPhotoCaptured"
        />

        <!-- Face Enrollment Modal -->
        <FaceEnrollmentModal
            :show="showEnrollment"
            @close="showEnrollment = false"
            @enrolled="onEnrolled"
        />
    </div>
</template>
