<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["mark-all-as-read"]);
</script>

<template>
    <div
        class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between"
    >
        <div class="mb-4 sm:mb-0">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <span class="flex items-center">
                        <div
                            class="w-3 h-3 bg-blue-500 rounded-full mr-2"
                        ></div>
                        Total: {{ stats.total }}
                    </span>
                    <span class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        Belum dibaca: {{ stats.unread }}
                    </span>
                    <span class="flex items-center">
                        <div
                            class="w-3 h-3 bg-green-500 rounded-full mr-2"
                        ></div>
                        Sudah dibaca: {{ stats.read }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <button
                @click="emit('mark-all-as-read')"
                :disabled="stats.unread === 0"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                Tandai Semua Dibaca
            </button>
        </div>
    </div>
</template>
