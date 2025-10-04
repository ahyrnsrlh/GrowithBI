<template>
    <AuthenticatedLayout>
        <Head title="Logbook Harian" />

        <!-- Header Section with BI Branding -->
        <div class="mb-8">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
            >
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">
                        Logbook Harian
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Catat perkembangan dan kegiatan harian selama masa
                        magang
                    </p>
                </div>
                <button
                    @click="showCreateModal = true"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
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
                    Tambah Logbook
                </button>
            </div>
        </div>

        <!-- Statistics Cards - Compact Design -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-blue-100 text-xs font-medium uppercase tracking-wide"
                        >
                            Total
                        </p>
                        <p class="text-3xl font-bold mt-1">
                            {{ stats.total_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                        <svg
                            class="w-6 h-6"
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
            </div>

            <div
                class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl shadow-md p-5 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-orange-100 text-xs font-medium uppercase tracking-wide"
                        >
                            Pending
                        </p>
                        <p class="text-3xl font-bold mt-1">
                            {{ stats.pending_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                        <svg
                            class="w-6 h-6"
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
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md p-5 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-green-100 text-xs font-medium uppercase tracking-wide"
                        >
                            Disetujui
                        </p>
                        <p class="text-3xl font-bold mt-1">
                            {{ stats.approved_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                        <svg
                            class="w-6 h-6"
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
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-5 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-purple-100 text-xs font-medium uppercase tracking-wide"
                        >
                            Revisi
                        </p>
                        <p class="text-3xl font-bold mt-1">
                            {{ stats.revision_logbooks || 0 }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                        <svg
                            class="w-6 h-6"
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
                </div>
            </div>
        </div>

        <!-- Sticky Filter Bar -->
        <div
            class="sticky top-20 z-30 bg-white/95 backdrop-blur-sm border border-gray-200 rounded-xl shadow-sm mb-6 p-4"
        >
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700"
                        >Filter:</span
                    >
                    <div class="flex flex-wrap gap-2">
                        <button
                            @click="setStatusFilter('')"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                filters.status === ''
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            Semua
                        </button>
                        <button
                            @click="setStatusFilter('submitted')"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                filters.status === 'submitted'
                                    ? 'bg-[#FFA726] text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            Pending
                        </button>
                        <button
                            @click="setStatusFilter('approved')"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                filters.status === 'approved'
                                    ? 'bg-[#43A047] text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            Disetujui
                        </button>
                        <button
                            @click="setStatusFilter('revision')"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                filters.status === 'revision'
                                    ? 'bg-[#AB47BC] text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            Revisi
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">
                        Menampilkan
                        <span class="font-semibold">{{
                            displayedLogbooks.length
                        }}</span>
                        dari
                        <span class="font-semibold">{{
                            filteredLogbooks.length
                        }}</span>
                        logbook
                    </span>
                </div>
            </div>
        </div>

        <!-- Logbook Cards - Modern Grid Layout -->
        <div v-if="filteredLogbooks.length > 0">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6"
            >
                <div
                    v-for="logbook in displayedLogbooks"
                    :key="logbook.id"
                    class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden"
                >
                    <!-- Card Header -->
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

                    <!-- Card Body -->
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

                        <!-- Learning Points Preview (if exists) -->
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

                    <!-- Card Footer -->
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

            <!-- Load More Button -->
            <div v-if="hasMoreToShow" class="flex justify-center mt-8 mb-4">
                <button
                    @click="loadMore"
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
                        >({{
                            filteredLogbooks.length - displayedCount
                        }}
                        lagi)</span
                    >
                </button>
            </div>
        </div>

        <!-- Empty State -->
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
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                Belum Ada Logbook
            </h3>
            <p class="text-gray-600 mb-6">
                {{
                    filters.status
                        ? "Tidak ada logbook dengan status ini. Coba ubah filter."
                        : "Mulai catat kegiatan harian Anda dengan menambahkan logbook pertama."
                }}
            </p>
            <button
                v-if="!filters.status"
                @click="showCreateModal = true"
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
                @click="setStatusFilter('')"
                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
            >
                Reset Filter
            </button>
        </div>

        <!-- Create Modal -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            @click.self="showCreateModal = false"
        >
            <div
                class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
            >
                <div
                    class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 border-b border-gray-200 flex items-center justify-between z-10"
                >
                    <div>
                        <h3 class="text-2xl font-bold text-white">
                            Tambah Logbook Baru
                        </h3>
                        <p class="text-blue-100 text-sm mt-1">
                            Catat kegiatan harian Anda
                        </p>
                    </div>
                    <button
                        @click="showCreateModal = false"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors"
                    >
                        <svg
                            class="w-6 h-6"
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

                <form @submit.prevent="submitLogbook" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="createForm.date"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                            />
                            <div
                                v-if="createForm.errors.date"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ createForm.errors.date }}
                            </div>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Durasi (jam) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model.number="createForm.duration"
                                type="number"
                                step="0.5"
                                min="0.5"
                                max="24"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                            />
                            <div
                                v-if="createForm.errors.duration"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ createForm.errors.duration }}
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mt-6">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Judul Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="createForm.title"
                            type="text"
                            required
                            placeholder="Contoh: Regulatory Technology (RegTech)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                        />
                        <div
                            v-if="createForm.errors.title"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ createForm.errors.title }}
                        </div>
                    </div>

                    <!-- Activities -->
                    <div class="mt-6">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Deskripsi Kegiatan
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="createForm.activities"
                            rows="4"
                            required
                            placeholder="Jelaskan kegiatan yang Anda lakukan hari ini..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                        ></textarea>
                        <div
                            v-if="createForm.errors.activities"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ createForm.errors.activities }}
                        </div>
                    </div>

                    <!-- Learning Points -->
                    <div class="mt-6">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Poin Pembelajaran
                        </label>
                        <textarea
                            v-model="createForm.learning_points"
                            rows="3"
                            placeholder="Apa yang Anda pelajari hari ini? (opsional)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                        ></textarea>
                        <div
                            v-if="createForm.errors.learning_points"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ createForm.errors.learning_points }}
                        </div>
                    </div>

                    <!-- Challenges -->
                    <div class="mt-6">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Tantangan & Hambatan
                        </label>
                        <textarea
                            v-model="createForm.challenges"
                            rows="3"
                            placeholder="Kendala yang dihadapi (opsional)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all resize-none"
                        ></textarea>
                        <div
                            v-if="createForm.errors.challenges"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ createForm.errors.challenges }}
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div
                        class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="button"
                            @click="showCreateModal = false"
                            class="px-6 py-2.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-medium transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                        >
                            <span v-if="createForm.processing"
                                >Menyimpan...</span
                            >
                            <span v-else>Simpan Logbook</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    logbooks: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_logbooks: 0,
            pending_logbooks: 0,
            approved_logbooks: 0,
            revision_logbooks: 0,
        }),
    },
    filters: {
        type: Object,
        default: () => ({
            month: "",
            status: "",
        }),
    },
});

