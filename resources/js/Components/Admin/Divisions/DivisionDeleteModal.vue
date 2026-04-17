<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    division: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close", "confirm"]);
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4"
                >
                    <svg
                        class="w-6 h-6 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.863-.833-2.633 0L4.181 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">
                    Hapus Divisi
                </h3>
                <p class="text-sm text-gray-600 text-center mb-4">
                    Apakah Anda yakin ingin menghapus divisi "{{
                        division?.name
                    }}"? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-center space-x-2">
                    <button
                        @click="emit('close')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                    >
                        Batal
                    </button>
                    <button
                        @click="emit('confirm')"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
