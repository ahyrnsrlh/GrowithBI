<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <svg
                class="w-5 h-5 inline mr-2 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                />
            </svg>
            Lampiran (Opsional)
        </h2>

        <div v-if="existingFiles.length > 0" class="mb-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3">
                File yang Sudah Ada
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    v-for="(file, index) in existingFiles"
                    :key="`existing-${index}`"
                    class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200"
                >
                    <div class="flex-shrink-0 mr-4">
                        <svg
                            class="w-8 h-8 text-gray-500"
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
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ file.name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ formatFileSize(file.size) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <button
                            type="button"
                            class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200 transition-colors"
                            @click="$emit('remove-existing-file', index)"
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
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"
                >Tambah File Baru</label
            >
            <div
                class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors"
                :class="
                    isDragging
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-300 hover:border-gray-400'
                "
                @drop="onDrop"
                @dragover="onDragOver"
                @dragleave="onDragLeave"
                @click="openFilePicker"
            >
                <input
                    ref="picker"
                    type="file"
                    multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx"
                    class="hidden"
                    @change="onSelect"
                />
                <svg
                    class="w-12 h-12 mx-auto mb-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-1">
                    Drag & drop file atau klik untuk pilih
                </p>
                <p class="text-sm text-gray-500">
                    PDF, DOC, JPG, PNG, XLS (Max. 10MB per file)
                </p>
            </div>

            <div v-if="newFiles.length > 0" class="mt-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div
                        v-for="(file, index) in newFiles"
                        :key="`new-${index}`"
                        class="flex items-center p-4 bg-blue-50 rounded-lg border border-blue-200"
                    >
                        <div class="flex-shrink-0 mr-4">
                            <svg
                                class="w-8 h-8 text-blue-500"
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
                        </div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-medium text-gray-900 truncate"
                            >
                                {{ file.name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ formatFileSize(file.size) }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <button
                                type="button"
                                class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200 transition-colors"
                                @click="$emit('remove-new-file', index)"
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
                    </div>
                </div>
            </div>

            <p v-if="errors.attachments" class="mt-2 text-sm text-red-600">
                {{ errors.attachments }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const picker = ref(null);
const isDragging = ref(false);

const emit = defineEmits([
    "add-files",
    "remove-new-file",
    "remove-existing-file",
]);

defineProps({
    existingFiles: {
        type: Array,
        default: () => [],
    },
    newFiles: {
        type: Array,
        default: () => [],
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    formatFileSize: {
        type: Function,
        required: true,
    },
});

const onDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const onDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};

const onDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    emit("add-files", Array.from(event.dataTransfer.files));
};

const openFilePicker = () => {
    picker.value?.click();
};

const onSelect = (event) => {
    emit("add-files", Array.from(event.target.files));
};
</script>
