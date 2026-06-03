<script setup>
import { Head } from "@inertiajs/vue3";
import RegisterBasicFields from "@/Components/Auth/Register/RegisterBasicFields.vue";
import RegisterCardHeader from "@/Components/Auth/Register/RegisterCardHeader.vue";
import RegisterLoginLink from "@/Components/Auth/Register/RegisterLoginLink.vue";
import RegisterPasswordFields from "@/Components/Auth/Register/RegisterPasswordFields.vue";
import RegisterSubmitButton from "@/Components/Auth/Register/RegisterSubmitButton.vue";
import RegisterSuccessModal from "@/Components/Auth/Register/RegisterSuccessModal.vue";
import { useRegisterPage } from "@/Composables/useRegisterPage";
import AuthSplitLayout from "@/Components/Auth/AuthSplitLayout.vue";
import AuthFormHeader from "@/Components/Auth/AuthFormHeader.vue";
import AuthSegmentedToggle from "@/Components/Auth/AuthSegmentedToggle.vue";

const {
    form,
    showPassword,
    showPasswordConfirmation,
    showSuccessModal,
    userEmail,
    submit,
    togglePassword,
    togglePasswordConfirmation,
    closeModalAndRedirect,
} = useRegisterPage();
</script>

<template>
    <Head title="Daftar" />

    <AuthSplitLayout>
        <div class="flex flex-col gap-5">
            <AuthFormHeader
                title="Buat akun"
                subtitle="Mulai pengalaman magang yang lebih terintegrasi dan efisien."
            />

            <AuthSegmentedToggle active="register" />

            <div
                class="rounded-2xl border border-slate-200 bg-slate-50/60 px-3 py-2"
            >
                <p class="text-xs text-slate-600">
                    Informasi Anda akan diverifikasi oleh tim kami sebelum
                    proses magang dimulai.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <RegisterBasicFields :form="form" />

                <RegisterPasswordFields
                    :form="form"
                    :show-password="showPassword"
                    :show-password-confirmation="showPasswordConfirmation"
                    @toggle-password="togglePassword"
                    @toggle-password-confirmation="togglePasswordConfirmation"
                />

                <RegisterSubmitButton :processing="form.processing" />
            </form>

            <RegisterLoginLink :href="route('login')" />
        </div>

        <RegisterSuccessModal
            :show="showSuccessModal"
            :user-email="userEmail"
            @close="closeModalAndRedirect"
        />
    </AuthSplitLayout>
</template>
