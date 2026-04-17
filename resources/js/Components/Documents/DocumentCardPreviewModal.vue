<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showPreview"
                class="fixed inset-0 z-50 overflow-y-auto"
                @keydown.esc="$emit('close')"
            >
                <div
                    class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20"
                >
                    <div
                        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                        @click="$emit('close')"
                    ></div>

                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="showPreview"
                            class="relative bg-white rounded-lg max-w-4xl w-full shadow-xl"
                            @click.stop
                        >
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-200"
                            >
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ documentName }}
                                </h3>
                                <button
                                    @click="$emit('close')"
                                    class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100"
                                    title="Tutup (ESC)"
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

                            <div
                                class="p-4 bg-gray-50 overflow-auto"
                                style="max-height: 70vh"
                            >
                                <div
                                    v-if="previewLoading"
                                    class="flex items-center justify-center min-h-[60vh]"
                                >
                                    <div class="text-center">
                                        <svg
                                            class="w-12 h-12 mx-auto text-blue-600 animate-spin"
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
                                        <p class="mt-4 text-gray-600">
                                            Memuat dokumen...
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-else-if="previewError"
                                    class="flex items-center justify-center min-h-[60vh]"
                                >
                                    <div class="text-center">
                                        <svg
                                            class="w-16 h-16 mx-auto text-red-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                            />
                                        </svg>
                                        <p class="mt-4 text-gray-600">
                                            Gagal memuat dokumen
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ previewError }}
                                        </p>
                                        <button
                                            @click="$emit('retry-preview')"
                                            class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors"
                                        >
                                            Coba Lagi
                                        </button>
                                    </div>
                                </div>

                                <iframe
                                    v-else-if="isPDF"
                                    :src="previewUrl"
                                    class="w-full min-h-[60vh] rounded border border-gray-200"
                                    @load="$emit('preview-load')"
                                    @error="$emit('preview-error')"
                                ></iframe>

                                <div v-else class="flex justify-center">
                                    <img
                                        :src="previewUrl"
                                        :alt="documentName"
                                        class="max-w-full h-auto rounded shadow-sm"
                                        @load="$emit('preview-load')"
                                        @error="$emit('preview-error')"
                                    />
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50"
                            >
                                <div class="text-sm text-gray-500">
                                    <span v-if="isPDF">Dokumen PDF</span>
                                    <span v-else>Dokumen Gambar</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button
                                        @click="$emit('download')"
                                        :disabled="isDownloading"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50"
                                    >
                                        <svg
                                            v-if="!isDownloading"
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4 mr-2 animate-spin"
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
                                        Unduh
                                    </button>
                                    <button
                                        @click="$emit('open-new-tab')"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors"
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
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                            />
                                        </svg>
                                        Buka Tab Baru
                                    </button>
                                    <button
                                        @click="$emit('close')"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    showPreview: {
        type: Boolean,
        default: false,
    },
    documentName: {
        type: String,
        required: true,
    },
    previewLoading: {
        type: Boolean,
        default: false,
    },
    previewError: {
        type: String,
        default: null,
    },
    previewUrl: {
        type: String,
        default: null,
    },
    isPDF: {
        type: Boolean,
        default: false,
    },
    isDownloading: {
        type: Boolean,
        default: false,
    },
});

defineEmits([
    "close",
    "retry-preview",
    "download",
    "open-new-tab",
    "preview-load",
    "preview-error",
]);
</script>
