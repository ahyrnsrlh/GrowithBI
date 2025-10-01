<template>
    <Head title="Absensi Online" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <!-- Minimal Header -->
        <div class="sticky top-0 z-50 backdrop-blur-md bg-white/60 border-b border-gray-200/50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <Link
                            :href="route('profile.edit')"
                            class="text-gray-600 hover:text-gray-900 transition-colors flex items-center space-x-2"
                        >
                            <ArrowLeftIcon class="h-5 w-5" />
                            <span class="text-sm font-medium">Kembali</span>
                        </Link>
                        <div class="h-5 w-px bg-gray-300"></div>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <CalendarIcon class="h-5 w-5 text-white" />
                            </div>
                            <h1 class="text-lg font-bold text-gray-900">Absensi Online</h1>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 border border-gray-200 shadow-sm">
                        <div class="text-xs font-medium text-gray-900">{{ formattedCurrentTime }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            <div
                v-if="$page.props.flash.success"
                class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center space-x-3"
            >
                <CheckCircleIcon class="h-5 w-5 text-emerald-600 flex-shrink-0" />
                <p class="text-sm font-medium text-emerald-800">{{ $page.props.flash.success }}</p>
            </div>

            <div
                v-if="$page.props.flash.error"
                class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-center space-x-3"
            >
                <ExclamationCircleIcon class="h-5 w-5 text-rose-600 flex-shrink-0" />
                <p class="text-sm font-medium text-rose-800">{{ $page.props.flash.error }}</p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-auto">
                <!-- Status Hari Ini - Large Card (spans 2 columns) -->
                <div class="md:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Status Hari Ini</h2>
                            <p class="text-xs text-gray-500 mt-1">{{ todayFormatted }}</p>
                        </div>
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <CalendarIcon class="h-6 w-6 text-indigo-600" />
                        </div>
                    </div>

                    <div v-if="todayAttendance" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Check-in -->
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                        <ClockIcon class="h-5 w-5 text-white" />
                                    </div>
                                    <span class="text-xs font-medium text-emerald-700">Check-in</span>
                                </div>
                                <p class="text-2xl font-bold text-emerald-900">{{ todayAttendance.check_in || "-" }}</p>
                            </div>

                            <!-- Check-out -->
                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                                        <ClockIcon class="h-5 w-5 text-white" />
                                    </div>
                                    <span class="text-xs font-medium text-amber-700">Check-out</span>
                                </div>
                                <p class="text-2xl font-bold text-amber-900">{{ todayAttendance.check_out || "-" }}</p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <span
                                :class="getStatusBadgeClass(todayAttendance.status)"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold"
                            >
                                {{ getStatusText(todayAttendance.status) }}
                            </span>
                        </div>
                    </div>

                    <div v-else class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <CalendarIcon class="h-8 w-8 text-gray-400" />
                        </div>
                        <p class="text-sm font-medium text-gray-600">Belum ada absensi hari ini</p>
                        <p class="text-xs text-gray-400 mt-1">Lakukan check-in untuk memulai</p>
                    </div>
                </div>

                <!-- Check-in Button - Tall Card -->
                <div class="md:row-span-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all">
                    <div class="flex flex-col h-full">
                        <div class="mb-auto">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <ArrowRightIcon class="h-7 w-7 text-white" />
                            </div>
                            <h3 class="text-white font-bold text-xl mb-2">Check-in</h3>
                            <p class="text-emerald-50 text-sm">Mulai absensi hari ini</p>
                        </div>

                        <button
                            @click="requestLocation('check-in')"
                            :disabled="!canCheckIn || isProcessing"
                            :class="[
                                'w-full py-4 rounded-xl font-semibold text-sm transition-all mt-6',
                                canCheckIn && !isProcessing
                                    ? 'bg-white text-emerald-600 hover:bg-emerald-50 shadow-lg'
                                    : 'bg-white/30 text-white/50 cursor-not-allowed'
                            ]"
                        >
                            <span v-if="isProcessing && processingAction === 'check-in'">Memproses...</span>
                            <span v-else>Ambil Absen</span>
                        </button>
                    </div>
                </div>

                <!-- Check-out Button - Tall Card -->
                <div class="md:row-span-2 bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all">
                    <div class="flex flex-col h-full">
                        <div class="mb-auto">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <ArrowLeftIcon class="h-7 w-7 text-white" />
                            </div>
                            <h3 class="text-white font-bold text-xl mb-2">Check-out</h3>
                            <p class="text-rose-50 text-sm">Akhiri absensi hari ini</p>
                        </div>

                        <button
                            @click="requestLocation('check-out')"
                            :disabled="!canCheckOut || isProcessing"
                            :class="[
                                'w-full py-4 rounded-xl font-semibold text-sm transition-all mt-6',
                                canCheckOut && !isProcessing
                                    ? 'bg-white text-rose-600 hover:bg-rose-50 shadow-lg'
                                    : 'bg-white/30 text-white/50 cursor-not-allowed'
                            ]"
                        >
                            <span v-if="isProcessing && processingAction === 'check-out'">Memproses...</span>
                            <span v-else>Ambil Absen</span>
                        </button>
                    </div>
                </div>

                <!-- Statistics Chart - Wide Card (spans 2 columns) -->
                <div class="md:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Statistik Bulan Ini</h2>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <ChartBarIcon class="h-6 w-6 text-blue-600" />
                        </div>
                    </div>

                    <div class="h-64">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </div>

                <!-- Quick Stats - Grid of 4 small cards -->
                <div class="bg-indigo-50 rounded-2xl border border-indigo-100 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <CalendarIcon class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xs font-medium text-indigo-700">Hadir</span>
                    </div>
                    <p class="text-3xl font-bold text-indigo-900">{{ stats.present_days }}</p>
                    <p class="text-xs text-indigo-600 mt-1">Hari</p>
                </div>

                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <CheckCircleIcon class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xs font-medium text-emerald-700">Tepat Waktu</span>
                    </div>
                    <p class="text-3xl font-bold text-emerald-900">{{ stats.on_time }}</p>
                    <p class="text-xs text-emerald-600 mt-1">Hari</p>
                </div>

                <div class="bg-amber-50 rounded-2xl border border-amber-100 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center">
                            <ClockIcon class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xs font-medium text-amber-700">Terlambat</span>
                    </div>
                    <p class="text-3xl font-bold text-amber-900">{{ stats.late }}</p>
                    <p class="text-xs text-amber-600 mt-1">Hari</p>
                </div>

                <div class="bg-purple-50 rounded-2xl border border-purple-100 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <ExclamationCircleIcon class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xs font-medium text-purple-700">Tidak Hadir</span>
                    </div>
                    <p class="text-3xl font-bold text-purple-900">{{ stats.absent }}</p>
                    <p class="text-xs text-purple-600 mt-1">Hari</p>
                </div>

                <!-- Location Status - Full width if active -->
                <div
                    v-if="locationStatus"
                    class="md:col-span-4 rounded-2xl p-4 border-2"
                    :class="locationStatus.isValid ? 'bg-emerald-50 border-emerald-300' : 'bg-rose-50 border-rose-300'"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            :class="[
                                'w-10 h-10 rounded-lg flex items-center justify-center',
                                locationStatus.isValid ? 'bg-emerald-500' : 'bg-rose-500'
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
                            :class="locationStatus.isValid ? 'text-emerald-900' : 'text-rose-900'"
                            class="text-sm font-semibold"
                        >
                            {{ locationStatus.message }}
                        </p>
                    </div>
                </div>

                <!-- Riwayat Absensi - Full width (spans 4 columns) -->
                <div class="md:col-span-4 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Riwayat Absensi</h2>
                            <p class="text-xs text-gray-400 mt-1">30 hari terakhir</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <ClockIcon class="h-6 w-6 text-purple-600" />
                        </div>
                    </div>

                    <div v-if="attendanceHistory.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto custom-scrollbar">
                        <div
                            v-for="attendance in attendanceHistory"
                            :key="attendance.id"
                            class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all"
                        >
                            <div class="flex items-start justify-between mb-3">
                                <p class="text-sm font-bold text-gray-900">{{ attendance.date_formatted }}</p>
                                <span
                                    :class="getStatusBadgeClass(attendance.status)"
                                    class="px-2 py-1 rounded-md text-xs font-semibold"
                                >
                                    {{ getStatusText(attendance.status) }}
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Masuk:</span>
                                    <span class="font-semibold text-emerald-700">{{ attendance.check_in || "-" }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Keluar:</span>
                                    <span class="font-semibold text-amber-700">{{ attendance.check_out || "-" }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <ClockIcon class="h-8 w-8 text-gray-400" />
                        </div>
                        <p class="text-sm font-medium text-gray-600">Belum ada riwayat absensi</p>
                        <p class="text-xs text-gray-400 mt-1">Riwayat akan muncul setelah melakukan absensi</p>
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

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

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
const showCameraModal = ref(false);
const pendingAction = ref(null);
const pendingLocation = ref(null);
const capturedPhoto = ref(null);

// Chart data
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
                "rgba(79, 70, 229, 0.9)",
                "rgba(16, 185, 129, 0.9)",
                "rgba(249, 115, 22, 0.9)",
                "rgba(139, 92, 246, 0.9)",
            ],
            borderRadius: 8,
            borderSkipped: false,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: "rgba(0, 0, 0, 0.8)",
            padding: 12,
            cornerRadius: 8,
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { font: { size: 11 }, color: "#6B7280" },
        },
        y: {
            beginAtZero: true,
            grid: { color: "rgba(156, 163, 175, 0.1)" },
            ticks: { font: { size: 11 }, color: "#6B7280", stepSize: 1 },
        },
    },
};

