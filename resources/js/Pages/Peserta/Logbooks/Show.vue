<template>
    <PesertaLayout>
        <!-- Modern Header Section with Gradient -->
        <div
            class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 -mt-12 mb-8"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Back Button -->
                <div class="mb-6">
                    <Link
                        :href="route('peserta.logbooks.index')"
                        class="inline-flex items-center text-blue-100 hover:text-white transition-colors group"
                    >
                        <svg
                            class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform"
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
                        <span class="font-medium"
                            >Kembali ke Daftar Logbook</span
                        >
                    </Link>
                </div>

                <!-- Page Title & Info -->
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div
                                class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white mb-1">
                                    {{ logbook.title }}
                                </h1>
                                <p class="text-blue-100 text-sm font-medium">
                                    Laporan kegiatan harian peserta magang
                                </p>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-4 mt-4">
                            <div class="flex items-center text-white/90">
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
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <span class="text-sm font-medium">{{
                                    formatDateShort(logbook.date)
                                }}</span>
                            </div>
                            <div class="flex items-center text-white/90">
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span class="text-sm font-medium"
                                    >{{ logbook.duration }} jam</span
                                >
                            </div>
                            <div>
                                <span
                                    :class="getStatusBadgeClass(logbook.status)"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm"
                                >
                                    <span
                                        class="w-2 h-2 rounded-full mr-2"
                                        :class="
                                            getStatusDotClass(logbook.status)
                                        "
                                    ></span>
                                    {{ getStatusText(logbook.status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div v-if="canEdit" class="hidden md:block">
                        <Link
                            :href="route('peserta.logbooks.edit', logbook.id)"
                            class="inline-flex items-center px-5 py-2.5 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl font-semibold"
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Edit Logbook
                        </Link>
                    </div>
                </div>

                <!-- Progress Bar for Status -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-blue-100"
                            >Progress Review</span
                        >
                        <span class="text-xs font-semibold text-blue-100"
                            >{{ getProgressPercentage(logbook.status) }}%</span
                        >
                    </div>
                    <div
                        class="w-full bg-blue-900/30 rounded-full h-2 overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-400 to-blue-300 h-2 rounded-full transition-all duration-500"
                            :style="{
                                width:
                                    getProgressPercentage(logbook.status) + '%',
                            }"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Activities Card -->
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
                            <p
                                class="text-gray-700 whitespace-pre-line leading-relaxed"
                            >
                                {{ logbook.activities }}
                            </p>
                        </div>
                    </div>

                    <!-- Learning Points Card -->
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
                            <p
                                class="text-gray-700 whitespace-pre-line leading-relaxed"
                            >
                                {{ logbook.learning_points }}
                            </p>
                        </div>
                    </div>

                    <!-- Challenges Card -->
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
                            <p
                                class="text-gray-700 whitespace-pre-line leading-relaxed"
                            >
                                {{ logbook.challenges }}
                            </p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div
                        v-if="
                            logbook.attachments &&
                            logbook.attachments.length > 0
                        "
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
                                    v-for="(
                                        attachment, index
                                    ) in logbook.attachments"
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
                                            {{
                                                formatFileSize(attachment.size)
                                            }}
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

                    <!-- Mentor Feedback Section -->
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
                                        :class="
                                            getStatusBadgeClass(logbook.status)
                                        "
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
                                    <span
                                        v-if="logbook.reviewer"
                                        class="font-semibold"
                                    >
                                        {{ logbook.reviewer.name }}
                                    </span>
                                    <span
                                        v-if="logbook.reviewed_at"
                                        class="text-gray-600"
                                    >
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

                    <!-- Comments Section -->
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
                                    <h3
                                        class="text-lg font-semibold text-gray-800"
                                    >
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
                            <!-- Comment List -->
                            <div
                                v-if="allComments.length > 0"
                                class="space-y-4 mb-6"
                            >
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
                                                {{
                                                    comment.user.name
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div
                                                class="flex items-center space-x-2 mb-2"
                                            >
                                                <h4
                                                    class="text-sm font-semibold text-gray-900"
                                                >
                                                    {{ comment.user.name }}
                                                </h4>
                                                <span
                                                    v-if="
                                                        comment.is_mentor_feedback
                                                    "
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
                                                        comment.user.role ===
                                                            'mentor' ||
                                                        comment.user.role ===
                                                            'admin'
                                                    "
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-100 text-blue-700"
                                                >
                                                    Pembimbing
                                                </span>
                                                <span
                                                    class="text-xs text-gray-500"
                                                >
                                                    •
                                                    {{
                                                        formatDateShort(
                                                            comment.created_at
                                                        )
                                                    }}
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

                            <!-- Add Comment Form -->
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
                </div>

                <!-- Sticky Sidebar -->
                <div class="space-y-6">
                    <!-- Informasi Logbook Card -->
                    <div
                        class="bg-white rounded-2xl shadow-md border-l-4 border-blue-600 overflow-hidden sticky top-6"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4"
                        >
                            <h3
                                class="text-lg font-bold text-white flex items-center"
                            >
                                <svg
                                    class="w-6 h-6 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Informasi Logbookk
                            </h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <!-- Tanggal -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-5 h-5 text-blue-600"
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
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                        >Tanggal</label
                                    >
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ formatDateShort(logbook.date) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Durasi -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-5 h-5 text-emerald-600"
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
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                        >Durasi</label
                                    >
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ logbook.duration }} jam kerja
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
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
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                        >Status</label
                                    >
                                    <span
                                        :class="
                                            getStatusBadgeClass(logbook.status)
                                        "
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold"
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
                            </div>

                            <!-- Divisi -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-5 h-5 text-amber-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                        >Divisi</label
                                    >
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ logbook.division?.name || "-" }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <!-- Tanggal Dibuat -->
                                <div class="flex items-start space-x-3 mb-4">
                                    <svg
                                        class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5"
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
                                    <div class="flex-1">
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
                                </div>

                                <!-- Terakhir Diubah -->
                                <div
                                    v-if="
                                        logbook.updated_at !==
                                        logbook.created_at
                                    "
                                    class="flex items-start space-x-3"
                                >
                                    <svg
                                        class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                    <div class="flex-1">
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

                            <!-- Reviewer Info -->
                            <div
                                v-if="logbook.reviewer"
                                class="pt-4 border-t border-gray-200"
                            >
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-indigo-600"
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
                                        <label
                                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1"
                                            >Direview oleh</label
                                        >
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ logbook.reviewer.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                v-if="canEdit"
                                class="pt-5 border-t border-gray-200"
                            >
                                <Link
                                    :href="
                                        route(
                                            'peserta.logbooks.edit',
                                            logbook.id
                                        )
                                    "
                                    class="w-full inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all"
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
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                    Edit Logbook
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Status Help Card -->
                    <div
                        class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl border border-blue-200 shadow-sm overflow-hidden"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4"
                        >
                            <h4
                                class="text-sm font-bold text-white flex items-center"
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
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Panduan Status
                            </h4>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex items-start space-x-3 group">
                                <div
                                    class="w-3 h-3 bg-gray-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                                ></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">
                                        Draft
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Belum dikirim untuk review
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 group">
                                <div
                                    class="w-3 h-3 bg-yellow-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                                ></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">
                                        Menunggu Review
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Sedang direview pembimbing
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 group">
                                <div
                                    class="w-3 h-3 bg-green-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                                ></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">
                                        Disetujui
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Logbook telah disetujui
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 group">
                                <div
                                    class="w-3 h-3 bg-purple-400 rounded-full mt-1 flex-shrink-0 group-hover:scale-125 transition-transform"
                                ></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">
                                        Perlu Revisi
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Perlu diperbaiki dan dikirim ulang
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PesertaLayout>
</template>

