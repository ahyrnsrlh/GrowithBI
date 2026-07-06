<template>
    <BaseModal
        :show="show"
        title="Upload Laporan Akhir"
        maxWidth="max-w-2xl"
        @close="$emit('close')"
    >
        <form @submit.prevent="$emit('submit')" class="space-y-5">
            <div>
                <label
                    for="title"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Judul Laporan <span class="text-red-500">*</span>
                </label>
                <input
                    v-model="form.title"
                    type="text"
                    id="title"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                    placeholder="Masukkan judul laporan"
                />
                <p v-if="form.errors.title" class="text-xs text-red-600 mt-1.5">{{ form.errors.title }}</p>
            </div>

            <div>
                <label
                    for="description"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="4"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm resize-none"
                    placeholder="Jelaskan ringkasan laporan Anda"
                ></textarea>
                <p v-if="form.errors.description" class="text-xs text-red-600 mt-1.5">{{ form.errors.description }}</p>
            </div>

            <div>
                <label
                    for="report_file"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    File Laporan (PDF) <span class="text-red-500">*</span>
                </label>
                <input
                    @change="$emit('file-change', $event)"
                    type="file"
                    id="report_file"
                    accept=".pdf"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                />
                <p class="text-xs text-gray-500 mt-1.5">
                    File harus berformat PDF dan maksimal 10MB
                </p>
                <p v-if="form.errors.report_file" class="text-xs text-red-600 mt-1.5">{{ form.errors.report_file }}</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
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
                    <span v-if="form.processing">
                        <svg
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        Menyimpan...
                    </span>
                    <span v-else>Simpan Laporan</span>
                </button>
            </div>
        </form>
    </BaseModal>
</template>

<script setup>
import BaseModal from "@/Components/BaseModal.vue";

defineProps({
    show: { type: Boolean, default: false },
    form: { type: Object, required: true },
});

defineEmits(["close", "submit", "file-change"]);
</script>
