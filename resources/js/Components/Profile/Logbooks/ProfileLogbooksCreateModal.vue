<template>
    <BaseModal
        :show="show"
        title="Tambah Logbook Baru"
        maxWidth="max-w-2xl"
        @close="$emit('close')"
    >
        <form @submit.prevent="$emit('submit')" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.date"
                        type="date"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                    />
                    <div v-if="form.errors.date" class="text-red-600 text-xs mt-1">
                        {{ form.errors.date }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Durasi (jam) <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model.number="form.duration"
                        type="number"
                        step="0.5"
                        min="0.5"
                        max="24"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                    />
                    <div v-if="form.errors.duration" class="text-red-600 text-xs mt-1">
                        {{ form.errors.duration }}
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Judul Kegiatan <span class="text-red-500">*</span>
                </label>
                <input
                    v-model="form.title"
                    type="text"
                    required
                    placeholder="Contoh: Regulatory Technology (RegTech)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                />
                <div v-if="form.errors.title" class="text-red-600 text-xs mt-1">
                    {{ form.errors.title }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi Kegiatan <span class="text-red-500">*</span>
                </label>
                <textarea
                    v-model="form.activities"
                    rows="4"
                    required
                    placeholder="Jelaskan kegiatan yang Anda lakukan hari ini..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                ></textarea>
                <div v-if="form.errors.activities" class="text-red-600 text-xs mt-1">
                    {{ form.errors.activities }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Poin Pembelajaran
                </label>
                <textarea
                    v-model="form.learning_points"
                    rows="3"
                    placeholder="Apa yang Anda pelajari hari ini? (opsional)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                ></textarea>
                <div v-if="form.errors.learning_points" class="text-red-600 text-xs mt-1">
                    {{ form.errors.learning_points }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tantangan & Hambatan
                </label>
                <textarea
                    v-model="form.challenges"
                    rows="3"
                    placeholder="Kendala yang dihadapi (opsional)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                ></textarea>
                <div v-if="form.errors.challenges" class="text-red-600 text-xs mt-1">
                    {{ form.errors.challenges }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Lampiran Dokumentasi
                </label>
                <div class="space-y-3">
                    <!-- New Files Queue -->
                    <div v-if="form.attachments?.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            v-for="(file, idx) in form.attachments"
                            :key="idx"
                            class="flex items-center justify-between p-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-xs"
                        >
                            <span class="truncate font-medium text-blue-700 flex-1 pr-2">{{ file.name }}</span>
                            <button
                                type="button"
                                @click="removeNewFile(idx)"
                                class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-1.5 rounded-lg transition-colors"
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

            <div class="flex justify-end gap-3 pt-5 border-t border-gray-150">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 text-sm bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-md hover:shadow-lg"
                >
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>Simpan Logbook</span>
                </button>
            </div>
        </form>
    </BaseModal>
</template>

<script setup>
import BaseModal from "@/Components/BaseModal.vue";

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

defineEmits(["close", "submit"]);

const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    props.form.attachments = [...(props.form.attachments || []), ...files];
};

const removeNewFile = (idx) => {
    props.form.attachments.splice(idx, 1);
};
</script>
