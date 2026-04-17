<template>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
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
                        <p class="text-sm font-medium text-gray-900">
                            Pendaftaran
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ formatDateTime(application.created_at) }}
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
                            isAcceptedStatus(application.status)
                                ? 'bg-green-100'
                                : 'bg-red-100',
                        ]"
                    >
                        <svg
                            v-if="isAcceptedStatus(application.status)"
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
                        <p class="text-sm font-medium text-gray-900">
                            {{
                                isAcceptedStatus(application.status)
                                    ? "Diterima"
                                    : "Ditolak"
                            }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ formatDateTime(application.reviewed_at) }}
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Statistik Cepat
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    <span
                        :class="[
                            'text-sm font-medium',
                            getStatusTextClass(application.status),
                        ]"
                    >
                        {{ application.status }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Divisi</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ application.division.name }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Lama Apply</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ getDaysAgo(application.created_at) }} hari lalu
                    </span>
                </div>
            </div>
        </div>

        <div
            v-if="isAcceptedStatus(application.status)"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
        >
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Surat Penerimaan
            </h3>

            <div v-if="application.acceptance_letter_path" class="mb-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                            >
                                <svg
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
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-800">
                                    Surat penerimaan telah diupload
                                </p>
                                <p class="text-xs text-green-600">
                                    {{
                                        formatDateTime(
                                            application.acceptance_letter_uploaded_at,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a
                                :href="`/storage/${application.acceptance_letter_path}`"
                                target="_blank"
                                class="text-green-600 hover:text-green-700 text-sm font-medium"
                            >
                                Lihat
                            </a>
                            <button
                                @click="$emit('open-letter-upload')"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                            >
                                Ganti
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="mb-4">
                <div
                    class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-yellow-600"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">
                                Surat penerimaan belum diupload
                            </p>
                            <p class="text-xs text-yellow-600">
                                Upload surat penerimaan untuk peserta ini
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <button
                v-if="!application.acceptance_letter_path"
                @click="$emit('open-letter-upload')"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
                Upload Surat Penerimaan
            </button>
        </div>

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
</template>

<script setup>
defineProps({
    application: { type: Object, required: true },
    formatDateTime: { type: Function, required: true },
    getDaysAgo: { type: Function, required: true },
    isAcceptedStatus: { type: Function, required: true },
    getStatusTextClass: { type: Function, required: true },
});

defineEmits(["open-letter-upload"]);
</script>