<script setup>
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const commentForm = useForm({
    comment: "",
});

const canEdit = computed(() => {
    return ["draft", "revision"].includes(props.logbook.status);
});

const allComments = computed(() => {
    let comments = [...(props.logbook.comments || [])];

    // Add mentor feedback as a special comment if it exists
    if (
        props.logbook.mentor_feedback &&
        props.logbook.status !== "draft" &&
        props.logbook.status !== "submitted"
    ) {
        const mentorComment = {
            id: "mentor-feedback",
            comment: props.logbook.mentor_feedback,
            created_at: props.logbook.reviewed_at || props.logbook.updated_at,
            is_mentor_feedback: true,
            user: props.logbook.reviewer || {
                id: "mentor",
                name: "Pembimbing",
                role: "mentor",
            },
        };

        // Insert mentor feedback at the beginning
        comments.unshift(mentorComment);
    }

    return comments.sort(
        (a, b) => new Date(b.created_at) - new Date(a.created_at)
    );
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
        return "-";
    }
};

const formatDateShort = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    } catch (error) {
        return "-";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        submitted: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        revision: "bg-purple-100 text-purple-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusBadgeClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-700 border border-gray-300",
        submitted: "bg-yellow-100 text-yellow-800 border border-yellow-300",
        approved: "bg-green-100 text-green-800 border border-green-300",
        revision: "bg-purple-100 text-purple-800 border border-purple-300",
        rejected: "bg-red-100 text-red-800 border border-red-300",
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
        rejected: "bg-red-500",
    };
    return classes[status] || "bg-gray-500";
};

const getStatusText = (status) => {
    const texts = {
        draft: "Draft",
        submitted: "Menunggu Review",
        approved: "Disetujui",
        revision: "Perlu Revisi",
        rejected: "Ditolak",
    };
    return texts[status] || "Unknown";
};

const getProgressPercentage = (status) => {
    const progress = {
        draft: 0,
        submitted: 50,
        approved: 100,
        revision: 25,
        rejected: 0,
    };
    return progress[status] || 0;
};

const addComment = () => {
    commentForm.post(
        route("peserta.logbooks.comments.store", props.logbook.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset();
            },
        }
    );
};
</script>
