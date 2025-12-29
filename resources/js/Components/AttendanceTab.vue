<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SimpleCameraModal from "@/Components/SimpleCameraModal.vue";
import axios from "axios";

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
});

const emit = defineEmits(["show-toast"]);

const page = usePage();

// Use attendanceHistory if available, fallback to attendances
const attendanceList = computed(
    () => props.attendanceHistory || props.attendances || []
);

const showCamera = ref(false);
const actionType = ref("");
const isProcessing = ref(false);
const photoBase64 = ref(null);
const faceDescriptor = ref(null);
const userLocation = ref(null);

// Real-time clock - USING SERVER TIME
const currentTime = ref("");
const serverTime = ref({
    iso: "",
    timestamp: 0,
    formatted: { date: "", time: "", datetime: "", timezone: "WIB", full: "" },
    year: 0,
    month: 0,
    day: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
});
const lastSyncTime = ref(0);

// Check-in time range info
const checkInTimeRange = ref({
    check_in: { start: "07:30:00", end: "08:00:00" },
    check_out: { start: "16:00:00", end: "18:00:00" },
});

// Filter and Pagination
const filterStatus = ref("all");
const currentPage = ref(1);
const perPage = 5;

const cameraTitle = computed(() =>
    actionType.value === "check-in" ? "Foto Check-in" : "Foto Check-out"
);

// Filtered attendance based on status
const filteredAttendance = computed(() => {
    if (filterStatus.value === "all") {
        return attendanceList.value;
    }
    return attendanceList.value.filter(
        (att) => att.status === filterStatus.value
    );
});

// Paginated attendance
const paginatedAttendance = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredAttendance.value.slice(start, end);
});

// Total pages
const totalPages = computed(() => {
    return Math.ceil(filteredAttendance.value.length / perPage);
});

// Monthly stats
const monthlyStats = computed(() => {
    const total = attendanceList.value.length;
    const onTime = attendanceList.value.filter(
        (att) => att.status === "On-Time"
    ).length;
    const late = attendanceList.value.filter(
        (att) => att.status === "Late"
    ).length;
    return { total, onTime, late };
});

/**
 * Fetch server time from API
 */
const fetchServerTime = async () => {
    try {
        const response = await axios.get("/api/server-time");
        const serverTimeData = response.data;

        serverTime.value = {
            iso: serverTimeData.time,
            timestamp: serverTimeData.timestamp,
            formatted: serverTimeData.formatted,
            year: parseInt(serverTimeData.formatted.date.split("-")[0]),
            month: parseInt(serverTimeData.formatted.date.split("-")[1]) - 1,
            day: parseInt(serverTimeData.formatted.date.split("-")[2]),
            hours: parseInt(serverTimeData.formatted.time.split(":")[0]),
            minutes: parseInt(serverTimeData.formatted.time.split(":")[1]),
            seconds: parseInt(serverTimeData.formatted.time.split(":")[2]),
        };

        lastSyncTime.value = Date.now();
        updateClockDisplay();
    } catch (error) {
        console.error("Error fetching server time:", error);
        currentTime.value = "Tidak dapat terhubung ke server";
    }
};

/**
 * Fetch check-in time range configuration
 */
const fetchCheckInTimeRange = async () => {
    try {
        const response = await axios.get("/api/check-in-time-range");
        checkInTimeRange.value = response.data;
    } catch (error) {
        console.error("Error fetching check-in time range:", error);
    }
};

/**
 * Get current server time (synchronized)
 */
const getServerTime = () => {
    if (!serverTime.value || serverTime.value.timestamp === 0) {
        return null;
    }

    const now = Date.now();
    const elapsedMs = now - lastSyncTime.value;
    const elapsedSeconds = Math.floor(elapsedMs / 1000);

    let seconds = serverTime.value.seconds + elapsedSeconds;
    let minutes = serverTime.value.minutes;
    let hours = serverTime.value.hours;
    let day = serverTime.value.day;

    if (seconds >= 60) {
        minutes += Math.floor(seconds / 60);
        seconds = seconds % 60;
    }

    if (minutes >= 60) {
        hours += Math.floor(minutes / 60);
        minutes = minutes % 60;
    }

    if (hours >= 24) {
        day += Math.floor(hours / 24);
        hours = hours % 24;
    }

    return {
        hours,
        minutes,
        seconds,
        day,
        month: serverTime.value.month,
        year: serverTime.value.year,
    };
};

