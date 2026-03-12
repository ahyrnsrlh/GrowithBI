<template>
    <Head title="Detail Absensi" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Modern Header with Gradient -->
                <div class="mb-8">
                    <Link
                        :href="route('admin.attendance.index')"
                        class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-900 mb-4 transition-colors duration-200"
                    >
                        <ArrowLeftIcon class="h-5 w-5" />
                        <span class="font-medium">Kembali</span>
                    </Link>

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                Detail Absensi
                            </h1>
                            <p class="text-gray-500">
                                {{ attendance.date_formatted }}
                            </p>
                        </div>
                        <div>
                            <span
                                :class="getStatusBadgeClass(attendance.status)"
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm"
                            >
                                <span
                                    class="w-2 h-2 rounded-full mr-2"
                                    :class="
                                        getStatusDotClass(attendance.status)
                                    "
                                ></span>
                                {{ getStatusText(attendance.status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Participant Card - Full Width -->
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 mb-6 text-white"
                >
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm"
                            >
                                <UserIcon class="h-8 w-8 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold">
                                {{ attendance.user.name }}
                            </h2>
                            <p class="text-blue-100 mt-1">
                                {{ attendance.user.email }}
                            </p>
                            <p
                                v-if="attendance.user.division"
                                class="text-blue-100 text-sm mt-1"
                            >
                                {{ attendance.user.division.name }}
                            </p>
                        </div>
                        <div>
                            <Link
                                :href="
                                    route(
                                        'admin.participants.show',
                                        attendance.user.id,
                                    )
                                "
                                class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors duration-200 shadow-md"
                            >
                                <span>Lihat Profil</span>
                                <ArrowTopRightOnSquareIcon
                                    class="h-4 w-4 ml-2"
                                />
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Time Information Card -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100"
                        >
                            <h3
                                class="text-lg font-bold text-gray-900 flex items-center"
                            >
                                <ClockIcon
                                    class="h-5 w-5 mr-2 text-green-600"
                                />
                                Waktu Absensi
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200"
                                >
                                    <div
                                        class="flex items-start justify-between mb-2"
                                    >
                                        <p
                                            class="text-sm font-medium text-green-800"
                                        >
                                            Check-in
                                        </p>
                                        <div
                                            class="h-8 w-8 rounded-full bg-green-200 flex items-center justify-center"
                                        >
                                            <svg
                                                class="h-4 w-4 text-green-700"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ attendance.check_in || "-" }}
                                    </p>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200"
                                >
                                    <div
                                        class="flex items-start justify-between mb-2"
                                    >
                                        <p
                                            class="text-sm font-medium text-orange-800"
                                        >
                                            Check-out
                                        </p>
                                        <div
                                            class="h-8 w-8 rounded-full bg-orange-200 flex items-center justify-center"
                                        >
                                            <svg
                                                class="h-4 w-4 text-orange-700"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ attendance.check_out || "-" }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200"
                            >
                                <div
                                    class="flex items-start justify-between mb-2"
                                >
                                    <p
                                        class="text-sm font-medium text-purple-800"
                                    >
                                        Durasi Kerja
                                    </p>
                                    <div
                                        class="h-8 w-8 rounded-full bg-purple-200 flex items-center justify-center"
                                    >
                                        <ClockIcon
                                            class="h-4 w-4 text-purple-700"
                                        />
                                    </div>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{
                                        formatDuration(
                                            attendance.working_duration,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Card -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100"
                        >
                            <h3
                                class="text-lg font-bold text-gray-900 flex items-center"
                            >
                                <MapPinIcon
                                    class="h-5 w-5 mr-2 text-blue-600"
                                />
                                Lokasi Absensi
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    class="bg-gray-50 rounded-xl p-4 border border-gray-200"
                                >
                                    <p
                                        class="text-sm font-medium text-gray-600 mb-2"
                                    >
                                        Alamat
                                    </p>
                                    <p class="text-gray-900 leading-relaxed">
                                        {{
                                            attendance.location.address ||
                                            "Alamat tidak tersedia"
                                        }}
                                    </p>
                                </div>

                                <div
                                    v-if="
                                        attendance.location.latitude &&
                                        attendance.location.longitude
                                    "
                                    class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 text-white text-center"
                                >
                                    <div
                                        class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm mb-3"
                                    >
                                        <MapPinIcon
                                            class="h-8 w-8 text-white"
                                        />
                                    </div>
                                    <p class="font-semibold mb-1">
                                        Koordinat GPS
                                    </p>
                                    <p
                                        class="text-sm font-mono text-blue-100 mb-4"
                                    >
                                        {{ attendance.location.latitude }},
                                        {{ attendance.location.longitude }}
                                    </p>
                                    <a
                                        :href="`https://www.google.com/maps?q=${attendance.location.latitude},${attendance.location.longitude}`"
                                        target="_blank"
                                        class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors duration-200 shadow-md"
                                    >
                                        <span>Buka di Google Maps</span>
                                        <ArrowTopRightOnSquareIcon
                                            class="h-4 w-4 ml-2"
                                        />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section - Full Width -->
                <div
                    class="mt-6 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                >
                    <div
                        class="bg-gradient-to-r from-amber-50 to-yellow-50 px-6 py-4 border-b border-gray-100"
                    >
                        <h3
                            class="text-lg font-bold text-gray-900 flex items-center"
                        >
                            <svg
                                class="h-5 w-5 mr-2 text-amber-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Catatan Admin
                        </h3>
                    </div>
                    <div class="p-6">
                        <form @submit.prevent="updateNotes">
                            <textarea
                                v-model="notesForm.notes"
                                rows="4"
                                placeholder="Tambahkan catatan untuk absensi ini..."
                                class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                            ></textarea>
                            <div class="mt-4 flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="notesForm.processing"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all duration-200"
                                >
                                    <svg
                                        v-if="notesForm.processing"
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    <span v-if="notesForm.processing"
                                        >Menyimpan...</span
                                    >
                                    <span v-else>Simpan Catatan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    ArrowLeftIcon,
    MapPinIcon,
    ArrowTopRightOnSquareIcon,
    UserIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    attendance: Object,
});

const notesForm = reactive({
    notes: props.attendance.notes || "",
    processing: false,
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
        "On-Time":
            "bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200",
        Late: "bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200",
        Absent: "bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200",
        "Not-Checked-Out":
            "bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200",
    };
    return (
        classes[status] || "bg-gray-100 text-gray-800 border border-gray-200"
    );
};

const getStatusDotClass = (status) => {
    const classes = {
        "On-Time": "bg-green-500 animate-pulse",
        Late: "bg-yellow-500 animate-pulse",
        Absent: "bg-red-500 animate-pulse",
        "Not-Checked-Out": "bg-blue-500 animate-pulse",
    };
    return classes[status] || "bg-gray-500";
};

const formatDuration = (minutes) => {
    if (!minutes) return "-";
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours} jam ${mins} menit`;
};

const updateNotes = () => {
    notesForm.processing = true;

    router.put(
        route("admin.attendance.update-notes", props.attendance.id),
        {
            notes: notesForm.notes,
        },
        {
            preserveScroll: true,
            onFinish: () => (notesForm.processing = false),
        },
    );
};
</script>
