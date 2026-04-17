<template>
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-[#2F4C9E] px-6 py-4 border-b border-[#274089]">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg
                        class="w-6 h-6 mr-2 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                    Riwayat Absensi
                </h2>

                <select
                    :value="filterStatus"
                    @change="$emit('update:filterStatus', $event.target.value)"
                    class="px-4 py-2 border border-white/30 rounded-lg text-sm focus:ring-2 focus:ring-white/50 focus:border-white bg-white/10 text-white backdrop-blur-sm"
                >
                    <option value="all" class="text-gray-900">
                        Semua Status
                    </option>
                    <option value="On-Time" class="text-gray-900">
                        Tepat Waktu
                    </option>
                    <option value="Late" class="text-gray-900">
                        Terlambat
                    </option>
                </select>
            </div>

            <div
                v-if="monthlyStats"
                class="mt-4 flex items-center space-x-6 text-sm"
            >
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <span class="text-white"
                        >Total Hadir:
                        <span class="font-bold">{{ monthlyStats.total }}</span>
                        hari</span
                    >
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-300 rounded-full"></div>
                    <span class="text-white"
                        >Tepat Waktu:
                        <span class="font-bold">{{
                            monthlyStats.onTime
                        }}</span></span
                    >
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-300 rounded-full"></div>
                    <span class="text-white"
                        >Terlambat:
                        <span class="font-bold">{{
                            monthlyStats.late
                        }}</span></span
                    >
                </div>
            </div>
        </div>

        <div class="p-6">
            <div
                v-if="paginatedAttendance && paginatedAttendance.length > 0"
                class="space-y-3"
            >
                <div
                    v-for="attendance in paginatedAttendance"
                    :key="attendance.id"
                    class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-200 hover:border-[#2F4C9E] hover:shadow-md transition-all duration-300"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="bg-[#E8EEF7] p-3 rounded-lg">
                                <svg
                                    class="w-5 h-5 text-[#2F4C9E]"
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
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ formatDate(attendance.date) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ formatDayName(attendance.date) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 flex-1">
                            <svg
                                class="w-4 h-4 text-green-600"
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
                            <div>
                                <p class="text-xs text-gray-500">Masuk</p>
                                <p class="font-semibold text-gray-900">
                                    {{
                                        attendance.check_in
                                            ? formatTime(attendance.check_in)
                                            : "-"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 flex-1">
                            <svg
                                class="w-4 h-4 text-[#2F4C9E]"
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
                            <div>
                                <p class="text-xs text-gray-500">Pulang</p>
                                <p class="font-semibold text-gray-900">
                                    {{
                                        attendance.check_out
                                            ? formatTime(attendance.check_out)
                                            : "-"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <span
                                v-if="attendance.status === 'On-Time'"
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200"
                            >
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Tepat Waktu
                            </span>
                            <span
                                v-else-if="attendance.status === 'Late'"
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200"
                            >
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Terlambat
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200"
                            >
                                {{ attendance.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <svg
                    class="w-16 h-16 text-gray-300 mx-auto mb-4"
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
                <p class="text-gray-500 font-medium">
                    Belum ada riwayat absensi
                </p>
                <p class="text-gray-400 text-sm mt-2">
                    Lakukan check-in untuk memulai
                </p>
            </div>

            <div
                v-if="filteredAttendance.length > perPage"
                class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4"
            >
                <div class="text-sm text-gray-600">
                    Menampilkan {{ (currentPage - 1) * perPage + 1 }} -
                    {{
                        Math.min(
                            currentPage * perPage,
                            filteredAttendance.length,
                        )
                    }}
                    dari {{ filteredAttendance.length }} data
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        @click="$emit('update:currentPage', currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#E8EEF7] hover:border-[#2F4C9E] transition-colors"
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
                    </button>

                    <div class="flex items-center space-x-1">
                        <button
                            v-for="pageNumber in totalPages"
                            :key="pageNumber"
                            @click="$emit('update:currentPage', pageNumber)"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                            :class="
                                pageNumber === currentPage
                                    ? 'bg-[#2F4C9E] text-white'
                                    : 'border border-gray-300 hover:bg-[#E8EEF7] hover:border-[#2F4C9E]'
                            "
                        >
                            {{ pageNumber }}
                        </button>
                    </div>

                    <button
                        @click="$emit('update:currentPage', currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#E8EEF7] hover:border-[#2F4C9E] transition-colors"
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
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    paginatedAttendance: { type: Array, default: () => [] },
    filterStatus: { type: String, default: "all" },
    currentPage: { type: Number, default: 1 },
    perPage: { type: Number, default: 5 },
    totalPages: { type: Number, default: 0 },
    filteredAttendance: { type: Array, default: () => [] },
    monthlyStats: { type: Object, default: null },
    formatDate: { type: Function, required: true },
    formatDayName: { type: Function, required: true },
    formatTime: { type: Function, required: true },
});

defineEmits(["update:filterStatus", "update:currentPage"]);
</script>
