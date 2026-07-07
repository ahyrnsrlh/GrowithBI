<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthSplitLayout from "@/Components/Auth/AuthSplitLayout.vue";
import AuthFormHeader from "@/Components/Auth/AuthFormHeader.vue";
</script>

<template>
    <Head title="Email Terverifikasi" />

    <AuthSplitLayout>
        <div class="flex flex-col gap-6 max-w-sm w-full mx-auto text-center">
            
            <!-- Custom animated success check mark -->
            <div class="mx-auto w-24 h-24 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center mb-2 shadow-inner">
                <div class="w-16 h-16 bg-gradient-to-tr from-emerald-400 to-green-500 rounded-full flex items-center justify-center shadow-md animate-[pulse_2s_infinite]">
                    <svg
                        class="w-9 h-9 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="3"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
            </div>

            <!-- Form header text -->
            <AuthFormHeader
                title="Email Terverifikasi!"
                subtitle="Akun GrowithBI Anda sekarang aktif dan siap digunakan."
            />

            <!-- Body detail card -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 shadow-sm text-sm text-slate-600 leading-relaxed">
                <p>
                    Terima kasih telah memverifikasi kepemilikan email Anda. Anda sekarang dapat masuk untuk melengkapi berkas administrasi dan melakukan presensi online.
                </p>
            </div>

            <!-- Action button to redirect to login -->
            <Link
                :href="route('login')"
                class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-2xl transition duration-150 shadow-lg hover:shadow-xl text-center"
            >
                Masuk ke Akun Saya
            </Link>
        </div>
    </AuthSplitLayout>
</template>
