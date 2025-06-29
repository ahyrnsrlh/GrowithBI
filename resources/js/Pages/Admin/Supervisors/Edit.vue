<template>
    <AdminLayout
        title="Edit Pembimbing"
        :subtitle="`Edit data pembimbing ${supervisor.name}`"
    >
        <div class="max-w-4xl mx-auto">
            <!-- Form -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Edit Data Pembimbing
                    </h2>
                    <Link
                        :href="route('admin.supervisors.show', supervisor.id)"
                        class="text-sm text-gray-600 hover:text-gray-900 flex items-center"
                    >
                        <svg
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Kembali ke Detail
                    </Link>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Informasi Personal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nama Lengkap *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Email *
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="nama@email.com"
                                    required
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nomor Telepon
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    v-model="form.phone"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="08xxxxxxxxxx"
                                />
                                <div
                                    v-if="form.errors.phone"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Status</label
                                >
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            v-model="form.is_active"
                                            :value="true"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700"
                                            >Aktif</span
                                        >
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            v-model="form.is_active"
                                            :value="false"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700"
                                            >Tidak Aktif</span
                                        >
                                    </label>
                                </div>
                                <div
                                    v-if="form.errors.is_active"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.is_active }}
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label
                                for="address"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Alamat
                            </label>
                            <textarea
                                id="address"
                                v-model="form.address"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Alamat lengkap"
                            ></textarea>
                            <div
                                v-if="form.errors.address"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.address }}
                            </div>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Ubah Password
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label
                                    for="password"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Password Baru
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    v-model="form.password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Minimal 8 karakter"
                                />
                                <div
                                    v-if="form.errors.password"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label
                                    for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Konfirmasi Password
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Ulangi password baru"
                                />
                                <div
                                    v-if="form.errors.password_confirmation"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.password_confirmation }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200"
                    >
                        <Link
                            :href="
                                route('admin.supervisors.show', supervisor.id)
                            "
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center"
                        >
                            <svg
                                v-if="form.processing"
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    ? "Menyimpan..."
                                    : "Simpan Perubahan"
                            }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informasi Divisi -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex">
                    <svg
                        class="flex-shrink-0 w-5 h-5 text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            Informasi Pembimbing
                        </h3>
                        <div class="mt-2 text-sm text-gray-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>
                                    Pembimbing ini saat ini membimbing
                                    {{
                                        supervisor.supervised_divisions_count ||
                                        0
                                    }}
                                    divisi
                                </li>
                                <li>
                                    Total peserta yang dibimbing:
                                    {{
                                        supervisor.supervised_applications_count ||
                                        0
                                    }}
                                    orang
                                </li>
                                <li>
                                    Status tidak aktif akan menonaktifkan akses
                                    login
                                </li>
                                <li>
                                    Perubahan email akan mempengaruhi proses
                                    login
                                </li>
                                <li>
                                    Password hanya akan diubah jika field diisi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

// Import route helper
const route = window.route;

const props = defineProps({
    supervisor: Object,
});

const form = useForm({
    name: props.supervisor.name,
    email: props.supervisor.email,
    phone: props.supervisor.phone,
    address: props.supervisor.address,
    password: "",
    password_confirmation: "",
    is_active: props.supervisor.is_active,
});

const submit = () => {
    form.put(route("admin.supervisors.update", props.supervisor.id));
};
</script>
