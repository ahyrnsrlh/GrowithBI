<template>
    <Head :title="division.name || 'Detail Program'" />

    <!-- Navbar Component - Consistent with Landing Page -->
    <PublicNavbar :auth="auth" />

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50 pt-24">
        <!-- Hero Section with Breadcrumb -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumb Navigation -->
                <nav class="flex items-center space-x-2 text-sm mb-6">
                    <Link
                        href="/"
                        class="text-gray-500 hover:text-blue-600 transition-colors"
                    >
                        Beranda
                    </Link>
                    <svg
                        class="w-4 h-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                    <Link
                        href="/#divisions"
                        class="text-gray-500 hover:text-blue-600 transition-colors"
                    >
                        Divisi
                    </Link>
                    <svg
                        class="w-4 h-4 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                    <span class="text-gray-900 font-medium">{{
                        division.name || "Detail Program"
                    }}</span>
                </nav>

                <!-- Program Header -->
                <div
                    class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6"
                >
                    <div class="flex items-start gap-5">
                        <!-- Institution Logo -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-white border-2 border-gray-100 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0"
                        >
                            <img
                                :src="division.logo || '/logo.png'"
                                :alt="division.institution || 'Bank Indonesia'"
                                class="w-12 h-12 sm:w-14 sm:h-14 object-contain"
                            />
                        </div>
                        <div>
                            <h1
                                class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"
                            >
                                {{ division.name || "Program Magang" }}
                            </h1>
                            <div
                                class="flex flex-wrap items-center gap-3 text-gray-600"
                            >
                                <span class="font-medium">{{
                                    division.institution || "Bank Indonesia"
                                }}</span>
                                <span class="hidden sm:inline text-gray-300"
                                    >•</span
                                >
                                <div class="flex items-center gap-1.5">
                                    <svg
                                        class="w-4 h-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    <span>{{
                                        division.location ||
                                        "Bandar Lampung, Indonesia"
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <div class="flex-shrink-0">
                        <button
                            @click="handleApply"
                            :disabled="availableSlots <= 0 || isLoading"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl disabled:shadow-none disabled:cursor-not-allowed flex items-center gap-2"
                        >
                            <template v-if="!isLoading">
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
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                                <span>{{
                                    availableSlots <= 0
                                        ? "Kuota Penuh"
                                        : "Daftar Sekarang"
                                }}</span>
                            </template>
                            <template v-else>
                                <svg
                                    class="animate-spin w-5 h-5"
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
                                <span>Memproses...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid - Bento Layout -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Bento Grid Container -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <!-- Row 1: Info Cards -->
                <div
                    class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Durasi Program</h3>
                    </div>
                    <div class="h-px bg-white/30 my-4"></div>
                    <p class="text-3xl font-bold mb-1">
                        {{ formattedDuration }}
                    </p>
                    <p class="text-blue-100 text-sm">Waktu magang</p>
                </div>

                <div
                    class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Total Kuota</h3>
                    </div>
                    <div class="h-px bg-white/30 my-4"></div>
                    <p class="text-3xl font-bold mb-1">{{ formatQuota }}</p>
                    <p class="text-blue-100 text-sm">Peserta diterima</p>
                </div>

                <div
                    class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
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
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Slot Tersedia</h3>
                    </div>
                    <div class="h-px bg-white/30 my-4"></div>
                    <p class="text-3xl font-bold mb-1">
                        {{ formatAvailableSlots }}
                    </p>
                    <p
                        :class="
                            availableSlots > 0
                                ? 'text-blue-100'
                                : 'text-red-200'
                        "
                        class="text-sm"
                    >
                        {{
                            availableSlots > 0
                                ? "Masih tersedia"
                                : "Kuota penuh"
                        }}
                    </p>
                </div>

                <div
                    class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
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
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Deadline</h3>
                    </div>
                    <div class="h-px bg-white/30 my-4"></div>
                    <p class="text-2xl font-bold mb-1">
                        {{ formatDate(division.application_deadline) }}
                    </p>
                    <p class="text-blue-100 text-sm">Batas pendaftaran</p>
                </div>

                <!-- Row 2: Description (Large) -->
                <div
                    class="lg:col-span-8 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center"
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
                        <h2 class="text-xl font-bold text-gray-900">
                            Deskripsi Program
                        </h2>
                    </div>
                    <p
                        v-if="division.description"
                        class="text-gray-700 leading-relaxed whitespace-pre-line"
                    >
                        {{ division.description }}
                    </p>
                    <p v-else class="text-gray-400 italic">
                        Deskripsi belum tersedia
                    </p>
                </div>

                <!-- Row 2: Schedule (Medium) -->
                <div
                    class="lg:col-span-4 bg-blue-50 rounded-2xl p-6 shadow-lg border border-blue-100"
                >
                    <h3
                        class="font-bold text-gray-900 mb-6 flex items-center gap-2"
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
                        Jadwal Program
                    </h3>
                    <div class="space-y-4">
                        <div
                            class="flex items-center gap-3 p-4 bg-white rounded-xl border border-blue-100"
                        >
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
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">
                                    Mulai Program
                                </p>
                                <p class="font-bold text-gray-900">
                                    {{ formatDate(division.start_date) }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-4 bg-white rounded-xl border border-green-100"
                        >
                            <div
                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600"
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
                            <div>
                                <p class="text-xs text-gray-500 font-medium">
                                    Selesai Program
                                </p>
                                <p class="font-bold text-gray-900">
                                    {{ formatDate(division.end_date) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Job Description -->
                <div
                    v-if="hasJobDescription"
                    class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Tugas & Tanggung Jawab
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li
                            v-for="(task, index) in normalizedJobDescription"
                            :key="index"
                            class="flex items-start gap-3"
                        >
                            <span
                                class="flex-shrink-0 w-7 h-7 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-xs font-bold"
                            >
                                {{ index + 1 }}
                            </span>
                            <span class="text-gray-700 leading-relaxed">{{
                                cleanTaskText(task)
                            }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Row 3: Requirements -->
                <div
                    v-if="hasRequirements"
                    class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-amber-600"
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
                        <h2 class="text-xl font-bold text-gray-900">
                            Persyaratan
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li
                            v-for="(req, index) in normalizedRequirements"
                            :key="index"
                            class="flex items-start gap-3"
                        >
                            <svg
                                class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span class="text-gray-700 leading-relaxed">{{
                                req
                            }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Row 4: Criteria (if available) -->
                <div
                    v-if="hasCriteria"
                    class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Kriteria Peserta
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li
                            v-for="(criteria, index) in normalizedCriteria"
                            :key="index"
                            class="flex items-start gap-3"
                        >
                            <svg
                                class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5"
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
                            <span class="text-gray-700 leading-relaxed">{{
                                criteria
                            }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Row 4: Benefits (if available) -->
                <div
                    v-if="hasBenefits"
                    class="lg:col-span-6 bg-blue-50 rounded-2xl p-8 shadow-lg border border-blue-100"
                >
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm"
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
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Benefit Program
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li
                            v-for="(benefit, index) in normalizedBenefits"
                            :key="index"
                            class="flex items-start gap-3"
                        >
                            <div
                                class="flex-shrink-0 w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                    />
                                </svg>
                            </div>
                            <span
                                class="text-gray-700 leading-relaxed font-medium"
                                >{{ benefit }}</span
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <Teleport to="body">
        <div
            v-if="notification.show"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                    @click="closeNotification"
                ></div>

                <!-- Modal Panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div
                                :class="[
                                    'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center',
                                    notification.type === 'success'
                                        ? 'bg-green-100'
                                        : 'bg-red-100',
                                ]"
                            >
                                <svg
                                    v-if="notification.type === 'success'"
                                    class="w-6 h-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-6 h-6 text-red-600"
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
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ notification.title }}
                                </h3>
                                <p
                                    class="mt-2 text-gray-600 whitespace-pre-line"
                                >
                                    {{ notification.message }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button
                            @click="closeNotification"
                            :class="[
                                'px-6 py-2.5 rounded-lg font-medium transition-colors',
                                notification.type === 'success'
                                    ? 'bg-green-600 hover:bg-green-700 text-white'
                                    : 'bg-red-600 hover:bg-red-700 text-white',
                            ]"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PublicNavbar from "@/Components/PublicNavbar.vue";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});

// Reactive data
const isLoading = ref(false);
const notification = ref({
    show: false,
    type: "success",
    title: "",
    message: "",
});

// Computed properties for data normalization
const hasJobDescription = computed(() => {
    const jd = props.division.job_description;
    return jd && (Array.isArray(jd) ? jd.length > 0 : !!jd);
});

const normalizedJobDescription = computed(() => {
    const jd = props.division.job_description;
    if (!jd) return [];
    if (Array.isArray(jd)) return jd.filter((item) => item && item.trim());
    if (typeof jd === "string")
        return jd.split("\n").filter((item) => item.trim());
    return [];
});

const hasRequirements = computed(() => {
    const req = props.division.requirements;
    return req && (Array.isArray(req) ? req.length > 0 : !!req);
});

const normalizedRequirements = computed(() => {
    const req = props.division.requirements;
    if (!req) return [];
    if (Array.isArray(req)) return req.filter((item) => item && item.trim());
    if (typeof req === "string")
        return req.split("\n").filter((item) => item.trim());
    return [];
});

const hasCriteria = computed(() => {
    const criteria = props.division.criteria;
    return (
        criteria && (Array.isArray(criteria) ? criteria.length > 0 : !!criteria)
    );
});

const normalizedCriteria = computed(() => {
    const criteria = props.division.criteria;
    if (!criteria) return [];
    if (Array.isArray(criteria))
        return criteria.filter((item) => item && item.trim());
    if (typeof criteria === "string")
        return criteria.split("\n").filter((item) => item.trim());
    return [];
});

const hasBenefits = computed(() => {
    const benefits = props.division.benefit || props.division.benefits;
    return (
        benefits && (Array.isArray(benefits) ? benefits.length > 0 : !!benefits)
    );
});

const normalizedBenefits = computed(() => {
    const benefits = props.division.benefit || props.division.benefits;
    if (!benefits) return [];
    if (Array.isArray(benefits))
        return benefits.filter((item) => item && item.trim());
    if (typeof benefits === "string")
        return benefits.split("\n").filter((item) => item.trim());
    return [];
});

const availableSlots = computed(() => {
    return (
        props.division.available_slots ??
        props.division.available_quota ??
        props.division.quota ??
        0
    );
});

const formattedDuration = computed(() => {
    const start = props.division.start_date;
    const end = props.division.end_date;

    if (!start || !end) return "-";

    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffTime = Math.abs(endDate - startDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const diffMonths = Math.round(diffDays / 30);

    if (diffMonths >= 1) {
        return `${diffMonths} bulan`;
    }
    return `${diffDays} hari`;
});

const formatQuota = computed(() => {
    const quota = props.division.quota ?? props.division.max_interns;
    return quota ? `${quota} orang` : "-";
});

const formatAvailableSlots = computed(() => {
    if (availableSlots.value === 0) return "Penuh";
    return availableSlots.value > 0 ? `${availableSlots.value} tersisa` : "-";
});

const registrationStatus = computed(() => {
    const deadline = props.division.application_deadline;
    const startDate = props.division.start_date;
    const now = new Date();

    if (!deadline) {
        return {
            label: "Belum Ditentukan",
            class: "bg-gray-100 text-gray-700",
            dotClass: "bg-gray-400",
        };
    }

    const deadlineDate = new Date(deadline);
    const start = startDate ? new Date(startDate) : null;

    if (now > deadlineDate) {
        return {
            label: "Pendaftaran Ditutup",
            class: "bg-red-100 text-red-700",
            dotClass: "bg-red-500",
        };
    }

    if (start && now < start) {
        const daysLeft = Math.ceil(
            (deadlineDate - now) / (1000 * 60 * 60 * 24)
        );
        if (daysLeft <= 7) {
            return {
                label: `Segera Ditutup (${daysLeft} hari)`,
                class: "bg-amber-100 text-amber-700",
                dotClass: "bg-amber-500",
            };
        }
    }

    if (availableSlots.value <= 0) {
        return {
            label: "Kuota Penuh",
            class: "bg-red-100 text-red-700",
            dotClass: "bg-red-500",
        };
    }

    return {
        label: "Pendaftaran Dibuka",
        class: "bg-green-100 text-green-700",
        dotClass: "bg-green-500",
    };
});

// Helper functions
const formatDate = (date) => {
    if (!date) return "-";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const cleanTaskText = (text) => {
    if (!text) return "";
    return text.replace(/^\d+\.\s*/, "").trim();
};

// Methods
const handleApply = async () => {
    if (availableSlots.value <= 0) {
        showNotification(
            "error",
            "Maaf",
            "Kuota untuk divisi ini sudah penuh."
        );
        return;
    }

    if (!props.auth?.user) {
        showNotification(
            "error",
            "Login Diperlukan",
            "Silakan login terlebih dahulu untuk mendaftar magang."
        );
        setTimeout(() => {
            router.visit("/login", {
                method: "get",
                preserveState: false,
                data: { redirect: window.location.pathname },
            });
        }, 2000);
        return;
    }

    isLoading.value = true;

    try {
        const response = await fetch(
            `/applications/check/${props.division.id}`,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );

        const result = await response.json();

        if (response.ok && result.canApply) {
            const applyResponse = await fetch("/profile/create-application", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({ division_id: props.division.id }),
            });

            const applyResult = await applyResponse.json();

            if (applyResponse.ok) {
                showNotification(
                    "success",
                    "Pendaftaran Berhasil!",
                    `Anda telah berhasil mendaftar untuk divisi ${props.division.name}. Silakan tunggu konfirmasi dari admin.`
                );
                setTimeout(() => window.location.reload(), 3000);
            } else {
                showNotification(
                    "error",
                    "Gagal Mendaftar",
                    applyResult.message || "Terjadi kesalahan saat mendaftar."
                );
            }
        } else {
            const missingItems = [];
            if (result.missingPersonalData?.length > 0) {
                missingItems.push(
                    `Data Pribadi: ${result.missingPersonalData.join(", ")}`
                );
            }
            if (result.missingDocuments?.length > 0) {
                missingItems.push(
                    `Dokumen: ${result.missingDocuments.join(", ")}`
                );
            }

            const missingMessage =
                missingItems.length > 0
                    ? `Harap lengkapi terlebih dahulu:\n\n${missingItems.join(
                          "\n\n"
                      )}`
                    : "Harap lengkapi profil Anda terlebih dahulu.";

            showNotification("error", "Data Belum Lengkap", missingMessage);
            setTimeout(
                () =>
                    router.visit("/profile", {
                        method: "get",
                        preserveState: false,
                    }),
                4000
            );
        }
    } catch (error) {
        console.error("Application error:", error);
        showNotification(
            "error",
            "Error",
            "Terjadi kesalahan saat memproses lamaran Anda. Silakan coba lagi."
        );
    } finally {
        isLoading.value = false;
    }
};

const showNotification = (type, title, message) => {
    notification.value = { show: true, type, title, message };
};

const closeNotification = () => {
    notification.value.show = false;
};
</script>