/**
 * Update clock display with server time
 */
const updateClockDisplay = () => {
    const now = getServerTime();

    if (!now) {
        currentTime.value = "Memuat waktu server...";
        return;
    }

    const days = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
    ];
    const months = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    const dateObj = new Date(now.year, now.month, now.day);
    const dayName = days[dateObj.getDay()];
    const monthName = months[now.month];

    const formattedDate = `${dayName}, ${now.day} ${monthName} ${now.year}`;
    const formattedTime = `${String(now.hours).padStart(2, "0")}:${String(
        now.minutes
    ).padStart(2, "0")}:${String(now.seconds).padStart(2, "0")}`;

    currentTime.value = `${formattedDate} ${formattedTime} WIB`;
};

/**
 * Check if current time is within allowed check-in range
 */
const isWithinCheckInTime = computed(() => {
    const now = getServerTime();
    if (!now) return false;

    const currentTimeInMinutes = now.hours * 60 + now.minutes;

    const [startHour, startMin] = checkInTimeRange.value.check_in.start
        .split(":")
        .map(Number);
    const [endHour, endMin] = checkInTimeRange.value.check_in.end
        .split(":")
        .map(Number);

    const startTimeInMinutes = startHour * 60 + startMin;
    const endTimeInMinutes = endHour * 60 + endMin;

    return (
        currentTimeInMinutes >= startTimeInMinutes &&
        currentTimeInMinutes <= endTimeInMinutes
    );
});

/**
 * Check if current time is within allowed check-out range
 */
const isWithinCheckOutTime = computed(() => {
    const now = getServerTime();
    if (!now) return false;

    const currentTimeInMinutes = now.hours * 60 + now.minutes;

    const [startHour, startMin] = checkInTimeRange.value.check_out.start
        .split(":")
        .map(Number);
    const [endHour, endMin] = checkInTimeRange.value.check_out.end
        .split(":")
        .map(Number);

    const startTimeInMinutes = startHour * 60 + startMin;
    const endTimeInMinutes = endHour * 60 + endMin;

    return (
        currentTimeInMinutes >= startTimeInMinutes &&
        currentTimeInMinutes <= endTimeInMinutes
    );
});

// Reset to page 1 when filter changes
watch(filterStatus, () => {
    currentPage.value = 1;
});

let clockInterval;
let serverSyncInterval;

onMounted(() => {
    fetchServerTime();
    fetchCheckInTimeRange();

    updateClockDisplay();
    clockInterval = setInterval(updateClockDisplay, 1000);

    serverSyncInterval = setInterval(fetchServerTime, 60000);
});

onUnmounted(() => {
    if (clockInterval) {
        clearInterval(clockInterval);
    }
    if (serverSyncInterval) {
        clearInterval(serverSyncInterval);
    }
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        let date;

        if (
            typeof dateString === "string" &&
            dateString.match(/^\d{4}-\d{2}-\d{2}/)
        ) {
            const parts = dateString.split(/[-\s:]/);
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            date = new Date(dateString);
        }

        if (isNaN(date.getTime())) {
            return dateString;
        }

        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    } catch (error) {
        console.error("Error formatting date:", error);
        return dateString;
    }
};

const formatDayName = (dateString) => {
    if (!dateString) return "-";
    try {
        let date;

        if (
            typeof dateString === "string" &&
            dateString.match(/^\d{4}-\d{2}-\d{2}/)
        ) {
            const parts = dateString.split(/[-\s:]/);
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            date = new Date(dateString);
        }

        if (isNaN(date.getTime())) {
            return "";
        }

        return date.toLocaleDateString("id-ID", {
            weekday: "long",
        });
    } catch (error) {
        console.error("Error formatting day name:", error);
        return "";
    }
};

