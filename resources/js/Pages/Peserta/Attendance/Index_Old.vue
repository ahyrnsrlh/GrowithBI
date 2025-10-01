<template>
    <Head title="Absensi Online" />

    <div
        class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50"
    >
        <!-- Header with Glass Morphism -->
        <div
            class="glass-card sticky top-0 z-50 shadow-lg border-b border-white/20"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center space-x-6">
                        <Link
                            :href="route('profile.edit')"
                            class="text-indigo-700 hover:text-indigo-900 transition-colors flex items-center space-x-2 group"
                        >
                            <ArrowLeftIcon
                                class="h-5 w-5 group-hover:transform group-hover:-translate-x-1 transition-transform"
                            />
                            <span class="font-medium">Kembali ke Profile</span>
                        </Link>
                        <div class="h-8 w-px bg-indigo-200"></div>
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg animate-float"
                            >
                                <CalendarIcon class="h-7 w-7 text-white" />
                            </div>
                            <h1
                                class="text-2xl font-bold gradient-text tracking-tight"
                            >
                                Absensi Online
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-white/80 backdrop-blur-sm rounded-xl px-5 py-3 border border-indigo-100 shadow-md"
                        >
                            <div
                                class="text-sm text-indigo-900 font-semibold"
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
                class="mb-8 glass-card rounded-2xl p-6 shadow-xl border-l-4 border-emerald-500 hover-glow"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                    >
                        <CheckCircleIcon class="h-7 w-7 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-emerald-900 font-semibold text-base">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                v-if="$page.props.flash.error"
                class="mb-8 glass-card rounded-2xl p-6 shadow-xl border-l-4 border-rose-500 hover-glow"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                    >
                        <ExclamationCircleIcon class="h-7 w-7 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-rose-900 font-semibold text-base">
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
                        class="glass-card rounded-2xl shadow-xl p-8 hover-glow"
                    >
                        <div class="flex items-center space-x-4 mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
                            >
                                <CalendarIcon class="h-7 w-7 text-white" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    Status Hari Ini
                                </h2>
                                <p class="text-sm text-indigo-600 font-medium">
                                    {{ todayFormatted }}
                                </p>
                            </div>
                        </div>

                        <div v-if="todayAttendance" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="relative bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-5 border border-emerald-200/50 shadow-md overflow-hidden"
                                >
                                    <div
                                        class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-200/30 rounded-full blur-2xl"
                                    ></div>
                                    <ClockIcon
                                        class="h-10 w-10 text-emerald-600 mb-3 relative z-10"
                                    />
                                    <p
                                        class="text-sm font-semibold text-emerald-700 mb-1 relative z-10"
                                    >
                                        Check-in
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-emerald-900 relative z-10"
                                    >
                                        {{ todayAttendance.check_in || "-" }}
                                    </p>
                                </div>
                                <div
                                    class="relative bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-5 border border-amber-200/50 shadow-md overflow-hidden"
                                >
                                    <div
                                        class="absolute -right-4 -top-4 w-20 h-20 bg-amber-200/30 rounded-full blur-2xl"
                                    ></div>
                                    <ClockIcon
                                        class="h-10 w-10 text-amber-600 mb-3 relative z-10"
                                    />
                                    <p
                                        class="text-sm font-semibold text-amber-700 mb-1 relative z-10"
                                    >
                                        Check-out
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-amber-900 relative z-10"
                                    >
                                        {{ todayAttendance.check_out || "-" }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-center mt-6">
                                <span
                                    :class="
                                        getStatusBadgeClass(
                                            todayAttendance.status
                                        )
                                    "
                                    class="inline-flex items-center px-6 py-3 rounded-2xl text-sm font-semibold shadow-lg"
                                >
                                    {{ getStatusText(todayAttendance.status) }}
                                </span>
                            </div>
                        </div>

                        <div v-else class="text-center py-10">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md"
                            >
                                <CalendarIcon class="h-10 w-10 text-gray-400" />
                            </div>
                            <p class="text-gray-600 font-semibold text-lg">
                                Belum ada absensi hari ini
                            </p>
                            <p class="text-sm text-gray-400 mt-2">
                                Lakukan check-in untuk memulai
                            </p>
                        </div>
                    </div>

                    <!-- Attendance Actions -->
                    <div
                        class="glass-card rounded-2xl shadow-xl p-8 hover-glow"
                    >
                        <div class="flex items-center space-x-4 mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg"
                            >
                                <ArrowRightIcon class="h-7 w-7 text-white" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">
                                Aksi Absensi
                            </h2>
                        </div>

                        <div class="space-y-4">
                            <!-- Check-in Button -->
                            <button
                                @click="requestLocation('check-in')"
                                :disabled="!canCheckIn || isProcessing"
                                :class="[
                                    'w-full flex items-center justify-center px-6 py-4 text-base font-semibold rounded-2xl transition-all duration-300 shadow-lg',
                                    canCheckIn && !isProcessing
                                        ? 'text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:scale-105 hover:shadow-2xl focus:ring-4 focus:ring-emerald-300'
                                        : 'text-gray-400 bg-gray-100 cursor-not-allowed',
                                ]"
                            >
                                <ArrowRightIcon
                                    v-if="
                                        isProcessing &&
                                        processingAction === 'check-in'
                                    "
                                    class="animate-spin h-5 w-5 mr-3"
                                />
                                <ArrowRightIcon v-else class="h-5 w-5 mr-3" />
                                {{
                                    isProcessing &&
                                    processingAction === "check-in"
                                        ? "Memproses..."
                                        : "Check-in Sekarang"
                                }}
                            </button>

                            <!-- Check-out Button -->
                            <button
                                @click="requestLocation('check-out')"
                                :disabled="!canCheckOut || isProcessing"
                                :class="[
                                    'w-full flex items-center justify-center px-6 py-4 text-base font-semibold rounded-2xl transition-all duration-300 shadow-lg',
                                    canCheckOut && !isProcessing
                                        ? 'text-white bg-gradient-to-r from-rose-500 to-red-600 hover:scale-105 hover:shadow-2xl focus:ring-4 focus:ring-rose-300'
                                        : 'text-gray-400 bg-gray-100 cursor-not-allowed',
                                ]"
                            >
                                <ArrowLeftIcon
                                    v-if="
                                        isProcessing &&
                                        processingAction === 'check-out'
                                    "
                                    class="animate-spin h-5 w-5 mr-3"
                                />
                                <ArrowLeftIcon v-else class="h-5 w-5 mr-3" />
                                {{
                                    isProcessing &&
                                    processingAction === "check-out"
                                        ? "Memproses..."
                                        : "Check-out Sekarang"
                                }}
                            </button>
                        </div>

                        <!-- Location Status -->
                        <div
                            v-if="locationStatus"
                            class="mt-6 p-4 rounded-2xl backdrop-blur-sm"
                            :class="
                                locationStatus.isValid
                                    ? 'bg-emerald-50/80 border-2 border-emerald-300'
                                    : 'bg-rose-50/80 border-2 border-rose-300'
                            "
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    :class="[
                                        'w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md',
                                        locationStatus.isValid
                                            ? 'bg-gradient-to-br from-emerald-500 to-green-600'
                                            : 'bg-gradient-to-br from-rose-500 to-red-600',
                                    ]"
                                >
                                    <CheckCircleIcon
                                        v-if="locationStatus.isValid"
                                        class="h-6 w-6 text-white"
                                    />
                                    <ExclamationCircleIcon
                                        v-else
                                        class="h-6 w-6 text-white"
                                    />
                                </div>
                                <p
                                    :class="
                                        locationStatus.isValid
                                            ? 'text-emerald-900'
                                            : 'text-rose-900'
                                    "
                                    class="text-sm font-semibold"
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
                        class="glass-card rounded-2xl shadow-xl p-8 hover-glow"
                    >
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg"
                            >
                                <ChartBarIcon class="h-7 w-7 text-white" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">
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
                        class="glass-card rounded-2xl shadow-xl p-8 hover-glow"
                    >
                        <div class="flex items-center space-x-4 mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg"
                            >
                                <ClockIcon class="h-7 w-7 text-white" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">
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
                                class="relative bg-gradient-to-r from-white to-gray-50 rounded-2xl p-5 border border-gray-200/50 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 overflow-hidden"
                            >
                                <div
                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100/30 to-purple-100/30 rounded-full blur-3xl -z-10"
                                ></div>
                                <div
                                    class="flex items-center justify-between relative z-10"
                                >
                                    <div class="flex-1">
                                        <p
                                            class="font-bold text-gray-900 mb-3 text-base"
                                        >
                                            {{ attendance.date_formatted }}
                                        </p>
                                        <div
                                            class="flex items-center space-x-6 text-sm"
                                        >
                                            <span
                                                class="flex items-center text-gray-700"
                                            >
                                                <span class="mr-2">Masuk:</span>
                                                <span
                                                    class="text-emerald-700 font-bold"
                                                    >{{
                                                        attendance.check_in ||
                                                        "-"
                                                    }}</span
                                                >
                                            </span>
                                            <span
                                                class="flex items-center text-gray-700"
                                            >
                                                <span class="mr-2"
                                                    >Keluar:</span
                                                >
                                                <span
                                                    class="text-amber-700 font-bold"
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
                                        class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold shadow-md"
                                    >
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md"
                            >
                                <ClockIcon class="h-10 w-10 text-gray-400" />
                            </div>
                            <p class="text-gray-600 font-semibold text-lg">
                                Belum ada riwayat absensi
                            </p>
                            <p class="text-sm text-gray-400 mt-2">
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
            :title="
                pendingAction === 'check-in'
                    ? 'Ambil Foto Check-in'
                    : 'Ambil Foto Check-out'
            "
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

/* Glass Morphism Effect */
.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Floating Animation */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Gradient Text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hover Glow Effect */
.hover-glow {
    transition: all 0.3s ease;
}

.hover-glow:hover {
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
    transform: translateY(-2px);
}
</style>
