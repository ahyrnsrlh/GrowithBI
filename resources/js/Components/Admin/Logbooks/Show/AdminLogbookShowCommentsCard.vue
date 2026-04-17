<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            Komentar & Feedback
        </h2>

        <div
            v-if="logbook.comments && logbook.comments.length > 0"
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
                        <span class="text-white text-sm font-medium">{{
                            comment.user.name.charAt(0).toUpperCase()
                        }}</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-sm font-medium text-gray-900">{{
                            comment.user.name
                        }}</span>
                        <span class="text-xs text-gray-500">{{
                            formatDate(comment.created_at)
                        }}</span>
                        <span
                            v-if="comment.type !== 'comment'"
                            :class="getCommentTypeClass(comment.type)"
                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                        >
                            {{ getCommentTypeText(comment.type) }}
                        </span>
                    </div>
                    <p class="text-gray-700 text-sm">{{ comment.comment }}</p>
                </div>
            </div>
        </div>

        <form @submit.prevent class="space-y-4">
            <div>
                <label
                    for="feedback"
                    class="block text-sm font-medium text-gray-700 mb-2"
                    >Feedback</label
                >
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
                    @click="$emit('submit-status', 'approved')"
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
                    @click="$emit('submit-status', 'revision')"
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
                    @click="$emit('submit-status', 'rejected')"
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
</template>

<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    reviewForm: {
        type: Object,
        required: true,
    },
    processing: {
        type: Boolean,
        default: false,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getCommentTypeClass: {
        type: Function,
        required: true,
    },
    getCommentTypeText: {
        type: Function,
        required: true,
    },
});

defineEmits(["submit-status"]);
</script>
