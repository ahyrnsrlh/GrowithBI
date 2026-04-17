<template>
    <Teleport to="body">
        <div
            v-if="notification.show"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                    @click="$emit('close')"
                ></div>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div
                                :class="[
                                    'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center',
                                    notification.type === 'success'
                                        ? 'bg-green-100'
                                        : 'bg-red-100',
                                ]"
                            >
                                <svg
                                    v-if="notification.type === 'success'"
                                    class="w-6 h-6 text-green-600"
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
                                <svg
                                    v-else
                                    class="w-6 h-6 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ notification.title }}
                                </h3>
                                <p
                                    class="mt-2 text-gray-600 whitespace-pre-line"
                                >
                                    {{ notification.message }}
                                </p>
                                <p
                                    v-if="
                                        notification.type === 'success' &&
                                        shouldRedirectToProfile
                                    "
                                    class="mt-3 text-sm text-gray-500 italic flex items-center gap-2"
                                >
                                    <svg
                                        class="w-4 h-4 animate-spin"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                    Redirect otomatis dalam
                                    {{ redirectCountdown }} detik...
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button
                            :class="[
                                'px-6 py-2.5 rounded-lg font-medium transition-colors',
                                notification.type === 'success'
                                    ? 'bg-green-600 hover:bg-green-700 text-white'
                                    : 'bg-red-600 hover:bg-red-700 text-white',
                            ]"
                            @click="$emit('close')"
                        >
                            {{
                                notification.type === "success" &&
                                shouldRedirectToProfile
                                    ? "Lihat Profil Sekarang"
                                    : "Tutup"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
defineEmits(["close"]);

defineProps({
    notification: {
        type: Object,
        required: true,
    },
    redirectCountdown: {
        type: Number,
        default: 5,
    },
    shouldRedirectToProfile: {
        type: Boolean,
        default: false,
    },
});
</script>
