<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Reset Password - GrowithBI" />

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center mb-6">
                    <img 
                        src="/logo.png" 
                        alt="Bank Indonesia Logo" 
                        class="h-12 w-12 mr-3"
                    />
                    <h1
                        class="ml-3 text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                    >
                        GrowithBI
                    </h1>
                </Link>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Reset Password
                </h2>
                <p class="text-gray-600">
                    Masukkan password baru untuk akun Anda di platform
                    GrowithBI.
                </p>
            </div>

            <!-- Form -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-8"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field (Hidden but required) -->
                    <input type="hidden" v-model="form.email" />

                    <!-- Email Display -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Email Account
                        </label>
                        <div
                            class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 font-medium"
                        >
                            {{ form.email }}
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Password Baru
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                                placeholder="Masukkan password baru"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <div
                            v-if="form.errors.password"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label
                            for="password_confirmation"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                                placeholder="Ulangi password baru"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <div
                            v-if="form.errors.password_confirmation"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 !text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin h-5 w-5 text-white"
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
                            form.processing
                                ? "Memproses..."
                                : "Update Password"
                        }}
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <Link
                        :href="route('login')"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                    >
                        ← Kembali ke Halaman Login
                    </Link>
                </div>

                <!-- Security Info -->
                <div
                    class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl"
                >
                    <div class="flex items-start space-x-2">
                        <svg
                            class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Tips Keamanan:</p>
                            <ul class="space-y-1 text-blue-700">
                                <li>
                                    • Gunakan kombinasi huruf besar, kecil,
                                    angka, dan simbol
                                </li>
                                <li>
                                    • Minimal 8 karakter untuk keamanan optimal
                                </li>
                                <li>
                                    • Jangan gunakan password yang sama dengan
                                    akun lain
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
