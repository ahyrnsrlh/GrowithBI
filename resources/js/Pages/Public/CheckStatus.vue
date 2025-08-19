<<<<<<< HEAD
=======
<template>
    <Head title="Cek Status Pendaftaran" />

    <GuestLayout>
        <div
            class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12"
        >
            <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4"
                    >
                        <svg
                            class="h-8 w-8 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Cek Status Pendaftaran
                    </h1>
                    <p class="text-lg text-gray-600">
                        Masukkan data Anda untuk mengecek status aplikasi magang
                    </p>
                </div>

                <!-- Status Check Form -->
                <div class="bg-white shadow-xl rounded-lg p-8">
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <div>
                                <InputLabel
                                    for="email"
                                    value="Email yang Terdaftar"
                                />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="contoh@email.com"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="application_id"
                                    value="ID Aplikasi"
                                />
                                <TextInput
                                    id="application_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.application_id"
                                    required
                                    placeholder="Nomor ID aplikasi dari email konfirmasi"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.application_id"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    ID aplikasi dapat ditemukan di email
                                    konfirmasi yang dikirim setelah pendaftaran
                                </p>
                            </div>

                            <!-- Error Display -->
                            <div
                                v-if="form.errors.error"
                                class="p-4 bg-red-50 border border-red-200 rounded-lg"
                            >
                                <p class="text-red-700 text-sm">
                                    {{ form.errors.error }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton
                                    class="w-full"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    {{
                                        form.processing
                                            ? "Mengecek..."
                                            : "Cek Status"
                                    }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Help Section -->
                <div class="mt-8 p-4 bg-white rounded-lg shadow">
                    <h3 class="font-semibold text-gray-900 mb-2">Bantuan</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p><strong>Tidak menerima email konfirmasi?</strong></p>
                        <ul class="ml-4 space-y-1">
                            <li>• Periksa folder spam/junk email Anda</li>
                            <li>
                                • Pastikan email yang dimasukkan sesuai dengan
                                saat pendaftaran
                            </li>
                            <li>• Tunggu hingga 24 jam setelah pendaftaran</li>
                        </ul>

                        <p class="mt-4"><strong>Lupa ID Aplikasi?</strong></p>
                        <p>
                            ID aplikasi tercantum dalam email konfirmasi dengan
                            format: APP-XXXXXXXX
                        </p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="mt-8 text-center space-y-4">
                    <p class="text-gray-600">
                        Belum mendaftar?
                        <Link
                            :href="route('public.apply')"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            Daftar magang sekarang
                        </Link>
                    </p>
                    <p class="text-gray-600">
                        <Link
                            :href="route('home')"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            ← Kembali ke halaman utama
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    email: "",
    application_id: "",
});

const submit = () => {
    form.post(route("public.get.status"));
};
</script>
>>>>>>> parent of b610151 (feat: Add Divisions page with search and filter functionality)
