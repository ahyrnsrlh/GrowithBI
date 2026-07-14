<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    Selection Scorecard
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Transparansi penilaian berdasarkan kriteria seleksi yang berlaku
                </p>
            </div>
            <button
                @click="$emit('edit-evaluation')"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ evaluation ? 'Edit Evaluasi' : 'Mulai Evaluasi' }}
            </button>
        </div>

        <!-- Empty State -->
        <div v-if="!evaluation" class="py-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 text-amber-500 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h4 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Evaluasi</h4>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                Kandidat belum dinilai. Klik tombol "Mulai Evaluasi" di atas untuk memasukkan skor penilaian kriteria seleksi.
            </p>
        </div>

        <!-- Scorecard Data Display -->
        <div v-else class="space-y-6">
            <!-- Grid 2-column: score bars and total display -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left (2 Columns): Score Bars -->
                <div class="md:col-span-2 space-y-4">
                    <div
                        v-for="criterion in evaluation.criteria"
                        :key="criterion.name"
                        class="p-4 bg-gray-50 rounded-xl border border-gray-100"
                    >
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">{{ getCriteriaLabel(criterion.name) }}</h4>
                                <p class="text-xs text-gray-500">Bobot: {{ criterion.weight }}%</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900">{{ criterion.raw_score }}</span>
                                <span class="text-xs text-gray-400 block">Weighted: {{ criterion.weighted_score }}</span>
                            </div>
                        </div>
                        
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div
                                :class="['h-full rounded-full', getProgressBarColor(criterion.raw_score)]"
                                :style="{ width: `${criterion.raw_score}%` }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Right (1 Column): Total Score Badge -->
                <div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl border border-slate-200 flex flex-col justify-between">
                    <div class="text-center">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Skor Akhir</span>
                        <div :class="['text-5xl font-extrabold my-2 inline-block px-4 py-2 rounded-2xl bg-white border shadow-sm', getScoreColorClass(evaluation.final_score)]">
                            {{ evaluation.final_score.toFixed(1) }}
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Rekomendasi</span>
                        <span :class="['inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full border', getRecommendationBadgeClass(evaluation.recommendation_level)]">
                            {{ getRecommendationLabel(evaluation.recommendation_level) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Reviewer Notes -->
            <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                <h4 class="text-sm font-bold text-blue-900 mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Catatan Reviewer
                </h4>
                <p class="text-sm text-blue-800 leading-relaxed whitespace-pre-line italic">
                    "{{ evaluation.reviewer_notes || 'Tidak ada catatan tertulis.' }}"
                </p>
            </div>

            <!-- Decision support warning -->
            <div class="text-xs text-amber-600 bg-amber-50 border border-amber-100 rounded-xl p-3.5 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="leading-relaxed">
                    <span class="font-bold block text-amber-800 mb-0.5">Catatan Penting</span>
                    Rekomendasi di atas adalah sistem pendukung keputusan. Keputusan akhir untuk <strong>Diterima</strong> atau <strong>Ditolak</strong> tetap sepenuhnya berada di tangan Administrator.
                </div>
            </div>

            <!-- Audit Trail & History -->
            <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs text-gray-500 gap-2">
                <div>
                    Reviewer: <span class="font-semibold text-gray-700">{{ evaluation.reviewer_name }}</span> | 
                    Tanggal: <span class="font-semibold text-gray-700">{{ evaluation.evaluation_date }}</span>
                </div>

                <!-- History Toggle -->
                <div v-if="application.evaluation?.score_history?.length > 0" class="relative">
                    <button 
                        @click="showHistory = !showHistory" 
                        class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 transition-colors"
                    >
                        <i class="fas fa-history"></i>
                        Lihat Riwayat Perubahan ({{ application.evaluation.score_history.length }})
                    </button>

                    <!-- History dropdown -->
                    <div 
                        v-if="showHistory" 
                        class="absolute right-0 bottom-6 sm:bottom-auto sm:top-6 mt-1 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-20 p-4 max-h-60 overflow-y-auto"
                    >
                        <h5 class="font-bold text-gray-800 mb-3 text-xs border-b border-gray-100 pb-1.5">Riwayat Evaluasi</h5>
                        <div class="space-y-3">
                            <div 
                                v-for="(hist, index) in application.evaluation.score_history" 
                                :key="index"
                                class="text-xs border-b border-dashed border-gray-100 pb-2 last:border-0 last:pb-0"
                            >
                                <div class="flex justify-between text-[11px] font-bold text-gray-700">
                                    <span>{{ hist.reviewer_name }}</span>
                                    <span class="text-indigo-600">Skor: {{ parseFloat(hist.final_score).toFixed(1) }}</span>
                                </div>
                                <div class="text-[10px] text-gray-400 my-0.5">
                                    {{ formatDateTime(hist.reviewed_at) }}
                                </div>
                                <div class="text-[11px] text-gray-500 font-medium">
                                    Comp: {{ hist.competency_score }} | Mot: {{ hist.motivation_score }} | Int: {{ hist.interview_score }}
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1 italic leading-relaxed truncate" :title="hist.reviewer_notes">
                                    "{{ hist.reviewer_notes }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    evaluation: { type: Object, default: null },
    selectionWeights: { type: Object, required: true },
    application: { type: Object, required: true },
});

defineEmits(["edit-evaluation"]);

const showHistory = ref(false);

const getCriteriaLabel = (name) => {
    const labels = {
        'Competency': 'Kompetensi Teknis',
        'Motivation Letter': 'Motivation Letter',
        'Interview': 'Wawancara'
    };
    return labels[name] || name;
};

const getProgressBarColor = (score) => {
    if (score >= 80) return "bg-emerald-500";
    if (score >= 70) return "bg-blue-500";
    if (score >= 60) return "bg-amber-500";
    return "bg-rose-500";
};

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

const formatDateTime = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    });
};
</script>
