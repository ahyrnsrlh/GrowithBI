<template>
    <Head title="Maps - Dashboard Admin" />

    <AdminLayout title="Maps Real-Time">
        <!-- Page Header -->
        <div class="mb-8">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 text-sm text-gray-500"
                        >
                            <li class="inline-flex items-center">
                                <a
                                    href="/admin/dashboard"
                                    class="hover:text-blue-600 transition-colors"
                                >
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 mx-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span class="text-gray-700 font-medium"
                                        >Maps Real-Time</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Maps Real-Time
                    </h1>
                    <p class="mt-1 text-gray-500">
                        Monitoring lokasi absensi peserta magang secara
                        real-time
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Date Badge -->
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700"
                    >
                        <svg
                            class="w-4 h-4 text-gray-500"
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
                        {{ formattedCurrentDate }}
                    </div>
                    <!-- Live Indicator -->
                    <div
                        class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl"
                    >
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                            ></span>
                            <span
                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"
                            ></span>
                        </span>
                        <span class="text-sm font-medium text-emerald-700"
                            >Live</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div
            v-if="$page.props.flash.success"
            class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4"
        >
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0"
                >
                    <CheckCircleIcon class="h-5 w-5 text-emerald-600" />
                </div>
                <p class="text-emerald-800 font-medium text-sm">
                    {{ $page.props.flash.success }}
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Hadir Card -->
            <div
                class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <UsersIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white/90">
                            Total Hadir
                        </p>
                        <h3 class="text-2xl font-bold text-white">
                            {{ stats.total_attendances }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">
                        Total peserta hadir hari ini
                    </p>
                </div>
            </div>

            <!-- Absensi Valid Card -->
            <div
                class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <CheckCircleIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white/90">
                            Absensi Valid
                        </p>
                        <h3 class="text-2xl font-bold text-white">
                            {{ stats.valid_attendances }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">
                        Dalam radius lokasi kantor
                    </p>
                </div>
            </div>

            <!-- Absensi Tidak Valid Card -->
            <div
                class="bg-gradient-to-br from-rose-500 via-red-500 to-pink-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <ExclamationTriangleIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white/90">
                            Tidak Valid
                        </p>
                        <h3 class="text-2xl font-bold text-white">
                            {{ stats.invalid_attendances }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">Di luar radius lokasi</p>
                </div>
            </div>

            <!-- Terlambat Card -->
            <div
                class="bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-600 rounded-2xl p-6 shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <ClockIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white/90">
                            Terlambat
                        </p>
                        <h3 class="text-2xl font-bold text-white">
                            {{ stats.late }}
                        </h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70">Peserta yang terlambat</p>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6"
        >
            <!-- Map Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                        >
                            <MapPinIcon class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">
                                Peta Lokasi Absensi
                            </h2>
                            <p class="text-sm text-white/80">
                                Monitoring real-time lokasi peserta
                            </p>
                        </div>
                    </div>
                    <button
                        @click="refreshData"
                        :disabled="isRefreshing"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 disabled:bg-white/10 backdrop-blur-sm text-white text-sm font-medium rounded-xl transition-all duration-200 border border-white/20 disabled:cursor-not-allowed"
                    >
                        <ArrowPathIcon
                            :class="[
                                'h-4 w-4',
                                isRefreshing ? 'animate-spin' : '',
                            ]"
                        />
                        {{ isRefreshing ? "Memuat..." : "Refresh" }}
                    </button>
                </div>
            </div>

            <!-- Legend -->
            <div class="px-6 py-3 bg-gray-50/50 border-b border-gray-100">
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-3 h-3 bg-blue-600 rounded-full ring-2 ring-blue-200"
                        ></span>
                        <span class="text-gray-600"
                            >Kantor ({{
                                officeLocation?.name ||
                                "Bank Indonesia KPw Lampung"
                            }})</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-emerald-200"
                        ></span>
                        <span class="text-gray-600">Absensi Valid</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="w-3 h-3 bg-rose-500 rounded-full ring-2 ring-rose-200"
                        ></span>
                        <span class="text-gray-600">Absensi Tidak Valid</span>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="relative">
                <div id="map" class="w-full h-[450px]"></div>

                <!-- Loading overlay -->
                <div
                    v-if="isLoading"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center"
                >
                    <div class="text-center">
                        <div
                            class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"
                        ></div>
                        <p class="text-sm text-gray-600 mt-3 font-medium">
                            Memuat peta...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <!-- Table Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <ListBulletIcon class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            Daftar Absensi Hari Ini
                        </h3>
                        <p class="text-sm text-white/80">
                            {{ attendances.length }} peserta tercatat
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div v-if="attendances.length > 0" class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Peserta
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Waktu Check-in
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Lokasi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="(attendance, index) in attendances"
                            :key="attendance.id"
                            :class="[
                                'hover:bg-blue-50/50 transition-colors duration-150',
                                index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30',
                            ]"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs shadow-sm"
                                    >
                                        {{ getInitials(attendance.user_name) }}
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-900"
                                        >{{ attendance.user_name }}</span
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-700"
                                >
                                    {{ attendance.division }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="flex items-center gap-1.5 text-sm text-gray-900"
                                >
                                    <svg
                                        class="w-4 h-4 text-gray-400"
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
                                    {{ attendance.check_in_time }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="
                                        getStatusBadgeClass(attendance.status)
                                    "
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold"
                                >
                                    <span
                                        :class="
                                            getStatusDotClass(attendance.status)
                                        "
                                        class="w-1.5 h-1.5 rounded-full"
                                    ></span>
                                    {{ getStatusText(attendance.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold',
                                        attendance.is_valid_location
                                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                            : 'bg-rose-50 text-rose-700 border border-rose-200',
                                    ]"
                                >
                                    <span
                                        :class="
                                            attendance.is_valid_location
                                                ? 'bg-emerald-500'
                                                : 'bg-rose-500'
                                        "
                                        class="w-1.5 h-1.5 rounded-full"
                                    ></span>
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

            <!-- Empty State -->
            <div v-else class="text-center py-16">
                <div
                    class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
                >
                    <MapPinIcon class="h-10 w-10 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">
                    Belum ada absensi hari ini
                </h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto">
                    Absensi peserta akan muncul secara real-time di sini ketika
                    mereka melakukan check-in
                </p>
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
        "On-Time": "bg-emerald-50 text-emerald-700 border border-emerald-200",
        Late: "bg-amber-50 text-amber-700 border border-amber-200",
        Absent: "bg-rose-50 text-rose-700 border border-rose-200",
        "Not-Checked-Out": "bg-blue-50 text-blue-700 border border-blue-200",
    };
    return classes[status] || "bg-gray-50 text-gray-700 border border-gray-200";
};

const getStatusDotClass = (status) => {
    const dotClasses = {
        "On-Time": "bg-emerald-500",
        Late: "bg-amber-500",
        Absent: "bg-rose-500",
        "Not-Checked-Out": "bg-blue-500",
    };
    return dotClasses[status] || "bg-gray-500";
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

const getInitials = (name) => {
    if (!name) return "?";
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
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
