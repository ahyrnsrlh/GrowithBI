<template>
    <div
        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
    >
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
                                :class="getStatusBadgeClass(attendance.status)"
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
</template>

<script setup>
import { ListBulletIcon, MapPinIcon } from "@heroicons/vue/24/outline";

defineProps({
    attendances: {
        type: Array,
        default: () => [],
    },
    getInitials: {
        type: Function,
        required: true,
    },
    getStatusBadgeClass: {
        type: Function,
        required: true,
    },
    getStatusDotClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});
</script>
