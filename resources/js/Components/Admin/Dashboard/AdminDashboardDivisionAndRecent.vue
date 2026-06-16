<template>
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        <!-- ── Distribusi Status (dipindah ke bawah) ─────────────────────── -->
        <div
            class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300 flex flex-col"
        >
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Distribusi Status
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Status pendaftaran saat ini
                    </p>
                </div>
                <div
                    class="w-10 h-10 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-xl flex items-center justify-center"
                >
                    <svg
                        class="w-5 h-5 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                        />
                    </svg>
                </div>
            </div>

            <PieChart
                :data="statusChartData"
                :options="{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                }"
                class="h-52"
            />

            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full"
                >
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Disetujui
                </span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-full"
                >
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    Menunggu
                </span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 text-xs font-medium rounded-full"
                >
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Ditolak
                </span>
            </div>
        </div>


        <div
            class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300"
        >
            <div
                class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Pendaftaran Terbaru
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            5 pendaftaran terakhir
                        </p>
                    </div>
                    <Link
                        href="/admin/applications"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors"
                    >
                        Lihat Semua
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
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Pendaftar
                            </th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Tanggal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="app in recentApplications"
                            :key="app.id"
                            class="hover:bg-blue-50/30 transition-colors duration-150"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-indigo-800 flex items-center justify-center text-white font-semibold text-sm shadow-sm"
                                    >
                                        {{ getInitials(app.name) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ app.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ app.email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-700"
                                >
                                    {{ app.division }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="getStatusClasses(app.status)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg"
                                >
                                    <span
                                        :class="getStatusDotClass(app.status)"
                                        class="w-1.5 h-1.5 rounded-full"
                                    ></span>
                                    {{ getStatusText(app.status) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(app.created_at) }}
                            </td>
                        </tr>
                        <tr
                            v-if="
                                !recentApplications ||
                                recentApplications.length === 0
                            "
                        >
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"
                                    >
                                        <svg
                                            class="w-8 h-8 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">
                                        Belum ada pendaftaran
                                    </p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Pendaftaran baru akan muncul di sini
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import PieChart from "@/Components/Charts/PieChart.vue";

defineProps({
    statusChartData: {
        type: Object,
        required: true,
    },
    recentApplications: {
        type: Array,
        default: () => [],
    },
    getInitials: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
    getStatusClasses: {
        type: Function,
        required: true,
    },
    getStatusDotClass: {
        type: Function,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>
