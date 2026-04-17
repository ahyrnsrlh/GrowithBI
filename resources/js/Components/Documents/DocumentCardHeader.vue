<template>
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-start space-x-3">
            <div
                class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                :class="
                    hasDocument
                        ? 'bg-green-50 text-green-600'
                        : 'bg-gray-100 text-gray-400'
                "
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        v-if="hasDocument"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                    <path
                        v-else
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                    />
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-gray-900 leading-tight">
                    {{ documentName }}
                    <span
                        v-if="isRequired"
                        class="text-red-500 ml-0.5"
                        title="Dokumen wajib"
                        >*</span
                    >
                </h3>
                <div class="mt-1">
                    <span
                        v-if="hasDocument"
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200"
                    >
                        <svg
                            class="w-3 h-3 mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Tersimpan
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200"
                    >
                        <svg
                            class="w-3 h-3 mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Belum Upload
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    documentName: {
        type: String,
        required: true,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    hasDocument: {
        type: Boolean,
        default: false,
    },
});
</script>
