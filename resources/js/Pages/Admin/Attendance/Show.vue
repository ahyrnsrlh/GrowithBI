<template>
    <Head title="Detail Absensi" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <Link
                                :href="route('admin.attendance.index')"
                                class="text-gray-500 hover:text-gray-700 flex items-center space-x-2 mb-2"
                            >
                                <ArrowLeftIcon class="h-5 w-5" />
                                <span>Kembali ke Daftar Absensi</span>
                            </Link>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Detail Absensi
                            </h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Detail informasi absensi peserta magang
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Attendance Information -->
                        <div
                            class="bg-white shadow-sm rounded-lg border border-gray-200 p-6"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Informasi Absensi
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Tanggal</label
                                    >
                                    <p class="text-lg text-gray-900">
                                        {{ attendance.date_formatted }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Status</label
                                    >
                                    <span
                                        :class="
                                            getStatusBadgeClass(
                                                attendance.status
                                            )
                                        "
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Check-in</label
                                    >
                                    <p class="text-lg text-gray-900">
                                        {{ attendance.check_in || "-" }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Check-out</label
                                    >
                                    <p class="text-lg text-gray-900">
                                        {{ attendance.check_out || "-" }}
                                    </p>
                                </div>

                                <div class="md:col-span-2">
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Durasi Kerja</label
                                    >
                                    <p class="text-lg text-gray-900">
                                        {{
                                            formatDuration(
                                                attendance.working_duration
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div
                            class="bg-white shadow-sm rounded-lg border border-gray-200 p-6"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Informasi Lokasi
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Alamat</label
                                    >
                                    <p class="text-gray-900">
                                        {{ attendance.location.address || "-" }}
                                    </p>
                                </div>

                                <div
                                    v-if="
                                        attendance.location.latitude &&
                                        attendance.location.longitude
                                    "
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Koordinat</label
                                    >
                                    <p class="text-gray-900 font-mono text-sm">
                                        {{ attendance.location.latitude }},
                                        {{ attendance.location.longitude }}
                                    </p>
                                </div>

                                <!-- Simple Map Placeholder -->
                                <div
                                    v-if="
                                        attendance.location.latitude &&
                                        attendance.location.longitude
                                    "
                                    class="bg-gray-100 rounded-lg p-8 text-center border border-gray-200"
                                >
                                    <MapPinIcon
                                        class="mx-auto h-12 w-12 text-gray-400 mb-2"
                                    />
                                    <p class="text-gray-500">Lokasi Absensi</p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ attendance.location.latitude }},
                                        {{ attendance.location.longitude }}
                                    </p>
                                    <a
                                        :href="`https://www.google.com/maps?q=${attendance.location.latitude},${attendance.location.longitude}`"
                                        target="_blank"
                                        class="inline-flex items-center mt-3 text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        <ArrowTopRightOnSquareIcon
                                            class="h-4 w-4 mr-1"
                                        />
                                        Buka di Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div
                            class="bg-white shadow-sm rounded-lg border border-gray-200 p-6"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Catatan Admin
                            </h2>

                            <form @submit.prevent="updateNotes">
                                <div>
                                    <textarea
                                        v-model="notesForm.notes"
                                        rows="4"
                                        placeholder="Tambahkan catatan untuk absensi ini..."
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="notesForm.processing"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                    >
                                        <span v-if="notesForm.processing"
                                            >Menyimpan...</span
                                        >
                                        <span v-else>Simpan Catatan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Participant Information -->
                        <div
                            class="bg-white shadow-sm rounded-lg border border-gray-200 p-6"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Informasi Peserta
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Nama</label
                                    >
                                    <p class="text-gray-900 font-medium">
                                        {{ attendance.user.name }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Email</label
                                    >
                                    <p class="text-gray-900">
                                        {{ attendance.user.email }}
                                    </p>
                                </div>

                                <div v-if="attendance.user.division">
                                    <label
                                        class="block text-sm font-medium text-gray-500 mb-1"
                                        >Divisi</label
                                    >
                                    <p class="text-gray-900">
                                        {{ attendance.user.division.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h3
                                    class="text-sm font-medium text-gray-900 mb-3"
                                >
                                    Aksi Cepat
                                </h3>
                                <div class="space-y-2">
                                    <Link
                                        :href="
                                            route(
                                                'admin.participants.show',
                                                attendance.user.id
                                            )
                                        "
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        <UserIcon class="h-4 w-4 mr-2" />
                                        Lihat Profil Peserta
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Summary -->
                        <div
                            class="bg-white shadow-sm rounded-lg border border-gray-200 p-6"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Ringkasan Absensi
                            </h2>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600"
                                        >Hari ini</span
                                    >
                                    <span
                                        :class="
                                            getStatusBadgeClass(
                                                attendance.status
                                            )
                                        "
                                        class="px-2 py-1 rounded text-xs font-medium"
                                    >
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                </div>

                                <div class="pt-2 border-t border-gray-100">
                                    <p
                                        class="text-xs text-gray-500 text-center"
                                    >
                                        Data absensi bulan ini akan ditampilkan
                                        di sini
                                    </p>
                                </div>
                            </div>
                        </div>
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
        "On-Time": "bg-green-100 text-green-800",
        Late: "bg-yellow-100 text-yellow-800",
        Absent: "bg-red-100 text-red-800",
        "Not-Checked-Out": "bg-blue-100 text-blue-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
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
        }
    );
};
</script>
