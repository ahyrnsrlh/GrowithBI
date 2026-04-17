<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2"
            >Lampiran (Opsional)</label
        >
        <div
            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
        >
            <input
                ref="fileInput"
                type="file"
                multiple
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                @change="$emit('file-upload', $event)"
                class="hidden"
            />

            <div v-if="selectedFiles.length === 0">
                <svg
                    class="w-12 h-12 mx-auto text-gray-400 mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                </svg>
                <button
                    type="button"
                    @click="openFilePicker"
                    class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                    </svg>
                    Pilih File
                </button>
                <p class="text-sm text-gray-500 mt-2">
                    PDF, DOC, DOCX, JPG, PNG (Max: 5MB per file)
                </p>
            </div>

            <div v-else class="space-y-2">
                <div
                    v-for="(file, index) in selectedFiles"
                    :key="`${file.name}-${index}`"
                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-gray-400 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        <span class="text-sm text-gray-700">{{
                            file.name
                        }}</span>
                        <span class="text-xs text-gray-500 ml-2"
                            >({{ formatFileSize(file.size) }})</span
                        >
                    </div>
                    <button
                        type="button"
                        @click="$emit('remove-file', index)"
                        class="text-red-500 hover:text-red-700"
                    >
                        <svg
                            class="w-4 h-4"
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
                <button
                    type="button"
                    @click="openFilePicker"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                >
                    + Tambah File Lain
                </button>
            </div>
        </div>
        <p v-if="attachmentsError" class="mt-1 text-sm text-red-600">
            {{ attachmentsError }}
        </p>
    </div>
</template>

<script setup>
import { ref } from "vue";

defineProps({
    selectedFiles: {
        type: Array,
        default: () => [],
    },
    formatFileSize: {
        type: Function,
        required: true,
    },
    attachmentsError: {
        type: String,
        default: "",
    },
});

defineEmits(["file-upload", "remove-file"]);

const fileInput = ref(null);

const openFilePicker = () => {
    if (fileInput.value) {
        fileInput.value.click();
    }
};
</script>
