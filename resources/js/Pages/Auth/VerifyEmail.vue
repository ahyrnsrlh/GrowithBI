<script setup>
import { computed, ref, onUnmounted } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import AuthSplitLayout from "@/Components/Auth/AuthSplitLayout.vue";
import AuthFormHeader from "@/Components/Auth/AuthFormHeader.vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const page = usePage();
const userEmail = page.props.auth?.user?.email || "";

const form = useForm({});

// Cooldown states
const resendCooldown = ref(0);
let cooldownInterval = null;

const startCooldown = () => {
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

const submit = () => {
    form.post(route("verification.send"), {
        onSuccess: () => {
            startCooldown();
        },
    });
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <Head title="Verifikasi Email Diperlukan" />

    <AuthSplitLayout>
        <div class="flex flex-col gap-6 max-w-md w-full mx-auto">
            
            <!-- Warning / Alert illustration -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <AuthFormHeader
                    title="Verifikasi Email Anda"
                    subtitle="Silakan aktifkan akun Anda melalui link yang dikirimkan."
                />
            </div>

            <!-- Context message -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-center shadow-sm space-y-3">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">
                        Email Terdaftar:
                    </p>
                    <p class="text-base font-bold text-slate-800 break-all select-all">
                        {{ userEmail }}
                    </p>
                </div>
                
                <p class="text-xs text-slate-500 leading-relaxed pt-1.5 border-t border-slate-200/60">
                    Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan. Jika tidak menemukannya, silakan klik tombol kirim ulang di bawah.
                </p>
            </div>

            <!-- Link sent status flash alert -->
            <div
                v-if="verificationLinkSent"
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs leading-relaxed transition-all duration-300"
            >
                🚀 Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
            </div>

            <!-- Form buttons -->
            <form @submit.prevent="submit" class="space-y-3">
                <button
                    type="submit"
                    :disabled="form.processing || resendCooldown > 0"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-slate-200 disabled:to-slate-200 text-white font-bold rounded-2xl transition duration-150 shadow-md flex items-center justify-center gap-2"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <span>
                        {{ resendCooldown > 0 ? `Kirim Ulang (${resendCooldown}s)` : 'Kirim Ulang Email Verifikasi' }}
                    </span>
                </button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full py-3 px-4 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-2xl transition duration-150 text-center"
                >
                    Keluar (Logout)
                </Link>
            </form>
        </div>
    </AuthSplitLayout>
</template>
