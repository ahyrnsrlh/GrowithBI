<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onUnmounted } from "vue";
import axios from "axios";
import AuthSplitLayout from "@/Components/Auth/AuthSplitLayout.vue";
import AuthFormHeader from "@/Components/Auth/AuthFormHeader.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
});

const currentEmail = ref(props.email);
const showChangeEmailForm = ref(false);

// Resend Cooldown
const isResending = ref(false);
const resendCooldown = ref(0);
let cooldownInterval = null;

// Change Email Form States
const newEmail = ref("");
const password = ref("");
const isChanging = ref(false);
const formErrors = ref({});
const successMessage = ref("");
const errorMessage = ref("");

const startResendCooldown = () => {
    resendCooldown.value = 60;
    cooldownInterval = setInterval(() => {
        resendCooldown.value--;
        if (resendCooldown.value <= 0) {
            clearInterval(cooldownInterval);
        }
    }, 1000);
};

onUnmounted(() => {
    if (cooldownInterval) clearInterval(cooldownInterval);
});

const handleResend = async () => {
    if (resendCooldown.value > 0 || isResending.value) return;

    isResending.value = true;
    errorMessage.value = "";
    successMessage.value = "";

    try {
        const response = await axios.post(route("register.resend"), {
            email: currentEmail.value,
        });

        if (response.data?.success) {
            successMessage.value = response.data.message;
            startResendCooldown();
        } else {
            errorMessage.value = response.data?.message || "Gagal mengirim ulang email verifikasi.";
        }
    } catch (err) {
        errorMessage.value = err.response?.data?.message || "Terjadi kesalahan sistem. Silakan coba lagi.";
    } finally {
        isResending.value = false;
    }
};

const handleChangeEmail = async () => {
    if (isChanging.value) return;

    isChanging.value = true;
    formErrors.value = {};
    errorMessage.value = "";
    successMessage.value = "";

    try {
        const response = await axios.post(route("register.change-email"), {
            old_email: currentEmail.value,
            new_email: newEmail.value,
            password: password.value,
        });

        if (response.data?.success) {
            successMessage.value = response.data.message;
            currentEmail.value = response.data.new_email;
            showChangeEmailForm.value = false;
            newEmail.value = "";
            password.value = "";
            startResendCooldown();
        } else {
            errorMessage.value = response.data?.message || "Gagal mengubah alamat email.";
        }
    } catch (err) {
        if (err.response?.status === 422) {
            formErrors.value = err.response.data.errors || {};
            errorMessage.value = "Mohon periksa kembali input Anda.";
        } else {
            errorMessage.value = err.response?.data?.message || "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    } finally {
        isChanging.value = false;
    }
};
</script>

<template>
    <Head title="Pendaftaran Berhasil" />

    <AuthSplitLayout>
        <div class="flex flex-col gap-6 max-w-md w-full mx-auto">
            <!-- Header section with premium typography -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 mb-4 animate-[bounce_1.5s_infinite_ease-in-out]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5" />
                    </svg>
                </div>
                <AuthFormHeader
                    title="Pendaftaran Berhasil!"
                    subtitle="Langkah terakhir: verifikasi email Anda."
                />
            </div>

            <!-- Email Display Card -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-center shadow-sm">
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">
                    Email Verifikasi Dikirim Ke:
                </p>
                <p class="text-base font-bold text-slate-800 break-all select-all">
                    {{ currentEmail }}
                </p>
                <p class="text-xs text-slate-500 mt-2.5 leading-relaxed">
                    Mohon periksa kotak masuk (inbox) atau folder spam/junk Anda untuk mengaktifkan akun.
                </p>
            </div>

            <!-- Toast Messages -->
            <div v-if="successMessage" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm leading-relaxed transition-all duration-300">
                🚀 {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm leading-relaxed transition-all duration-300">
                ⚠️ {{ errorMessage }}
            </div>

            <!-- Primary actions -->
            <div class="space-y-3">
                <button
                    type="button"
                    @click="handleResend"
                    :disabled="resendCooldown > 0 || isResending"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-slate-200 disabled:to-slate-200 text-white font-semibold rounded-2xl transition duration-150 shadow-md flex items-center justify-center gap-2"
                >
                    <svg v-if="isResending" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <span>
                        {{ resendCooldown > 0 ? `Kirim Ulang (${resendCooldown}s)` : 'Kirim Ulang Link Verifikasi' }}
                    </span>
                </button>

                <!-- Change email toggle button -->
                <button
                    type="button"
                    @click="showChangeEmailForm = !showChangeEmailForm"
                    class="w-full py-3 px-4 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-2xl transition duration-150"
                >
                    {{ showChangeEmailForm ? 'Batal Ubah Email' : 'Salah Menulis Email? Ubah di sini' }}
                </button>
            </div>

            <!-- Inline Typography Form for changing Email -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform -translate-y-2 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-2 opacity-0"
            >
                <form
                    v-if="showChangeEmailForm"
                    @submit.prevent="handleChangeEmail"
                    class="bg-slate-50/50 border border-slate-200 rounded-2xl p-5 space-y-4"
                >
                    <h4 class="text-sm font-bold text-slate-800">Ubah Alamat Email</h4>
                    
                    <!-- New Email Field -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Email Baru</label>
                        <input
                            type="email"
                            v-model="newEmail"
                            required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm"
                        />
                        <p v-if="formErrors.new_email" class="text-xs text-red-600 mt-1">{{ formErrors.new_email[0] }}</p>
                    </div>

                    <!-- Password Field for security -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Password Akun</label>
                        <input
                            type="password"
                            v-model="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm"
                        />
                        <p v-if="formErrors.password" class="text-xs text-red-600 mt-1">{{ formErrors.password[0] }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="isChanging"
                        class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition duration-150 flex items-center justify-center gap-1.5"
                    >
                        <svg v-if="isChanging" class="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Simpan & Kirim Verifikasi Baru
                    </button>
                </form>
            </Transition>

            <!-- Bottom link -->
            <div class="text-center pt-2">
                <Link
                    :href="route('login')"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition duration-150 inline-flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Halaman Login
                </Link>
            </div>
        </div>
    </AuthSplitLayout>
</template>
