<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Update Status Pendaftaran
                </h3>
                <form @submit.prevent="$emit('submit')">
                    <!-- Current Status Info Box -->
                    <div class="mb-5 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-1">Panduan Alur Pendaftaran</span>
                            Status pendaftaran saat ini adalah <span class="font-extrabold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md inline-block mt-0.5">{{ getLabel(currentStatus) }}</span>.
                            Pilihan di bawah telah disaring otomatis untuk memandu proses tahap demi tahap.
                        </div>
                    </div>

                    <!-- Evaluation Summary Box -->
                    <div v-if="['accepted', 'rejected', 'letter_ready'].includes(statusForm.status)" class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2 font-semibold">
                            Ringkasan Evaluasi Pendaftar
                        </label>
                        <div v-if="evaluation" class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3 text-xs text-slate-700">
                            <div class="flex justify-between items-center">
                                <span>Skor Akhir:</span>
                                <span :class="['font-extrabold px-2 py-0.5 rounded-md border text-[11px]', getScoreColorClass(evaluation.final_score)]">
                                    {{ evaluation.final_score.toFixed(1) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Rekomendasi:</span>
                                <span :class="['font-bold px-2 py-0.5 rounded-full border text-[10px]', getRecommendationBadgeClass(evaluation.recommendation_level)]">
                                    {{ getRecommendationLabel(evaluation.recommendation_level) }}
                                </span>
                            </div>
                            <div class="border-t border-slate-200 pt-2">
                                <span class="font-semibold block mb-1">Catatan Reviewer:</span>
                                <p class="italic text-slate-600">"{{ evaluation.reviewer_notes || '-' }}"</p>
                            </div>
                        </div>
                        <div v-else class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 leading-relaxed flex gap-2">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <span class="font-bold block mb-0.5 text-amber-900">Belum Dievaluasi</span>
                                Pendaftar belum memiliki data evaluasi. Anda disarankan mengisi Selection Scorecard terlebih dahulu sebelum menerima atau menolak.
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Status Baru
                        </label>
                        <select
                            v-model="statusForm.status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                        >
                            <option
                                v-for="option in filteredOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <div
                        v-if="statusForm.status === 'interview_scheduled'"
                        class="space-y-4 mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200"
                    >
                        <h4 class="text-sm font-semibold text-blue-900">
                            Detail Jadwal Wawancara
                        </h4>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Wawancara
                            </label>
                            <input
                                type="date"
                                v-model="statusForm.interview_date"
                                :min="minDate"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Jam Wawancara
                            </label>
                            <input
                                type="time"
                                v-model="statusForm.interview_time"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Lokasi
                            </label>
                            <input
                                type="text"
                                v-model="statusForm.interview_location"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Contoh: Kantor Pusat, Ruang Meeting A"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Detail Lokasi / Instruksi
                            </label>
                            <textarea
                                v-model="statusForm.interview_location_detail"
                                rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Contoh: Jl. Contoh No. 123, Jakarta. Silakan lapor ke resepsionis lantai 3"
                            ></textarea>
                        </div>
                    </div>

                    <div v-if="statusForm.status === 'rejected'" class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Alasan Penolakan
                        </label>
                        <textarea
                            v-model="statusForm.rejection_reason"
                            rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Jelaskan alasan penolakan (opsional)"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Catatan Admin
                        </label>
                        <textarea
                            v-model="statusForm.admin_notes"
                            rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Tambahkan catatan (opsional)"
                        ></textarea>
                    </div>

                    <!-- Quota Warning -->
                    <div
                        v-if="isQuotaFull && isAccepting"
                        class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-start gap-3"
                    >
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="leading-relaxed">
                            <span class="font-bold block mb-1 text-red-800">Kuota Divisi Telah Penuh</span>
                            Peserta tidak dapat diterima karena kuota divisi telah terpenuhi. Silakan pilih divisi lain atau tambah kuota terlebih dahulu.
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="isQuotaFull && isAccepting"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    statusForm: { type: Object, required: true },
    currentStatus: { type: String, default: "menunggu" },
    division: { type: Object, required: true },
    evaluation: { type: Object, default: null },
});

defineEmits(["close", "submit"]);

const isQuotaFull = computed(() => {
    return props.division && (props.division.available_quota ?? 0) <= 0;
});

const isAccepting = computed(() => {
    return ["accepted", "letter_ready"].includes(props.statusForm.status);
});

const allStatusOptions = [
    { value: "menunggu", label: "Menunggu Review" },
    { value: "in_review", label: "Dalam Proses Seleksi" },
    { value: "interview_scheduled", label: "Wawancara Dijadwalkan" },
    { value: "accepted", label: "Diterima" },
    { value: "rejected", label: "Ditolak" },
    { value: "letter_ready", label: "Surat Penerimaan Tersedia" },
    { value: "expired", label: "Kedaluwarsa" },
];

const getLabel = (value) => {
    const option = allStatusOptions.find((o) => o.value === value);
    return option ? option.label : value;
};

const filteredOptions = computed(() => {
    const current = props.currentStatus || "menunggu";

    // Status transition map to enforce step-by-step update flow
    const transitions = {
        menunggu: ["menunggu", "in_review", "rejected", "expired"],
        in_review: ["in_review", "interview_scheduled", "rejected", "expired"],
        interview_scheduled: [
            "interview_scheduled",
            "accepted",
            "rejected",
            "expired",
        ],
        accepted: ["accepted", "letter_ready", "expired"],
        letter_ready: ["letter_ready", "expired"],
        rejected: ["rejected", "menunggu"],
        expired: ["expired", "menunggu"],
    };

    const allowed = transitions[current] || [current];
    return allStatusOptions.filter((option) => allowed.includes(option.value));
});

const minDate = computed(() => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
});

const getScoreColorClass = (score) => {
    if (score >= 90) return "text-emerald-600 border-emerald-200 bg-emerald-50/50";
    if (score >= 80) return "text-blue-600 border-blue-200 bg-blue-50/50";
    if (score >= 70) return "text-amber-600 border-amber-200 bg-amber-50/50";
    return "text-red-600 border-red-200 bg-red-50/50";
};

const getRecommendationLabel = (level) => {
    const levels = {
        'Excellent Candidate': 'Sangat Direkomendasikan',
        'Recommended': 'Direkomendasikan',
        'Recommended with Consideration': 'Direkomendasikan dengan Pertimbangan',
        'Not Recommended': 'Tidak Direkomendasikan'
    };
    return levels[level] || level;
};

const getRecommendationBadgeClass = (level) => {
    const classes = {
        'Excellent Candidate': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Recommended': 'bg-blue-50 text-blue-700 border-blue-200',
        'Recommended with Consideration': 'bg-amber-50 text-amber-700 border-amber-200',
        'Not Recommended': 'bg-red-50 text-red-700 border-red-200'
    };
    return classes[level] || 'bg-gray-50 text-gray-700 border-gray-200';
};
</script>
