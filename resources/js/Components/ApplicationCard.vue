<template>
    <div class="group relative">
        <!-- Main Card -->
        <div
            class="bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 hover:border-gray-200"
        >
            <!-- Header Section -->
            <div class="relative p-6 pb-4">
                <!-- Decorative gradient accent -->
                <div
                    :class="[
                        'absolute top-0 left-0 right-0 h-1',
                        getAccentGradient(application.status),
                    ]"
                ></div>

                <!-- Top row: Division name + Status badge -->
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-xl font-bold text-gray-900 tracking-tight truncate"
                        >
                            {{ application.division?.name || "Divisi" }}
                        </h3>
                        <p
                            class="mt-1 text-sm text-gray-500 line-clamp-2 leading-relaxed"
                        >
                            {{
                                application.division?.description ||
                                "Deskripsi tidak tersedia"
                            }}
                        </p>
                    </div>

                    <!-- Modern Status Badge -->
                    <div class="flex-shrink-0">
                        <span
                            :class="[
                                'inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full transition-all',
                                getStatusBadgeClass(application.status),
                            ]"
                        >
                            <span
                                :class="[
                                    'w-1.5 h-1.5 rounded-full',
                                    getStatusDotClass(application.status),
                                ]"
                            ></span>
                            {{ getStatusText(application.status) }}
                        </span>
                    </div>
                </div>

                <!-- Meta info row -->
                <div
                    class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500"
                >
                    <div class="flex items-center gap-2">
                        <svg
                            class="w-4 h-4 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <span>{{ formatDate(application.created_at) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg
                            class="w-4 h-4 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                            />
                        </svg>
                        <span class="font-mono"
                            >#{{
                                String(application.id).padStart(4, "0")
                            }}</span
                        >
                    </div>
                </div>
            </div>

            <!-- Timeline Section - Horizontal Design with Grid -->
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

                <!-- Timeline Container with Grid Background -->
                <div
                    class="relative bg-white rounded-xl border border-gray-100 p-5"
                >
                    <!-- Horizontal Grid Lines -->
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

                    <!-- Horizontal Timeline -->
                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div
                            class="absolute top-4 left-[10%] right-[10%] h-0.5 bg-gray-200 rounded-full"
                        ></div>
                        <!-- Progress Bar Active -->
                        <div
                            class="absolute top-4 left-[10%] h-0.5 rounded-full transition-all duration-500"
                            :class="getProgressBarColor(application.status)"
                            :style="{
                                width: getProgressWidthAdjusted(
                                    application.status
                                ),
                            }"
                        ></div>

                        <!-- Steps Container -->
                        <div class="relative flex justify-between">
                            <!-- Step 1: Dikirim -->
                            <div
                                class="flex flex-col items-center"
                                style="width: 20%"
                            >
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
                                    >{{
                                        formatShortDate(application.created_at)
                                    }}</span
                                >
                            </div>

                            <!-- Step 2: Review -->
                            <div
                                class="flex flex-col items-center"
                                style="width: 20%"
                            >
                                <div
                                    :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                        isStepComplete(2) || isStepActive(2)
                                            ? 'bg-indigo-600'
                                            : 'bg-gray-100 border-2 border-gray-200',
                                    ]"
                                >
                                    <svg
                                        v-if="
                                            isStepComplete(2) || isStepActive(2)
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
                                                application.created_at
                                        )
                                    }}</span
                                >
                                <span
                                    v-else
                                    class="text-[10px] text-gray-400 text-center mt-0.5"
                                    >-</span
                                >
                            </div>

                            <!-- Step 3: Interview -->
                            <div
                                class="flex flex-col items-center"
                                style="width: 20%"
                            >
                                <div
                                    :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center z-10 transition-all ring-4 ring-white',
                                        isStepComplete(3) || isStepActive(3)
                                            ? 'bg-indigo-600'
                                            : 'bg-gray-100 border-2 border-gray-200',
                                    ]"
                                >
                                    <svg
                                        v-if="
                                            isStepComplete(3) || isStepActive(3)
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
                                    {{ formatShortDate(application.interview_date || application.created_at) }}
                                    <span v-if="application.interview_time" class="block">
                                        {{ application.interview_time }}
                                    </span>
                                </span>
                                <span
                                    v-else
                                    class="text-[10px] text-gray-400 text-center mt-0.5"
                                    >-</span
                                >
                            </div>

                            <!-- Step 4: Keputusan -->
                            <div
                                class="flex flex-col items-center"
                                style="width: 20%"
                            >
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
                                    >{{
                                        formatShortDate(application.updated_at)
                                    }}</span
                                >
                                <span
                                    v-else
                                    class="text-[10px] text-gray-400 text-center mt-0.5"
                                    >-</span
                                >
                            </div>

                            <!-- Step 5: Surat (conditional) -->
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
                                        v-if="
                                            application.status ===
                                            'letter_ready'
                                        "
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
                                    >{{
                                        formatShortDate(application.updated_at)
                                    }}</span
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

            <!-- Action Buttons -->
            <div
                class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3"
            >
                <button
                    @click="viewDetails"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200"
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
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>
                    Lihat Detail
                </button>

                <button
                    v-if="
                        ['accepted', 'letter_ready', 'diterima'].includes(
                            application.status
                        )
                    "
                    @click="downloadOffer"
                    :disabled="downloading"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 transition-all duration-200 disabled:opacity-50"
                >
                    <svg
                        v-if="!downloading"
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-4 h-4 animate-spin"
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
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        ></path>
                    </svg>
                    {{ downloading ? "Mengunduh..." : "Unduh Surat" }}
                </button>
            </div>
        </div>

        <!-- Detail Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 overflow-y-auto"
                >
                    <div
                        class="flex min-h-full items-center justify-center p-4"
                    >
                        <!-- Backdrop -->
                        <div
                            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
                            @click="closeModal"
                        ></div>

                        <!-- Modal Content -->
                        <Transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="showModal"
                                class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden"
                            >
                                <!-- Modal Header -->
                                <div
                                    class="relative px-6 pt-6 pb-4 border-b border-gray-100"
                                >
                                    <div
                                        :class="[
                                            'absolute top-0 left-0 right-0 h-1',
                                            getAccentGradient(
                                                application.status
                                            ),
                                        ]"
                                    ></div>
                                    <div
                                        class="flex items-start justify-between"
                                    >
                                        <div>
                                            <h3
                                                class="text-xl font-bold text-gray-900"
                                            >
                                                Detail Lamaran
                                            </h3>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                #{{
                                                    String(
                                                        application.id
                                                    ).padStart(4, "0")
                                                }}
                                            </p>
                                        </div>
                                        <button
                                            @click="closeModal"
                                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        >
                                            <svg
                                                class="w-5 h-5"
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
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal Body -->
                                <div
                                    class="px-6 py-5 space-y-5 max-h-[60vh] overflow-y-auto"
                                >
                                    <!-- Division Info -->
                                    <div>
                                        <label
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                            >Divisi</label
                                        >
                                        <p
                                            class="mt-1 text-lg font-semibold text-gray-900"
                                        >
                                            {{ application.division?.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{
                                                application.division
                                                    ?.description
                                            }}
                                        </p>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                            >Status</label
                                        >
                                        <div class="mt-2">
                                            <span
                                                :class="[
                                                    'inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-full',
                                                    getStatusBadgeClass(
                                                        application.status
                                                    ),
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        'w-2 h-2 rounded-full',
                                                        getStatusDotClass(
                                                            application.status
                                                        ),
                                                    ]"
                                                ></span>
                                                {{
                                                    getStatusText(
                                                        application.status
                                                    )
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                                >Tanggal Lamar</label
                                            >
                                            <p
                                                class="mt-1 text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    formatDate(
                                                        application.created_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div v-if="application.reviewed_at">
                                            <label
                                                class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                                >Tanggal Review</label
                                            >
                                            <p
                                                class="mt-1 text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    formatDate(
                                                        application.reviewed_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Interview Info -->
                                    <div
                                        v-if="application.interview_date"
                                        class="bg-purple-50 rounded-xl p-4 border border-purple-100"
                                    >
                                        <div class="flex items-center gap-2 mb-3">
                                            <svg
                                                class="w-5 h-5 text-purple-600"
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
                                            <label
                                                class="text-xs font-semibold text-purple-600 uppercase tracking-wider"
                                                >Jadwal Wawancara</label
                                            >
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <!-- Tanggal -->
                                            <div class="flex items-start gap-2">
                                                <svg
                                                    class="w-4 h-4 text-purple-500 mt-0.5"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-purple-600 font-medium">Tanggal</p>
                                                    <p class="text-sm font-semibold text-purple-900">
                                                        {{ formatDate(application.interview_date) }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Waktu -->
                                            <div
                                                v-if="application.interview_time"
                                                class="flex items-start gap-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-purple-500 mt-0.5"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-purple-600 font-medium">Jam</p>
                                                    <p class="text-sm font-semibold text-purple-900">
                                                        {{ application.interview_time }} WIB
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Lokasi -->
                                            <div
                                                v-if="application.interview_location"
                                                class="flex items-start gap-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-purple-500 mt-0.5"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-xs text-purple-600 font-medium">Lokasi</p>
                                                    <p class="text-sm font-semibold text-purple-900">
                                                        {{ application.interview_location }}
                                                    </p>
                                                    <p
                                                        v-if="application.interview_location_detail"
                                                        class="text-xs text-purple-700 mt-1 leading-relaxed"
                                                    >
                                                        {{ application.interview_location_detail }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Motivation -->
                                    <div v-if="application.motivation">
                                        <label
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                            >Motivasi</label
                                        >
                                        <p
                                            class="mt-2 text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-xl p-4"
                                        >
                                            {{ application.motivation }}
                                        </p>
                                    </div>

                                    <!-- Notes/Feedback -->
                                    <div
                                        v-if="
                                            application.notes ||
                                            application.feedback ||
                                            application.admin_notes
                                        "
                                        class="bg-blue-50 rounded-xl p-4"
                                    >
                                        <label
                                            class="text-xs font-semibold text-blue-600 uppercase tracking-wider"
                                            >Catatan Admin</label
                                        >
                                        <p class="mt-2 text-sm text-blue-800">
                                            {{
                                                application.admin_notes ||
                                                application.notes ||
                                                application.feedback
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div
                                    class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3"
                                >
                                    <button
                                        @click="closeModal"
                                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                                    >
                                        Tutup
                                    </button>
                                    <button
                                        v-if="
                                            [
                                                'accepted',
                                                'letter_ready',
                                                'diterima',
                                            ].includes(application.status)
                                        "
                                        @click="downloadOffer"
                                        :disabled="downloading"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all disabled:opacity-50"
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
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            />
                                        </svg>
                                        Unduh Surat
                                    </button>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    application: Object,
});

const emit = defineEmits(["status-updated"]);

const showModal = ref(false);
const downloading = ref(false);

// Echo channel for real-time status updates
let echoChannel = null;

onMounted(() => {
    if (window.Echo && props.application.user_id) {
        echoChannel = window.Echo.private(
            `App.Models.User.${props.application.user_id}`
        ).notification((notification) => {
            if (notification.application_id === props.application.id) {
                router.reload({ only: ["applications"] });
                emit("status-updated", {
                    applicationId: props.application.id,
                    newStatus: notification.status,
                });
            }
        });
    }
});

onUnmounted(() => {
    if (echoChannel && window.Echo) {
        window.Echo.leave(`App.Models.User.${props.application.user_id}`);
    }
});

// Helper functions
const acceptedStatuses = ["accepted", "letter_ready", "diterima"];

const isAccepted = (status) => acceptedStatuses.includes(status);

const getStepNumber = (status) => {
    const steps = {
        menunggu: 1,
        in_review: 2,
        dalam_review: 2,
        interview_scheduled: 3,
        wawancara: 3,
        accepted: 4,
        rejected: 4,
        diterima: 4,
        ditolak: 4,
        letter_ready: 5,
    };
    return steps[status] || 1;
};

const isStepComplete = (step) => {
    return getStepNumber(props.application.status) > step;
};

const isStepActive = (step) => {
    return getStepNumber(props.application.status) === step;
};

const showLetterStep = (status) => {
    return ["accepted", "letter_ready", "diterima"].includes(status);
};

const getProgressWidth = (status) => {
    const step = getStepNumber(status);
    const showLetter = showLetterStep(status);
    const totalSteps = showLetter ? 5 : 4;
    const percentage = ((step - 1) / (totalSteps - 1)) * 100;
    return `${percentage}%`;
};

// Adjusted progress width for timeline with padding
const getProgressWidthAdjusted = (status) => {
    const step = getStepNumber(status);
    const showLetter = showLetterStep(status);
    const totalSteps = showLetter ? 5 : 4;
    // Calculate based on 80% track width (10% padding on each side)
    const percentage = ((step - 1) / (totalSteps - 1)) * 80;
    return `${percentage}%`;
};

const getProgressBarColor = (status) => {
    if (status === "rejected" || status === "ditolak") {
        return "bg-red-500";
    }
    if (status === "letter_ready") {
        return "bg-emerald-500";
    }
    if (isAccepted(status)) {
        return "bg-emerald-500";
    }
    return "bg-indigo-600";
};

const getProgressColor = (status) => {
    if (status === "rejected" || status === "ditolak") {
        return "bg-red-500";
    }
    if (status === "letter_ready") {
        return "bg-gradient-to-r from-blue-500 via-green-500 to-emerald-500";
    }
    return "bg-gradient-to-r from-blue-500 to-blue-400";
};

const getAccentGradient = (status) => {
    const gradients = {
        menunggu: "bg-gradient-to-r from-amber-400 to-yellow-400",
        in_review: "bg-gradient-to-r from-blue-400 to-indigo-400",
        interview_scheduled: "bg-gradient-to-r from-purple-400 to-violet-400",
        accepted: "bg-gradient-to-r from-green-400 to-emerald-400",
        rejected: "bg-gradient-to-r from-red-400 to-rose-400",
        letter_ready: "bg-gradient-to-r from-emerald-400 to-teal-400",
        diterima: "bg-gradient-to-r from-green-400 to-emerald-400",
        ditolak: "bg-gradient-to-r from-red-400 to-rose-400",
    };
    return gradients[status] || "bg-gradient-to-r from-gray-400 to-gray-300";
};

const getStatusBadgeClass = (status) => {
    const classes = {
        menunggu: "bg-amber-50 text-amber-700 ring-1 ring-amber-600/20",
        in_review: "bg-blue-50 text-blue-700 ring-1 ring-blue-600/20",
        interview_scheduled:
            "bg-purple-50 text-purple-700 ring-1 ring-purple-600/20",
        accepted: "bg-green-50 text-green-700 ring-1 ring-green-600/20",
        rejected: "bg-red-50 text-red-700 ring-1 ring-red-600/20",
        letter_ready:
            "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20",
        diterima: "bg-green-50 text-green-700 ring-1 ring-green-600/20",
        ditolak: "bg-red-50 text-red-700 ring-1 ring-red-600/20",
    };
    return (
        classes[status] || "bg-gray-50 text-gray-700 ring-1 ring-gray-600/20"
    );
};

const getStatusDotClass = (status) => {
    const classes = {
        menunggu: "bg-amber-500",
        in_review: "bg-blue-500",
        interview_scheduled: "bg-purple-500",
        accepted: "bg-green-500",
        rejected: "bg-red-500",
        letter_ready: "bg-emerald-500 animate-pulse",
        diterima: "bg-green-500",
        ditolak: "bg-red-500",
    };
    return classes[status] || "bg-gray-500";
};

const getStatusText = (status) => {
    const texts = {
        menunggu: "Menunggu Review",
        in_review: "Sedang Direview",
        interview_scheduled: "Wawancara",
        accepted: "Diterima",
        rejected: "Ditolak",
        letter_ready: "Surat Siap",
        expired: "Kedaluwarsa",
        diterima: "Diterima",
        ditolak: "Ditolak",
    };
    return texts[status] || status;
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatShortDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
    });
};

const viewDetails = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const downloadOffer = async () => {
    downloading.value = true;
    try {
        const checkResponse = await fetch(
            route("acceptance-letter.check", props.application.id)
        );
        const checkResult = await checkResponse.json();

        if (!checkResult.success || !checkResult.has_letter) {
            alert("Surat penerimaan belum tersedia. Hubungi pembimbing Anda.");
            return;
        }

        window.open(
            route("acceptance-letter.download", props.application.id),
            "_blank"
        );
    } catch (error) {
        console.error("Error downloading acceptance letter:", error);
        alert("Terjadi kesalahan saat mengunduh surat penerimaan.");
    } finally {
        downloading.value = false;
    }
};
</script>
