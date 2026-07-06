<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="$emit('close')"
    >
        <div
            class="flex items-start sm:items-center justify-center min-h-screen px-2 sm:px-4 py-4 text-center"
        >
            <div
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
            ></div>

            <div
                class="relative inline-block w-full max-w-4xl max-h-[92vh] p-4 sm:p-6 my-2 sm:my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
            >
                <div
                    class="flex items-center justify-between mb-6 pb-4 border-b"
                >
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Tambah Logbook Harian
                        </h3>
                        <p class="text-gray-600 mt-1">
                            Catat aktivitas dan pencapaian hari ini
                        </p>
                    </div>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
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

                <form
                    @submit.prevent="$emit('submit')"
                    class="space-y-6 max-h-[calc(92vh-120px)] overflow-y-auto pr-1"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="date"
                                v-model="form.date"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            />
                            <div
                                v-if="form.errors.date"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.date }}
                            </div>
                        </div>

                        <div>
                            <label
                                for="duration"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Durasi (jam)
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="duration"
                                v-model="form.duration"
                                min="1"
                                max="12"
                                step="0.5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="8"
                                required
                            />
                            <div
                                v-if="form.errors.duration"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.duration }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Judul Aktivitas
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Pengembangan Fitur Login System"
                            required
                        />
                        <div
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="activities"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi Aktivitas
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="activities"
                            v-model="form.activities"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan secara detail aktivitas yang telah dilakukan hari ini..."
                            required
                        ></textarea>
                        <div
                            v-if="form.errors.activities"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.activities }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="learning_points"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Pembelajaran yang Didapat
                        </label>
                        <textarea
                            id="learning_points"
                            v-model="form.learning_points"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Apa yang Anda pelajari hari ini..."
                        ></textarea>
                        <div
                            v-if="form.errors.learning_points"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.learning_points }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="challenges"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Kendala yang Dihadapi
                        </label>
                        <textarea
                            id="challenges"
                            v-model="form.challenges"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Kendala atau kesulitan yang dihadapi..."
                        ></textarea>
                        <div
                            v-if="form.errors.challenges"
                            class="mt-1 text-sm text-red-600"
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
                        class="flex justify-end space-x-3 pt-5 mt-2 border-t sticky bottom-2 bg-white pb-3 z-10"
                    >
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Logbook</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    show: { type: Boolean, default: false },
    form: { type: Object, required: true },
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