const formatTime = (timeString) => {
    if (!timeString) return "-";
    try {
        if (typeof timeString === "string" && timeString.includes(" ")) {
            const timePart = timeString.split(" ")[1];
            if (timePart) {
                return timePart.substring(0, 5);
            }
        }

        if (
            typeof timeString === "string" &&
            timeString.match(/^\d{2}:\d{2}:\d{2}$/)
        ) {
            return timeString.substring(0, 5);
        }

        const date = new Date(timeString);

        if (isNaN(date.getTime())) {
            return timeString;
        }

        return date.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
            timeZone: "Asia/Jakarta",
        });
    } catch (error) {
        console.error("Error formatting time:", error, timeString);
        return timeString;
    }
};

const handleCheckIn = async () => {
    actionType.value = "check-in";
    await getLocation();
};

const handleCheckOut = async () => {
    actionType.value = "check-out";
    await getLocation();
};

const getLocation = async () => {
    if (!navigator.geolocation) {
        emit("show-toast", "error", "Geolocation tidak didukung");
        return;
    }

    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
            });
        });

        userLocation.value = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
        };

        showCamera.value = true;
    } catch (error) {
        emit(
            "show-toast",
            "error",
            "Gagal mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi."
        );
    }
};

const onPhotoCaptured = (data) => {
    if (typeof data === "string") {
        photoBase64.value = data;
    } else {
        photoBase64.value = data.photo;
        faceDescriptor.value = data.faceDescriptor;
    }
    submit();
};

const submit = () => {
    if (!photoBase64.value || !userLocation.value) {
        emit("show-toast", "error", "Data tidak lengkap");
        return;
    }

    if (!faceDescriptor.value || faceDescriptor.value.length !== 128) {
        emit(
            "show-toast",
            "error",
            "Face descriptor tidak valid. Silakan ambil foto ulang."
        );
        return;
    }

    const routeName =
        actionType.value === "check-in"
            ? "profile.attendance.check-in"
            : "profile.attendance.check-out";

    isProcessing.value = true;

    router.post(
        route(routeName),
        {
            photo: photoBase64.value,
            face_descriptor: faceDescriptor.value,
            latitude: userLocation.value.latitude,
            longitude: userLocation.value.longitude,
        },
        {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => {
                const successMessage =
                    actionType.value === "check-in"
                        ? "Check-in berhasil!"
                        : "Check-out berhasil!";
                isProcessing.value = false;
                photoBase64.value = null;
                faceDescriptor.value = null;
                userLocation.value = null;
                actionType.value = "";
                showCamera.value = false;
                emit("show-toast", "success", successMessage);
            },
            onError: (errors) => {
                isProcessing.value = false;
                emit(
                    "show-toast",
                    "error",
                    errors.error ||
                        errors.photo ||
                        errors.face_descriptor ||
                        "Gagal menyimpan absensi"
                );
            },
            onFinish: () => {
                isProcessing.value = false;
            },
        }
    );
};
</script>

