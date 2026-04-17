<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    showPassword: {
        type: Boolean,
        required: true,
    },
    showPasswordConfirmation: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["toggle-password", "toggle-password-confirmation"]);
</script>

<template>
    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label
                for="password"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Password *
            </label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="h-5 w-5 text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder="Minimal 8 karakter"
                />
                <button
                    type="button"
                    @click="emit('toggle-password')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                    <svg
                        v-if="!showPassword"
                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path
                            fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <svg
                        v-else
                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                            clip-rule="evenodd"
                        />
                        <path
                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                        />
                    </svg>
                </button>
            </div>
            <div
                v-if="form.errors.password"
                class="mt-2 text-sm text-red-600 flex items-center"
            >
                <svg
                    class="w-4 h-4 mr-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                {{ form.errors.password }}
            </div>
        </div>

        <div>
            <label
                for="password_confirmation"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Konfirmasi Password *
            </label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="h-5 w-5 text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <input
                    id="password_confirmation"
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder="Ulangi password"
                />
                <button
                    type="button"
                    @click="emit('toggle-password-confirmation')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                    <svg
                        v-if="!showPasswordConfirmation"
                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path
                            fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <svg
                        v-else
                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                            clip-rule="evenodd"
                        />
                        <path
                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                        />
                    </svg>
                </button>
            </div>
            <div
                v-if="form.errors.password_confirmation"
                class="mt-2 text-sm text-red-600 flex items-center"
            >
                <svg
                    class="w-4 h-4 mr-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                {{ form.errors.password_confirmation }}
            </div>
        </div>
    </div>
</template>
