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
                                    for="phone"
                                    value="Nomor Telepon"
                                />
                                <TextInput
                                    id="phone"
                                    type="tel"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                    placeholder="08XXXXXXXXXX"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.phone"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Masukkan nomor telepon yang sama dengan saat pendaftaran
                                </p>
                            </div>

                            <!-- Error Display -->
                            <div
                                v-if="form.errors.search"
                                class="p-4 bg-red-50 border border-red-200 rounded-lg"
                            >
                                <p class="text-red-700 text-sm">
                                    {{ form.errors.search }}
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

                <!-- Application Result -->
                <div v-if="$page.props.application" class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800">Status Aplikasi Ditemukan</h3>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium text-gray-900">{{ $page.props.application.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900">{{ $page.props.application.email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Divisi:</span>
                            <span class="font-medium text-gray-900">{{ $page.props.application.division }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span 
                                :class="{
                                    'bg-yellow-100 text-yellow-800': $page.props.application.status === 'menunggu',
                                    'bg-green-100 text-green-800': $page.props.application.status === 'diterima',
                                    'bg-red-100 text-red-800': $page.props.application.status === 'ditolak'
                                }"
                                class="px-3 py-1 rounded-full text-xs font-medium"
                            >
                                {{ getStatusText($page.props.application.status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Daftar:</span>
                            <span class="font-medium text-gray-900">{{ $page.props.application.created_at }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terakhir Update:</span>
                            <span class="font-medium text-gray-900">{{ $page.props.application.updated_at }}</span>
                        </div>
                    </div>
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
                            :href="route('public.application.form')"
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
    phone: "",
});

const submit = () => {
    form.post(route("public.search.status"), {
        preserveState: true,
        preserveScroll: true
    });
};

const getStatusText = (status) => {
    const statusMap = {
        'menunggu': 'Menunggu Review',
        'diterima': 'Diterima',
        'ditolak': 'Ditolak'
    };
    return statusMap[status] || status;
};
</script>
