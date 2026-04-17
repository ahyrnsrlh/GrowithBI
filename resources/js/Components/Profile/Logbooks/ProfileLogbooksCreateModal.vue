<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="$emit('close')"
    >
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
        >
            <div
                class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 border-b border-gray-200 flex items-center justify-between z-10"
            >
                <div>
                    <h3 class="text-2xl font-bold text-white">
                        Tambah Logbook Baru
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">
                        Catat kegiatan harian Anda
                    </p>
                </div>
                <button
                    @click="$emit('close')"
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="$emit('submit')" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.date"
                            type="date"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        />
                        <div
                            v-if="form.errors.date"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.date }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Durasi (jam) <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model.number="form.duration"
                            type="number"
                            step="0.5"
                            min="0.5"
                            max="24"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        />
                        <div
                            v-if="form.errors.duration"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.duration }}
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Judul Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.title"
                        type="text"
                        required
                        placeholder="Contoh: Regulatory Technology (RegTech)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                    />
                    <div
                        v-if="form.errors.title"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.title }}
                    </div>
                </div>

                <div class="mt-6">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Deskripsi Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        v-model="form.activities"
                        rows="4"
                        required
                        placeholder="Jelaskan kegiatan yang Anda lakukan hari ini..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                    ></textarea>
                    <div
                        v-if="form.errors.activities"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.activities }}
                    </div>
                </div>

                <div class="mt-6">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Poin Pembelajaran
                    </label>
                    <textarea
                        v-model="form.learning_points"
                        rows="3"
                        placeholder="Apa yang Anda pelajari hari ini? (opsional)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                    ></textarea>
                    <div
                        v-if="form.errors.learning_points"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.learning_points }}
                    </div>
                </div>

                <div class="mt-6">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Tantangan & Hambatan
                    </label>
                    <textarea
                        v-model="form.challenges"
                        rows="3"
                        placeholder="Kendala yang dihadapi (opsional)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                    ></textarea>
                    <div
                        v-if="form.errors.challenges"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.challenges }}
                    </div>
                </div>

                <div
                    class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200"
                >
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-6 py-2.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-medium transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Logbook</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    form: {
        type: Object,
        required: true,
    },
});

defineEmits(["close", "submit"]);
</script>
