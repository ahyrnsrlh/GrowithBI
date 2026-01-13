<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useRecaptcha } from "@/Composables/useRecaptcha";

const props = defineProps({
    status: {
        type: String,
    },
    recaptchaSiteKey: {
        type: String,
        default: null,
    },
    recaptchaEnabled: {
        type: Boolean,
        default: false,
    },
});

// Initialize reCAPTCHA
const { executeRecaptcha, isReady: recaptchaReady } = useRecaptcha(
    props.recaptchaSiteKey,
    props.recaptchaEnabled
);

const form = useForm({
    email: "",
    captcha_token: null,
});

const submit = async () => {
    // Execute reCAPTCHA before submitting
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        const token = await executeRecaptcha("forgot_password");
        form.captcha_token = token;
    }

    form.post(route("password.email"), {
        onFinish: () => {
            form.captcha_token = null;
        },
    });
};
</script>

<template>
    <Head title="Lupa Password - GrowithBI" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Forgot Password Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Logo and Header -->
                <div class="text-center mb-8">
                    <Link href="/" class="inline-flex justify-center mb-4">
                        <img
                            src="/storage/logo_web2.png"
                            alt="GrowithBI Logo"
                            class="h-12 w-auto object-contain"
                        />
                    </Link>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        Forgot Password?
                    </h2>
                    <p class="text-gray-600 text-sm">
                        Enter your email and we'll send you a reset link.
                    </p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-4 h-4 text-green-600 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span class="text-sm text-green-800">{{ status }}</span>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Field -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Email Address
                        </label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
                            form.processing
                                ? "Processing..."
                                : "Email Password Reset Link"
                        }}
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <Link
                        :href="route('login')"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                    >
                        ← Back to login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
