import { useForm, router } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import { useRecaptcha } from "@/Composables/useRecaptcha";

export function useTwoFactorChallengePage(props) {
    const { executeRecaptcha } = useRecaptcha(
        props.recaptchaSiteKey,
        props.recaptchaEnabled,
    );

    const form = useForm({
        code: "",
        trust_device: false,
        captcha_token: null,
    });

    const otpInputs = ref([]);
    const otpDigits = ref(["", "", "", "", "", ""]);
    const countdown = ref(props.remainingTime);
    const resendCooldown = ref(props.cooldownRemaining);
    const isResending = ref(false);

    const isExpired = computed(() => countdown.value <= 0);
    const canResendNow = computed(() => resendCooldown.value <= 0);
    const formattedCountdown = computed(() => {
        const minutes = Math.floor(countdown.value / 60);
        const seconds = countdown.value % 60;
        return `${minutes}:${seconds.toString().padStart(2, "0")}`;
    });
    const fullCode = computed(() => otpDigits.value.join(""));

    let countdownInterval = null;
    let cooldownInterval = null;

    const stopCountdownTimer = () => {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    };

    const stopCooldownTimer = () => {
        if (cooldownInterval) {
            clearInterval(cooldownInterval);
            cooldownInterval = null;
        }
    };

    const startCountdownTimer = () => {
        stopCountdownTimer();

        if (countdown.value <= 0) {
            return;
        }

        countdownInterval = setInterval(() => {
            if (countdown.value > 0) {
                countdown.value -= 1;
            } else {
                stopCountdownTimer();
            }
        }, 1000);
    };

    const startCooldownTimer = () => {
        stopCooldownTimer();

        if (resendCooldown.value <= 0) {
            return;
        }

        cooldownInterval = setInterval(() => {
            if (resendCooldown.value > 0) {
                resendCooldown.value -= 1;
            } else {
                stopCooldownTimer();
            }
        }, 1000);
    };

    const focusInput = (index) => {
        otpInputs.value[index]?.focus();
    };

    const setOtpInputRef = (index, element) => {
        otpInputs.value[index] = element;
    };

    const resetOtpInputs = () => {
        otpDigits.value = ["", "", "", "", "", ""];
        focusInput(0);
    };

    const handleOtpInput = (index, event) => {
        const value = event.target.value;

        if (!/^\d*$/.test(value)) {
            otpDigits.value[index] = "";
            return;
        }

        if (value.length > 1) {
            const digits = value.slice(0, 6).split("");
            digits.forEach((digit, digitIndex) => {
                if (digitIndex < 6) {
                    otpDigits.value[digitIndex] = digit;
                }
            });

            const lastFilledIndex = Math.min(digits.length - 1, 5);
            focusInput(lastFilledIndex >= 0 ? lastFilledIndex : 0);
            return;
        }

        otpDigits.value[index] = value;

        if (value && index < 5) {
            focusInput(index + 1);
        }
    };

    const handleOtpKeydown = (index, event) => {
        if (event.key === "Backspace") {
            if (!otpDigits.value[index] && index > 0) {
                focusInput(index - 1);
            }
            otpDigits.value[index] = "";
        }

        if (event.key === "ArrowLeft" && index > 0) {
            focusInput(index - 1);
        }

        if (event.key === "ArrowRight" && index < 5) {
            focusInput(index + 1);
        }
    };

    const handlePaste = (event) => {
        event.preventDefault();
        const pastedData = event.clipboardData
            .getData("text")
            .replace(/\D/g, "");
        const digits = pastedData.slice(0, 6).split("");

        digits.forEach((digit, index) => {
            if (index < 6) {
                otpDigits.value[index] = digit;
            }
        });

        const focusIndex = Math.min(digits.length, 5);
        focusInput(focusIndex);
    };

    const submit = async () => {
        form.code = fullCode.value;

        if (props.recaptchaEnabled && props.recaptchaSiteKey) {
            const token = await executeRecaptcha("otp_verification");
            form.captcha_token = token;
        }

        form.post(route("two-factor.verify"), {
            preserveScroll: true,
            onError: () => {
                resetOtpInputs();
                form.captcha_token = null;
            },
            onFinish: () => {
                form.captcha_token = null;
            },
        });
    };

    const resendCode = async () => {
        if (!canResendNow.value || isResending.value) {
            return;
        }

        isResending.value = true;

        let captchaToken = null;
        if (props.recaptchaEnabled && props.recaptchaSiteKey) {
            captchaToken = await executeRecaptcha("otp_resend");
        }

        router.post(
            route("two-factor.resend"),
            { captcha_token: captchaToken },
            {
                preserveScroll: true,
                onSuccess: () => {
                    countdown.value = props.expiresInMinutes * 60;
                    resendCooldown.value = 60;

                    startCountdownTimer();
                    startCooldownTimer();
                    resetOtpInputs();
                },
                onFinish: () => {
                    isResending.value = false;
                },
            },
        );
    };

    watch(fullCode, (newCode) => {
        if (newCode.length === 6) {
            form.code = newCode;
            submit();
        }
    });

    onMounted(() => {
        startCountdownTimer();
        startCooldownTimer();
        focusInput(0);
    });

    onUnmounted(() => {
        stopCountdownTimer();
        stopCooldownTimer();
    });

    return {
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
    };
}
