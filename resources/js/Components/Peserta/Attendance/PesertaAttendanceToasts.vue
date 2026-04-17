<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div
            v-if="successMessage && showSuccess"
            class="fixed top-4 right-4 z-50 max-w-md"
        >
            <div
                class="bg-white rounded-2xl shadow-lg border border-green-200 p-4 flex items-start space-x-3"
            >
                <div class="flex-shrink-0">
                    <svg
                        class="w-6 h-6 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Berhasil!</p>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ successMessage }}
                    </p>
                </div>
                <button
                    @click="$emit('close-success')"
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </transition>

    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div
            v-if="errorMessage && showError"
            class="fixed top-4 right-4 z-50 max-w-md"
        >
            <div
                class="bg-white rounded-2xl shadow-lg border border-red-200 p-4 flex items-start space-x-3"
            >
                <div class="flex-shrink-0">
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
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Gagal!</p>
                    <p class="mt-1 text-sm text-gray-600">{{ errorMessage }}</p>
                </div>
                <button
                    @click="$emit('close-error')"
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    successMessage: { type: String, default: "" },
    errorMessage: { type: String, default: "" },
    showSuccess: { type: Boolean, default: false },
    showError: { type: Boolean, default: false },
});

defineEmits(["close-success", "close-error"]);
</script>
