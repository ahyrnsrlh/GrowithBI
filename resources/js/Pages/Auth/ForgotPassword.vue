<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Lupa Password - GrowithBI" />

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center mb-6">
                    <div
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 p-3 rounded-xl"
                    >
                        <svg
                            class="w-8 h-8 text-white"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"
                            />
                        </svg>
                    </div>
                    <h1
                        class="ml-3 text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                    >
                        GrowithBI
                    </h1>
                </Link>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Lupa Password?
                </h2>
                <p class="text-gray-600">
                    Tidak masalah! Masukkan email Anda dan kami akan mengirimkan
                    link untuk reset password.
                </p>
            </div>

            <!-- Form -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-8"
            >
                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-green-600 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span class="text-sm font-medium text-green-800">{{
                            status
                        }}</span>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Email Address
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
                                        d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                                    />
                                    <path
                                        d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                                    />
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan email Anda"
                            />
                        </div>
                        <div
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105"
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
                            form.processing
                                ? "Mengirim..."
                                : "📧 Kirim Link Reset Password"
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

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <Link
                        href="/"
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
