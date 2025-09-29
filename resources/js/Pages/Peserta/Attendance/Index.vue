<template>
    <Head title="Absensi Online" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center space-x-6">
                        <Link
                            :href="route('profile.edit')"
                            class="text-blue-100 hover:text-white transition-colors flex items-center space-x-2 group"
                        >
                            <ArrowLeftIcon class="h-5 w-5 group-hover:transform group-hover:-translate-x-1 transition-transform" />
                            <span class="font-medium">Kembali ke Profile</span>
                        </Link>
                        <div class="h-8 w-px bg-blue-700"></div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center">
                                <CalendarIcon class="h-6 w-6 text-blue-900" />
                            </div>
                            <h1 class="text-2xl font-bold text-white tracking-tight">
                                Absensi Online
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-800/50 backdrop-blur-sm rounded-lg px-4 py-2 border border-blue-700">
                            <div class="text-sm text-blue-100 font-medium" id="current-time">
                                {{ formattedCurrentTime }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            <div
                v-if="$page.props.flash.success"
                class="mb-6 bg-green-50 border border-green-200 rounded-md p-4"
            >
                <div class="flex">
                    <CheckCircleIcon class="h-5 w-5 text-green-400" />
                    <div class="ml-3">
                        <p class="text-sm text-green-800">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                v-if="$page.props.flash.error"
                class="mb-6 bg-red-50 border border-red-200 rounded-md p-4"
            >
                <div class="flex">
                    <ExclamationCircleIcon class="h-5 w-5 text-red-400" />
                    <div class="ml-3">
                        <p class="text-sm text-red-800">
                            {{ $page.props.flash.error }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Attendance Actions -->
                <div class="space-y-6">
                    <!-- Today's Status Card -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Status Hari Ini
                            </h2>
                            <span class="text-sm text-gray-500">{{
                                todayFormatted
                            }}</span>
                        </div>

                        <div v-if="todayAttendance" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="text-center p-4 bg-blue-50 rounded-lg"
                                >
                                    <ClockIcon
                                        class="h-8 w-8 text-blue-600 mx-auto mb-2"
                                    />
                                    <p class="text-sm text-gray-600">
                                        Check-in
                                    </p>
                                    <p class="font-semibold text-blue-900">
                                        {{ todayAttendance.check_in || "-" }}
                                    </p>
                                </div>
                                <div
                                    class="text-center p-4 bg-blue-50 rounded-lg"
                                >
                                    <ClockIcon
                                        class="h-8 w-8 text-blue-600 mx-auto mb-2"
                                    />
                                    <p class="text-sm text-gray-600">
                                        Check-out
                                    </p>
                                    <p class="font-semibold text-blue-900">
                                        {{ todayAttendance.check_out || "-" }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-center">
                                <span
                                    :class="
                                        getStatusBadgeClass(
                                            todayAttendance.status
                                        )
                                    "
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ getStatusText(todayAttendance.status) }}
                                </span>
                            </div>
                        </div>

                        <div v-else class="text-center py-8">
                            <CalendarIcon
                                class="h-12 w-12 text-gray-400 mx-auto mb-4"
                            />
                            <p class="text-gray-500">
                                Belum ada absensi hari ini
                            </p>
                        </div>
                    </div>

                    <!-- Attendance Actions -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Aksi Absensi
                        </h2>

                        <div class="space-y-4">
                            <!-- Check-in Button -->
                            <button
                                @click="requestLocation('check-in')"
                                :disabled="!canCheckIn || isProcessing"
                                :class="[
                                    'w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm',
                                    canCheckIn && !isProcessing
                                        ? 'text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500'
                                        : 'text-gray-400 bg-gray-100 cursor-not-allowed',
                                ]"
                            >
                                <ArrowRightIcon
                                    v-if="
                                        isProcessing &&
                                        processingAction === 'check-in'
                                    "
                                    class="animate-spin h-4 w-4 mr-2"
                                />
                                <ArrowRightIcon v-else class="h-4 w-4 mr-2" />
                                {{
                                    isProcessing &&
                                    processingAction === "check-in"
                                        ? "Memproses..."
                                        : "Check-in"
                                }}
                            </button>

                            <!-- Check-out Button -->
                            <button
                                @click="requestLocation('check-out')"
                                :disabled="!canCheckOut || isProcessing"
                                :class="[
                                    'w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm',
                                    canCheckOut && !isProcessing
                                        ? 'text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500'
                                        : 'text-gray-400 bg-gray-100 cursor-not-allowed',
                                ]"
                            >
                                <ArrowLeftIcon
                                    v-if="
                                        isProcessing &&
                                        processingAction === 'check-out'
                                    "
                                    class="animate-spin h-4 w-4 mr-2"
                                />
                                <ArrowLeftIcon v-else class="h-4 w-4 mr-2" />
                                {{
                                    isProcessing &&
                                    processingAction === "check-out"
                                        ? "Memproses..."
                                        : "Check-out"
                                }}
                            </button>
                        </div>

                        <!-- Location Status -->
                        <div
                            v-if="locationStatus"
                            class="mt-4 p-3 rounded-md"
                            :class="
                                locationStatus.isValid
                                    ? 'bg-green-50'
                                    : 'bg-red-50'
                            "
                        >
                            <p
                                :class="
                                    locationStatus.isValid
                                        ? 'text-green-800'
                                        : 'text-red-800'
                                "
                                class="text-sm"
                            >
                                {{ locationStatus.message }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Statistics and History -->
                <div class="space-y-6">
                    <!-- Statistics -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Statistik Bulan Ini
                        </h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ stats.present_days }}
                                </div>
                                <div class="text-sm text-gray-600">Hadir</div>
                            </div>
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ stats.on_time }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Tepat Waktu
                                </div>
                            </div>
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ stats.late }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Terlambat
                                </div>
                            </div>
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ stats.absent }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Tidak Hadir
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent History -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Riwayat Absensi (30 Hari Terakhir)
                        </h2>

                        <div
                            v-if="attendanceHistory.length"
                            class="space-y-3 max-h-96 overflow-y-auto"
                        >
                            <div
                                v-for="attendance in attendanceHistory"
                                :key="attendance.id"
                                class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ attendance.date_formatted }}
                                    </p>
                                    <div
                                        class="flex items-center space-x-4 text-sm text-gray-600"
                                    >
                                        <span
                                            >Masuk:
                                            {{
                                                attendance.check_in || "-"
                                            }}</span
                                        >
                                        <span
                                            >Keluar:
                                            {{
                                                attendance.check_out || "-"
                                            }}</span
                                        >
                                    </div>
                                </div>
                                <span
                                    :class="
                                        getStatusBadgeClass(attendance.status)
                                    "
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                >
                                    {{ getStatusText(attendance.status) }}
                                </span>
                            </div>
                        </div>

                        <div v-else class="text-center py-8 text-gray-500">
                            Belum ada riwayat absensi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    ClockIcon,
    CalendarIcon,
    ArrowRightIcon,
    ArrowLeftIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    ArrowLeftIcon as BackIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    todayAttendance: Object,
    attendanceHistory: Array,
    stats: Object,
    officeLocation: Object,
    currentDateTime: String,
});

