<script setup>
import { Head } from "@inertiajs/vue3";
import RegisterBasicFields from "@/Components/Auth/Register/RegisterBasicFields.vue";
import RegisterCardHeader from "@/Components/Auth/Register/RegisterCardHeader.vue";
import RegisterLoginLink from "@/Components/Auth/Register/RegisterLoginLink.vue";
import RegisterPasswordFields from "@/Components/Auth/Register/RegisterPasswordFields.vue";
import RegisterSubmitButton from "@/Components/Auth/Register/RegisterSubmitButton.vue";
import RegisterSuccessModal from "@/Components/Auth/Register/RegisterSuccessModal.vue";
import { useRegisterPage } from "@/Composables/useRegisterPage";

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
    <Head title="Daftar - GrowithBI" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <RegisterCardHeader />

                <form @submit.prevent="submit" class="space-y-5">
                    <RegisterBasicFields :form="form" />

                    <RegisterPasswordFields
                        :form="form"
                        :show-password="showPassword"
                        :show-password-confirmation="showPasswordConfirmation"
                        @toggle-password="togglePassword"
                        @toggle-password-confirmation="
                            togglePasswordConfirmation
                        "
                    />

                    <RegisterSubmitButton :processing="form.processing" />
                </form>

                <RegisterLoginLink :href="route('login')" />
            </div>
        </div>

        <RegisterSuccessModal
            :show="showSuccessModal"
            :user-email="userEmail"
            @close="closeModalAndRedirect"
        />
    </div>
</template>
