<template>
    <Head title="Manajemen Absensi Peserta" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Manajemen Absensi
                            </h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Kelola dan pantau absensi peserta magang
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex space-x-3">
                            <button
                                @click="exportData"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <ArrowDownTrayIcon class="h-4 w-4 mr-2" />
                                Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <div
                        class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CalendarDaysIcon
                                        class="h-8 w-8 text-blue-600"
                                    />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Kehadiran
                                        </dt>
                                        <dd
                                            class="text-2xl font-semibold text-gray-900"
                                        >
                                            {{ stats.present_days }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <ClockIcon class="h-8 w-8 text-green-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Tingkat Kehadiran
                                        </dt>
                                        <dd
                                            class="text-2xl font-semibold text-gray-900"
                                        >
                                            {{ stats.attendance_rate }}%
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CheckCircleIcon
                                        class="h-8 w-8 text-green-600"
                                    />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Ketepatan Waktu
                                        </dt>
                                        <dd
                                            class="text-2xl font-semibold text-gray-900"
                                        >
                                            {{ stats.punctuality_rate }}%
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <XCircleIcon class="h-8 w-8 text-red-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Tidak Hadir
                                        </dt>
                                        <dd
                                            class="text-2xl font-semibold text-gray-900"
                                        >
                                            {{ stats.absent_days }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-8"
                >
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                    >
                        <!-- Search -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Cari Peserta
                            </label>
                            <input
                                v-model="filterForm.search"
                                @input="debounceSearch"
                                type="text"
                                placeholder="Nama atau email..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Tanggal Mulai
                            </label>
                            <input
                                v-model="filterForm.date_from"
                                @change="applyFilters"
                                type="date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Tanggal Akhir
                            </label>
                            <input
                                v-model="filterForm.date_to"
                                @change="applyFilters"
                                type="date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Divisi
                            </label>
                            <select
                                v-model="filterForm.division_id"
                                @change="applyFilters"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Semua Divisi</option>
                                <option
                                    v-for="division in divisions"
                                    :key="division.id"
                                    :value="division.id"
                                >
                                    {{ division.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Participant Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Peserta
                            </label>
                            <select
                                v-model="filterForm.participant_id"
                                @change="applyFilters"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Semua Peserta</option>
                                <option
                                    v-for="participant in participants"
                                    :key="participant.id"
                                    :value="participant.id"
                                >
                                    {{ participant.name }} ({{
                                        participant.division
                                    }})
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Status
                            </label>
                            <select
                                v-model="filterForm.status"
                                @change="applyFilters"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="On-Time">Tepat Waktu</option>
                                <option value="Late">Terlambat</option>
                                <option value="Absent">Tidak Hadir</option>
                                <option value="Not-Checked-Out">
                                    Belum Check-out
                                </option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end space-x-2">
                            <button
                                @click="clearFilters"
                                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div
                    class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Tanggal
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Peserta
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Divisi
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Check-in
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Check-out
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Durasi
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="attendance in attendances.data"
                                    :key="attendance.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ attendance.date_formatted }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ attendance.user.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ attendance.user.email }}
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{
                                            attendance.user.division?.name ||
                                            "-"
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ attendance.check_in || "-" }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ attendance.check_out || "-" }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="
                                                getStatusBadgeClass(
                                                    attendance.status
                                                )
                                            "
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        >
                                            {{
                                                getStatusText(attendance.status)
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{
                                            formatDuration(
                                                attendance.working_duration
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'admin.attendance.show',
                                                    attendance.id
                                                )
                                            "
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Detail
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="!attendances.data.length"
                        class="text-center py-12"
                    >
                        <CalendarDaysIcon
                            class="mx-auto h-12 w-12 text-gray-400"
                        />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            Tidak ada data absensi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tidak ada data absensi yang sesuai dengan filter
                            yang dipilih.
                        </p>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="attendances.data.length"
                        class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
                    >
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link
                                v-if="attendances.prev_page_url"
                                :href="attendances.prev_page_url"
                                preserve-scroll
                                preserve-state
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </Link>
                            <Link
                                v-if="attendances.next_page_url"
                                :href="attendances.next_page_url"
                                preserve-scroll
                                preserve-state
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Next
                            </Link>
                        </div>
                        <div
                            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                        >
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing {{ attendances.from }} to
                                    {{ attendances.to }} of
                                    {{ attendances.total }} results
                                </p>
                            </div>
                            <div
                                v-if="attendances.links"
                                class="flex space-x-1"
                            >
                                <Link
                                    v-for="link in attendances.links"
                                    :key="link.label"
                                    :href="link.url"
                                    preserve-scroll
                                    preserve-state
                                    v-html="link.label"
                                    :class="[
                                        'px-3 py-2 border text-sm font-medium rounded-md',
                                        link.active
                                            ? 'bg-blue-50 border-blue-500 text-blue-600'
                                            : 'border-gray-300 text-gray-500 hover:bg-gray-50',
                                    ]"
                                >
                                </Link>
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
    CalendarDaysIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    ArrowDownTrayIcon,
} from "@heroicons/vue/24/outline";
import { debounce } from "lodash";

const props = defineProps({
    attendances: Object,
    divisions: Array,
    participants: Array,
    filters: Object,
    stats: Object,
});

const filterForm = reactive({
    search: props.filters.search || "",
    date_from: props.filters.date_from || "",
    date_to: props.filters.date_to || "",
    division_id: props.filters.division_id || "",
    participant_id: props.filters.participant_id || "",
    status: props.filters.status || "",
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
    return `${hours}h ${mins}m`;
};

const applyFilters = () => {
    router.get(route("admin.attendance.index"), filterForm, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const debounceSearch = debounce(() => {
    applyFilters();
}, 300);

const clearFilters = () => {
    Object.keys(filterForm).forEach((key) => {
        filterForm[key] = "";
    });
    applyFilters();
};

const exportData = () => {
    window.location.href = route("admin.attendance.export", filterForm);
};
</script>
