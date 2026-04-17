<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                        Dokumen Persyaratan
                    </h2>
                    <p class="text-sm text-gray-600">
                        Lengkapi semua dokumen yang diperlukan untuk proses
                        magang
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <svg
                            class="w-5 h-5"
                            :class="
                                documentProgress === 100
                                    ? 'text-green-600'
                                    : 'text-blue-600'
                            "
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                v-if="documentProgress === 100"
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                            <path
                                v-else
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span
                            class="text-sm font-medium"
                            :class="
                                documentProgress === 100
                                    ? 'text-green-700'
                                    : 'text-gray-700'
                            "
                        >
                            {{
                                documentProgress === 100
                                    ? "100% Lengkap"
                                    : `${documentProgress}% Lengkap`
                            }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500">
                        {{ uploadedDocumentsCount }} dari 8 dokumen
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div
                        class="h-2 rounded-full transition-all duration-300"
                        :class="
                            documentProgress === 100
                                ? 'bg-green-600'
                                : 'bg-blue-600'
                        "
                        :style="`width: ${documentProgress}%`"
                    ></div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                <div class="flex items-start space-x-2">
                    <svg
                        class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <div class="text-xs text-blue-800">
                        <span class="font-medium">Persyaratan file:</span>
                        Format PDF, JPG, atau PNG • Maksimal 5MB per file
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <DocumentCard
                    document-type="surat_pengantar"
                    document-name="Surat Pengantar"
                    :document-path="user.surat_pengantar_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="cv"
                    document-name="Curriculum Vitae (CV)"
                    :document-path="user.cv_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="motivation_letter"
                    document-name="Motivation Letter"
                    :document-path="user.motivation_letter_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="transkrip"
                    document-name="Transkrip Nilai"
                    :document-path="user.transkrip_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="ktp"
                    document-name="KTP"
                    :document-path="user.ktp_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="npwp"
                    document-name="NPWP"
                    :document-path="user.npwp_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="buku_rekening"
                    document-name="Buku Rekening Tabungan"
                    :document-path="user.buku_rekening_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />

                <DocumentCard
                    document-type="pas_foto"
                    document-name="Pas Foto 3x4"
                    :document-path="user.pas_foto_path"
                    :is-required="true"
                    @upload="forwardUpload"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import DocumentCard from "@/Components/DocumentCard.vue";

const props = defineProps({
    user: { type: Object, required: true },
    documentProgress: { type: Number, default: 0 },
});

const emit = defineEmits(["upload-document"]);

const forwardUpload = (type, file) => {
    emit("upload-document", type, file);
};

const uploadedDocumentsCount = computed(() => {
    return [
        props.user.surat_pengantar_path,
        props.user.cv_path,
        props.user.motivation_letter_path,
        props.user.transkrip_path,
        props.user.ktp_path,
        props.user.npwp_path,
        props.user.buku_rekening_path,
        props.user.pas_foto_path,
    ].filter(Boolean).length;
});
</script>
