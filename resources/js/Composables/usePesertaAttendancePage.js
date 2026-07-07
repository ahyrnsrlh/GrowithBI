import { ref, computed, watch, onMounted } from "vue";
import { useAttendanceServerClock } from "@/Composables/Attendance/useAttendanceServerClock";
import { useAttendanceFormatters } from "@/Composables/Attendance/useAttendanceFormatters";
import { useAttendanceCapture } from "@/Composables/Attendance/useAttendanceCapture";
import { useLocationSampler } from "@/Composables/Attendance/useLocationSampler";

export function usePesertaAttendancePage(props, page) {
    const attendanceList = computed(
        () => props.attendanceHistory || props.attendances || [],
    );

    const locationSampler = useLocationSampler({
        allowedRadius: props.allowedRadius,
        officeLocation: props.officeLocation,
    });

    const {
        showCamera,
        actionType,
        isProcessing,
        cameraTitle,
        handleCheckIn,
        handleCheckOut,
        onPhotoCaptured,
    } = useAttendanceCapture({
        locationSampler,
        onNotify: (type, msg) => {
            // Emulate backend errors if any
            if (type === 'error') {
                page.props.flash.error = msg;
                showErrorToast.value = true;
                setTimeout(() => { showErrorToast.value = false; }, 5000);
            }
        }
    });

    const { currentTime, isWithinCheckInTime, isWithinCheckOutTime } =
        useAttendanceServerClock();

    const { formatDate, formatDayName, formatTime } = useAttendanceFormatters();

    const showSuccessToast = ref(false);
    const showErrorToast   = ref(false);

    // ── Face enrollment ───────────────────────────────────────────────────────
    /** Reactive enrollment status; seeded from Inertia prop OR shared auth.face_enrolled */
    const faceEnrolled = ref(
        props.face_enrolled ?? page.props.auth?.face_enrolled ?? false
    );

    // Sync when the page prop updates (e.g. after Inertia reload)
    watch(
        () => props.face_enrolled,
        (val) => { if (val !== undefined) faceEnrolled.value = val; },
    );
    // Also sync from the globally shared auth prop
    watch(
        () => page.props.auth?.face_enrolled,
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
        // Location Verification
        locationSampler,
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
