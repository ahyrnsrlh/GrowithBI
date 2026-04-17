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
});
</script>

<template>
    <div
        class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200"
    >
        <Link
            :href="cancelHref"
            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
        >
            Batal
        </Link>
        <button
            type="submit"
            :disabled="processing"
            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            <span v-if="processing">Menyimpan...</span>
            <span v-else>Simpan Logbook</span>
        </button>
    </div>
</template>
