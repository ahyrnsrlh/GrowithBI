<template>
    <Teleport to="body">
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="transform translate-y-[-100%] opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-[-100%] opacity-0"
        >
            <div
                v-if="successMessage && showSuccess"
                class="fixed top-20 right-4 z-[100] max-w-sm"
            >
                <div
                    class="bg-white rounded-xl shadow-lg border-l-4 border-emerald-500 p-4 flex items-start gap-3"
                >
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-4 h-4 text-emerald-600"
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
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">
                            Berhasil
                        </p>
                        <p class="text-sm text-gray-600 mt-0.5">
                            {{ successMessage }}
                        </p>
                    </div>
                    <button
                        @click="$emit('close-success')"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
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
            enter-from-class="transform translate-y-[-100%] opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-[-100%] opacity-0"
        >
            <div
                v-if="errorMessage && showError"
                class="fixed top-20 right-4 z-[100] max-w-sm"
            >
                <div
                    class="bg-white rounded-xl shadow-lg border-l-4 border-red-500 p-4 flex items-start gap-3"
                >
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-4 h-4 text-red-600"
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
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">Gagal</p>
                        <p class="text-sm text-gray-600 mt-0.5">
                            {{ errorMessage }}
                        </p>
                    </div>
                    <button
                        @click="$emit('close-error')"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
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
    </Teleport>
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
