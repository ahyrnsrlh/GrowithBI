<template>
    <AdminLayout title="Edit Divisi" :subtitle="`Edit divisi ${form.name}`">
        <div class="max-w-4xl mx-auto">
            <!-- Form Edit Divisi -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Edit Divisi
                    </h2>
                    <Link
                        :href="route('admin.divisions.index')"
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
                        Kembali ke Daftar Divisi
                    </Link>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama Divisi -->
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Divisi *
                        </label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama divisi"
                            required
                        />
                        <div
                            v-if="form.errors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Deskripsi divisi dan tugas-tugas yang akan dikerjakan"
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <!-- Pembimbing -->
                    <div>
                        <label
                            for="supervisor_id"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Pembimbing
                        </label>
                        <select
                            id="supervisor_id"
                            v-model="form.supervisor_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Pilih Pembimbing</option>
                            <option
                                v-for="supervisor in supervisors"
                                :key="supervisor.id"
                                :value="supervisor.id"
                            >
                                {{ supervisor.name }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.supervisor_id"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.supervisor_id }}
                        </div>
                    </div>

                    <!-- Kuota -->
                    <div>
                        <label
                            for="quota"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Kuota Peserta *
                        </label>
                        <input
                            type="number"
                            id="quota"
                            v-model="form.quota"
                            min="1"
                            max="50"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Maksimal peserta yang dapat diterima"
                            required
                        />
                        <div
                            v-if="form.errors.quota"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.quota }}
                        </div>
                    </div>

                    <!-- Requirement -->
                    <div>
                        <label
                            for="requirements"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Persyaratan
                        </label>
                        <textarea
                            id="requirements"
                            v-model="form.requirements"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Persyaratan khusus untuk divisi ini"
                        ></textarea>
                        <div
                            v-if="form.errors.requirements"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.requirements }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Status Divisi</label
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

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200"
                    >
                        <Link
                            :href="route('admin.divisions.index')"
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

            <!-- Tips -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg
                        class="flex-shrink-0 w-5 h-5 text-blue-400"
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
                        <h3 class="text-sm font-medium text-blue-800">
                            Tips Edit Divisi
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>
                                    Pastikan nama divisi unik dan deskriptif
                                </li>
                                <li>
                                    Kuota disesuaikan dengan kapasitas
                                    pembimbing
                                </li>
                                <li>
                                    Persyaratan yang jelas membantu calon
                                    peserta
                                </li>
                                <li>
                                    Divisi tidak aktif tidak akan menerima
                                    pendaftar baru
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
    division: Object,
    supervisors: Array,
});

const form = useForm({
    name: props.division.name,
    description: props.division.description,
    supervisor_id: props.division.supervisor_id,
    quota: props.division.quota,
    requirements: props.division.requirements,
    is_active: props.division.is_active,
});

const submit = () => {
    form.put(route("admin.divisions.update", props.division.id));
};
</script>
