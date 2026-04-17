import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";

export function useRegisterPage() {
    const page = usePage();

    const form = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        role: "peserta",
        phone: "",
        address: "",
    });

    const showPassword = ref(false);
    const showPasswordConfirmation = ref(false);
    const showSuccessModal = ref(false);

    const registrationSuccess = computed(
        () => page.props.flash?.registration_success,
    );
    const userEmail = computed(() => page.props.flash?.user_email);
    const userName = computed(() => page.props.flash?.user_name);

    let redirectTimeout = null;

    const startRedirectCountdown = () => {
        if (redirectTimeout) {
            clearTimeout(redirectTimeout);
        }

        redirectTimeout = setTimeout(() => {
            window.location.href = route("login");
        }, 3000);
    };

    watch(
        () => page.props.flash,
        (newFlash) => {
            if (newFlash?.registration_success) {
                showSuccessModal.value = true;
                startRedirectCountdown();
            }
        },
        { immediate: true },
    );

    onMounted(() => {
        if (registrationSuccess.value) {
            showSuccessModal.value = true;
            startRedirectCountdown();
        }
    });

    onUnmounted(() => {
        if (redirectTimeout) {
            clearTimeout(redirectTimeout);
        }
    });

    const submit = () => {
        form.post(route("register"), {
            onFinish: () => {
                if (!form.hasErrors) {
                    form.reset("password", "password_confirmation");
                }
            },
        });
    };

    const togglePassword = () => {
        showPassword.value = !showPassword.value;
    };

    const togglePasswordConfirmation = () => {
        showPasswordConfirmation.value = !showPasswordConfirmation.value;
    };

    const closeModalAndRedirect = () => {
        showSuccessModal.value = false;
        window.location.href = route("login");
    };

    return {
        form,
        showPassword,
        showPasswordConfirmation,
        showSuccessModal,
        userEmail,
        userName,
        submit,
        togglePassword,
        togglePasswordConfirmation,
        closeModalAndRedirect,
    };
}
