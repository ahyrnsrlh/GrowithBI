<template>
    <div
        v-if="logbookStatus === 'revision' && lastRevisionComment"
        class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6"
    >
        <div class="flex">
            <svg
                class="w-5 h-5 text-yellow-600 mt-0.5 mr-3"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                />
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                    Feedback Revisi dari Pembimbing
                </h3>
                <p class="text-yellow-700 whitespace-pre-wrap">
                    {{ lastRevisionComment.comment }}
                </p>
                <p class="text-sm text-yellow-600 mt-2">
                    <strong>{{ lastRevisionComment.user.name }}</strong>
                    - {{ formatDate(lastRevisionComment.created_at) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    logbookStatus: {
        type: String,
        required: true,
    },
    lastRevisionComment: {
        type: Object,
        default: null,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>
