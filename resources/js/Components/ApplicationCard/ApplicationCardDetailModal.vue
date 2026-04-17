<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div
                        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
                        @click="$emit('close')"
                    ></div>

                    <Transition
                        enter-active-class="transition duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-200 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="show"
                            class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden"
                        >
                            <div
                                class="relative px-6 pt-6 pb-4 border-b border-gray-100"
                            >
                                <div
                                    :class="[
                                        'absolute top-0 left-0 right-0 h-1',
                                        getAccentGradient(application.status),
                                    ]"
                                ></div>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                        >
                                            Detail Lamaran
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            #{{
                                                String(application.id).padStart(
                                                    4,
                                                    "0",
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <button
                                        @click="$emit('close')"
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

                            <div
                                class="px-6 py-5 space-y-5 max-h-[60vh] overflow-y-auto"
                            >
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
                                        {{ application.division?.description }}
                                    </p>
                                </div>

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
                                                    application.status,
                                                ),
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-2 h-2 rounded-full',
                                                    getStatusDotClass(
                                                        application.status,
                                                    ),
                                                ]"
                                            ></span>
                                            {{
                                                getStatusText(
                                                    application.status,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

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
                                                    application.created_at,
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
                                                    application.reviewed_at,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

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
                                                <p
                                                    class="text-xs text-purple-600 font-medium"
                                                >
                                                    Tanggal
                                                </p>
                                                <p
                                                    class="text-sm font-semibold text-purple-900"
                                                >
                                                    {{
                                                        formatDate(
                                                            application.interview_date,
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>

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
                                                <p
                                                    class="text-xs text-purple-600 font-medium"
                                                >
                                                    Jam
                                                </p>
                                                <p
                                                    class="text-sm font-semibold text-purple-900"
                                                >
                                                    {{
                                                        application.interview_time
                                                    }}
                                                    WIB
                                                </p>
                                            </div>
                                        </div>

                                        <div
                                            v-if="
                                                application.interview_location
                                            "
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
                                                <p
                                                    class="text-xs text-purple-600 font-medium"
                                                >
                                                    Lokasi
                                                </p>
                                                <p
                                                    class="text-sm font-semibold text-purple-900"
                                                >
                                                    {{
                                                        application.interview_location
                                                    }}
                                                </p>
                                                <p
                                                    v-if="
                                                        application.interview_location_detail
                                                    "
                                                    class="text-xs text-purple-700 mt-1 leading-relaxed"
                                                >
                                                    {{
                                                        application.interview_location_detail
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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

                            <div
                                class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3"
                            >
                                <button
                                    @click="$emit('close')"
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
                                    @click="$emit('download-offer')"
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
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    application: { type: Object, required: true },
    downloading: { type: Boolean, default: false },
    getAccentGradient: { type: Function, required: true },
    getStatusBadgeClass: { type: Function, required: true },
    getStatusDotClass: { type: Function, required: true },
    getStatusText: { type: Function, required: true },
    formatDate: { type: Function, required: true },
});

defineEmits(["close", "download-offer"]);
</script>
