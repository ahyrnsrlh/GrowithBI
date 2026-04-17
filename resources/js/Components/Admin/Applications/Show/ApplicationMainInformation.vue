<template>
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Informasi Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Nama Lengkap
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.name || "-" }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Email
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.email || "-" }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        No. Telepon
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.phone || "-" }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Universitas
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.university || "-" }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Jurusan
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.major || "-" }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Semester
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{
                            application.user?.semester
                                ? `Semester ${application.user.semester}`
                                : "-"
                        }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">
                        Alamat
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.user?.address || "-" }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Informasi Divisi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Divisi
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.division.name }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Pembimbing
                    </label>
                    <p class="mt-1 text-sm text-gray-900">GrowithBI Admin</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Kuota
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ application.division.quota }} orang
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">
                        Periode
                    </label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ formatDate(application.division.start_date) }} -
                        {{ formatDate(application.division.end_date) }}
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-500">
                    Deskripsi Divisi
                </label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ application.division.description }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="item in documents"
                    :key="item.key"
                    class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 transition-colors"
                >
                    <div
                        :class="[
                            'w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3',
                            item.badgeClass,
                        ]"
                    >
                        <svg
                            class="w-6 h-6"
                            :class="item.iconClass"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                :d="item.iconPath"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 mb-1">
                        {{ item.title }}
                    </p>
                    <p class="text-xs text-gray-500 mb-3">
                        {{
                            getDocumentPath(item.key)
                                ? "Terupload"
                                : "Belum ada"
                        }}
                    </p>
                    <div class="flex space-x-2 justify-center">
                        <a
                            v-if="getDocumentPath(item.key)"
                            :href="`/storage/${getDocumentPath(item.key)}`"
                            target="_blank"
                            class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors"
                        >
                            <i class="fas fa-eye mr-1"></i>
                            Lihat
                        </a>
                        <a
                            v-if="getDocumentPath(item.key)"
                            :href="`/storage/${getDocumentPath(item.key)}`"
                            download
                            class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition-colors"
                        >
                            <i class="fas fa-download mr-1"></i>
                            Unduh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const documents = [
    {
        key: "surat_pengantar_path",
        title: "Surat Pengantar",
        badgeClass: "bg-blue-100",
        iconClass: "text-blue-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "cv_path",
        title: "Curriculum Vitae (CV)",
        badgeClass: "bg-red-100",
        iconClass: "text-red-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "motivation_letter_path",
        title: "Motivation Letter",
        badgeClass: "bg-purple-100",
        iconClass: "text-purple-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "transkrip_path",
        title: "Transkrip Nilai",
        badgeClass: "bg-yellow-100",
        iconClass: "text-yellow-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "ktp_path",
        title: "KTP",
        badgeClass: "bg-indigo-100",
        iconClass: "text-indigo-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "npwp_path",
        title: "NPWP",
        badgeClass: "bg-orange-100",
        iconClass: "text-orange-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "buku_rekening_path",
        title: "Buku Rekening",
        badgeClass: "bg-teal-100",
        iconClass: "text-teal-600",
        iconPath:
            "M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z",
    },
    {
        key: "pas_foto_path",
        title: "Pas Foto",
        badgeClass: "bg-pink-100",
        iconClass: "text-pink-600",
        iconPath:
            "M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z",
    },
];

const props = defineProps({
    application: { type: Object, required: true },
    formatDate: { type: Function, required: true },
});

const getDocumentPath = (key) => props.application.user?.[key] || "";
</script>
