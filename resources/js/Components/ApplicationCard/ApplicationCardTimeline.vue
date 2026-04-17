<template>
    <div class="px-6 py-5 bg-gray-50/50 border-t border-gray-100">
        <div class="flex items-center gap-2 mb-5">
            <div
                class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center"
            >
                <svg
                    class="w-3.5 h-3.5 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                </svg>
            </div>
            <span class="text-sm font-semibold text-gray-900"
                >Timeline Status</span
            >
        </div>

        <div class="relative bg-white rounded-xl border border-gray-100 p-5">
            <div class="absolute inset-0 overflow-hidden rounded-xl">
                <div
                    class="absolute top-1/2 left-0 right-0 h-px bg-gray-100"
                ></div>
                <div
                    class="absolute top-0 bottom-0 left-1/4 w-px bg-gray-50"
                ></div>
                <div
                    class="absolute top-0 bottom-0 left-1/2 w-px bg-gray-50"
                ></div>
                <div
                    class="absolute top-0 bottom-0 left-3/4 w-px bg-gray-50"
                ></div>
            </div>

            <div class="relative">
                <div
                    class="absolute top-4 left-[10%] right-[10%] h-0.5 bg-gray-200 rounded-full"
                ></div>
                <div
                    class="absolute top-4 left-[10%] h-0.5 rounded-full transition-all duration-500"
                    :class="getProgressBarColor(application.status)"
                    :style="{
                        width: getProgressWidthAdjusted(application.status),
                    }"
                ></div>

                <div class="relative flex justify-between">
                    <div class="flex flex-col items-center" style="width: 20%">
                        <div
                            class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center z-10 ring-4 ring-white"
                        >
                            <svg
                                class="w-4 h-4 text-white"
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
                        <span
                            class="mt-3 text-xs font-medium text-gray-900 text-center"
                            >Dikirim</span
                        >
                        <span
                            class="text-[10px] text-gray-500 text-center mt-0.5"
                            >{{ formatShortDate(application.created_at) }}</span
                        >
                    </div>

                    <div class="flex flex-col items-center" style="width: 20%">
                        <div
                            :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                isStepComplete(2) || isStepActive(2)
                                    ? 'bg-indigo-600'
                                    : 'bg-gray-100 border-2 border-gray-200',
                            ]"
                        >
                            <svg
                                v-if="isStepComplete(2) || isStepActive(2)"
                                class="w-4 h-4 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                v-else
                                class="w-2 h-2 bg-gray-300 rounded-full"
                            ></span>
                        </div>
                        <span
                            :class="[
                                'mt-3 text-xs font-medium text-center',
                                isStepComplete(2) || isStepActive(2)
                                    ? 'text-gray-900'
                                    : 'text-gray-400',
                            ]"
                            >Review</span
                        >
                        <span
                            v-if="isStepComplete(2) || isStepActive(2)"
                            class="text-[10px] text-gray-500 text-center mt-0.5"
                            >{{
                                formatShortDate(
                                    application.reviewed_at ||
                                        application.created_at,
                                )
                            }}</span
                        >
                        <span
                            v-else
                            class="text-[10px] text-gray-400 text-center mt-0.5"
                            >-</span
                        >
                    </div>

                    <div class="flex flex-col items-center" style="width: 20%">
                        <div
                            :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                isStepComplete(3) || isStepActive(3)
                                    ? 'bg-indigo-600'
                                    : 'bg-gray-100 border-2 border-gray-200',
                            ]"
                        >
                            <svg
                                v-if="isStepComplete(3) || isStepActive(3)"
                                class="w-4 h-4 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                v-else
                                class="w-2 h-2 bg-gray-300 rounded-full"
                            ></span>
                        </div>
                        <span
                            :class="[
                                'mt-3 text-xs font-medium text-center',
                                isStepComplete(3) || isStepActive(3)
                                    ? 'text-gray-900'
                                    : 'text-gray-400',
                            ]"
                            >Interview</span
                        >
                        <span
                            v-if="isStepComplete(3) || isStepActive(3)"
                            class="text-[10px] text-gray-500 text-center mt-0.5"
                        >
                            {{
                                formatShortDate(
                                    application.interview_date ||
                                        application.created_at,
                                )
                            }}
                            <span
                                v-if="application.interview_time"
                                class="block"
                            >
                                {{ application.interview_time }}
                            </span>
                        </span>
                        <span
                            v-else
                            class="text-[10px] text-gray-400 text-center mt-0.5"
                            >-</span
                        >
                    </div>

                    <div class="flex flex-col items-center" style="width: 20%">
                        <div
                            :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                isStepComplete(4) &&
                                isAccepted(application.status)
                                    ? 'bg-emerald-500'
                                    : isStepComplete(4) &&
                                        !isAccepted(application.status)
                                      ? 'bg-red-500'
                                      : 'bg-gray-100 border-2 border-gray-200',
                            ]"
                        >
                            <svg
                                v-if="
                                    isStepComplete(4) &&
                                    isAccepted(application.status)
                                "
                                class="w-4 h-4 text-white"
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
                                v-else-if="
                                    isStepComplete(4) &&
                                    !isAccepted(application.status)
                                "
                                class="w-4 h-4 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                v-else
                                class="w-2 h-2 bg-gray-300 rounded-full"
                            ></span>
                        </div>
                        <span
                            :class="[
                                'mt-3 text-xs font-medium text-center',
                                isStepComplete(4) &&
                                isAccepted(application.status)
                                    ? 'text-emerald-600'
                                    : isStepComplete(4) &&
                                        !isAccepted(application.status)
                                      ? 'text-red-600'
                                      : 'text-gray-400',
                            ]"
                        >
                            {{
                                isStepComplete(4)
                                    ? isAccepted(application.status)
                                        ? "Diterima"
                                        : "Ditolak"
                                    : "Keputusan"
                            }}
                        </span>
                        <span
                            v-if="isStepComplete(4)"
                            class="text-[10px] text-gray-500 text-center mt-0.5"
                            >{{ formatShortDate(application.updated_at) }}</span
                        >
                        <span
                            v-else
                            class="text-[10px] text-gray-400 text-center mt-0.5"
                            >-</span
                        >
                    </div>

                    <div
                        v-if="showLetterStep(application.status)"
                        class="flex flex-col items-center"
                        style="width: 20%"
                    >
                        <div
                            :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                application.status === 'letter_ready'
                                    ? 'bg-emerald-500'
                                    : 'bg-gray-100 border-2 border-gray-200',
                            ]"
                        >
                            <svg
                                v-if="application.status === 'letter_ready'"
                                class="w-4 h-4 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                v-else
                                class="w-2 h-2 bg-gray-300 rounded-full"
                            ></span>
                        </div>
                        <span
                            :class="[
                                'mt-3 text-xs font-medium text-center',
                                application.status === 'letter_ready'
                                    ? 'text-emerald-600'
                                    : 'text-gray-400',
                            ]"
                            >Surat</span
                        >
                        <span
                            v-if="application.status === 'letter_ready'"
                            class="text-[10px] text-gray-500 text-center mt-0.5"
                            >{{ formatShortDate(application.updated_at) }}</span
                        >
                        <span
                            v-else
                            class="text-[10px] text-gray-400 text-center mt-0.5"
                            >-</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    application: { type: Object, required: true },
    getProgressBarColor: { type: Function, required: true },
    getProgressWidthAdjusted: { type: Function, required: true },
    isStepComplete: { type: Function, required: true },
    isStepActive: { type: Function, required: true },
    isAccepted: { type: Function, required: true },
    showLetterStep: { type: Function, required: true },
    formatShortDate: { type: Function, required: true },
});
</script>
