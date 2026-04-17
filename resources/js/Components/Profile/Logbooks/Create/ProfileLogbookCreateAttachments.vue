<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    handleDrop: {
        type: Function,
        required: true,
    },
    handleFileSelect: {
        type: Function,
        required: true,
    },
    removeFile: {
        type: Function,
        required: true,
    },
    formatFileSize: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Lampiran (opsional)
        </label>
        <div
            @drop="handleDrop"
            @dragover.prevent
            @dragenter.prevent
            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
        >
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 48 48"
            >
                <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            <div class="mt-4">
                <label for="attachments" class="cursor-pointer">
                    <span class="mt-2 block text-sm font-medium text-gray-900">
                        Seret file ke sini atau klik untuk pilih
                    </span>
                    <span class="mt-1 block text-xs text-gray-500">
                        PNG, JPG, PDF hingga 10MB
                    </span>
                </label>
                <input
                    id="attachments"
                    type="file"
                    multiple
                    @change="handleFileSelect"
                    class="hidden"
                    accept="image/*,.pdf"
                />
            </div>
        </div>

        <div v-if="form.attachments.length > 0" class="mt-4 space-y-2">
            <h4 class="text-sm font-medium text-gray-700">
                File yang dipilih:
            </h4>
            <div class="space-y-2">
                <div
                    v-for="(file, index) in form.attachments"
                    :key="index"
                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                >
                    <div class="flex items-center space-x-3">
                        <svg
                            class="h-8 w-8 text-gray-400"
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
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ file.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ formatFileSize(file.size) }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="removeFile(index)"
                        class="text-red-500 hover:text-red-700"
                    >
                        <svg
                            class="h-5 w-5"
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
</template>
