<template>
    <Head title="Edit Laporan" />

    <PesertaLayout
        title="Edit Laporan"
        subtitle="Edit laporan akhir magang Anda"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Edit Laporan
                    </h1>
                    <p class="text-gray-600">
                        Perbarui informasi laporan akhir magang Anda
                    </p>
                </div>
                <Link
                    :href="route('peserta.reports.show', report.id)"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <svg
                        class="w-4 h-4 mr-2"
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
                    Kembali
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Form Edit Laporan
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Ubah informasi laporan Anda
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.title }"
                            placeholder="Contoh: Laporan Akhir Magang Divisi IT"
                            required
                        />
                        <p
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Deskripsi
                            <span class="text-gray-500">(Opsional)</span>
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{
                                'border-red-500': form.errors.description,
                            }"
                            placeholder="Deskripsi singkat tentang isi laporan..."
                        ></textarea>
                        <p
                            v-if="form.errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- File Upload (Optional for edit) -->
                    <div>
                        <label
                            for="report_file"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Ganti File Laporan
                            <span class="text-gray-500">(Opsional)</span>
                        </label>
                        <div class="space-y-3">
                            <!-- Current file info -->
                            <div
                                class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg"
                            >
                                <svg
                                    class="w-5 h-5 text-gray-400 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                <div class="flex-1">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        File saat ini:
                                        {{ report.file_name || "Unknown" }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Upload file baru jika ingin mengganti
                                    </p>
                                </div>
                            </div>

                            <!-- File input -->
                            <input
                                type="file"
                                id="report_file"
                                @change="handleFileChange"
                                accept=".pdf,.doc,.docx,.xls,.xlsx"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{
                                    'border-red-500': form.errors.report_file,
                                }"
                            />
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Format: PDF, Word (.doc, .docx), Excel (.xls,
                            .xlsx). Maksimal 10MB.
                        </p>
                        <p
                            v-if="form.errors.report_file"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.report_file }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <Link
                            :href="route('peserta.reports.show', report.id)"
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
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

const props = defineProps({
    report: Object,
});

const form = useForm({
    title: props.report.title || "",
    description: props.report.description || "",
    report_file: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.report_file = file;
    }
};

const submit = () => {
    form.put(route("peserta.reports.update", props.report.id), {
        forceFormData: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>
