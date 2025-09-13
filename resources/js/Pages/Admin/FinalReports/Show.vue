<template>
    <AdminLayout>
        <Head title="Detail Laporan Akhir" />

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <Link
                        :href="route('admin.final-reports.index')"
                        class="text-blue-600 hover:text-blue-800 flex items-center mb-2"
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
                        Kembali ke Daftar Laporan
                    </Link>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Detail Laporan Akhir
                    </h1>
                </div>
                <div class="flex space-x-3">
                    <a
                        :href="route('admin.final-reports.download', report.id)"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
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
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Download File
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Report Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        Informasi Laporan
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Judul Laporan</label
                            >
                            <p class="text-lg font-medium text-gray-900">
                                {{ report.title }}
                            </p>
                        </div>

                        <div v-if="report.description">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Deskripsi</label
                            >
                            <p class="text-gray-900 whitespace-pre-wrap">
                                {{ report.description }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Nama File</label
                                >
                                <p class="text-gray-900">
                                    {{ report.file_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Ukuran File</label
                                >
                                <p class="text-gray-900">
                                    {{ formatFileSize(report.file_size) }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Tanggal Submit</label
                                >
                                <p class="text-gray-900">
                                    {{ formatDate(report.created_at) }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Status</label
                                >
                                <span
                                    :class="{
                                        'bg-yellow-100 text-yellow-800':
                                            report.status === 'submitted',
                                        'bg-green-100 text-green-800':
                                            report.status === 'approved',
                                        'bg-red-100 text-red-800':
                                            report.status === 'revision',
                                    }"
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"
                                >
                                    {{ getStatusLabel(report.status) }}
                                </span>
                            </div>
                        </div>

                        <div v-if="report.feedback">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Feedback</label
                            >
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">
                                    {{ report.feedback }}
                                </p>
                                <div
                                    v-if="report.reviewer"
                                    class="mt-3 text-sm text-gray-600"
                                >
                                    <span class="font-medium"
                                        >Direview oleh:</span
                                    >
                                    {{ report.reviewer.name }}
                                    <span class="ml-2">{{
                                        formatDate(report.reviewed_at)
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions & Participant Info -->
            <div class="space-y-6">
                <!-- Participant Info -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Informasi Peserta
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Nama</label
                            >
                            <p class="text-gray-900">{{ report.user.name }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Email</label
                            >
                            <p class="text-gray-900">{{ report.user.email }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Divisi</label
                            >
                            <p class="text-gray-900">
                                {{ report.application.division.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status Update -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Update Status
                    </h3>

                    <form @submit.prevent="updateStatus" class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Status</label
                            >
                            <select
                                v-model="statusForm.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="submitted">
                                    Menunggu Review
                                </option>
                                <option value="approved">Disetujui</option>
                                <option value="revision">Perlu Revisi</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Feedback</label
                            >
                            <textarea
                                v-model="statusForm.feedback"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Berikan feedback untuk peserta..."
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="processing"
                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                            <span v-if="processing">Memproses...</span>
                            <span v-else>Update Status</span>
                        </button>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Aksi Cepat
                    </h3>

                    <div class="space-y-3">
                        <button
                            @click="quickApprove"
                            :disabled="processing"
                            class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
                        >
                            Setujui Laporan
                        </button>

                        <button
                            @click="quickRevision"
                            :disabled="processing"
                            class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50"
                        >
                            Minta Revisi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    report: Object,
});

const processing = ref(false);

const statusForm = reactive({
    status: props.report.status,
    feedback: props.report.feedback || "",
});

const updateStatus = () => {
    processing.value = true;

    router.put(
        route("admin.final-reports.update-status", props.report.id),
        statusForm,
        {
            onSuccess: () => {
                processing.value = false;
            },
            onError: () => {
                processing.value = false;
            },
        }
    );
};

const quickApprove = () => {
    statusForm.status = "approved";
    if (!statusForm.feedback) {
        statusForm.feedback = "Laporan telah disetujui.";
    }
    updateStatus();
};

const quickRevision = () => {
    statusForm.status = "revision";
    updateStatus();
};

const getStatusLabel = (status) => {
    switch (status) {
        case "submitted":
            return "Menunggu Review";
        case "approved":
            return "Disetujui";
        case "revision":
            return "Perlu Revisi";
        default:
            return status;
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
</script>
