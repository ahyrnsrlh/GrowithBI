<template>
    <div class="space-y-6">
        <div
            class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 overflow-hidden"
        >
            <div
                class="bg-gradient-to-r from-blue-50 to-blue-100/50 px-6 py-4 border-b border-blue-200"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm"
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Aktivitas yang Dilakukan
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ logbook.activities }}
                </p>
            </div>
        </div>

        <div
            v-if="logbook.learning_points"
            class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 overflow-hidden"
        >
            <div
                class="bg-gradient-to-r from-emerald-50 to-emerald-100/50 px-6 py-4 border-b border-emerald-200"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-sm"
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
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Poin Pembelajaran
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ logbook.learning_points }}
                </p>
            </div>
        </div>

        <div
            v-if="logbook.challenges"
            class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 overflow-hidden"
        >
            <div
                class="bg-gradient-to-r from-amber-50 to-amber-100/50 px-6 py-4 border-b border-amber-200"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-amber-600 rounded-xl flex items-center justify-center shadow-sm"
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
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Tantangan yang Dihadapi
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ logbook.challenges }}
                </p>
            </div>
        </div>

        <div
            v-if="logbook.attachments && logbook.attachments.length > 0"
            class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 overflow-hidden"
        >
            <div
                class="bg-gradient-to-r from-purple-50 to-purple-100/50 px-6 py-4 border-b border-purple-200"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center shadow-sm"
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
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Lampiran File
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="(attachment, index) in logbook.attachments"
                        :key="index"
                        class="group flex items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl border border-gray-200 hover:border-blue-300 transition-all"
                    >
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <h4
                                class="text-sm font-semibold text-gray-900 truncate"
                            >
                                {{ attachment.name }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formatFileSize(attachment.size) }}
                            </p>
                        </div>
                        <a
                            :href="`/storage/${attachment.path}`"
                            target="_blank"
                            class="ml-3 inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm"
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
        </div>

        <div
            v-if="
                logbook.mentor_feedback &&
                logbook.status !== 'draft' &&
                logbook.status !== 'submitted'
            "
            class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl border-l-4 border-blue-600 shadow-lg p-6"
        >
            <div class="flex items-start space-x-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg"
                >
                    <svg
                        class="w-7 h-7 text-white"
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
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-900">
                            Feedback dari Pembimbing
                        </h3>
                        <span
                            :class="getStatusBadgeClass(logbook.status)"
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                        >
                            {{ getStatusText(logbook.status) }}
                        </span>
                    </div>
                    <div
                        class="flex items-center space-x-2 text-sm text-blue-700 mb-4"
                    >
                        <svg
                            class="w-4 h-4"
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
                        <span v-if="logbook.reviewer" class="font-semibold">
                            {{ logbook.reviewer.name }}
                        </span>
                        <span v-if="logbook.reviewed_at" class="text-gray-600">
                            • {{ formatDate(logbook.reviewed_at) }}
                        </span>
                    </div>
                    <div
                        class="bg-white rounded-xl border border-blue-200 shadow-sm p-5"
                    >
                        <p
                            class="text-gray-800 whitespace-pre-line leading-relaxed"
                        >
                            {{ logbook.mentor_feedback }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    logbook: { type: Object, required: true },
    formatDate: { type: Function, required: true },
    formatFileSize: { type: Function, required: true },
    getStatusBadgeClass: { type: Function, required: true },
    getStatusText: { type: Function, required: true },
});
</script>
