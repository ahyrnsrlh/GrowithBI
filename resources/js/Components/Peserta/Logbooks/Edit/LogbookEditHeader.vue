<template>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Logbook</h1>
                <p class="text-gray-600 mt-2">
                    Perbaiki logbook sesuai dengan feedback pembimbing
                </p>
            </div>
            <div class="flex space-x-3">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                    @click="$emit('cancel')"
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
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Batalkan
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineEmits(["cancel"]);
</script>
