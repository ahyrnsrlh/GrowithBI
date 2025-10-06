<template>
    <Head title="Absensi Online" />

    <PesertaLayout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-2xl font-bold mb-2">Absensi Online</h1>
                <p class="text-gray-600 mb-6">Kelola kehadiran Anda</p>

                <div
                    v-if="$page.props.flash.success"
                    class="mb-4 p-4 bg-green-100 text-green-700 rounded"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div
                    v-if="$page.props.flash.error"
                    class="mb-4 p-4 bg-red-100 text-red-700 rounded"
                >
                    {{ $page.props.flash.error }}
                </div>

                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-bold mb-4">Status Hari Ini</h2>

                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="p-4 border rounded">
                            <div class="text-sm text-gray-600">Check-in</div>
                            <div
                                class="text-xl font-bold"
                                :class="
                                    todayAttendance?.check_in
                                        ? 'text-green-600'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    todayAttendance?.check_in
                                        ? formatTime(todayAttendance.check_in)
                                        : "--:--"
                                }}
                            </div>
                        </div>

                        <div class="p-4 border rounded">
                            <div class="text-sm text-gray-600">Check-out</div>
                            <div
                                class="text-xl font-bold"
                                :class="
                                    todayAttendance?.check_out
                                        ? 'text-blue-600'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    todayAttendance?.check_out
                                        ? formatTime(todayAttendance.check_out)
                                        : "--:--"
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <button
                            @click="handleCheckIn"
                            :disabled="
                                isProcessing ||
                                (todayAttendance && todayAttendance.check_in)
                            "
                            class="px-6 py-3 rounded font-semibold text-white disabled:opacity-50"
                            :class="
                                todayAttendance?.check_in
                                    ? 'bg-gray-400'
                                    : 'bg-green-600 hover:bg-green-700'
                            "
                        >
                            {{
                                isProcessing && actionType === "check-in"
                                    ? "Memproses..."
                                    : todayAttendance?.check_in
                                    ? "Sudah Check-in"
                                    : "Check-in"
                            }}
                        </button>

                        <button
                            @click="handleCheckOut"
                            :disabled="
                                isProcessing ||
                                !todayAttendance?.check_in ||
                                todayAttendance?.check_out
                            "
                            class="px-6 py-3 rounded font-semibold text-white disabled:opacity-50"
                            :class="
                                todayAttendance?.check_out
                                    ? 'bg-gray-400'
                                    : 'bg-blue-600 hover:bg-blue-700'
                            "
                        >
                            {{
                                isProcessing && actionType === "check-out"
                                    ? "Memproses..."
                                    : todayAttendance?.check_out
                                    ? "Sudah Check-out"
                                    : "Check-out"
                            }}
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">Riwayat</h2>

                    <div
                        v-if="attendanceList && attendanceList.length > 0"
                        class="overflow-x-auto"
                    >
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">
                                        Check-in
                                    </th>
                                    <th class="px-4 py-2 text-left">
                                        Check-out
                                    </th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="att in attendanceList"
                                    :key="att.id"
                                    class="border-b hover:bg-gray-50"
                                >
                                    <td class="px-4 py-2">
                                        {{ formatDate(att.date) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{
                                            att.check_in
                                                ? formatTime(att.check_in)
                                                : "-"
                                        }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{
                                            att.check_out
                                                ? formatTime(att.check_out)
                                                : "-"
                                        }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            v-if="att.status === 'On-Time'"
                                            class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded"
                                            >Tepat Waktu</span
                                        >
                                        <span
                                            v-else-if="att.status === 'Late'"
                                            class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded"
                                            >Terlambat</span
                                        >
                                        <span
                                            v-else
                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded"
                                            >{{ att.status }}</span
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="text-center py-8 text-gray-500">
                        Belum ada riwayat absensi
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
    </PesertaLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import SimpleCameraModal from "@/Components/SimpleCameraModal.vue";

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
    officeLocation: Object,
    currentDateTime: String,
});

// Use attendanceHistory if available, fallback to attendances
const attendanceList = computed(
    () => props.attendanceHistory || props.attendances || []
);

const showCamera = ref(false);
const actionType = ref("");
const isProcessing = ref(false);
const photoBase64 = ref(null);
const userLocation = ref(null);

const cameraTitle = computed(() =>
    actionType.value === "check-in" ? "Foto Check-in" : "Foto Check-out"
);

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            weekday: "short",
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    } catch (error) {
        return dateString;
    }
};

const formatTime = (timeString) => {
    if (!timeString) return "-";
    try {
        // If it's already in H:i:s format (from backend), just show H:i
        if (
            typeof timeString === "string" &&
            timeString.match(/^\d{2}:\d{2}:\d{2}$/)
        ) {
            return timeString.substring(0, 5); // Return HH:MM only
        }
        // Otherwise parse as full datetime
        const date = new Date(timeString);
        return date.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
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
        alert("Geolocation tidak didukung");
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
        alert(
            "Gagal mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi."
        );
    }
};

const onPhotoCaptured = (base64) => {
    photoBase64.value = base64;
    submit();
};

const submit = () => {
    if (!photoBase64.value || !userLocation.value) {
        alert("Data tidak lengkap");
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
            latitude: userLocation.value.latitude,
            longitude: userLocation.value.longitude,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                isProcessing.value = false;
                photoBase64.value = null;
                userLocation.value = null;
                actionType.value = "";
            },
            onError: (errors) => {
                isProcessing.value = false;
                alert(
                    errors.error || errors.photo || "Gagal menyimpan absensi"
                );
            },
            onFinish: () => {
                isProcessing.value = false;
            },
        }
    );
};
</script>
