<template>
    <AuthenticatedLayout>
        <Head :title="`Detail Logbook - ${logbook.title}`" />
        <div class="min-h-screen bg-gray-50">
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <Link
                        :href="route('profile.edit')"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors group"
                    >
                        <svg
                            class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-0.5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        <span class="font-medium">Kembali</span>
                    </Link>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div
                            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
                        >
                            <div class="p-6 sm:p-8">
                                <div
                                    class="flex items-start justify-between mb-6"
                                >
                                    <div class="flex-1">
                                        <h1
                                            class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"
                                        >
                                            {{ logbook.title }}
                                        </h1>
                                        <div
                                            class="flex flex-wrap items-center gap-4 text-sm text-gray-600"
                                        >
                                            <div class="flex items-center">
                                                <svg
                                                    class="w-4 h-4 mr-1.5 text-gray-400"
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
                                                {{
                                                    formatDateShort(
                                                        logbook.date
                                                    )
                                                }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg
                                                    class="w-4 h-4 mr-1.5 text-gray-400"
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
                                                {{ logbook.duration }} jam
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        :class="
                                            getStatusBadgeClass(logbook.status)
                                        "
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap"
                                    >
                                        <span
                                            class="w-2 h-2 rounded-full mr-2"
                                            :class="
                                                getStatusDotClass(
                                                    logbook.status
                                                )
                                            "
                                        ></span>
                                        {{ getStatusText(logbook.status) }}
                                    </span>
                                </div>
                                <div
                                    v-if="logbook.status !== 'draft'"
                                    class="mb-6"
                                >
                                    <div
                                        class="flex items-center justify-between text-xs text-gray-500 mb-2"
                                    >
                                        <span>Progress</span>
                                        <span class="font-semibold"
                                            >{{
                                                getProgressPercentage(
                                                    logbook.status
                                                )
                                            }}%</span
                                        >
                                    </div>
                                    <div
                                        class="w-full bg-gray-200 rounded-full h-1.5"
                                    >
                                        <div
                                            class="bg-blue-600 h-1.5 rounded-full transition-all duration-700"
                                            :style="{
                                                width:
                                                    getProgressPercentage(
                                                        logbook.status
                                                    ) + '%',
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <div
                                    class="border-t border-gray-200 my-6"
                                ></div>
                                <div class="space-y-6">
                                    <div>
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-1 h-5 bg-blue-600 rounded-full mr-3"
                                            ></div>
                                            <h2
                                                class="text-lg font-semibold text-gray-900"
                                            >
                                                Aktivitas yang Dilakukan
                                            </h2>
                                        </div>
                                        <p
                                            class="text-gray-700 leading-relaxed whitespace-pre-line"
                                        >
                                            {{ logbook.activities }}
                                        </p>
                                    </div>
                                    <div v-if="logbook.learning_points">
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-1 h-5 bg-emerald-600 rounded-full mr-3"
                                            ></div>
                                            <h2
                                                class="text-lg font-semibold text-gray-900"
                                            >
                                                Poin Pembelajaran
                                            </h2>
                                        </div>
                                        <p
                                            class="text-gray-700 leading-relaxed whitespace-pre-line"
                                        >
                                            {{ logbook.learning_points }}
                                        </p>
                                    </div>
                                    <div v-if="logbook.challenges">
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-1 h-5 bg-amber-600 rounded-full mr-3"
                                            ></div>
                                            <h2
                                                class="text-lg font-semibold text-gray-900"
                                            >
                                                Tantangan yang Dihadapi
                                            </h2>
                                        </div>
                                        <p
                                            class="text-gray-700 leading-relaxed whitespace-pre-line"
                                        >
                                            {{ logbook.challenges }}
                                        </p>
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
                                        <h3
                                            class="text-lg font-semibold text-gray-900 mb-1"
                                        >
                                            Feedback dari Pembimbing
                                        </h3>
                                        <p
                                            v-if="logbook.reviewed_at"
                                            class="text-xs text-gray-500 mb-4"
                                        >
                                            {{
                                                formatDate(logbook.reviewed_at)
                                            }}
                                        </p>
                                        <div
                                            class="bg-white rounded-lg border border-blue-100 p-4"
                                        >
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
                    </div>
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">
                            <div
                                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
                            >
                                <div
                                    class="px-6 py-4 border-b border-gray-200 bg-gray-50"
                                >
                                    <h3
                                        class="text-sm font-semibold text-gray-900 uppercase tracking-wide"
                                    >
                                        Informasi
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-2"
                                            >Status</label
                                        >
                                        <span
                                            :class="
                                                getStatusBadgeClass(
                                                    logbook.status
                                                )
                                            "
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold"
                                        >
                                            <span
                                                class="w-2 h-2 rounded-full mr-2"
                                                :class="
                                                    getStatusDotClass(
                                                        logbook.status
                                                    )
                                                "
                                            ></span>
                                            {{ getStatusText(logbook.status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1"
                                            >Tanggal</label
                                        >
                                        <p class="text-sm text-gray-900">
                                            {{ formatDate(logbook.date) }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1"
                                            >Durasi</label
                                        >
                                        <p class="text-sm text-gray-900">
                                            {{ logbook.duration }} jam kerja
                                        </p>
                                    </div>
                                    <div v-if="logbook.division">
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1"
                                            >Divisi</label
                                        >
                                        <p class="text-sm text-gray-900">
                                            {{ logbook.division.name }}
                                        </p>
                                    </div>
                                    <div
                                        class="pt-4 border-t border-gray-200 space-y-3"
                                    >
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 mb-1"
                                                >Dibuat</label
                                            >
                                            <p class="text-xs text-gray-700">
                                                {{
                                                    formatDateShort(
                                                        logbook.created_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            v-if="
                                                logbook.updated_at !==
                                                logbook.created_at
                                            "
                                        >
                                            <label
                                                class="block text-xs font-medium text-gray-500 mb-1"
                                                >Terakhir Diubah</label
                                            >
                                            <p class="text-xs text-gray-700">
                                                {{
                                                    formatDateShort(
                                                        logbook.updated_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const props = defineProps({ logbook: Object });
const formatDate = (date) => {
    if (!date) return "-";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};
const formatDateShort = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "short", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};
const getStatusText = (status) => {
    const statusMap = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
    };
    return statusMap[status] || status;
};
const getStatusBadgeClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-700 border border-gray-300",
        submitted: "bg-yellow-100 text-yellow-800 border border-yellow-300",
        approved: "bg-green-100 text-green-800 border border-green-300",
        revision: "bg-purple-100 text-purple-800 border border-purple-300",
    };
    return (
        classes[status] || "bg-gray-100 text-gray-700 border border-gray-300"
    );
};
const getStatusDotClass = (status) => {
    const classes = {
        draft: "bg-gray-500",
        submitted: "bg-yellow-500 animate-pulse",
        approved: "bg-green-500",
        revision: "bg-purple-500",
    };
    return classes[status] || "bg-gray-500";
};
const getProgressPercentage = (status) => {
    const progress = { draft: 0, submitted: 50, approved: 100, revision: 25 };
    return progress[status] || 0;
};
</script>
