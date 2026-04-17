<template>
    <div
        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
    >
        <div
            class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50"
        >
            <div class="flex items-center justify-between">
                <h2
                    class="text-lg font-semibold text-gray-900 flex items-center gap-2"
                >
                    <svg
                        class="w-5 h-5 text-blue-600"
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
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                    <option value="all">Semua Status</option>
                    <option value="On-Time">Tepat Waktu</option>
                    <option value="Late">Terlambat</option>
                </select>
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
                    class="group bg-white rounded-xl p-4 border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3 min-w-[140px]">
                            <div
                                class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors"
                            >
                                <svg
                                    class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors"
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
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ formatDate(attendance.date) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ formatDayName(attendance.date) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 bg-emerald-100 rounded flex items-center justify-center"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-[10px] uppercase tracking-wider text-gray-400 font-medium"
                                    >
                                        Masuk
                                    </p>
                                    <p class="font-semibold text-gray-900">
                                        {{
                                            attendance.check_in
                                                ? formatTime(
                                                      attendance.check_in,
                                                  )
                                                : "-"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-[10px] uppercase tracking-wider text-gray-400 font-medium"
                                    >
                                        Pulang
                                    </p>
                                    <p class="font-semibold text-gray-900">
                                        {{
                                            attendance.check_out
                                                ? formatTime(
                                                      attendance.check_out,
                                                  )
                                                : "-"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <span
                                v-if="attendance.status === 'On-Time'"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"
                            >
                                <svg
                                    class="w-3 h-3"
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
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"
                            >
                                <svg
                                    class="w-3 h-3"
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
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                            >
                                {{ attendance.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-16">
                <div
                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
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
                <h3 class="text-gray-900 font-medium mb-1">
                    Belum ada riwayat absensi
                </h3>
                <p class="text-sm text-gray-500">
                    Lakukan check-in untuk memulai
                </p>
            </div>

            <div
                v-if="filteredAttendance.length > perPage"
                class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between"
            >
                <p class="text-sm text-gray-500">
                    Menampilkan {{ (currentPage - 1) * perPage + 1 }} -
                    {{
                        Math.min(
                            currentPage * perPage,
                            filteredAttendance.length,
                        )
                    }}
                    dari {{ filteredAttendance.length }}
                </p>
                <div class="flex items-center gap-1">
                    <button
                        @click="$emit('update:currentPage', currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
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

                    <template
                        v-for="pageNumber in totalPages"
                        :key="pageNumber"
                    >
                        <button
                            @click="$emit('update:currentPage', pageNumber)"
                            class="w-8 h-8 rounded-lg text-sm font-medium transition-colors"
                            :class="
                                pageNumber === currentPage
                                    ? 'bg-blue-600 text-white'
                                    : 'text-gray-600 hover:bg-gray-100'
                            "
                        >
                            {{ pageNumber }}
                        </button>
                    </template>

                    <button
                        @click="$emit('update:currentPage', currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
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
    formatDate: { type: Function, required: true },
    formatDayName: { type: Function, required: true },
    formatTime: { type: Function, required: true },
});

defineEmits(["update:filterStatus", "update:currentPage"]);
</script>
