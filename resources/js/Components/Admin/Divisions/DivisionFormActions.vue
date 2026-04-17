<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    processing: {
        type: Boolean,
        required: true,
    },
    cancelHref: {
        type: String,
        required: true,
    },
    submitLabel: {
        type: String,
        required: true,
    },
    processingLabel: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200"
    >
        <Link
            :href="cancelHref"
            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200"
        >
            Batal
        </Link>
        <button
            type="submit"
            :disabled="processing"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center"
        >
            <svg
                v-if="processing"
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
            {{ processing ? processingLabel : submitLabel }}
        </button>
    </div>
</template>
