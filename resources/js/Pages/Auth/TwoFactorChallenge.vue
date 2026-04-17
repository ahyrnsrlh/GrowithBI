<script setup>
import TwoFactorBackToLogin from "@/Components/Auth/TwoFactor/TwoFactorBackToLogin.vue";
import TwoFactorCardHeader from "@/Components/Auth/TwoFactor/TwoFactorCardHeader.vue";
import TwoFactorOtpForm from "@/Components/Auth/TwoFactor/TwoFactorOtpForm.vue";
import TwoFactorResendSection from "@/Components/Auth/TwoFactor/TwoFactorResendSection.vue";
import TwoFactorSecurityNotice from "@/Components/Auth/TwoFactor/TwoFactorSecurityNotice.vue";
import TwoFactorTimer from "@/Components/Auth/TwoFactor/TwoFactorTimer.vue";
import { useTwoFactorChallengePage } from "@/Composables/useTwoFactorChallengePage";
import { Head } from "@inertiajs/vue3";

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

const {
    form,
    otpDigits,
    setOtpInputRef,
    isExpired,
    canResendNow,
    formattedCountdown,
    fullCode,
    resendCooldown,
    isResending,
    handleOtpInput,
    handleOtpKeydown,
    handlePaste,
    submit,
    resendCode,
} = useTwoFactorChallengePage(props);
</script>

<template>
    <Head title="Verifikasi Dua Faktor - GrowithBI" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <TwoFactorCardHeader :email="email" />

                <TwoFactorTimer
                    :is-expired="isExpired"
                    :formatted-countdown="formattedCountdown"
                />

                <TwoFactorOtpForm
                    :form="form"
                    :otp-digits="otpDigits"
                    :set-otp-input-ref="setOtpInputRef"
                    :trusted-device-enabled="trustedDeviceEnabled"
                    :full-code="fullCode"
                    :is-expired="isExpired"
                    @submit="submit"
                    @paste="handlePaste"
                    @otp-input="handleOtpInput"
                    @otp-keydown="handleOtpKeydown"
                />

                <TwoFactorResendSection
                    :can-resend-now="canResendNow"
                    :is-resending="isResending"
                    :resend-cooldown="resendCooldown"
                    @resend="resendCode"
                />

                <TwoFactorSecurityNotice />

                <TwoFactorBackToLogin />
            </div>
        </div>
    </div>
</template>
