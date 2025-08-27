<template>
    <PesertaLayout
        :title="`Detail Logbook - ${logbook.title}`"
        subtitle="Detail aktivitas harian magang"
    >
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ logbook.title }}
                    </h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="text-sm text-gray-500">
                            <svg
                                class="w-4 h-4 inline mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDate(logbook.date) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <svg
                                class="w-4 h-4 inline mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ logbook.duration }} jam
                        </span>
                        <span
                            :class="getStatusClass(logbook.status)"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        >
                            {{ getStatusText(logbook.status) }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <Link
                        :href="route('peserta.logbooks.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
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
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Kembali
                    </Link>
                    <Link
                        v-if="canEdit"
                        :href="route('peserta.logbooks.edit', logbook.id)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                        Edit
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Activities -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Aktivitas yang Dilakukan
                        </h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">
                                {{ logbook.activities }}
                            </p>
                        </div>
                    </div>

                    <!-- Learning Points -->
                    <div
                        v-if="logbook.learning_points"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Poin Pembelajaran
                        </h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">
                                {{ logbook.learning_points }}
                            </p>
                        </div>
                    </div>

                    <!-- Challenges -->
                    <div
                        v-if="logbook.challenges"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Tantangan yang Dihadapi
                        </h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">
                                {{ logbook.challenges }}
                            </p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div
                        v-if="
                            logbook.attachments &&
                            logbook.attachments.length > 0
                        "
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Lampiran File
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="(
                                    attachment, index
                                ) in logbook.attachments"
                                :key="index"
                                class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200"
                            >
                                <div class="flex-shrink-0">
                                    <svg
                                        class="w-8 h-8 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ attachment.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ formatFileSize(attachment.size) }}
                                    </p>
                                </div>
                                <a
                                    :href="`/storage/${attachment.path}`"
                                    target="_blank"
                                    class="ml-4 inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-100 transition-colors"
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
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Komentar ({{
                                    logbook.comments
                                        ? logbook.comments.length
                                        : 0
                                }})
                            </h3>
                        </div>

                        <!-- Comment List -->
                        <div
                            v-if="
                                logbook.comments && logbook.comments.length > 0
                            "
                            class="space-y-4 mb-6"
                        >
                            <div
                                v-for="comment in logbook.comments"
                                :key="comment.id"
                                class="border border-gray-200 rounded-lg p-4"
                            >
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-semibold text-blue-600"
                                        >
                                            {{
                                                comment.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="flex items-center space-x-2 mb-1"
                                        >
                                            <h4
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ comment.user.name }}
                                            </h4>
                                            <span
                                                v-if="comment.is_internal"
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"
                                            >
                                                Pembimbing
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{
                                                    formatDate(
                                                        comment.created_at
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-sm text-gray-700 whitespace-pre-line"
                                        >
                                            {{ comment.comment }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6 text-gray-500">
                            Belum ada komentar
                        </div>

                        <!-- Add Comment Form -->
                        <form @submit.prevent="addComment" class="mt-6">
                            <div class="border border-gray-300 rounded-lg">
                                <textarea
                                    v-model="commentForm.comment"
                                    rows="3"
                                    placeholder="Tambahkan komentar atau pertanyaan..."
                                    class="w-full px-4 py-3 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                ></textarea>
                            </div>
                            <div
                                v-if="commentForm.errors.comment"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ commentForm.errors.comment }}
                            </div>
                            <div class="flex justify-end mt-3">
                                <button
                                    type="submit"
                                    :disabled="
                                        commentForm.processing ||
                                        !commentForm.comment.trim()
                                    "
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                                >
                                    <svg
                                        v-if="commentForm.processing"
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Info -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Logbook
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Status</label
                                >
                                <span
                                    :class="getStatusClass(logbook.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium mt-1"
                                >
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Divisi</label
                                >
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ logbook.division?.name || "-" }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Tanggal Dibuat</label
                                >
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ formatDate(logbook.created_at) }}
                                </p>
                            </div>
                            <div
                                v-if="logbook.updated_at !== logbook.created_at"
                            >
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Terakhir Diubah</label
                                >
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ formatDate(logbook.updated_at) }}
                                </p>
                            </div>
                            <div v-if="logbook.reviewer">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Direview oleh</label
                                >
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ logbook.reviewer.name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Help -->
                    <div
                        class="bg-blue-50 rounded-xl border border-blue-200 p-6"
                    >
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">
                            Status Logbook
                        </h4>
                        <div class="space-y-2 text-sm text-blue-800">
                            <div class="flex items-center">
                                <div
                                    class="w-2 h-2 bg-gray-400 rounded-full mr-2"
                                ></div>
                                <span
                                    ><strong>Draft:</strong> Belum dikirim untuk
                                    review</span
                                >
                            </div>
                            <div class="flex items-center">
                                <div
                                    class="w-2 h-2 bg-yellow-400 rounded-full mr-2"
                                ></div>
                                <span
                                    ><strong>Menunggu Review:</strong> Sedang
                                    direview pembimbing</span
                                >
                            </div>
                            <div class="flex items-center">
                                <div
                                    class="w-2 h-2 bg-green-400 rounded-full mr-2"
                                ></div>
                                <span
                                    ><strong>Disetujui:</strong> Logbook telah
                                    disetujui</span
                                >
                            </div>
                            <div class="flex items-center">
                                <div
                                    class="w-2 h-2 bg-red-400 rounded-full mr-2"
                                ></div>
                                <span
                                    ><strong>Perlu Revisi:</strong> Perlu
                                    diperbaiki dan dikirim ulang</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const commentForm = useForm({
    comment: "",
});

const canEdit = computed(() => {
    return ["draft", "revision"].includes(props.logbook.status);
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
        return "-";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
        rejected: "Ditolak",
    };
    return texts[status] || "Unknown";
};

const addComment = () => {
    commentForm.post(
        route("peserta.logbooks.comments.store", props.logbook.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset();
            },
        }
    );
};
</script>
