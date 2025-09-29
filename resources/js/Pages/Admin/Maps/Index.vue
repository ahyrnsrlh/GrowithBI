<template>
    <Head title="Maps - Dashboard Admin" />

    <AdminLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20">
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center"
                                >
                                    <MapIcon class="h-6 w-6 text-blue-900" />
                                </div>
                                <div>
                                    <h1
                                        class="text-2xl font-bold text-white tracking-tight"
                                    >
                                        Maps Real-Time
                                    </h1>
                                    <p class="text-blue-100 text-sm">
                                        Monitoring Absensi Peserta Magang
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div
                                class="bg-blue-800/50 backdrop-blur-sm rounded-lg px-4 py-2 border border-blue-700"
                            >
                                <div class="text-sm text-blue-100 font-medium">
                                    {{ formattedCurrentDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
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

                <!-- Statistics Cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <UsersIcon class="h-5 w-5 text-white" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Total Hadir
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ stats.total_attendances }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-emerald-100/50 p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center"
                            >
                                <CheckCircleIcon class="h-5 w-5 text-white" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Absensi Valid
                                </p>
                                <p class="text-2xl font-bold text-emerald-600">
                                    {{ stats.valid_attendances }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-rose-100/50 p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-rose-600 rounded-lg flex items-center justify-center"
                            >
                                <ExclamationTriangleIcon
                                    class="h-5 w-5 text-white"
                                />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Absensi Tidak Valid
                                </p>
                                <p class="text-2xl font-bold text-rose-600">
                                    {{ stats.invalid_attendances }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/90 rounded-xl shadow-sm border border-amber-100/50 p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center"
                            >
                                <ClockIcon class="h-5 w-5 text-white" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Terlambat
                                </p>
                                <p class="text-2xl font-bold text-amber-600">
                                    {{ stats.late }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div
                    class="bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <MapPinIcon class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Peta Lokasi Absensi Real-Time
                                </h2>
                                <p class="text-sm text-gray-600">
                                    Monitoring lokasi absensi peserta magang
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="refreshData"
                                :disabled="isRefreshing"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            >
                                <ArrowPathIcon
                                    :class="[
                                        'h-4 w-4 mr-1',
                                        isRefreshing ? 'animate-spin' : '',
                                    ]"
                                />
                                Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="mb-4">
                        <div
                            class="flex flex-wrap items-center space-x-6 text-sm"
                        >
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-3 h-3 bg-blue-600 rounded-full"
                                ></div>
                                <span class="text-gray-600"
                                    >Kantor (Bank Indonesia KPw Lampung)</span
                                >
                            </div>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-3 h-3 bg-emerald-500 rounded-full"
                                ></div>
                                <span class="text-gray-600">Absensi Valid</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-3 h-3 bg-rose-500 rounded-full"
                                ></div>
                                <span class="text-gray-600"
                                    >Absensi Tidak Valid</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="relative">
                        <div
                            id="map"
                            class="w-full h-96 rounded-lg border border-gray-200"
                        ></div>

                        <!-- Loading overlay -->
                        <div
                            v-if="isLoading"
                            class="absolute inset-0 bg-white/75 backdrop-blur-sm rounded-lg flex items-center justify-center"
                        >
                            <div class="text-center">
                                <div
                                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"
                                ></div>
                                <p class="text-sm text-gray-600 mt-2">
                                    Memuat peta...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance List -->
                <div
                    class="mt-8 bg-white/90 rounded-xl shadow-sm border border-blue-100/50 p-6"
                >
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                        >
                            <ListBulletIcon class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Daftar Absensi Hari Ini
                        </h3>
                    </div>

                    <div v-if="attendances.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Peserta
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Divisi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Waktu Check-in
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Lokasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="attendance in attendances"
                                    :key="attendance.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ attendance.user_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ attendance.division }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ attendance.check_in_time }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="
                                                getStatusBadgeClass(
                                                    attendance.status
                                                )
                                            "
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        >
                                            {{
                                                getStatusText(attendance.status)
                                            }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="
                                                attendance.is_valid_location
                                                    ? 'text-emerald-600'
                                                    : 'text-rose-600'
                                            "
                                            class="inline-flex items-center text-sm font-medium"
                                        >
                                            <div
                                                :class="
                                                    attendance.is_valid_location
                                                        ? 'bg-emerald-500'
                                                        : 'bg-rose-500'
                                                "
                                                class="w-2 h-2 rounded-full mr-2"
                                            ></div>
                                            {{
                                                attendance.is_valid_location
                                                    ? "Valid"
                                                    : "Tidak Valid"
                                            }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="text-center py-8">
                        <div
                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <MapPinIcon class="h-8 w-8 text-gray-400" />
                        </div>
                        <p class="text-gray-500 font-medium">
                            Belum ada absensi hari ini
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            Absensi peserta akan muncul secara real-time di sini
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    MapIcon,
    MapPinIcon,
    UsersIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    ClockIcon,
    ArrowPathIcon,
    ListBulletIcon,
} from "@heroicons/vue/24/outline";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Fix Leaflet default markers
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
    iconUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
    shadowUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
});

const props = defineProps({
    attendances: Array,
    stats: Object,
    officeLocation: Object,
    currentDate: String,
});

const map = ref(null);
const markers = ref([]);
const isLoading = ref(true);
const isRefreshing = ref(false);
const attendances = ref(props.attendances);
const stats = ref(props.stats);

const formattedCurrentDate = computed(() => {
    return new Date(props.currentDate).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const initializeMap = () => {
    // Initialize map centered on office location
    map.value = L.map("map").setView(
        [props.officeLocation.latitude, props.officeLocation.longitude],
        15
    );

    // Add tile layer
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
    }).addTo(map.value);

    // Add office marker
    const officeIcon = L.divIcon({
        className: "custom-div-icon",
        html: `
            <div class="w-6 h-6 bg-blue-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <div class="w-2 h-2 bg-white rounded-full"></div>
            </div>
        `,
        iconSize: [24, 24],
        iconAnchor: [12, 12],
    });

    const officeMarker = L.marker(
        [props.officeLocation.latitude, props.officeLocation.longitude],
        { icon: officeIcon }
    ).addTo(map.value).bindPopup(`
            <div class="text-center">
                <h3 class="font-semibold text-gray-900">${props.officeLocation.name}</h3>
                <p class="text-sm text-gray-600">Kantor Pusat</p>
                <p class="text-xs text-gray-500 mt-1">Radius Valid: ${props.officeLocation.radius}m</p>
            </div>
        `);

    // Add office radius circle
    L.circle([props.officeLocation.latitude, props.officeLocation.longitude], {
        radius: props.officeLocation.radius,
        color: "#3b82f6",
        fillColor: "#3b82f6",
        fillOpacity: 0.1,
        weight: 2,
        dashArray: "5, 5",
    }).addTo(map.value);

    // Add attendance markers
    addAttendanceMarkers();

    isLoading.value = false;
};

const addAttendanceMarkers = () => {
    // Clear existing markers
    markers.value.forEach((marker) => map.value.removeLayer(marker));
    markers.value = [];

    // Add new markers
    attendances.value.forEach((attendance) => {
        const markerColor = attendance.is_valid_location
            ? "#10b981"
            : "#ef4444";

        const attendanceIcon = L.divIcon({
            className: "custom-div-icon",
            html: `
                <div class="w-6 h-6 rounded-full border-2 border-white shadow-lg flex items-center justify-center" style="background-color: ${markerColor}">
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                </div>
            `,
            iconSize: [24, 24],
            iconAnchor: [12, 12],
        });

        const marker = L.marker([attendance.latitude, attendance.longitude], {
            icon: attendanceIcon,
        }).addTo(map.value).bindPopup(`
                <div class="min-w-48">
                    <h3 class="font-semibold text-gray-900 mb-2">${
                        attendance.user_name
                    }</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-medium">Divisi:</span> ${
                            attendance.division
                        }</p>
                        <p><span class="font-medium">Check-in:</span> ${
                            attendance.check_in_time
                        }</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(
                                attendance.status
                            )}">
                                ${getStatusText(attendance.status)}
                            </span>
                        </p>
                        <p><span class="font-medium">Lokasi:</span> 
                            <span class="font-medium ${
                                attendance.is_valid_location
                                    ? "text-emerald-600"
                                    : "text-rose-600"
                            }">
                                ${
                                    attendance.is_valid_location
                                        ? "Valid"
                                        : "Tidak Valid"
                                }
                            </span>
                        </p>
                    </div>
                </div>
            `);

        markers.value.push(marker);
    });
};

const refreshData = async () => {
    isRefreshing.value = true;

    try {
        const response = await fetch(route("admin.attendance.locations.api"), {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        const data = await response.json();

        if (data.success) {
            attendances.value = data.data;
            stats.value = data.stats;
            addAttendanceMarkers();
        }
    } catch (error) {
        console.error("Error refreshing data:", error);
    } finally {
        isRefreshing.value = false;
    }
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

const getStatusText = (status) => {
    const statusMap = {
        "On-Time": "Tepat Waktu",
        Late: "Terlambat",
        Absent: "Tidak Hadir",
        "Not-Checked-Out": "Belum Check-out",
    };
    return statusMap[status] || status;
};

onMounted(() => {
    // Initialize map after component is mounted
    setTimeout(initializeMap, 100);

    // Set up auto-refresh every 30 seconds
    const refreshInterval = setInterval(refreshData, 30000);

    // Set up real-time event listener
    if (window.Echo) {
        window.Echo.channel("admin-maps").listen("AttendanceUpdated", (e) => {
            console.log("Real-time attendance update:", e);

            // Update attendances data
            const existingIndex = attendances.value.findIndex(
                (a) => a.id === e.attendance.id
            );
            if (existingIndex !== -1) {
                // Update existing attendance
                attendances.value[existingIndex] = e.attendance;
            } else {
                // Add new attendance
                attendances.value.push(e.attendance);
            }

            // Update stats
            stats.value = e.stats;

            // Update map markers
            addAttendanceMarkers();

            // Show notification
            showNotification(e.attendance);
        });
    } else {
        // Fallback to polling if Echo is not available
        console.log("WebSocket not available, using polling method");
    }

    // Cleanup interval and listeners on unmount
    onUnmounted(() => {
        clearInterval(refreshInterval);
        if (window.Echo) {
            window.Echo.leave("admin-maps");
        }
    });
});

// Show notification for real-time updates
const showNotification = (attendance) => {
    // You can customize this to show toast notifications
    console.log(
        `New attendance: ${attendance.user_name} - ${attendance.status}`
    );
};
</script>

<style scoped>
/* Custom styles for Leaflet map */
:deep(.leaflet-container) {
    font-family: "Inter", system-ui, sans-serif;
}

:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-tip) {
    box-shadow: none;
}

:deep(.custom-div-icon) {
    background: transparent;
    border: none;
}
</style>
