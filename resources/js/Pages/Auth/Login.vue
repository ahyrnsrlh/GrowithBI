<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useRecaptcha } from "@/Composables/useRecaptcha";

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
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

// Debug logging - REMOVE AFTER TESTING
console.log("🔐 reCAPTCHA Config:", {
    siteKey: props.recaptchaSiteKey,
    enabled: props.recaptchaEnabled,
});

// Initialize reCAPTCHA
const { executeRecaptcha, isReady: recaptchaReady } = useRecaptcha(
    props.recaptchaSiteKey,
    props.recaptchaEnabled
);

const form = useForm({
    email: "",
    password: "",
    remember: false,
    captcha_token: null,
});

const showPassword = ref(false);

const submit = async () => {
    console.log("🚀 Submit triggered");

    // Execute reCAPTCHA before submitting
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        console.log("✅ reCAPTCHA enabled, executing...");
        console.log("📊 reCAPTCHA ready status:", recaptchaReady.value);

        const token = await executeRecaptcha("login");

        console.log(
            "🎫 reCAPTCHA token:",
            token ? "✅ Generated" : "❌ Failed"
        );
        console.log("Token length:", token?.length || 0);

        form.captcha_token = token;
    } else {
        console.log(
            "⚠️ reCAPTCHA skipped - enabled:",
            props.recaptchaEnabled,
            "siteKey:",
            props.recaptchaSiteKey
        );
    }

    console.log("📤 Form data:", {
        email: form.email,
        hasCaptchaToken: !!form.captcha_token,
    });

    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
            form.captcha_token = null;
        },
    });
};
</script>

<template>
    <Head title="Login - GrowithBI" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Login Card -->
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
                    <p class="text-gray-600 text-sm">
                        Program Magang Bank Indonesia Provinsi Lampung
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
                            Username
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

                    <!-- Password Field -->
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder=""
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg
                                    v-if="!showPassword"
                                    class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            v-if="form.errors.password"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Remember this Device</span
                            >
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                        >
                            Forgot Password ?
                        </Link>
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
                        {{ form.processing ? "Processing..." : "Sign In" }}
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        New to GrowithBI?
                        <Link
                            :href="route('register')"
                            class="font-medium text-blue-600 hover:text-blue-700"
                        >
                            Create an account
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