// Update time
let timeInterval;
onMounted(() => {
    timeInterval = setInterval(() => {
        currentTime.value = new Date();
    }, 1000);
});

onUnmounted(() => {
    if (timeInterval) clearInterval(timeInterval);
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
    return props.todayAttendance && props.todayAttendance.check_in && !props.todayAttendance.check_out;
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
            const distance = calculateDistance(
                props.officeLocation.latitude,
                props.officeLocation.longitude,
                latitude,
                longitude
            );

            if (distance <= props.officeLocation.radius) {
                locationStatus.value = {
                    isValid: true,
                    message: `Lokasi valid (${Math.round(distance)}m dari kantor)`,
                };
                pendingAction.value = action;
                pendingLocation.value = { latitude, longitude };
                showCameraModal.value = true;
                isProcessing.value = false;
                processingAction.value = null;
            } else {
                locationStatus.value = {
                    isValid: false,
                    message: `Anda berada ${Math.round(distance)}m dari kantor. Absensi hanya dapat dilakukan dalam radius ${props.officeLocation.radius}m.`,
                };
                isProcessing.value = false;
                processingAction.value = null;
            }
        },
        (error) => {
            let message = "Tidak dapat mengakses lokasi Anda";
            if (error.code === error.PERMISSION_DENIED) {
                message = "Izin akses lokasi ditolak. Silakan aktifkan GPS dan izinkan akses lokasi.";
            } else if (error.code === error.POSITION_UNAVAILABLE) {
                message = "Informasi lokasi tidak tersedia. Pastikan GPS aktif.";
            } else if (error.code === error.TIMEOUT) {
                message = "Request timeout. Silakan coba lagi.";
            }
            locationStatus.value = { isValid: false, message: message };
            isProcessing.value = false;
            processingAction.value = null;
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
};

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
    pendingAction.value = null;
    pendingLocation.value = null;
};

const handleCameraModalClose = () => {
    showCameraModal.value = false;
    pendingAction.value = null;
    pendingLocation.value = null;
    capturedPhoto.value = null;
};

const calculateDistance = (lat1, lon1, lat2, lon2) => {
    const R = 6371000;
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
        { latitude, longitude, photo },
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
    scrollbar-color: rgba(99, 102, 241, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.3);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(99, 102, 241, 0.5);
}
</style>
