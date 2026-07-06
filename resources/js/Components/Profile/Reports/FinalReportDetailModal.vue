<template>
    <BaseModal :show="show" @close="$emit('close')">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Detail Laporan Akhir</h3>
                    <p class="text-xs text-gray-500 mt-1">Informasi lengkap dokumen laporan magang Anda</p>
                </div>
                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Loader State -->
            <div v-if="loading" class="py-12 flex flex-col items-center justify-center">
                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-sm text-gray-500">Memuat detail laporan...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="py-12 text-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 mb-1">Gagal Memuat Data</h4>
                <p class="text-xs text-gray-500 max-w-xs mx-auto mb-4">{{ error }}</p>
                <button
                    @click="fetchReportDetails"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors"
                >
                    Coba Lagi
                </button>
            </div>

            <!-- Content Area -->
            <div v-else-if="report" class="mt-6 space-y-6 max-h-[60vh] overflow-y-auto pr-1">
                <!-- Title & Status -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div class="space-y-1">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Judul Laporan</span>
                        <h4 class="text-base font-bold text-gray-900">{{ report.title }}</h4>
                    </div>
                    <span
                        :class="getStatusClass(report.status)"
                        class="px-3 py-1 text-xs font-semibold rounded-full w-fit self-start sm:self-center"
                    >
                        {{ getStatusText(report.status) }}
                    </span>
                </div>

                <!-- Description Section -->
                <div class="space-y-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">Isi / Deskripsi Laporan</span>
                    <div class="prose prose-sm max-w-none text-gray-700 bg-white rounded-xl border border-gray-150 p-4 min-h-[100px] whitespace-pre-wrap">
                        {{ report.description || 'Tidak ada deskripsi.' }}
                    </div>
                </div>

                <!-- Meta Details & Files -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50/30 p-4 rounded-xl border border-gray-100 space-y-3.5">
                        <div class="flex items-center gap-2.5 text-xs text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tanggal Diunggah</p>
                                <p class="font-medium text-gray-800 mt-0.5">{{ formatDate(report.created_at) }}</p>
                            </div>
                        </div>

                        <div v-if="report.application?.division?.name" class="flex items-center gap-2.5 text-xs text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Divisi Magang</p>
                                <p class="font-medium text-gray-800 mt-0.5">{{ report.application.division.name }}</p>
                            </div>
                        </div>

                        <div v-if="report.reviewer?.name" class="flex items-center gap-2.5 text-xs text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Reviewer / Pembimbing</p>
                                <p class="font-medium text-gray-800 mt-0.5">{{ report.reviewer.name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Download Area -->
                    <div class="bg-gray-50/30 p-4 rounded-xl border border-gray-100 flex flex-col justify-between">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0 text-red-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-700 truncate">{{ report.file_name || 'Dokumen Laporan' }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ formatBytes(report.file_size) }} &bull; {{ report.file_type?.split('/').pop()?.toUpperCase() || 'PDF' }}</p>
                            </div>
                        </div>

                        <a
                            :href="route('peserta.reports.download', report.id)"
                            class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Dokumen
                        </a>
                    </div>
                </div>

                <!-- Review Notes Section -->
                <div v-if="report.feedback" class="bg-purple-50 rounded-xl p-4 border border-purple-100 space-y-2">
                    <div class="flex items-center gap-1.5 text-purple-800 font-bold text-xs">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Feedback Reviewer
                    </div>
                    <p class="text-xs text-purple-950 whitespace-pre-wrap">{{ report.feedback }}</p>
                </div>
            </div>
        </div>
    </BaseModal>
</template>

<script setup>
import { ref, watch } from "vue";
import BaseModal from "@/Components/BaseModal.vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    reportId: { type: [Number, String], default: null },
});

const emit = defineEmits(["close"]);

const report = ref(null);
const loading = ref(false);
const error = ref(null);

const fetchReportDetails = () => {
    if (!props.reportId) return;

    loading.value = true;
    error.value = null;

    window.axios
        .get(route("peserta.reports.show", props.reportId))
        .then((response) => {
            report.value = response.data?.report || response.data;
        })
        .catch((err) => {
            console.error("Failed to load final report:", err);
            error.value = "Terjadi kesalahan saat memuat data laporan.";
        })
        .finally(() => {
            loading.value = false;
        });
};

watch(
    () => props.show,
    (isShown) => {
        if (isShown) {
            report.value = null;
            fetchReportDetails();
        }
    }
);

const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatBytes = (bytes, decimals = 2) => {
    if (!bytes) return "0 Bytes";
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800 border border-gray-200",
        submitted: "bg-orange-50 text-orange-700 border border-orange-200",
        approved: "bg-green-50 text-green-700 border border-green-200",
        revision: "bg-purple-50 text-purple-700 border border-purple-200",
    };
    return classes[status] || "bg-gray-150 text-gray-600";
};

const getStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Pending",
        approved: "Disetujui",
        revision: "Perlu Revisi",
    };
    return texts[status] || "Unknown";
};
</script>
