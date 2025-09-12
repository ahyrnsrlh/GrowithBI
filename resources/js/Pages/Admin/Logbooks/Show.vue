<template>
    <AdminLayout
        title="Review Logbook"
        subtitle="Detail dan review laporan harian peserta"
    >
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8"
            >
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <Link
                            :href="route('admin.logbooks.index')"
                            class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
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
                            Kembali ke Daftar
                        </Link>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ logbook.title }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{ logbook.user.name }} - {{ formatDate(logbook.date) }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span
                        :class="getStatusClass(logbook.status)"
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                    >
                        {{ getStatusText(logbook.status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Logbook Details -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"
                    >
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            Detail Logbook
                        </h2>

                        <div class="space-y-6">
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Aktivitas yang Dilakukan
                                </h3>
                                <p class="text-gray-900 leading-relaxed">
                                    {{ logbook.activities }}
                                </p>
                            </div>

                            <div v-if="logbook.learning_outcome">
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Hasil Pembelajaran
                                </h3>
                                <p class="text-gray-900 leading-relaxed">
                                    {{ logbook.learning_outcome }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h3
                                        class="text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Durasi
                                    </h3>
                                    <p class="text-gray-900">
                                        {{ logbook.duration }} jam
                                    </p>
                                </div>
                                <div v-if="logbook.user.division">
                                    <h3
                                        class="text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Divisi
                                    </h3>
                                    <p class="text-gray-900">
                                        {{ logbook.user.division.name }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="
                                    logbook.attachments &&
                                    logbook.attachments.length > 0
                                "
                            >
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Lampiran
                                </h3>
                                <div class="space-y-2">
                                    <div
                                        v-for="attachment in logbook.attachments"
                                        :key="attachment.name"
                                        class="flex items-center p-3 bg-gray-50 rounded-lg"
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
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                            />
                                        </svg>
                                        <span class="text-gray-900">{{
                                            attachment.name
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            Komentar & Feedback
                        </h2>

                        <!-- Existing Comments -->
                        <div
                            v-if="
                                logbook.comments && logbook.comments.length > 0
                            "
                            class="space-y-4 mb-6"
                        >
                            <div
                                v-for="comment in logbook.comments"
                                :key="comment.id"
                                class="flex space-x-3 p-4 bg-gray-50 rounded-lg"
                            >
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-white text-sm font-medium"
                                        >
                                            {{
                                                comment.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-1"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-900"
                                            >{{ comment.user.name }}</span
                                        >
                                        <span class="text-xs text-gray-500">{{
                                            formatDate(comment.created_at)
                                        }}</span>
                                        <span
                                            v-if="comment.type !== 'comment'"
                                            :class="
                                                getCommentTypeClass(
                                                    comment.type
                                                )
                                            "
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                        >
                                            {{
                                                getCommentTypeText(comment.type)
                                            }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 text-sm">
                                        {{ comment.comment }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Add Comment Form -->
                        <form @submit.prevent="submitReview" class="space-y-4">
                            <div>
                                <label
                                    for="feedback"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Feedback
                                </label>
                                <textarea
                                    id="feedback"
                                    v-model="reviewForm.feedback"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Berikan feedback untuk logbook ini..."
                                    required
                                ></textarea>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <button
                                    type="button"
                                    @click="
                                        reviewForm.status = 'approved';
                                        submitReview();
                                    "
                                    :disabled="processing"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 transition-all"
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
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    Setujui
                                </button>

                                <button
                                    type="button"
                                    @click="
                                        reviewForm.status = 'revision';
                                        submitReview();
                                    "
                                    :disabled="processing"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 disabled:opacity-50 transition-all"
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
                                    Minta Revisi
                                </button>

                                <button
                                    type="button"
                                    @click="
                                        reviewForm.status = 'rejected';
                                        submitReview();
                                    "
                                    :disabled="processing"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 disabled:opacity-50 transition-all"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Participant Info -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Informasi Peserta
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-white text-lg font-medium"
                                    >
                                        {{
                                            logbook.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </span>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ logbook.user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ logbook.user.email }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="logbook.user.division"
                                class="pt-3 border-t border-gray-200"
                            >
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Divisi:</span>
                                    {{ logbook.user.division.name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Logbook Timeline -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Timeline
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-2 h-2 bg-blue-500 rounded-full mt-2"
                                ></div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Dibuat
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ formatDateTime(logbook.created_at) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="logbook.updated_at !== logbook.created_at"
                                class="flex items-start space-x-3"
                            >
                                <div
                                    class="w-2 h-2 bg-yellow-500 rounded-full mt-2"
                                ></div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Diperbarui
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ formatDateTime(logbook.updated_at) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="logbook.approved_at"
                                class="flex items-start space-x-3"
                            >
                                <div
                                    class="w-2 h-2 bg-green-500 rounded-full mt-2"
                                ></div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Disetujui
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{
                                            formatDateTime(logbook.approved_at)
                                        }}
                                    </p>
                                    <p
                                        v-if="logbook.approver"
                                        class="text-xs text-gray-400"
                                    >
                                        oleh {{ logbook.approver.name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    logbook: Object,
});

const processing = ref(false);
const reviewForm = reactive({
    status: "",
    feedback: "",
});

const submitReview = () => {
    console.log(
        "Submit review called:",
        reviewForm.status,
        reviewForm.feedback
    );

    if (!reviewForm.status || !reviewForm.feedback.trim()) {
        alert("Silakan pilih status dan berikan feedback");
        return;
    }

    processing.value = true;

    useForm({
        status: reviewForm.status,
        feedback: reviewForm.feedback,
    }).put(route("admin.logbooks.update-status", props.logbook.id), {
        onSuccess: (page) => {
            console.log("Success response:", page);
            reviewForm.feedback = "";
            reviewForm.status = "";

            // Show success message
            alert("Status logbook berhasil diperbarui!");
        },
        onError: (errors) => {
            console.error("Validation errors:", errors);

            let errorMessage = "Terjadi kesalahan: ";
            if (errors.error) {
                errorMessage += errors.error;
            } else if (errors.status) {
                errorMessage += errors.status[0];
            } else if (errors.feedback) {
                errorMessage += errors.feedback[0];
            } else {
                errorMessage += Object.values(errors).join(", ");
            }

            alert(errorMessage);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatDateTime = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case "draft":
            return "bg-gray-100 text-gray-800";
        case "submitted":
            return "bg-blue-100 text-blue-800";
        case "approved":
            return "bg-green-100 text-green-800";
        case "rejected":
            return "bg-red-100 text-red-800";
        case "revision":
            return "bg-yellow-100 text-yellow-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getStatusText = (status) => {
    switch (status) {
        case "draft":
            return "Draft";
        case "submitted":
            return "Diajukan";
        case "approved":
            return "Disetujui";
        case "rejected":
            return "Ditolak";
        case "revision":
            return "Perlu Revisi";
        default:
            return "Tidak Diketahui";
    }
};

const getCommentTypeClass = (type) => {
    switch (type) {
        case "feedback":
            return "bg-blue-100 text-blue-800";
        case "revision_request":
            return "bg-yellow-100 text-yellow-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getCommentTypeText = (type) => {
    switch (type) {
        case "feedback":
            return "Feedback";
        case "revision_request":
            return "Revisi";
        default:
            return "Komentar";
    }
};
</script>
