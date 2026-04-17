import { ref, computed, watch, onMounted } from "vue";
import { useAttendanceServerClock } from "@/Composables/Attendance/useAttendanceServerClock";
import { useAttendanceFormatters } from "@/Composables/Attendance/useAttendanceFormatters";
import { useAttendanceCapture } from "@/Composables/Attendance/useAttendanceCapture";

export function useProfileAttendancePage(props, page) {
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
    } = useAttendanceCapture();

    const { currentTime, isWithinCheckInTime, isWithinCheckOutTime } =
        useAttendanceServerClock();

    const { formatDate, formatDayName, formatTime } = useAttendanceFormatters();

    const showSuccessToast = ref(false);
    const showErrorToast = ref(false);

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

    const monthlyStats = computed(() => {
        const total = attendanceList.value.length;
        const onTime = attendanceList.value.filter(
            (attendance) => attendance.status === "On-Time",
        ).length;
        const late = attendanceList.value.filter(
            (attendance) => attendance.status === "Late",
        ).length;
        return { total, onTime, late };
    });

    const showToastFor = (type) => {
        if (type === "success") {
            showSuccessToast.value = true;
            setTimeout(() => {
                showSuccessToast.value = false;
            }, 5000);
            return;
        }

        showErrorToast.value = true;
        setTimeout(() => {
            showErrorToast.value = false;
        }, 5000);
    };

    watch(
        () => page.props.flash?.success,
        (value) => {
            if (value) {
                showToastFor("success");
            }
        },
    );

    watch(
        () => page.props.flash?.error,
        (value) => {
            if (value) {
                showToastFor("error");
            }
        },
    );

    watch(filterStatus, () => {
        currentPage.value = 1;
    });

    onMounted(() => {
        if (page.props.flash?.success) {
            showToastFor("success");
        }
        if (page.props.flash?.error) {
            showToastFor("error");
        }
    });

    return {
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
    };
}
