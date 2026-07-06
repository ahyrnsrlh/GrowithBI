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
                    <p class="text-white text-sm mt-1">
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

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Lampiran Dokumentasi
                        </label>
                        <div class="space-y-3">
                            <!-- New Files Queue -->
                            <div v-if="form.attachments?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div
                                    v-for="(file, idx) in form.attachments"
                                    :key="idx"
                                    class="flex items-center justify-between p-2.5 rounded-lg border border-blue-200 bg-blue-50/30 text-xs"
                                >
                                    <span class="truncate font-medium text-blue-700 flex-1 pr-2">{{ file.name }}</span>
                                    <button
                                        type="button"
                                        @click="removeNewFile(idx)"
                                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-1 rounded transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <label
                                class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-xl p-4 bg-white cursor-pointer hover:bg-blue-50/10 transition-all group"
                            >
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-xs font-medium text-gray-600 group-hover:text-blue-600 transition-colors">Unggah File Lampiran</span>
                                <input
                                    type="file"
                                    multiple
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    class="hidden"
                                    @change="handleFileChange"
                                />
                            </label>
                            <span class="text-[10px] text-gray-400 block">Mendukung file: PDF, Word (DOC/DOCX), Gambar (JPG/PNG). Maks: 5MB per file.</span>
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
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    form: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["close", "submit"]);

const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    props.form.attachments = [...(props.form.attachments || []), ...files];
};

const removeNewFile = (idx) => {
    props.form.attachments.splice(idx, 1);
};
</script>
