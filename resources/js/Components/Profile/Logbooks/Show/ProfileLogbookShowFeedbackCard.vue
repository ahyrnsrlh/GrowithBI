<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div
        v-if="
            logbook.mentor_feedback &&
            logbook.status !== 'draft' &&
            logbook.status !== 'submitted'
        "
        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden"
    >
        <div class="p-6 sm:p-8">
            <div class="flex items-start space-x-4">
                <div
                    class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0"
                >
                    <svg
                        class="w-5 h-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Feedback dari Pembimbing
                    </h3>
                    <p
                        v-if="logbook.reviewed_at"
                        class="text-xs text-gray-500 mb-4"
                    >
                        {{ formatDate(logbook.reviewed_at) }}
                    </p>
                    <div class="bg-white rounded-lg border border-blue-100 p-4">
                        <p
                            class="text-gray-700 leading-relaxed whitespace-pre-line"
                        >
                            {{ logbook.mentor_feedback }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