const showCreateModal = ref(false);
const displayedCount = ref(9); // Initial load: 9 items (3x3 grid)

const filters = ref({
    month: props.filters.month || "",
    status: props.filters.status || "",
});

// Filter logbooks based on status
const filteredLogbooks = computed(() => {
    if (!filters.value.status) {
        return props.logbooks;
    }
    return props.logbooks.filter(
        (logbook) => logbook.status === filters.value.status
    );
});

// Display limited logbooks
const displayedLogbooks = computed(() => {
    return filteredLogbooks.value.slice(0, displayedCount.value);
});

// Check if there are more items to show
const hasMoreToShow = computed(() => {
    return displayedCount.value < filteredLogbooks.value.length;
});

// Load more function
const loadMore = () => {
    displayedCount.value += 6; // Load 6 more items
};

// Set status filter
const setStatusFilter = (status) => {
    filters.value.status = status;
    displayedCount.value = 9; // Reset to initial count
};

// Create form for modal
const createForm = useForm({
    date: new Date().toISOString().split("T")[0],
    duration: 8,
    title: "",
    activities: "",
    learning_points: "",
    challenges: "",
    status: "submitted",
});

const submitLogbook = () => {
    createForm.post(route("profile.logbooks.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

const formatDateShort = (date) => {
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const getStatusClass = (status) => {
    const classes = {
        submitted: "bg-[#FFA726] text-white",
        approved: "bg-[#43A047] text-white",
        revision: "bg-[#AB47BC] text-white",
        draft: "bg-gray-500 text-white",
    };
    return classes[status] || "bg-gray-500 text-white";
};

const getStatusText = (status) => {
    const texts = {
        submitted: "Pending",
        approved: "Disetujui",
        revision: "Revisi",
        draft: "Draft",
    };
    return texts[status] || "Unknown";
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: rgb(37 99 235);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #003366;
}
</style>
