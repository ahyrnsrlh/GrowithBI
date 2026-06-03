<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useRecaptcha } from "@/Composables/useRecaptcha";
import AuthSplitLayout from "@/Components/Auth/AuthSplitLayout.vue";
import AuthFormHeader from "@/Components/Auth/AuthFormHeader.vue";
import AuthSegmentedToggle from "@/Components/Auth/AuthSegmentedToggle.vue";

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
    props.recaptchaEnabled,
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
            token ? "✅ Generated" : "❌ Failed",
        );
        console.log("Token length:", token?.length || 0);

        form.captcha_token = token;
    } else {
        console.log(
            "⚠️ reCAPTCHA skipped - enabled:",
            props.recaptchaEnabled,
            "siteKey:",
            props.recaptchaSiteKey,
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
    <Head title="Login" />

    <AuthSplitLayout>
        <div class="flex flex-col gap-6">
            <AuthFormHeader
                title="Selamat datang"
                subtitle="Masuk ke GrowithBI untuk pengalaman magang yang lebih terintegrasi dan efisien."
            />

            <AuthSegmentedToggle active="login" />

            <div
                v-if="status"
                class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-3"
            >
                <div class="flex items-center gap-2 text-sm text-emerald-700">
                    <span
                        class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-white"
                    >
                        <svg
                            class="h-3 w-3"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                    {{ status }}
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="space-y-4">
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Email
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <svg
                                    class="h-5 w-5 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                class="block w-full rounded-xl border border-slate-200 bg-white/70 py-3 pl-10 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <div
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="password"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Password
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <svg
                                    class="h-5 w-5 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 11c1.105 0 2-.895 2-2V7a2 2 0 10-4 0v2c0 1.105.895 2 2 2z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 11h14v8H5z"
                                    />
                                </svg>
                            </span>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="block w-full rounded-xl border border-slate-200 bg-white/70 py-3 pl-10 pr-12 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                placeholder="Masukkan password"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600"
                                aria-label="Toggle password"
                            >
                                <svg
                                    v-if="!showPassword"
                                    class="h-5 w-5"
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
                                    class="h-5 w-5"
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
                </div>

                <div
                    class="flex flex-wrap items-center justify-between gap-3 text-sm"
                >
                    <label
                        class="inline-flex items-center gap-2 text-slate-600"
                    >
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        />
                        Ingat perangkat ini
                    </label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="font-medium text-blue-600 transition hover:text-blue-700"
                    >
                        Lupa password?
                    </Link>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 via-blue-500 to-blue-700 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg
                        v-if="form.processing"
                        class="h-5 w-5 animate-spin"
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

            <div class="text-center text-sm text-slate-500">
                Belum punya akun?
                <Link
                    :href="route('register')"
                    class="font-semibold text-blue-600 hover:text-blue-700"
                >
                    Daftar sekarang
                </Link>
            </div>
        </div>
    </AuthSplitLayout>
</template>