const currentTime = ref(new Date(props.currentDateTime));
const isProcessing = ref(false);
const processingAction = ref(null);
const locationStatus = ref(null);

// Update current time every second
let timeInterval;
onMounted(() => {
    timeInterval = setInterval(() => {
        currentTime.value = new Date();
    }, 1000);
});

onUnmounted(() => {
    if (timeInterval) {
        clearInterval(timeInterval);
    }
});

const formattedCurrentTime = computed(() => {
    return currentTime.value.toLocaleString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
});

const todayFormatted = computed(() => {
    return new Date().toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const canCheckIn = computed(() => {
    return !props.todayAttendance || !props.todayAttendance.check_in;
});

const canCheckOut = computed(() => {
    return (
        props.todayAttendance &&
        props.todayAttendance.check_in &&
        !props.todayAttendance.check_out
    );
});

const getStatusText = (status) => {
    const statusMap = {
        "On-Time": "Tepat Waktu",
        Late: "Terlambat",
        Absent: "Tidak Hadir",
        "Not-Checked-Out": "Belum Check-out",
    };
    return statusMap[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        "On-Time": "bg-green-100 text-green-800",
        Late: "bg-yellow-100 text-yellow-800",
        Absent: "bg-red-100 text-red-800",
        "Not-Checked-Out": "bg-blue-100 text-blue-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const requestLocation = (action) => {
    if (isProcessing.value) return;

    isProcessing.value = true;
    processingAction.value = action;
    locationStatus.value = null;

    if (!navigator.geolocation) {
        locationStatus.value = {
            isValid: false,
            message: "Browser Anda tidak mendukung geolocation",
        };
        isProcessing.value = false;
        processingAction.value = null;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const { latitude, longitude } = position.coords;

            // Calculate distance from office
            const distance = calculateDistance(
                props.officeLocation.latitude,
                props.officeLocation.longitude,
                latitude,
                longitude
            );

            if (distance <= props.officeLocation.radius) {
                locationStatus.value = {
                    isValid: true,
                    message: `Lokasi valid (${Math.round(
                        distance
                    )}m dari kantor)`,
                };

                // Submit the attendance
                submitAttendance(action, latitude, longitude);
            } else {
                locationStatus.value = {
                    isValid: false,
                    message: `Anda berada ${Math.round(
                        distance
                    )}m dari kantor. Absensi hanya dapat dilakukan dalam radius ${
                        props.officeLocation.radius
                    }m.`,
                };
                isProcessing.value = false;
                processingAction.value = null;
            }
        },
        (error) => {
            let message = "Tidak dapat mengakses lokasi Anda";
            if (error.code === error.PERMISSION_DENIED) {
                message =
                    "Izin akses lokasi ditolak. Silakan aktifkan GPS dan izinkan akses lokasi.";
            } else if (error.code === error.POSITION_UNAVAILABLE) {
                message =
                    "Informasi lokasi tidak tersedia. Pastikan GPS aktif.";
            } else if (error.code === error.TIMEOUT) {
                message = "Request timeout. Silakan coba lagi.";
            }

            locationStatus.value = {
                isValid: false,
                message: message,
            };
            isProcessing.value = false;
            processingAction.value = null;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        }
    );
};

const calculateDistance = (lat1, lon1, lat2, lon2) => {
    const R = 6371000; // meters
    const φ1 = (lat1 * Math.PI) / 180;
    const φ2 = (lat2 * Math.PI) / 180;
    const Δφ = ((lat2 - lat1) * Math.PI) / 180;
    const Δλ = ((lon2 - lon1) * Math.PI) / 180;

    const a =
        Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
};

const submitAttendance = (action, latitude, longitude) => {
    const routeName =
        action === "check-in"
            ? "profile.attendance.check-in"
            : "profile.attendance.check-out";

    router.post(
        route(routeName),
        {
            latitude: latitude,
            longitude: longitude,
        },
        {
            preserveScroll: true,
            onFinish: () => {
                isProcessing.value = false;
                processingAction.value = null;
                locationStatus.value = null;
            },
        }
    );
};
</script>
