<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
    >
        <div class="relative mx-auto p-6 border w-full max-w-lg shadow-2xl rounded-2xl bg-white transition-all transform">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">
                    Input Evaluasi & Penilaian
                </h3>
                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="$emit('submit')">
                <!-- Info weights -->
                <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-xs text-blue-800 leading-relaxed">
                        <span class="font-bold block mb-1">Bobot Penilaian Aktif</span>
                        Kompetensi: <span class="font-bold">{{ weights.competency }}%</span> | 
                        Motivation Letter: <span class="font-bold">{{ weights.motivation }}%</span> | 
                        Wawancara: <span class="font-bold">{{ weights.interview }}%</span>.
                        <br/>Masukkan nilai mentah (0 - 100) untuk masing-masing kriteria.
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    <!-- Competency Score -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">
                                Nilai Kompetensi <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-500 font-medium">Bobot: {{ weights.competency }}%</span>
                        </div>
                        <input
                            type="number"
                            v-model.number="form.competency_score"
                            min="0"
                            max="100"
                            step="0.1"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:outline-none transition-all"
                            placeholder="0 - 100"
                            required
                        />
                    </div>

                    <!-- Motivation Letter Score -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">
                                Nilai Motivation Letter <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-500 font-medium">Bobot: {{ weights.motivation }}%</span>
                        </div>
                        <input
                            type="number"
                            v-model.number="form.motivation_score"
                            min="0"
                            max="100"
                            step="0.1"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:outline-none transition-all"
                            placeholder="0 - 100"
                            required
                        />
                    </div>

                    <!-- Interview Score -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">
                                Nilai Wawancara <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-500 font-medium">Bobot: {{ weights.interview }}%</span>
                        </div>
                        <input
                            type="number"
                            v-model.number="form.interview_score"
                            min="0"
                            max="100"
                            step="0.1"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:outline-none transition-all"
                            placeholder="0 - 100"
                            required
                        />
                    </div>

                    <!-- Reviewer Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Catatan Kualitatif / Reviewer Notes <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.reviewer_notes"
                            rows="4"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:outline-none transition-all resize-none"
                            placeholder="Berikan umpan balik atau penjelasan mengenai hasil penilaian kandidat..."
                            required
                        ></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors"
                    >
                        Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    form: { type: Object, required: true },
    weights: { type: Object, required: true },
});

defineEmits(["close", "submit"]);
</script>
