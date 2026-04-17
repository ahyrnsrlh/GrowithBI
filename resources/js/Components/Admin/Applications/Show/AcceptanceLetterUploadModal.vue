<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Upload Surat Penerimaan
                </h3>
                <form @submit.prevent="$emit('submit')">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            File Surat Penerimaan
                        </label>
                        <input
                            :key="inputKey"
                            type="file"
                            @change="$emit('file-change', $event)"
                            accept=".pdf,.doc,.docx"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            required
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Format yang didukung: PDF, DOC, DOCX (Maks. 5MB)
                        </p>
                    </div>

                    <div
                        v-if="uploadError"
                        class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md"
                    >
                        <p class="text-sm text-red-600">{{ uploadError }}</p>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            :disabled="isUploading"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
                            :disabled="isUploading || !selectedFile"
                        >
                            <svg
                                v-if="isUploading"
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                            {{ isUploading ? "Mengupload..." : "Upload" }}
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
    uploadError: { type: String, default: "" },
    isUploading: { type: Boolean, default: false },
    selectedFile: { type: [Object, null], default: null },
    inputKey: { type: Number, default: 0 },
});

defineEmits(["close", "submit", "file-change"]);
</script>
