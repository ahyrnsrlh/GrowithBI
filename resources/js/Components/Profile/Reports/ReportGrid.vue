<template>
    <div>
        <div
            v-if="!reports || reports.length === 0"
            class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16"
        >
            <div
                class="w-20 h-20 mx-auto mb-6 bg-emerald-100 rounded-full flex items-center justify-center"
            >
                <svg
                    class="w-10 h-10 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                Belum Ada Laporan
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Upload laporan akhir berdasarkan kegiatan magang Anda.
            </p>
            <button
                @click="$emit('create')"
                class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl"
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
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Buat Laporan Pertama
            </button>
        </div>

        <div v-else-if="reports && reports.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div
                    v-for="report in reports"
                    :key="report?.id || Math.random()"
                    class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group"
                >
                    <div
                        class="px-5 py-4 bg-gradient-to-r from-[#F6F8FA] to-white border-b border-gray-100"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-4 h-4 text-white"
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
                                    <h4
                                        class="text-base font-bold text-blue-600 group-hover:text-blue-600 transition-colors truncate"
                                    >
                                        {{
                                            report?.title ||
                                            "Laporan Tanpa Judul"
                                        }}
                                    </h4>
                                </div>
                                <p class="text-xs text-gray-500 ml-10">
                                    Laporan Akhir Magang
                                </p>
                            </div>

                            <span
                                v-if="
                                    (report?.status || 'submitted') ===
                                    'submitted'
                                "
                                class="px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"
                                ></div>
                                Pending
                            </span>
                            <span
                                v-else-if="
                                    (report?.status || 'submitted') ===
                                    'approved'
                                "
                                class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-green-400"
                                ></div>
                                Disetujui
                            </span>
                            <span
                                v-else-if="
                                    (report?.status || 'submitted') ===
                                    'revision'
                                "
                                class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-purple-400"
                                ></div>
                                Perlu Revisi
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        <div
                            v-if="report?.description"
                            class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-400"
                        >
                            <p class="text-sm text-blue-900 line-clamp-3">
                                {{ report.description }}
                            </p>
                        </div>

                        <div
                            v-if="report?.file_path"
                            class="bg-green-50 rounded-lg p-3 flex items-center gap-3"
                        >
                            <div
                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600"
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
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-green-900 font-semibold">
                                    Dokumen Tersimpan
                                </p>
                                <p class="text-xs text-green-600 truncate">
                                    {{ report.file_path.split("/").pop() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center"
                    >
                        <span
                            class="text-xs text-gray-500 flex items-center gap-1.5"
                        >
                            <svg
                                class="w-3.5 h-3.5"
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
                            {{
                                report?.created_at
                                    ? formatDate(report.created_at)
                                    : "N/A"
                            }}
                        </span>
                        <div class="flex gap-2">
                            <a
                                v-if="report?.file_path"
                                :href="`/storage/${report.file_path}`"
                                target="_blank"
                                class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center gap-1.5"
                            >
                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                Download
                            </a>
                            <button
                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                title="Lihat Detail"
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
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    reports: { type: Array, default: () => [] },
    formatDate: { type: Function, required: true },
});

defineEmits(["create"]);
</script>
