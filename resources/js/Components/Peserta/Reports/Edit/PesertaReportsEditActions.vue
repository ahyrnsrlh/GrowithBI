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
    <div class="flex items-center justify-end space-x-4 pt-6">
        <Link
            :href="cancelHref"
            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
            Batal
        </Link>
        <button
            type="submit"
            :disabled="processing"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            <span v-if="processing">Menyimpan...</span>
            <span v-else>Simpan Perubahan</span>
        </button>
    </div>
</template>