<template>
    <div>
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25"
                    >
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Absensi Online
                        </h1>
                        <p class="text-gray-500 mt-0.5">
                            Kelola kehadiran Anda dengan mudah
                        </p>
                    </div>
                </div>

                <!-- Real-time Clock Badge -->
                <div
                    class="flex items-center gap-2 bg-slate-100 px-4 py-2.5 rounded-xl"
                >
                    <div
                        class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"
                    ></div>
                    <span class="text-sm font-medium text-slate-700">{{
                        currentTime
                    }}</span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ monthlyStats.total }}
                        </p>
                        <p class="text-xs text-gray-500">Total Hadir</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-emerald-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ monthlyStats.onTime }}
                        </p>
                        <p class="text-xs text-gray-500">Tepat Waktu</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ monthlyStats.late }}
                        </p>
                        <p class="text-xs text-gray-500">Terlambat</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Status Card -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8"
        >
            <!-- Card Header -->
            <div
                class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50"
            >
                <h2
                    class="text-lg font-semibold text-gray-900 flex items-center gap-2"
                >
                    <svg
                        class="w-5 h-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Status Hari Ini
                </h2>
            </div>

            <div class="p-6">
                <!-- Status Cards -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <!-- Check-in Card -->
                    <div
                        class="relative rounded-xl p-5 border-2 transition-all duration-300"
                        :class="
                            todayAttendance?.check_in
                                ? 'bg-emerald-50/50 border-emerald-200'
                                : 'bg-gray-50 border-gray-200'
                        "
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center"
                                        :class="
                                            todayAttendance?.check_in
                                                ? 'bg-emerald-100'
                                                : 'bg-gray-200'
                                        "
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            :class="
                                                todayAttendance?.check_in
                                                    ? 'text-emerald-600'
                                                    : 'text-gray-500'
                                            "
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                            />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium"
                                        :class="
                                            todayAttendance?.check_in
                                                ? 'text-emerald-700'
                                                : 'text-gray-600'
                                        "
                                        >Check-in</span
                                    >
                                </div>
                                <p
                                    class="text-3xl font-bold tracking-tight"
                                    :class="
                                        todayAttendance?.check_in
                                            ? 'text-emerald-700'
                                            : 'text-gray-300'
                                    "
                                >
                                    {{
                                        todayAttendance?.check_in
                                            ? formatTime(
                                                  todayAttendance.check_in
                                              )
                                            : "--:--"
                                    }}
                                </p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-full flex items-center justify-center"
                                :class="
                                    todayAttendance?.check_in
                                        ? 'bg-emerald-500'
                                        : 'bg-gray-200'
                                "
                            >
                                <svg
                                    v-if="todayAttendance?.check_in"
                                    class="w-7 h-7 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-7 h-7 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-out Card -->
                    <div
                        class="relative rounded-xl p-5 border-2 transition-all duration-300"
                        :class="
                            todayAttendance?.check_out
                                ? 'bg-blue-50/50 border-blue-200'
                                : 'bg-gray-50 border-gray-200'
                        "
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center"
                                        :class="
                                            todayAttendance?.check_out
                                                ? 'bg-blue-100'
                                                : 'bg-gray-200'
                                        "
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            :class="
                                                todayAttendance?.check_out
                                                    ? 'text-blue-600'
                                                    : 'text-gray-500'
                                            "
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium"
                                        :class="
                                            todayAttendance?.check_out
                                                ? 'text-blue-700'
                                                : 'text-gray-600'
                                        "
                                        >Check-out</span
                                    >
                                </div>
                                <p
                                    class="text-3xl font-bold tracking-tight"
                                    :class="
                                        todayAttendance?.check_out
                                            ? 'text-blue-700'
                                            : 'text-gray-300'
                                    "
                                >
                                    {{
                                        todayAttendance?.check_out
                                            ? formatTime(
                                                  todayAttendance.check_out
                                              )
                                            : "--:--"
                                    }}
                                </p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-full flex items-center justify-center"
                                :class="
                                    todayAttendance?.check_out
                                        ? 'bg-blue-500'
                                        : 'bg-gray-200'
                                "
                            >
                                <svg
                                    v-if="todayAttendance?.check_out"
                                    class="w-7 h-7 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-7 h-7 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <button
                            @click="handleCheckIn"
                            :disabled="
                                isProcessing ||
                                (todayAttendance && todayAttendance.check_in)
                            "
                            class="w-full h-14 rounded-xl font-semibold text-white transition-all duration-200 flex items-center justify-center gap-2 disabled:cursor-not-allowed"
                            :class="
                                todayAttendance?.check_in
                                    ? 'bg-gray-300 text-gray-500'
                                    : 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40'
                            "
                        >
                            <svg
                                v-if="isProcessing && actionType === 'check-in'"
                                class="animate-spin h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                />
                            </svg>
                            <span>{{
                                isProcessing && actionType === "check-in"
                                    ? "Memproses..."
                                    : todayAttendance?.check_in
                                    ? "Sudah Check-in"
                                    : "Check-in Sekarang"
                            }}</span>
                        </button>
                        <div
                            v-if="
                                !todayAttendance?.check_in &&
                                !isWithinCheckInTime
                            "
                            class="mt-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg"
                        >
                            <p
                                class="text-xs text-amber-700 text-center flex items-center justify-center gap-1"
                            >
                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                                Check-in hanya diperbolehkan antara 07:30 -
                                08:00 WIB
                            </p>
                        </div>
                    </div>

                    <div>
                        <button
                            @click="handleCheckOut"
                            :disabled="
                                isProcessing ||
                                !todayAttendance?.check_in ||
                                todayAttendance?.check_out
                            "
                            class="w-full h-14 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 disabled:cursor-not-allowed"
                            :class="
                                todayAttendance?.check_out
                                    ? 'bg-gray-300 text-gray-500'
                                    : !todayAttendance?.check_in
                                    ? 'bg-gray-100 text-gray-400 border-2 border-dashed border-gray-300'
                                    : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40'
                            "
                        >
                            <svg
                                v-if="
                                    isProcessing && actionType === 'check-out'
                                "
                                class="animate-spin h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                />
                            </svg>
                            <span>{{
                                isProcessing && actionType === "check-out"
                                    ? "Memproses..."
                                    : todayAttendance?.check_out
                                    ? "Sudah Check-out"
                                    : "Check-out Sekarang"
                            }}</span>
                        </button>
                        <div
                            v-if="
                                todayAttendance?.check_in &&
                                !todayAttendance?.check_out &&
                                !isWithinCheckOutTime
                            "
                            class="mt-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg"
                        >
                            <p
                                class="text-xs text-amber-700 text-center flex items-center justify-center gap-1"
                            >
                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                                Check-out hanya diperbolehkan antara 16:00 -
                                18:00 WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History Card -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
            <!-- Card Header -->
            <div
                class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50"
            >
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2"
                    >
                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                        Riwayat Absensi
                    </h2>

                    <!-- Filter Dropdown -->
                    <select
                        v-model="filterStatus"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                        <option value="all">Semua Status</option>
                        <option value="On-Time">Tepat Waktu</option>
                        <option value="Late">Terlambat</option>
                    </select>
                </div>
            </div>

            <div class="p-6">
                <!-- History List -->
                <div
                    v-if="paginatedAttendance && paginatedAttendance.length > 0"
                    class="space-y-3"
                >
                    <div
                        v-for="att in paginatedAttendance"
                        :key="att.id"
                        class="group bg-white rounded-xl p-4 border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-200"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center gap-4"
                        >
                            <!-- Date Column -->
                            <div
                                class="flex items-center gap-3 sm:min-w-[140px]"
                            >
                                <div
                                    class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 text-sm"
                                    >
                                        {{ formatDate(att.date) }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ formatDayName(att.date) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Time Columns -->
                            <div class="flex-1 grid grid-cols-2 gap-4">
                                <!-- Check-in Time -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-6 h-6 bg-emerald-100 rounded flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5 text-emerald-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-gray-400 font-medium"
                                        >
                                            Masuk
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            {{
                                                att.check_in
                                                    ? formatTime(att.check_in)
                                                    : "-"
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Check-out Time -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] uppercase tracking-wider text-gray-400 font-medium"
                                        >
                                            Pulang
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            {{
                                                att.check_out
                                                    ? formatTime(att.check_out)
                                                    : "-"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex-shrink-0">
                                <span
                                    v-if="att.status === 'On-Time'"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Tepat Waktu
                                </span>
                                <span
                                    v-else-if="att.status === 'Late'"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Terlambat
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                                >
                                    {{ att.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16">
                    <div
                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-gray-900 font-medium mb-1">
                        Belum ada riwayat absensi
                    </h3>
                    <p class="text-sm text-gray-500">
                        Lakukan check-in untuk memulai
                    </p>
                </div>

                <!-- Pagination -->
                <div
                    v-if="filteredAttendance.length > perPage"
                    class="mt-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4"
                >
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ (currentPage - 1) * perPage + 1 }} -
                        {{
                            Math.min(
                                currentPage * perPage,
                                filteredAttendance.length
                            )
                        }}
                        dari {{ filteredAttendance.length }}
                    </p>
                    <div class="flex items-center gap-1">
                        <button
                            @click="currentPage--"
                            :disabled="currentPage === 1"
                            class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>

                        <template v-for="page in totalPages" :key="page">
                            <button
                                @click="currentPage = page"
                                class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                                :class="
                                    page === currentPage
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-600 hover:bg-gray-100'
                                "
                            >
                                {{ page }}
                            </button>
                        </template>

                        <button
                            @click="currentPage++"
                            :disabled="currentPage === totalPages"
                            class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <SimpleCameraModal
            :show="showCamera"
            :title="cameraTitle"
            @close="showCamera = false"
            @photo-captured="onPhotoCaptured"
        />
    </div>
</template>
