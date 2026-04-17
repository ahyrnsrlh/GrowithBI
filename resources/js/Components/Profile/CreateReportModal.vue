<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="$emit('close')"
    >
        <div
            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center"
        >
            <div
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
            ></div>

            <div
                class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
            >
                <div
                    class="flex items-center justify-between mb-6 pb-4 border-b"
                >
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Upload Laporan Akhir
                        </h3>
                        <p class="text-gray-600 mt-1">
                            Upload laporan akhir berdasarkan kegiatan magang
                            Anda
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

                <form @submit.prevent="$emit('submit')" class="space-y-6">
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Judul Laporan
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            id="title"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan judul laporan"
                        />
                    </div>

                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                        </label>
                        <textarea
                            v-model="form.description"
                            id="description"
                            rows="4"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan ringkasan laporan Anda"
                        ></textarea>
                    </div>

                    <div>
                        <label
                            for="report_file"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            File Laporan (PDF)
                        </label>
                        <input
                            @change="$emit('file-change', $event)"
                            type="file"
                            id="report_file"
                            accept=".pdf"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p class="text-sm text-gray-500 mt-1">
                            File harus berformat PDF dan maksimal 10MB
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">
                                <svg
                                    class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline"
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
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    form: { type: Object, required: true },
});

defineEmits(["close", "submit", "file-change"]);
</script>
