<template>
    <AuthenticatedLayout>
        <Head :title="`Detail Logbook - ${logbook.title}`" />

        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ logbook.title }}
                            </h1>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-sm text-gray-500">
                                    <svg
                                        class="w-4 h-4 inline mr-1"
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
                                    {{ formatDate(logbook.date) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <svg
                                        class="w-4 h-4 inline mr-1"
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
                                </span>
                                <span
                                    :class="getStatusClass(logbook.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                >
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('profile.edit')"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
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
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                    />
                                </svg>
                                Kembali
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Activities -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Aktivitas yang Dilakukan
                            </h3>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{ logbook.activities }}
                                </p>
                            </div>
                        </div>

                        <!-- Learning Points -->
                        <div
                            v-if="logbook.learning_points"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Poin Pembelajaran
                            </h3>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{ logbook.learning_points }}
                                </p>
                            </div>
                        </div>

                        <!-- Challenges -->
                        <div
                            v-if="logbook.challenges"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Tantangan yang Dihadapi
                            </h3>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{ logbook.challenges }}
                                </p>
                            </div>
                        </div>

                        <!-- Mentor Feedback Section -->
                        <div
                            v-if="
                                logbook.mentor_feedback &&
                                logbook.status !== 'draft' &&
                                logbook.status !== 'submitted'
                            "
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6"
                        >
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center"
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
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3
                                        class="text-lg font-semibold text-blue-900 mb-2"
                                    >
                                        Feedback dari Pembimbing
                                    </h3>
                                    <div class="text-sm text-blue-700 mb-3">
                                        <span v-if="logbook.reviewed_at">
                                            {{
                                                formatDate(logbook.reviewed_at)
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        class="bg-white rounded-lg border border-blue-200 p-4"
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

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Informasi Logbook
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Tanggal
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ formatDate(logbook.date) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Durasi
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ logbook.duration }} jam
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Status
                                    </dt>
                                    <dd>
                                        <span
                                            :class="
                                                getStatusClass(logbook.status)
                                            "
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        >
                                            {{ getStatusText(logbook.status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div v-if="logbook.division">
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Divisi
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ logbook.division.name }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Dibuat
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ formatDate(logbook.created_at) }}
                                    </dd>
                                </div>
                                <div
                                    v-if="
                                        logbook.updated_at !==
                                        logbook.created_at
                                    "
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        Terakhir Diupdate
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ formatDate(logbook.updated_at) }}
                                    </dd>
                                </div>
                            </dl>
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

const props = defineProps({
    logbook: Object,
});

// Format date helper
const formatDate = (date) => {
    if (!date) return "-";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date(date).toLocaleDateString("id-ID", options);
};

// Get status text
const getStatusText = (status) => {
    const statusMap = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
    };
    return statusMap[status] || status;
};

// Get status class
const getStatusClass = (status) => {
    const statusClasses = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-red-100 text-red-800",
    };
    return statusClasses[status] || "bg-gray-100 text-gray-800";
};
</script>
