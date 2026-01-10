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

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex justify-center mb-6">
                    <img
                        src="/storage/logo_web2.png"
                        alt="GrowithBI Logo"
                        class="h-16 w-auto object-contain"
                    />
                </Link>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Selamat Datang Kembali
                </h2>
                <p class="text-gray-600">
                    Masuk ke akun GrowithBI - Program Magang Bank Indonesia KPW
                    Lampung
                </p>
            </div>

            <!-- Login Form -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-8"
            >
                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-green-600 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span class="text-sm font-medium text-green-800">{{
                            status
                        }}</span>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Email Address
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
                                        d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                                    />
                                    <path
                                        d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                                    />
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan email Anda"
                            />
                        </div>
                        <div
                            v-if="form.errors.email"
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
                                autocomplete="current-password"
                                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan password Anda"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
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

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Remember me</span
                            >
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                        >
                            Lupa Password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105"
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
                        {{ form.processing ? "Memproses..." : "Masuk" }}
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <Link
                            :href="route('register')"
                            class="font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                        >
                            Daftar Sekarang
                        </Link>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <Link
                        href="/"
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        ← Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
