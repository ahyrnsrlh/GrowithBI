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
                            <ArrowLeftIcon
                                class="h-5 w-5 group-hover:transform group-hover:-translate-x-1 transition-transform"
                            />
                            <span class="font-medium">Kembali ke Profile</span>
                        </Link>
                        <div class="h-8 w-px bg-blue-700"></div>
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center"
                            >
                                <CalendarIcon class="h-6 w-6 text-blue-900" />
                            </div>
                            <h1
                                class="text-2xl font-bold text-white tracking-tight"
                            >
                                Absensi Online
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-blue-800/50 backdrop-blur-sm rounded-lg px-4 py-2 border border-blue-700"
                        >
                            <div
                                class="text-sm text-blue-100 font-medium"
                                id="current-time"
                            >
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
                class="mb-8 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-l-4 border-emerald-500 rounded-xl p-6 shadow-lg"
            >
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0"
                    >
                        <CheckCircleIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-emerald-800 font-medium">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                v-if="$page.props.flash.error"
                class="mb-8 bg-gradient-to-r from-rose-50 to-rose-100/50 border-l-4 border-rose-500 rounded-xl p-6 shadow-lg"
            >
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center flex-shrink-0"
                    >
                        <ExclamationCircleIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-rose-800 font-medium">
                            {{ $page.props.flash.error }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Attendance Actions -->
                <div class="space-y-6">
                    <!-- Today's Status Card -->
                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                    >
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <CalendarIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Status Hari Ini
                                </h2>
                                <p class="text-sm text-gray-500">
                                    {{ todayFormatted }}
                                </p>
                            </div>
                        </div>

                        <div v-if="todayAttendance" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-emerald-50 rounded-lg p-4 border border-emerald-100"
                                >
                                    <ClockIcon
                                        class="h-8 w-8 text-emerald-600 mb-2"
                                    />
                                    <p
                                        class="text-sm font-medium text-emerald-700 mb-1"
                                    >
                                        Check-in
                                    </p>
                                    <p
                                        class="text-lg font-bold text-emerald-900"
                                    >
                                        {{ todayAttendance.check_in || "-" }}
                                    </p>
                                </div>
                                <div
                                    class="bg-amber-50 rounded-lg p-4 border border-amber-100"
                                >
                                    <ClockIcon
                                        class="h-8 w-8 text-amber-600 mb-2"
                                    />
                                    <p
                                        class="text-sm font-medium text-amber-700 mb-1"
                                    >
                                        Check-out
                                    </p>
                                    <p class="text-lg font-bold text-amber-900">
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
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium"
                                >
                                    {{ getStatusText(todayAttendance.status) }}
                                </span>
                            </div>
                        </div>

                        <div v-else class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <CalendarIcon class="h-8 w-8 text-gray-400" />
                            </div>
                            <p class="text-gray-500 font-medium">
                                Belum ada absensi hari ini
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                Lakukan check-in untuk memulai
                            </p>
                        </div>
                    </div>

                    <!-- Attendance Actions -->
                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                    >
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <ArrowRightIcon class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Aksi Absensi
                            </h2>
                        </div>

                        <div class="space-y-3">
                            <!-- Check-in Button -->
                            <button
                                @click="requestLocation('check-in')"
                                :disabled="!canCheckIn || isProcessing"
                                :class="[
                                    'w-full flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200',
                                    canCheckIn && !isProcessing
                                        ? 'text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500'
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
                                    'w-full flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200',
                                    canCheckOut && !isProcessing
                                        ? 'text-white bg-rose-600 hover:bg-rose-700 focus:ring-2 focus:ring-rose-500'
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
                            class="mt-4 p-3 rounded-lg"
                            :class="
                                locationStatus.isValid
                                    ? 'bg-emerald-50 border border-emerald-200'
                                    : 'bg-rose-50 border border-rose-200'
                            "
                        >
                            <div class="flex items-center space-x-2">
                                <div
                                    :class="[
                                        'w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0',
                                        locationStatus.isValid
                                            ? 'bg-emerald-500'
                                            : 'bg-rose-500',
                                    ]"
                                >
                                    <CheckCircleIcon
                                        v-if="locationStatus.isValid"
                                        class="h-3 w-3 text-white"
                                    />
                                    <ExclamationCircleIcon
                                        v-else
                                        class="h-3 w-3 text-white"
                                    />
                                </div>
                                <p
                                    :class="
                                        locationStatus.isValid
                                            ? 'text-emerald-800'
                                            : 'text-rose-800'
                                    "
                                    class="text-sm font-medium"
                                >
                                    {{ locationStatus.message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Statistics and History -->
                <div class="space-y-6">
                    <!-- Statistics -->
                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                    >
                        <div class="flex items-center space-x-3 mb-8">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <ChartBarIcon class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Statistik Bulan Ini
                            </h2>
                        </div>

                        <div class="flex justify-center">
                            <!-- Bar Chart Container -->
                            <div class="w-full h-80">
                                <Bar
                                    :data="chartData"
                                    :options="chartOptions"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Recent History -->
                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                    >
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <ClockIcon class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Riwayat Absensi (30 Hari Terakhir)
                            </h2>
                        </div>

                        <div
                            v-if="attendanceHistory.length"
                            class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar"
                        >
                            <div
                                v-for="attendance in attendanceHistory"
                                :key="attendance.id"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p
                                            class="font-medium text-gray-900 mb-2"
                                        >
                                            {{ attendance.date_formatted }}
                                        </p>
                                        <div
                                            class="flex items-center space-x-4 text-sm"
                                        >
                                            <span class="text-gray-600">
                                                Masuk:
                                                <span
                                                    class="text-emerald-700 font-medium"
                                                    >{{
                                                        attendance.check_in ||
                                                        "-"
                                                    }}</span
                                                >
                                            </span>
                                            <span class="text-gray-600">
                                                Keluar:
                                                <span
                                                    class="text-amber-700 font-medium"
                                                    >{{
                                                        attendance.check_out ||
                                                        "-"
                                                    }}</span
                                                >
                                            </span>
                                        </div>
                                    </div>
                                    <span
                                        :class="
                                            getStatusBadgeClass(
                                                attendance.status
                                            )
                                        "
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <ClockIcon class="h-8 w-8 text-gray-400" />
                            </div>
                            <p class="text-gray-500 font-medium">
                                Belum ada riwayat absensi
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                Riwayat akan muncul setelah melakukan absensi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Camera Modal -->
        <CameraModal
            :show="showCameraModal"
            :title="pendingAction === 'check-in' ? 'Ambil Foto Check-in' : 'Ambil Foto Check-out'"
            @close="handleCameraModalClose"
            @captured="handlePhotoCaptured"
        />
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
    ChartBarIcon,
} from "@heroicons/vue/24/outline";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";
import CameraModal from "@/Components/CameraModal.vue";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

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

// Camera modal state
const showCameraModal = ref(false);
const pendingAction = ref(null);
const pendingLocation = ref(null);
const capturedPhoto = ref(null);

// Chart data for bar chart
const chartData = computed(() => ({
    labels: ["Hadir", "Tepat Waktu", "Terlambat", "Tidak Hadir"],
    datasets: [
        {
            label: "Jumlah Hari",
            data: [
                props.stats.present_days,
                props.stats.on_time,
                props.stats.late,
                props.stats.absent,
            ],
            backgroundColor: [
                "rgba(79, 70, 229, 0.8)", // Indigo - Total
                "rgba(16, 185, 129, 0.8)", // Emerald - Tepat Waktu
                "rgba(249, 115, 22, 0.8)", // Orange - Terlambat
                "rgba(139, 92, 246, 0.8)", // Purple - Tidak Hadir
            ],
            borderColor: [
                "rgb(79, 70, 229)",
                "rgb(16, 185, 129)",
                "rgb(249, 115, 22)",
                "rgb(139, 92, 246)",
            ],
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false, // Hide legend for cleaner look
        },
        tooltip: {
            backgroundColor: "rgba(0, 0, 0, 0.8)",
            titleColor: "white",
            bodyColor: "white",
            borderColor: "rgba(255, 255, 255, 0.1)",
            borderWidth: 1,
            bodyFont: {
                size: 13,
                family: "Inter, system-ui, sans-serif",
            },
            titleFont: {
                size: 14,
                family: "Inter, system-ui, sans-serif",
                weight: "600",
            },
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
            ticks: {
                font: {
                    size: 12,
                    family: "Inter, system-ui, sans-serif",
                    weight: "500",
                },
                color: "#6B7280",
            },
        },
        y: {
            beginAtZero: true,
            grid: {
                color: "rgba(156, 163, 175, 0.2)",
                borderDash: [5, 5],
            },
            ticks: {
                font: {
                    size: 12,
                    family: "Inter, system-ui, sans-serif",
                },
                color: "#6B7280",
                stepSize: 1,
            },
        },
    },
    layout: {
        padding: {
            top: 20,
            bottom: 10,
            left: 10,
            right: 10,
        },
    },
};

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
        "On-Time": "bg-emerald-100 text-emerald-800",
        Late: "bg-amber-100 text-amber-800",
        Absent: "bg-rose-100 text-rose-800",
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

                // Open camera modal for photo capture
                pendingAction.value = action;
                pendingLocation.value = { latitude, longitude };
                showCameraModal.value = true;
                isProcessing.value = false;
                processingAction.value = null;
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

// Handle photo captured from camera modal
const handlePhotoCaptured = (photoBase64) => {
    capturedPhoto.value = photoBase64;
    
    if (pendingAction.value && pendingLocation.value) {
        submitAttendance(
            pendingAction.value,
            pendingLocation.value.latitude,
            pendingLocation.value.longitude,
            photoBase64
        );
    }
    
    // Reset pending state
    pendingAction.value = null;
    pendingLocation.value = null;
};

// Handle camera modal closed
const handleCameraModalClose = () => {
    showCameraModal.value = false;
    pendingAction.value = null;
    pendingLocation.value = null;
    capturedPhoto.value = null;
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

const submitAttendance = (action, latitude, longitude, photo) => {
    const routeName =
        action === "check-in"
            ? "profile.attendance.check-in"
            : "profile.attendance.check-out";

    isProcessing.value = true;
    processingAction.value = action;

    router.post(
        route(routeName),
        {
            latitude: latitude,
            longitude: longitude,
            photo: photo,
        },
        {
            preserveScroll: true,
            onFinish: () => {
                isProcessing.value = false;
                processingAction.value = null;
                locationStatus.value = null;
                capturedPhoto.value = null;
            },
        }
    );
};
</script>

<style scoped>
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.3);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.5);
}
</style>
