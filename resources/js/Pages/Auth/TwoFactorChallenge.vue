<script setup>
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { useRecaptcha } from "@/Composables/useRecaptcha";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    remainingTime: {
        type: Number,
        default: 0,
    },
    canResend: {
        type: Boolean,
        default: true,
    },
    cooldownRemaining: {
        type: Number,
        default: 0,
    },
    trustedDeviceEnabled: {
        type: Boolean,
        default: false,
    },
    expiresInMinutes: {
        type: Number,
        default: 5,
    },
    maxAttempts: {
        type: Number,
        default: 5,
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

// Form state
const form = useForm({
    code: "",
    trust_device: false,
    captcha_token: null,
});

// OTP input refs
const otpInputs = ref([]);
const otpDigits = ref(["", "", "", "", "", ""]);

// Timer state
const countdown = ref(props.remainingTime);
const resendCooldown = ref(props.cooldownRemaining);
const isResending = ref(false);

// Computed
const isExpired = computed(() => countdown.value <= 0);
const canResendNow = computed(() => resendCooldown.value <= 0);
const formattedCountdown = computed(() => {
    const minutes = Math.floor(countdown.value / 60);
    const seconds = countdown.value % 60;
    return `${minutes}:${seconds.toString().padStart(2, "0")}`;
});
const fullCode = computed(() => otpDigits.value.join(""));

// Timer interval
let countdownInterval = null;
let cooldownInterval = null;

onMounted(() => {
    // Start countdown timer
    if (countdown.value > 0) {
        countdownInterval = setInterval(() => {
            if (countdown.value > 0) {
                countdown.value--;
            } else {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }

    // Start cooldown timer
    if (resendCooldown.value > 0) {
        cooldownInterval = setInterval(() => {
            if (resendCooldown.value > 0) {
                resendCooldown.value--;
            } else {
                clearInterval(cooldownInterval);
            }
        }, 1000);
    }

    // Focus first input
    if (otpInputs.value[0]) {
        otpInputs.value[0].focus();
    }
});

onUnmounted(() => {
    if (countdownInterval) clearInterval(countdownInterval);
    if (cooldownInterval) clearInterval(cooldownInterval);
});

// Watch for complete code
watch(fullCode, (newCode) => {
    if (newCode.length === 6) {
        form.code = newCode;
        submit();
    }
});

// OTP input handling
const handleOtpInput = (index, event) => {
    const value = event.target.value;

    // Only allow digits
    if (!/^\d*$/.test(value)) {
        otpDigits.value[index] = "";
        return;
    }

    // Handle paste
    if (value.length > 1) {
        const digits = value.slice(0, 6).split("");
        digits.forEach((digit, i) => {
            if (i < 6) {
                otpDigits.value[i] = digit;
            }
        });
        // Focus last filled input or next empty
        const lastFilledIndex = Math.min(digits.length - 1, 5);
        if (otpInputs.value[lastFilledIndex]) {
            otpInputs.value[lastFilledIndex].focus();
        }
        return;
    }

    // Single digit input
    otpDigits.value[index] = value;

    // Move to next input
    if (value && index < 5) {
        otpInputs.value[index + 1]?.focus();
    }
};

const handleOtpKeydown = (index, event) => {
    // Handle backspace
    if (event.key === "Backspace") {
        if (!otpDigits.value[index] && index > 0) {
            otpInputs.value[index - 1]?.focus();
        }
        otpDigits.value[index] = "";
    }

    // Handle arrow keys
    if (event.key === "ArrowLeft" && index > 0) {
        otpInputs.value[index - 1]?.focus();
    }
    if (event.key === "ArrowRight" && index < 5) {
        otpInputs.value[index + 1]?.focus();
    }
};

const handlePaste = (event) => {
    event.preventDefault();
    const pastedData = event.clipboardData.getData("text").replace(/\D/g, "");
    const digits = pastedData.slice(0, 6).split("");

    digits.forEach((digit, i) => {
        if (i < 6) {
            otpDigits.value[i] = digit;
        }
    });

    // Focus appropriate input
    const focusIndex = Math.min(digits.length, 5);
    otpInputs.value[focusIndex]?.focus();
};

const submit = async () => {
    form.code = fullCode.value;

    // Execute reCAPTCHA before submitting (for peserta only)
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        const token = await executeRecaptcha("otp_verification");
        form.captcha_token = token;
    }

    form.post(route("two-factor.verify"), {
        preserveScroll: true,
        onError: () => {
            // Clear inputs on error
            otpDigits.value = ["", "", "", "", "", ""];
            otpInputs.value[0]?.focus();
            form.captcha_token = null;
        },
        onFinish: () => {
            form.captcha_token = null;
        },
    });
};

const resendCode = async () => {
    if (!canResendNow.value || isResending.value) return;

    isResending.value = true;

    // Get captcha token for resend action
    let captchaToken = null;
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        captchaToken = await executeRecaptcha("otp_resend");
    }

    router.post(
        route("two-factor.resend"),
        { captcha_token: captchaToken },
        {
            preserveScroll: true,
            onSuccess: (page) => {
                // Reset countdown
                countdown.value = props.expiresInMinutes * 60;
                resendCooldown.value = 60;

                // Restart timers
                if (countdownInterval) clearInterval(countdownInterval);
                countdownInterval = setInterval(() => {
                    if (countdown.value > 0) {
                        countdown.value--;
                    } else {
                        clearInterval(countdownInterval);
                    }
                }, 1000);

                if (cooldownInterval) clearInterval(cooldownInterval);
                cooldownInterval = setInterval(() => {
                    if (resendCooldown.value > 0) {
                        resendCooldown.value--;
                    } else {
                        clearInterval(cooldownInterval);
                    }
                }, 1000);

                // Clear inputs
                otpDigits.value = ["", "", "", "", "", ""];
                otpInputs.value[0]?.focus();
            },
            onFinish: () => {
                isResending.value = false;
            },
        }
    );
};
</script>

<template>
    <Head title="Verifikasi Dua Faktor - GrowithBI" />

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
                    Verifikasi Dua Faktor
                </h2>
                <p class="text-gray-600">
                    Masukkan kode 6 digit yang telah dikirim ke
                </p>
                <p class="text-blue-600 font-medium mt-1">{{ email }}</p>
            </div>

            <!-- Verification Form -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-8"
            >
                <!-- Timer -->
                <div class="text-center mb-6">
                    <div
                        v-if="!isExpired"
                        class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full"
                    >
                        <svg
                            class="w-5 h-5 text-blue-600 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span class="text-sm font-medium text-blue-700">
                            Kode berlaku
                            <span class="font-bold">{{
                                formattedCountdown
                            }}</span>
                        </span>
                    </div>
                    <div
                        v-else
                        class="inline-flex items-center px-4 py-2 bg-red-50 rounded-full"
                    >
                        <svg
                            class="w-5 h-5 text-red-600 mr-2"
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
                        <span class="text-sm font-medium text-red-700">
                            Kode sudah kadaluarsa
                        </span>
                    </div>
                </div>

                <!-- Error Message -->
                <div
                    v-if="form.errors.code"
                    class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-red-600 mr-2 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span class="text-sm font-medium text-red-800">{{
                            form.errors.code
                        }}</span>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- OTP Input -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-4 text-center"
                        >
                            Kode Verifikasi
                        </label>
                        <div
                            class="flex justify-center gap-2 sm:gap-3"
                            @paste="handlePaste"
                        >
                            <input
                                v-for="(digit, index) in otpDigits"
                                :key="index"
                                :ref="(el) => (otpInputs[index] = el)"
                                type="text"
                                inputmode="numeric"
                                maxlength="6"
                                :value="digit"
                                @input="handleOtpInput(index, $event)"
                                @keydown="handleOtpKeydown(index, $event)"
                                :disabled="form.processing"
                                class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="{
                                    'border-red-500 focus:border-red-500 focus:ring-red-500':
                                        form.errors.code,
                                }"
                            />
                        </div>
                    </div>

                    <!-- Trust Device Checkbox -->
                    <div
                        v-if="trustedDeviceEnabled"
                        class="flex items-start space-x-3"
                    >
                        <input
                            id="trust_device"
                            type="checkbox"
                            v-model="form.trust_device"
                            class="h-5 w-5 mt-0.5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="trust_device" class="text-sm text-gray-600">
                            <span class="font-medium text-gray-700"
                                >Percaya perangkat ini</span
                            >
                            <br />
                            <span class="text-xs text-gray-500">
                                Anda tidak akan diminta kode verifikasi selama
                                30 hari pada perangkat ini
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="
                            form.processing ||
                            fullCode.length !== 6 ||
                            isExpired
                        "
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
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
                            form.processing ? "Memverifikasi..." : "Verifikasi"
                        }}
                    </button>
                </form>

                <!-- Resend Code -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        Tidak menerima kode?
                    </p>
                    <button
                        type="button"
                        @click="resendCode"
                        :disabled="!canResendNow || isResending"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors"
                    >
                        <span v-if="isResending">
                            <svg
                                class="animate-spin inline-block w-4 h-4 mr-1"
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
                            Mengirim...
                        </span>
                        <span v-else-if="!canResendNow">
                            Kirim ulang kode ({{ resendCooldown }}s)
                        </span>
                        <span v-else> Kirim Ulang Kode </span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div
                    class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl"
                >
                    <div class="flex items-start">
                        <svg
                            class="w-5 h-5 text-amber-600 mr-2 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-800">
                                Peringatan Keamanan
                            </p>
                            <p class="text-xs text-amber-700 mt-1">
                                Jangan bagikan kode verifikasi ini kepada
                                siapapun, termasuk yang mengaku sebagai staf
                                GrowithBI.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        ← Kembali ke Login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
