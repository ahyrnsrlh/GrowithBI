<template>
    <Head title="Buat Laporan Magang" />

    <PesertaLayout
        title="Buat Laporan Magang"
        subtitle="Buat laporan progress dan pencapaian selama magang"
    >
        <div class="max-w-4xl mx-auto">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Report Type Selection -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Jenis Laporan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label
                            class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none"
                        >
                            <input
                                v-model="form.report_type"
                                type="radio"
                                value="weekly"
                                class="sr-only"
                            />
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span
                                        class="block text-sm font-medium text-gray-900"
                                        >Laporan Mingguan</span
                                    >
                                    <span
                                        class="mt-1 flex items-center text-sm text-gray-500"
                                        >Ringkasan aktivitas per minggu</span
                                    >
                                </span>
                            </span>
                            <svg
                                v-show="form.report_type === 'weekly'"
                                class="h-5 w-5 text-blue-600"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </label>

                        <label
                            class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none"
                        >
                            <input
                                v-model="form.report_type"
                                type="radio"
                                value="monthly"
                                class="sr-only"
                            />
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span
                                        class="block text-sm font-medium text-gray-900"
                                        >Laporan Bulanan</span
                                    >
                                    <span
                                        class="mt-1 flex items-center text-sm text-gray-500"
                                        >Ringkasan aktivitas per bulan</span
                                    >
                                </span>
                            </span>
                            <svg
                                v-show="form.report_type === 'monthly'"
                                class="h-5 w-5 text-blue-600"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </label>

                        <label
                            class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none"
                        >
                            <input
                                v-model="form.report_type"
                                type="radio"
                                value="final"
                                class="sr-only"
                            />
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span
                                        class="block text-sm font-medium text-gray-900"
                                        >Laporan Akhir</span
                                    >
                                    <span
                                        class="mt-1 flex items-center text-sm text-gray-500"
                                        >Laporan keseluruhan magang</span
                                    >
                                </span>
                            </span>
                            <svg
                                v-show="form.report_type === 'final'"
                                class="h-5 w-5 text-blue-600"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </label>
                    </div>
                </div>

                <!-- Report Details -->
                <div class="bg-white rounded-lg shadow p-6 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        Detail Laporan
                    </h3>

                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700"
                            >Judul Laporan</label
                        >
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan judul laporan"
                        />
                        <div
                            v-if="errors.title"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.title }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="period_start"
                                class="block text-sm font-medium text-gray-700"
                                >Periode Mulai</label
                            >
                            <input
                                id="period_start"
                                v-model="form.period_start"
                                type="date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            <div
                                v-if="errors.period_start"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ errors.period_start }}
                            </div>
                        </div>

                        <div>
                            <label
                                for="period_end"
                                class="block text-sm font-medium text-gray-700"
                                >Periode Selesai</label
                            >
                            <input
                                id="period_end"
                                v-model="form.period_end"
                                type="date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            <div
                                v-if="errors.period_end"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ errors.period_end }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            for="summary"
                            class="block text-sm font-medium text-gray-700"
                            >Ringkasan Aktivitas</label
                        >
                        <textarea
                            id="summary"
                            v-model="form.summary"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan ringkasan aktivitas selama periode laporan"
                        ></textarea>
                        <div
                            v-if="errors.summary"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.summary }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="achievements"
                            class="block text-sm font-medium text-gray-700"
                            >Pencapaian</label
                        >
                        <textarea
                            id="achievements"
                            v-model="form.achievements"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan pencapaian dan hasil yang diperoleh"
                        ></textarea>
                        <div
                            v-if="errors.achievements"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.achievements }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="challenges"
                            class="block text-sm font-medium text-gray-700"
                            >Tantangan & Pembelajaran</label
                        >
                        <textarea
                            id="challenges"
                            v-model="form.challenges"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan tantangan yang dihadapi dan pembelajaran yang didapat"
                        ></textarea>
                        <div
                            v-if="errors.challenges"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.challenges }}
                        </div>
                    </div>

                    <div>
                        <label
                            for="next_plans"
                            class="block text-sm font-medium text-gray-700"
                            >Rencana Selanjutnya</label
                        >
                        <textarea
                            id="next_plans"
                            v-model="form.next_plans"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan rencana aktivitas untuk periode selanjutnya"
                        ></textarea>
                        <div
                            v-if="errors.next_plans"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.next_plans }}
                        </div>
                    </div>
                </div>

                <!-- Logbook Selection -->
                <div
                    v-if="logbooks.length > 0"
                    class="bg-white rounded-lg shadow p-6"
                >
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Pilih Logbook yang Termasuk
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Pilih logbook yang akan disertakan dalam laporan ini
                    </p>

                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <label
                            v-for="logbook in logbooks"
                            :key="logbook.id"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer"
                        >
                            <input
                                v-model="form.selected_logbooks"
                                :value="logbook.id"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <div class="ml-3 flex-1">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ logbook.title || "Aktivitas Harian" }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ formatDate(logbook.date) }} •
                                    {{ logbook.duration }} jam
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Upload File Laporan
                    </h3>
                    <div>
                        <label
                            for="report_file"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            File Laporan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="file"
                            id="report_file"
                            @change="handleFileChange"
                            accept=".pdf,.doc,.docx,.xls,.xlsx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{
                                'border-red-500': errors.report_file,
                            }"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Format: PDF, Word (.doc, .docx), Excel (.xls,
                            .xlsx). Maksimal 10MB.
                        </p>
                        <div
                            v-if="errors.report_file"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ errors.report_file }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <Link
                        :href="route('peserta.reports.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
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
                        {{ form.processing ? "Membuat..." : "Buat Laporan" }}
                    </button>
                </div>
            </form>
        </div>
    </PesertaLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";

const props = defineProps({
    division: Object,
    logbooks: Array,
    application: Object,
    errors: Object,
});

const form = useForm({
    report_type: "weekly",
    title: "",
    period_start: "",
    period_end: "",
    summary: "",
    achievements: "",
    challenges: "",
    next_plans: "",
    selected_logbooks: [],
    report_file: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.report_file = file;
    }
};

const submit = () => {
    form.post(route("peserta.reports.store"), {
        forceFormData: true,
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>
