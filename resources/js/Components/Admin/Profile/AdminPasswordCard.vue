<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ganti Password</h2>
        </div>
        <div class="p-6">
            <form class="space-y-6" @submit.prevent="$emit('submit-password')">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Password Saat Ini</label
                    >
                    <input
                        v-model="passwordForm.current_password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan password saat ini"
                    />
                    <div
                        v-if="passwordForm.errors.current_password"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ passwordForm.errors.current_password }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Password Baru</label
                        >
                        <input
                            v-model="passwordForm.password"
                            type="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan password baru"
                        />
                        <div
                            v-if="passwordForm.errors.password"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ passwordForm.errors.password }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Konfirmasi Password Baru</label
                        >
                        <input
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Konfirmasi password baru"
                        />
                        <div
                            v-if="passwordForm.errors.password_confirmation"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ passwordForm.errors.password_confirmation }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="passwordForm.processing"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                    >
                        <svg
                            v-if="passwordForm.processing"
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
                        {{
                            passwordForm.processing
                                ? "Mengubah..."
                                : "Ganti Password"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
defineProps({
    passwordForm: {
        type: Object,
        required: true,
    },
});

defineEmits(["submit-password"]);
</script>
