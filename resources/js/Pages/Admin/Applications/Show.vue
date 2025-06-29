<template>
    <AdminLayout
        title="Detail Pendaftaran"
        :subtitle="`Pendaftaran ${application.user.name} - ${application.division.name}`"
    >
        <div class="max-w-4xl mx-auto">
            <!-- Header with Actions -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <Link
                            href="/admin/applications"
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
                                {{ application.user.name }}
                            </h1>
                            <p class="text-gray-600">
                                {{ application.user.email }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span
                            :class="[
                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                application.status === 'menunggu'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : application.status === 'diterima'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800',
                            ]"
                        >
                            {{ application.status }}
                        </span>
                        <button
                            @click="showStatusModal = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            Update Status
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Nama Lengkap</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.user.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Email</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.user.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >No. Telepon</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.user.phone || "-" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Alamat</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.user.address || "-" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Division Information -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Divisi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Divisi</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.division.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Pembimbing</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        application.division.supervisor?.name ||
                                        "Belum ditentukan"
                                    }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Kuota</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ application.division.quota }} orang
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    >Periode</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{
                                        formatDate(
                                            application.division.start_date
                                        )
                                    }}
                                    -
                                    {{
                                        formatDate(
                                            application.division.end_date
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-500"
                                >Deskripsi Divisi</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ application.division.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Motivation Letter -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Surat Motivasi
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900 leading-relaxed">
                                {{
                                    application.motivation_letter ||
                                    "Tidak ada surat motivasi"
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Dokumen
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="border border-gray-200 rounded-lg p-4 text-center"
                            >
                                <svg
                                    class="w-8 h-8 text-red-500 mx-auto mb-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">
                                    CV
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        application.cv_file
                                            ? "Tersedia"
                                            : "Belum upload"
                                    }}
                                </p>
                                <button
                                    v-if="application.cv_file"
                                    class="mt-2 text-blue-600 hover:text-blue-700 text-xs"
                                >
                                    Download
                                </button>
                            </div>
                            <div
                                class="border border-gray-200 rounded-lg p-4 text-center"
                            >
                                <svg
                                    class="w-8 h-8 text-blue-500 mx-auto mb-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">
                                    KTP
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        application.ktp_file
                                            ? "Tersedia"
                                            : "Belum upload"
                                    }}
                                </p>
                                <button
                                    v-if="application.ktp_file"
                                    class="mt-2 text-blue-600 hover:text-blue-700 text-xs"
                                >
                                    Download
                                </button>
                            </div>
                            <div
                                class="border border-gray-200 rounded-lg p-4 text-center"
                            >
                                <svg
                                    class="w-8 h-8 text-green-500 mx-auto mb-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">
                                    Surat Lamaran
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        application.application_letter_file
                                            ? "Tersedia"
                                            : "Belum upload"
                                    }}
                                </p>
                                <button
                                    v-if="application.application_letter_file"
                                    class="mt-2 text-blue-600 hover:text-blue-700 text-xs"
                                >
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Timeline -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Timeline
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-4 h-4 text-blue-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Pendaftaran
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{
                                            formatDateTime(
                                                application.created_at
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="application.reviewed_at"
                                class="flex items-start space-x-3"
                            >
                                <div
                                    :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center',
                                        application.status === 'diterima'
                                            ? 'bg-green-100'
                                            : 'bg-red-100',
                                    ]"
                                >
                                    <svg
                                        v-if="application.status === 'diterima'"
                                        class="w-4 h-4 text-green-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-4 h-4 text-red-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            application.status === "diterima"
                                                ? "Diterima"
                                                : "Ditolak"
                                        }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{
                                            formatDateTime(
                                                application.reviewed_at
                                            )
                                        }}
                                    </p>
                                    <p
                                        v-if="application.reviewer"
                                        class="text-xs text-gray-500"
                                    >
                                        oleh {{ application.reviewer.name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Statistik Cepat
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600"
                                    >Status</span
                                >
                                <span
                                    :class="[
                                        'text-sm font-medium',
                                        application.status === 'menunggu'
                                            ? 'text-yellow-600'
                                            : application.status === 'diterima'
                                            ? 'text-green-600'
                                            : 'text-red-600',
                                    ]"
                                >
                                    {{ application.status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600"
                                    >Divisi</span
                                >
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ application.division.name }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600"
                                    >Lama Apply</span
                                >
                                <span class="text-sm font-medium text-gray-900"
                                    >{{
                                        getDaysAgo(application.created_at)
                                    }}
                                    hari lalu</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div
                        v-if="application.admin_notes"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Catatan Admin
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900">
                                {{ application.admin_notes }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div
            v-if="showStatusModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Update Status Pendaftaran
                    </h3>
                    <form @submit.prevent="updateStatus">
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Status</label
                            >
                            <select
                                v-model="statusForm.status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                            >
                                <option value="menunggu">Menunggu</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Catatan Admin</label
                            >
                            <textarea
                                v-model="statusForm.admin_notes"
                                rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Tambahkan catatan (opsional)"
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="showStatusModal = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
    application: Object,
});

const showStatusModal = ref(false);

const statusForm = reactive({
    status: props.application.status,
    admin_notes: props.application.admin_notes || "",
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatDateTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getDaysAgo = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
};

const updateStatus = () => {
    router.put(`/admin/applications/${props.application.id}`, statusForm, {
        onSuccess: () => {
            showStatusModal.value = false;
        },
    });
};
</script>
