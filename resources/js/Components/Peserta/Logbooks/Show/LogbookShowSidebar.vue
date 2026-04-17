<template>
    <div class="space-y-6">
        <div
            class="bg-white rounded-2xl shadow-md border-l-4 border-blue-600 overflow-hidden sticky top-6"
        >
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg
                        class="w-6 h-6 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    Informasi Logbookk
                </h3>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-start space-x-3">
                    <div
                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"
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
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                            >Tanggal</label
                        >
                        <p class="text-sm font-medium text-gray-900">
                            {{ formatDateShort(logbook.date) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div
                        class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-5 h-5 text-emerald-600"
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
                    </div>
                    <div class="flex-1">
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                            >Durasi</label
                        >
                        <p class="text-sm font-medium text-gray-900">
                            {{ logbook.duration }} jam kerja
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div
                        class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-5 h-5 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                            >Status</label
                        >
                        <span
                            :class="getStatusBadgeClass(logbook.status)"
                            class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold"
                        >
                            <span
                                class="w-2 h-2 rounded-full mr-2"
                                :class="getStatusDotClass(logbook.status)"
                            ></span>
                            {{ getStatusText(logbook.status) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div
                        class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-5 h-5 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                            >Divisi</label
                        >
                        <p class="text-sm font-medium text-gray-900">
                            {{ logbook.division?.name || "-" }}
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-start space-x-3 mb-4">
                        <svg
                            class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        <div class="flex-1">
                            <label
                                class="block text-xs font-medium text-gray-500 mb-1"
                                >Dibuat</label
                            >
                            <p class="text-xs text-gray-700">
                                {{ formatDateShort(logbook.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="logbook.updated_at !== logbook.created_at"
                        class="flex items-start space-x-3"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5"
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
                        <div class="flex-1">
                            <label
                                class="block text-xs font-medium text-gray-500 mb-1"
                                >Terakhir Diubah</label
                            >
                            <p class="text-xs text-gray-700">
                                {{ formatDateShort(logbook.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    v-if="logbook.reviewer"
                    class="pt-4 border-t border-gray-200"
                >
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0"
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                >Direview oleh</label
                            >
                            <p class="text-sm font-medium text-gray-900">
                                {{ logbook.reviewer.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="pt-5 border-t border-gray-200">
                    <Link
                        :href="route('peserta.logbooks.edit', logbook.id)"
                        class="w-full inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                        Edit Logbook
                    </Link>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl border border-blue-200 shadow-sm overflow-hidden"
        >
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h4 class="text-sm font-bold text-white flex items-center">
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    Panduan Status
                </h4>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-start space-x-3 group">
                    <div
                        class="w-3 h-3 bg-gray-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                    ></div>
                    <div>
                        <p class="text-xs font-bold text-gray-700">Draft</p>
                        <p class="text-xs text-gray-600">
                            Belum dikirim untuk review
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 group">
                    <div
                        class="w-3 h-3 bg-yellow-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                    ></div>
                    <div>
                        <p class="text-xs font-bold text-gray-700">
                            Menunggu Review
                        </p>
                        <p class="text-xs text-gray-600">
                            Sedang direview pembimbing
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 group">
                    <div
                        class="w-3 h-3 bg-green-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                    ></div>
                    <div>
                        <p class="text-xs font-bold text-gray-700">Disetujui</p>
                        <p class="text-xs text-gray-600">
                            Logbook telah disetujui
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 group">
                    <div
                        class="w-3 h-3 bg-purple-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                    ></div>
                    <div>
                        <p class="text-xs font-bold text-gray-700">
                            Perlu Revisi
                        </p>
                        <p class="text-xs text-gray-600">
                            Perlu diperbaiki dan dikirim ulang
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    logbook: { type: Object, required: true },
    canEdit: { type: Boolean, default: false },
    formatDateShort: { type: Function, required: true },
    getStatusBadgeClass: { type: Function, required: true },
    getStatusDotClass: { type: Function, required: true },
    getStatusText: { type: Function, required: true },
});
</script>
