<template>
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
    >
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Harian</h1>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-4">
            <button
                @click="$emit('bulk-approve')"
                :disabled="selectedCount === 0"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
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
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Setujui Terpilih
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    selectedCount: {
        type: Number,
        default: 0,
    },
});

defineEmits(["bulk-approve"]);
</script>
