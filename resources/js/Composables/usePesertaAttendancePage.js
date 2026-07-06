import { ref, computed, watch, onMounted } from "vue";
import { useAttendanceServerClock } from "@/Composables/Attendance/useAttendanceServerClock";
import { useAttendanceFormatters } from "@/Composables/Attendance/useAttendanceFormatters";
import { useAttendanceCapture } from "@/Composables/Attendance/useAttendanceCapture";

export function usePesertaAttendancePage(props, page) {
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
    const showErrorToast   = ref(false);

    // ── Face enrollment ───────────────────────────────────────────────────────
    /** Reactive enrollment status; seeded from Inertia prop, updated after enrollment */
    const faceEnrolled    = ref(props.face_enrolled ?? false);

    // Watch for prop changes
    watch(
        () => props.face_enrolled,
        (val) => { if (val !== undefined) faceEnrolled.value = val; },
    );

    // ── Location (GPS) status label for SecureCameraModal ────────────────────
    const locationStatus = ref(null);

    // ── Attendance filtering / pagination ─────────────────────────────────────
    const filterStatus = ref("all");
    const currentPage  = ref(1);
    const perPage      = 5;

    const filteredAttendance = computed(() => {
        if (filterStatus.value === "all") return attendanceList.value;
        return attendanceList.value.filter(
            (a) => a.status === filterStatus.value,
        );
    });

    const paginatedAttendance = computed(() => {
        const start = (currentPage.value - 1) * perPage;
        return filteredAttendance.value.slice(start, start + perPage);
    });

    const totalPages = computed(() =>
        Math.ceil(filteredAttendance.value.length / perPage),
    );

    const monthlyStats = computed(() => {
        const total  = attendanceList.value.length;
        const onTime = attendanceList.value.filter((a) => a.status === "On-Time").length;
        const late   = attendanceList.value.filter((a) => a.status === "Late").length;
        return { total, onTime, late };
    });

    // ── Toast helpers ─────────────────────────────────────────────────────────
    const showToastFor = (type) => {
        if (type === "success") {
            showSuccessToast.value = true;
            setTimeout(() => { showSuccessToast.value = false; }, 5000);
            return;
        }
        showErrorToast.value = true;
        setTimeout(() => { showErrorToast.value = false; }, 5000);
    };

    watch(() => page.props.flash?.success, (val) => { if (val) showToastFor("success"); });
    watch(() => page.props.flash?.error,   (val) => { if (val) showToastFor("error"); });
    watch(filterStatus, () => { currentPage.value = 1; });

    onMounted(() => {
        if (page.props.flash?.success) showToastFor("success");
        if (page.props.flash?.error)   showToastFor("error");
    });

    return {
        // Camera
        showCamera,
        actionType,
        isProcessing,
        cameraTitle,
        locationStatus,
        handleCheckIn,
        handleCheckOut,
        onPhotoCaptured,
        // Face enrollment
        faceEnrolled,
        // Toast
        showSuccessToast,
        showErrorToast,
        // Clock
        currentTime,
        isWithinCheckInTime,
        isWithinCheckOutTime,
        // Filters
        filterStatus,
        currentPage,
        perPage,
        filteredAttendance,
        paginatedAttendance,
        totalPages,
        monthlyStats,
        // Formatters
        formatDate,
        formatDayName,
        formatTime,
    };
}
