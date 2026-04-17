<template>
    <div
        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
    >
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            Data Absensi
                        </h3>
                        <p class="text-sm text-white/80">
                            {{ attendances?.total || 0 }} total records
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="divide-y divide-gray-100">
            <div v-for="i in 5" :key="i" class="px-6 py-4 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                    </div>
                    <div class="h-6 w-20 bg-gray-200 rounded-full"></div>
                </div>
            </div>
        </div>

        <div v-else class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/80 sticky top-0 z-10">
                        <th
                            scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        >
                            Tanggal
                        </th>
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
                            Check-in
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        >
                            Check-out
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        >
                            Foto
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
                            Durasi
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                        >
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="(attendance, index) in attendances?.data || []"
                        :key="attendance.id"
                        :class="[
                            'hover:bg-blue-50/50 transition-colors duration-150',
                            index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30',
                        ]"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ attendance.date_formatted }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm"
                                >
                                    {{ getInitials(attendance.user?.name) }}
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ attendance.user?.name || "-" }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ attendance.user?.email || "-" }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-700"
                            >
                                {{ attendance.user?.division?.name || "-" }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <svg
                                    class="w-4 h-4 text-green-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                    />
                                </svg>
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ attendance.check_in || "-" }}</span
                                >
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <svg
                                    class="w-4 h-4 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ attendance.check_out || "-" }}</span
                                >
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div
                                    v-if="attendance.photos?.checkin"
                                    class="relative group/photo"
                                >
                                    <img
                                        :src="attendance.photos.checkin"
                                        alt="Check-in"
                                        class="w-10 h-10 rounded-lg object-cover cursor-pointer border-2 border-green-200 hover:border-green-400 hover:scale-110 transition-all duration-200 shadow-sm"
                                        @click="
                                            $emit(
                                                'open-photo',
                                                attendance.photos.checkin,
                                                'Check-in - ' +
                                                    attendance.user?.name,
                                            )
                                        "
                                    />
                                    <div
                                        class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/photo:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                    >
                                        Check-in
                                    </div>
                                </div>
                                <span
                                    v-else
                                    class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </span>

                                <div
                                    v-if="attendance.photos?.checkout"
                                    class="relative group/photo"
                                >
                                    <img
                                        :src="attendance.photos.checkout"
                                        alt="Check-out"
                                        class="w-10 h-10 rounded-lg object-cover cursor-pointer border-2 border-orange-200 hover:border-orange-400 hover:scale-110 transition-all duration-200 shadow-sm"
                                        @click="
                                            $emit(
                                                'open-photo',
                                                attendance.photos.checkout,
                                                'Check-out - ' +
                                                    attendance.user?.name,
                                            )
                                        "
                                    />
                                    <div
                                        class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/photo:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                    >
                                        Check-out
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative group/status">
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
                                <div
                                    class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1.5 px-3 opacity-0 group-hover/status:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                >
                                    {{ getStatusTooltip(attendance.status) }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-700">
                                {{
                                    formatDuration(attendance.working_duration)
                                }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <Link
                                :href="
                                    route(
                                        'admin.attendance.show',
                                        attendance.id,
                                    )
                                "
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                            >
                                Detail
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="
                !isLoading &&
                (!attendances?.data || attendances.data.length === 0)
            "
            class="text-center py-16"
        >
            <div
                class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <svg
                    class="w-10 h-10 text-gray-400"
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
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">
                Tidak ada data absensi
            </h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                Tidak ada data absensi yang sesuai dengan filter yang dipilih.
                Coba ubah filter atau reset untuk melihat semua data.
            </p>
            <button
                @click="$emit('clear-filters')"
                class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                    />
                </svg>
                Reset Filter
            </button>
        </div>

        <div
            v-if="attendances?.data?.length"
            class="px-6 py-4 border-t border-gray-100 bg-gray-50/50"
        >
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <p class="text-sm text-gray-600">
                    Menampilkan
                    <span class="font-medium">{{ attendances.from }}</span>
                    -
                    <span class="font-medium">{{ attendances.to }}</span>
                    dari
                    <span class="font-medium">{{ attendances.total }}</span>
                    data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-if="attendances.prev_page_url"
                        :href="attendances.prev_page_url"
                        preserve-scroll
                        preserve-state
                        class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Prev
                    </Link>

                    <template v-for="link in paginationLinks" :key="link.label">
                        <Link
                            v-if="
                                link.url &&
                                !link.label.includes('Previous') &&
                                !link.label.includes('Next')
                            "
                            :href="link.url"
                            preserve-scroll
                            preserve-state
                            :class="[
                                'inline-flex items-center justify-center w-10 h-10 text-sm font-medium rounded-lg transition-colors',
                                link.active
                                    ? 'bg-blue-600 text-white'
                                    : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50',
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else-if="
                                !link.url &&
                                !link.label.includes('Previous') &&
                                !link.label.includes('Next') &&
                                link.label === '...'
                            "
                            class="inline-flex items-center justify-center w-10 h-10 text-sm text-gray-400"
                        >
                            ...
                        </span>
                    </template>

                    <Link
                        v-if="attendances.next_page_url"
                        :href="attendances.next_page_url"
                        preserve-scroll
                        preserve-state
                        class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Next
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    attendances: { type: Object, default: () => ({}) },
    isLoading: { type: Boolean, default: false },
    paginationLinks: { type: Array, default: () => [] },
});

defineEmits(["open-photo", "clear-filters"]);

const getInitials = (name) => {
    if (!name) {
        return "?";
    }
    return name
        .split(" ")
        .map((part) => part[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
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

const getStatusTooltip = (status) => {
    const tooltipMap = {
        "On-Time": "Peserta hadir tepat waktu",
        Late: "Peserta datang terlambat",
        Absent: "Peserta tidak hadir",
        "Not-Checked-Out": "Peserta belum melakukan check-out",
    };
    return tooltipMap[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        "On-Time": "bg-emerald-50 text-emerald-700 border border-emerald-200",
        Late: "bg-amber-50 text-amber-700 border border-amber-200",
        Absent: "bg-red-50 text-red-700 border border-red-200",
        "Not-Checked-Out": "bg-blue-50 text-blue-700 border border-blue-200",
    };
    return classes[status] || "bg-gray-50 text-gray-700 border border-gray-200";
};

const getStatusDotClass = (status) => {
    const dotClasses = {
        "On-Time": "bg-emerald-500",
        Late: "bg-amber-500",
        Absent: "bg-red-500",
        "Not-Checked-Out": "bg-blue-500",
    };
    return dotClasses[status] || "bg-gray-500";
};

const formatDuration = (minutes) => {
    const totalMinutes = Number(minutes);

    if (!Number.isFinite(totalMinutes) || totalMinutes <= 0) {
        return "-";
    }

    const hours = Math.floor(totalMinutes / 60);
    const mins = totalMinutes % 60;

    if (hours === 0) {
        return `${mins}m`;
    }
    return `${hours}j ${mins}m`;
};
</script>
