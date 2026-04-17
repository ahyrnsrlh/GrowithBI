<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    cancelHref: {
        type: String,
        required: true,
    },
    processing: {
        type: Boolean,
        required: true,
    },
    uploading: {
        type: Boolean,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>
    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
        <Link
            :href="cancelHref"
            class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
        >
            Batal
        </Link>
        <button
            type="submit"
            :disabled="disabled"
            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center"
        >
            <svg
                v-if="uploading || processing"
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
            {{ uploading || processing ? "Mengupload..." : "Upload Laporan" }}
        </button>
    </div>
</template>
