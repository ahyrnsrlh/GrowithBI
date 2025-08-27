<template>
    <Head title="Form Pendaftaran Magang" />

    <GuestLayout variant="public">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Form Pendaftaran Magang
                    </h1>
                    <p class="text-lg text-gray-600">
                        Lengkapi formulir di bawah ini untuk mendaftar program
                        magang di PT GrowithBI
                    </p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Personal Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Informasi Pribadi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Nama Lengkap *
                                    </label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Masukkan nama lengkap"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Email *
                                    </label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="contoh@email.com"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Nomor Telepon *
                                    </label>
                                    <input
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        required
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="08123456789"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="division_id"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Pilih Divisi *
                                    </label>
                                    <select
                                        id="division_id"
                                        v-model="form.division_id"
                                        required
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    >
                                        <option value="">Pilih divisi</option>
                                        <option
                                            v-for="division in divisions"
                                            :key="division.id"
                                            :value="division.id"
                                        >
                                            {{ division.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Alamat
                            </h3>
                            <div>
                                <label
                                    for="address"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Alamat Lengkap *
                                </label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    rows="3"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="Masukkan alamat lengkap"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Motivation -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Motivasi
                            </h3>
                            <div>
                                <label
                                    for="motivation"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Ceritakan motivasi Anda untuk magang di
                                    divisi ini *
                                </label>
                                <textarea
                                    id="motivation"
                                    v-model="form.motivation"
                                    rows="4"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="Minimal 50 karakter..."
                                ></textarea>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ form.motivation?.length || 0 }} karakter
                                    (minimal 50)
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <Link
                                href="/"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
                            >
                                {{
                                    processing ? "Mengirim..." : "Kirim Lamaran"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

// Props
const props = defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
});

// Form
const form = useForm({
    name: "",
    email: "",
    phone: "",
    address: "",
    division_id: "",
    motivation: "",
});

// Submit function
const submit = () => {
    form.post(route("application.submit"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
