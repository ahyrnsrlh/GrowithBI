<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    selectedFile: {
        type: Object,
        default: null,
    },
    isDragging: {
        type: Boolean,
        required: true,
    },
    setFileInputRef: {
        type: Function,
        required: true,
    },
    openFilePicker: {
        type: Function,
        required: true,
    },
    handleFileSelect: {
        type: Function,
        required: true,
    },
    handleDrop: {
        type: Function,
        required: true,
    },
    handleDragEnter: {
        type: Function,
        required: true,
    },
    handleDragLeave: {
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
            File Laporan <span class="text-red-500">*</span>
        </label>

        <div
            @drop.prevent="handleDrop"
            @dragover.prevent
            @dragenter.prevent="handleDragEnter"
            @dragleave.prevent="handleDragLeave"
            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors"
            :class="{
                'border-red-500': form.errors.report_file,
                'border-blue-400 bg-blue-50': isDragging,
            }"
        >
            <div v-if="!selectedFile">
                <svg
                    class="w-12 h-12 text-gray-400 mx-auto mb-4"
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
                <p class="text-lg font-medium text-gray-900 mb-2">
                    Upload file laporan
                </p>
                <p class="text-sm text-gray-500 mb-4">
                    Drag dan drop file atau klik untuk browse
                </p>
                <button
                    type="button"
                    @click="openFilePicker"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
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
            </div>

            <div v-else class="space-y-4">
                <div class="flex items-center justify-center space-x-3">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
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
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">
                            {{ selectedFile.name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ formatFileSize(selectedFile.size) }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="removeFile"
                    class="text-sm text-red-600 hover:text-red-700 font-medium"
                >
                    Hapus File
                </button>
            </div>
        </div>

        <input
            type="file"
            :ref="setFileInputRef"
            @change="handleFileSelect"
            accept=".pdf,.doc,.docx,.xls,.xlsx"
            class="hidden"
        />

        <p class="mt-2 text-xs text-gray-500">
            Format yang didukung: PDF, Word (.doc, .docx), Excel (.xls, .xlsx).
            Maksimal 10MB.
        </p>
        <p v-if="form.errors.report_file" class="mt-1 text-sm text-red-600">
            {{ form.errors.report_file }}
        </p>
    </div>
</template>
