<template>
    <AdminLayout title="Tambah Divisi" subtitle="Buat divisi magang baru">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex items-center space-x-4 mb-8">
                <Link
                    href="/admin/divisions"
                    class="text-blue-600 hover:text-blue-700"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tambah Divisi Baru
                    </h1>
                    <p class="text-gray-600">
                        Lengkapi informasi divisi magang
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <form @submit.prevent="submitForm">
                    <!-- Division Name -->
                    <div class="mb-6">
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Divisi <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: IT Development"
                            required
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan tentang divisi ini, tugas-tugas yang akan dikerjakan, teknologi yang digunakan, dll."
                            required
                        ></textarea>
                        <p
                            v-if="errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.description }}
                        </p>
                    </div>

                    <!-- Job Description -->
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Tugas dan Tanggung Jawab
                        </label>
                        <div class="space-y-3">
                            <div v-for="(job, index) in form.job_description" :key="index" class="flex items-center space-x-3">
                                <input
                                    v-model="form.job_description[index]"
                                    type="text"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :placeholder="`Tugas ${index + 1}`"
                                />
                                <button
                                    v-if="form.job_description.length > 1"
                                    @click="removeJobDescription(index)"
                                    type="button"
                                    class="text-red-600 hover:text-red-700 p-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <button
                                @click="addJobDescription"
                                type="button"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Tugas
                            </button>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Persyaratan
                        </label>
                        <div class="space-y-3">
                            <div v-for="(requirement, index) in form.requirements" :key="index" class="flex items-center space-x-3">
                                <input
                                    v-model="form.requirements[index]"
                                    type="text"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :placeholder="`Persyaratan ${index + 1}`"
                                />
                                <button
                                    v-if="form.requirements.length > 1"
                                    @click="removeRequirement(index)"
                                    type="button"
                                    class="text-red-600 hover:text-red-700 p-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <button
                                @click="addRequirement"
                                type="button"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Persyaratan
                            </button>
                        </div>
                    </div>

                    <!-- Quota and Supervisor -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                for="quota"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Kuota <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="quota"
                                v-model.number="form.quota"
                                type="number"
                                min="1"
                                max="100"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="10"
                                required
                            />
                            <p
                                v-if="errors.quota"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.quota }}
                            </p>
                        </div>
                    </div>

                    <!-- Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                for="start_date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Mulai
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="start_date"
                                v-model="form.start_date"
                                type="date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <p
                                v-if="errors.start_date"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.start_date }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="end_date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Selesai
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="end_date"
                                v-model="form.end_date"
                                type="date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <p
                                v-if="errors.end_date"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.end_date }}
                            </p>
                        </div>
                    </div>

                    <!-- Application and Selection Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                for="application_deadline"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Batas Waktu Pendaftaran
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="application_deadline"
                                v-model="form.application_deadline"
                                type="date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <p
                                v-if="errors.application_deadline"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.application_deadline }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="selection_announcement"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Pengumuman Hasil Seleksi
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="selection_announcement"
                                v-model="form.selection_announcement"
                                type="date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <p
                                v-if="errors.selection_announcement"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.selection_announcement }}
                            </p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-8">
                        <label class="flex items-center">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Aktif (dapat menerima pendaftaran)</span
                            >
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <Link
                            href="/admin/divisions"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Divisi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Text -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg
                        class="w-5 h-5 text-blue-600 mt-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Tips</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>
                                    Pastikan deskripsi divisi jelas dan menarik
                                    untuk calon peserta
                                </li>
                                <li>
                                    Kuota dapat disesuaikan berdasarkan
                                    kapasitas pembimbing
                                </li>
                                <li>
                                    Periode magang sebaiknya minimal 2-3 bulan
                                    untuk hasil optimal
                                </li>
                                <li>
                                    Pilih pembimbing yang sesuai dengan
                                    expertise divisi
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

const props = defineProps({
    errors: Object,
});

const form = useForm({
    name: "",
    description: "",
    job_description: [""],
    requirements: [""],
    quota: 10,
    start_date: "",
    end_date: "",
    application_deadline: "",
    selection_announcement: "",
    is_active: true,
});

const addJobDescription = () => {
    form.job_description.push("");
};

const removeJobDescription = (index) => {
    form.job_description.splice(index, 1);
};

const addRequirement = () => {
    form.requirements.push("");
};

const removeRequirement = (index) => {
    form.requirements.splice(index, 1);
};

const submitForm = () => {
    // Filter out empty values
    form.job_description = form.job_description.filter(job => job.trim() !== "");
    form.requirements = form.requirements.filter(req => req.trim() !== "");
    
    form.post("/admin/divisions");
};
</script>
