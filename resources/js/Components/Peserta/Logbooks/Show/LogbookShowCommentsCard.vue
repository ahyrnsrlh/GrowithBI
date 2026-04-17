<template>
    <div
        class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden"
    >
        <div
            class="bg-gradient-to-r from-gray-50 to-gray-100/50 px-6 py-4 border-b border-gray-200"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center shadow-sm"
                    >
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Komentar & Diskusi
                    </h3>
                </div>
                <span
                    class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full"
                >
                    {{ allComments.length }} Komentar
                </span>
            </div>
        </div>

        <div class="p-6">
            <div v-if="allComments.length > 0" class="space-y-4 mb-6">
                <div
                    v-for="comment in allComments"
                    :key="comment.id"
                    :class="[
                        'border rounded-xl p-5 transition-all hover:shadow-md',
                        comment.is_mentor_feedback
                            ? 'border-blue-300 bg-gradient-to-r from-blue-50 to-indigo-50 shadow-sm'
                            : 'border-gray-200 bg-white hover:border-gray-300',
                    ]"
                >
                    <div class="flex items-start space-x-3">
                        <div
                            :class="[
                                'w-10 h-10 rounded-xl flex items-center justify-center shadow-sm',
                                comment.is_mentor_feedback
                                    ? 'bg-gradient-to-br from-blue-500 to-blue-700'
                                    : 'bg-gradient-to-br from-gray-200 to-gray-300',
                            ]"
                        >
                            <span
                                :class="[
                                    'text-sm font-bold',
                                    comment.is_mentor_feedback
                                        ? 'text-white'
                                        : 'text-gray-700',
                                ]"
                            >
                                {{ comment.user.name.charAt(0).toUpperCase() }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="text-sm font-semibold text-gray-900">
                                    {{ comment.user.name }}
                                </h4>
                                <span
                                    v-if="comment.is_mentor_feedback"
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-blue-600 text-white shadow-sm"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    Feedback Resmi
                                </span>
                                <span
                                    v-else-if="
                                        comment.user.role === 'mentor' ||
                                        comment.user.role === 'admin'
                                    "
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-100 text-blue-700"
                                >
                                    Pembimbing
                                </span>
                                <span class="text-xs text-gray-500">
                                    • {{ formatDateShort(comment.created_at) }}
                                </span>
                            </div>
                            <p
                                class="text-sm text-gray-700 whitespace-pre-line leading-relaxed"
                            >
                                {{ comment.comment }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-else
                class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300"
            >
                <svg
                    class="w-16 h-16 mx-auto text-gray-400 mb-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                </svg>
                <p class="text-gray-500 font-medium">
                    Belum ada komentar atau feedback
                </p>
                <p class="text-gray-400 text-sm mt-1">
                    Jadilah yang pertama memberikan komentar
                </p>
            </div>

            <form @submit.prevent="addComment" class="mt-6">
                <div
                    class="border-2 border-gray-300 focus-within:border-blue-500 rounded-xl transition-all overflow-hidden"
                >
                    <textarea
                        v-model="commentForm.comment"
                        rows="4"
                        placeholder="Tulis komentar atau pertanyaan Anda di sini..."
                        class="w-full px-4 py-3 border-0 focus:ring-0 resize-none text-sm"
                    ></textarea>
                </div>
                <div
                    v-if="commentForm.errors.comment"
                    class="mt-2 text-sm text-red-600 font-medium"
                >
                    {{ commentForm.errors.comment }}
                </div>
                <div class="flex justify-end mt-4">
                    <button
                        type="submit"
                        :disabled="
                            commentForm.processing ||
                            !commentForm.comment.trim()
                        "
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg hover:shadow-xl transition-all"
                    >
                        <svg
                            v-if="commentForm.processing"
                            class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
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
                        <svg
                            v-else
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                            />
                        </svg>
                        Kirim Komentar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
defineProps({
    allComments: { type: Array, default: () => [] },
    commentForm: { type: Object, required: true },
    formatDateShort: { type: Function, required: true },
    addComment: { type: Function, required: true },
});
</script>
