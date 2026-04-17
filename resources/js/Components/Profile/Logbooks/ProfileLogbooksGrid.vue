<template>
    <div v-if="filteredCount > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div
                v-for="logbook in displayedLogbooks"
                :key="logbook.id"
                class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden"
            >
                <div
                    class="px-5 py-4 bg-gradient-to-r from-[#F6F8FA] to-white border-b border-gray-100"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600 mb-1"
                            >
                                <svg
                                    class="w-4 h-4 flex-shrink-0"
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
                                <span class="font-medium truncate">{{
                                    formatDateShort(logbook.date)
                                }}</span>
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600"
                            >
                                <svg
                                    class="w-4 h-4 flex-shrink-0"
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
                                <span class="font-semibold"
                                    >{{ logbook.duration }}h</span
                                >
                            </div>
                        </div>
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-semibold flex-shrink-0',
                                getStatusClass(logbook.status),
                            ]"
                        >
                            {{ getStatusText(logbook.status) }}
                        </span>
                    </div>
                </div>

                <div class="px-5 py-4">
                    <h3
                        class="text-base font-bold text-blue-600 mb-2 line-clamp-2 group-hover:text-blue-700 transition-colors"
                    >
                        {{ logbook.title }}
                    </h3>
                    <p
                        class="text-sm text-gray-600 line-clamp-2 leading-relaxed mb-3"
                    >
                        {{ logbook.activities || "Tidak ada deskripsi" }}
                    </p>

                    <div
                        v-if="logbook.learning_points"
                        class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-100"
                    >
                        <div class="flex items-start gap-2">
                            <svg
                                class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5"
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
                            <p
                                class="text-xs text-blue-700 line-clamp-1 flex-1"
                            >
                                {{ logbook.learning_points }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2"
                >
                    <Link
                        :href="route('profile.logbooks.show', logbook.id)"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200"
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
                        Detail
                    </Link>
                    <Link
                        v-if="logbook.status !== 'approved'"
                        :href="route('profile.logbooks.edit', logbook.id)"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all duration-200"
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
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                        Edit
                    </Link>
                </div>
            </div>
        </div>

        <div v-if="hasMoreToShow" class="flex justify-center mt-8 mb-4">
            <button
                @click="$emit('load-more')"
                class="group inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl border-2 border-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-lg"
            >
                <svg
                    class="w-5 h-5 transition-transform group-hover:translate-y-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
                Lihat Lebih Banyak
                <span class="text-sm opacity-75"
                    >({{ filteredCount - displayedCount }} lagi)</span
                >
            </button>
        </div>
    </div>

    <div
        v-else
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center"
    >
        <div
            class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center"
        >
            <svg
                class="w-12 h-12 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Logbook</h3>
        <p class="text-gray-600 mb-6">
            {{
                statusFilter
                    ? "Tidak ada logbook dengan status ini. Coba ubah filter."
                    : "Mulai catat kegiatan harian Anda dengan menambahkan logbook pertama."
            }}
        </p>
        <button
            v-if="!statusFilter"
            @click="$emit('create')"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl"
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
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
            </svg>
            Tambah Logbook Pertama
        </button>
        <button
            v-else
            @click="$emit('reset-filter')"
            class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
        >
            Reset Filter
        </button>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    displayedLogbooks: {
        type: Array,
        default: () => [],
    },
    filteredCount: {
        type: Number,
        default: 0,
    },
    displayedCount: {
        type: Number,
        default: 0,
    },
    hasMoreToShow: {
        type: Boolean,
        default: false,
    },
    statusFilter: {
        type: String,
        default: "",
    },
    formatDateShort: {
        type: Function,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});

defineEmits(["load-more", "create", "reset-filter"]);
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
