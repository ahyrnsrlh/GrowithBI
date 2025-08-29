<template>
    <Head :title="division.name"></Head>
    <div class="min-h-screen bg-white relative overflow-hidden">
        <!-- Grid Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div
                class="absolute inset-0"
                style="
                    background-image: linear-gradient(
                            rgba(59, 130, 246, 0.1) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            90deg,
                            rgba(59, 130, 246, 0.1) 1px,
                            transparent 1px
                        );
                    background-size: 50px 50px;
                "
            ></div>
        </div>

        <!-- Content -->
        <div class="relative z-10">
            <!-- Modal Notification Overlay -->
            <div
                v-if="notification.show"
                class="fixed inset-0 z-50 overflow-y-auto"
                @click="closeNotification"
            >
                <div
                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <!-- Background overlay -->
                    <div
                        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity duration-300"
                    ></div>

                    <!-- Modal panel -->
                    <div
                        @click.stop
                        class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in"
                    >
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full sm:mx-0 sm:h-14 sm:w-14 animate-pulse"
                                    :class="
                                        notification.type === 'success'
                                            ? 'bg-green-100'
                                            : 'bg-red-100'
                                    "
                                >
                                    <svg
                                        v-if="notification.type === 'success'"
                                        class="h-8 w-8 text-green-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
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
                                        class="h-8 w-8 text-red-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                                >
                                    <h3
                                        class="text-xl leading-6 font-bold text-gray-900 mb-3"
                                    >
                                        {{ notification.title }}
                                    </h3>
                                    <div class="mt-2">
                                        <p
                                            class="text-sm text-gray-600 leading-relaxed"
                                        >
                                            {{ notification.message }}
                                        </p>
                                    </div>

                                    <!-- Progress bar for auto-redirect -->
                                    <div
                                        v-if="
                                            notification.type === 'success' &&
                                            redirectCountdown > 0
                                        "
                                        class="mt-4"
                                    >
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-500 mb-2"
                                        >
                                            <span
                                                >Mengarahkan ke profil...</span
                                            >
                                            <span
                                                >{{ redirectCountdown }}s</span
                                            >
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-1.5"
                                        >
                                            <div
                                                class="bg-green-600 h-1.5 rounded-full transition-all duration-1000 ease-linear"
                                                :style="`width: ${
                                                    ((3 - redirectCountdown) /
                                                        3) *
                                                    100
                                                }%`"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse"
                        >
                            <button
                                v-if="notification.type === 'success'"
                                @click="goToProfile"
                                type="button"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fas fa-user mr-2"></i>
                                Lihat Status Lamaran
                            </button>
                            <button
                                @click="closeNotification"
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors sm:mt-0 sm:w-auto sm:text-sm"
                            >
                                {{
                                    notification.type === "success"
                                        ? "Tutup"
                                        : "OK"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="min-h-screen bg-white/60 backdrop-blur-sm">
                <div
                    class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
                >
                    <!-- Back Navigation -->
                    <div class="mb-4 lg:mb-6">
                        <Link
                            href="/#divisions"
                            class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200"
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
                                    d="M15 19l-7-7 7-7"
                                ></path>
                            </svg>
                            <span class="text-sm font-medium"
                                >Kembali ke Daftar Divisi</span
                            >
                        </Link>
                    </div>

                    <!-- Header Section -->
                    <div
                        class="bg-blue-600 rounded-xl border border-blue-600 p-6 lg:p-8 mb-6 shadow-xl"
                    >
                        <div
                            class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6"
                        >
                            <div class="flex-1">
                                <h1
                                    class="text-2xl lg:text-3xl font-bold text-white mb-2"
                                >
                                    {{ division.name }}
                                </h1>
                                <p class="text-blue-100 mb-4">
                                    PT GrowithBI (Persero)
                                </p>

                                <!-- Location and Type Info -->
                                <div
                                    class="flex flex-wrap items-center gap-4 mb-4"
                                >
                                    <div
                                        class="flex items-center text-blue-100"
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
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                        </svg>
                                        <span class="text-sm"
                                            >Kota Bandung • Jakarta Pusat</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center text-blue-100"
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
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        <span class="text-sm">Onsite</span>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="flex flex-wrap items-center gap-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-blue-600"
                                    >
                                        Umum
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white"
                                    >
                                        {{ division.quota }} Posisi
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-700 text-white"
                                    >
                                        6 Bulan
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-blue-600"
                                    >
                                        Onsite
                                    </span>
                                </div>
                            </div>

                            <!-- Apply Button -->
                            <div class="lg:flex-shrink-0 lg:ml-6 mt-4 lg:mt-0">
                                <button
                                    @click="handleApply"
                                    :disabled="
                                        division.available_slots <= 0 ||
                                        isLoading
                                    "
                                    class="w-full lg:w-auto bg-white hover:bg-gray-100 disabled:bg-gray-300 text-blue-600 font-semibold px-8 py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl disabled:cursor-not-allowed disabled:shadow-none"
                                >
                                    <span
                                        v-if="!isLoading"
                                        class="flex items-center justify-center"
                                    >
                                        <span class="text-base">
                                            {{
                                                division.available_slots <= 0
                                                    ? "Kuota Penuh"
                                                    : "Daftar Sekarang"
                                            }}
                                        </span>
                                    </span>
                                    <span
                                        v-else
                                        class="flex items-center justify-center"
                                    >
                                        <svg
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600"
                                            xmlns="http://www.w3.org/2000/svg"
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
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Content Sections -->
                    <div class="w-full">
                        <!-- Tab Navigation -->
                        <div
                            class="glass rounded-lg border border-white/20 mb-6 shadow-lg overflow-hidden"
                        >
                            <div class="border-b border-white/20">
                                <nav class="flex" aria-label="Tabs">
                                    <button
                                        @click="activeTab = 'description'"
                                        :class="
                                            activeTab === 'description'
                                                ? 'border-blue-500 text-blue-600 bg-blue-50/50 backdrop-blur-sm'
                                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-white/30'
                                        "
                                        class="flex-1 py-3 px-6 border-b-2 font-medium text-sm lg:text-base transition-all duration-300 text-center"
                                    >
                                        Deskripsi Lowongan
                                    </button>
                                    <button
                                        @click="activeTab = 'requirements'"
                                        :class="
                                            activeTab === 'requirements'
                                                ? 'border-blue-500 text-blue-600 bg-blue-50/50 backdrop-blur-sm'
                                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-white/30'
                                        "
                                        class="flex-1 py-3 px-6 border-b-2 font-medium text-sm lg:text-base transition-all duration-300 text-center"
                                    >
                                        Persyaratan
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div
                            class="glass-strong rounded-lg border border-white/30 p-6 lg:p-8 shadow-lg"
                        >
                            <!-- Description Tab -->
                            <div
                                v-if="activeTab === 'description'"
                                class="animate-fadeIn"
                            >
                                <!-- Education Section -->
                                <div class="mb-8">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-4 flex items-center"
                                    >
                                        <div
                                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                ></path>
                                            </svg>
                                        </div>
                                        Pendidikan
                                    </h3>
                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100"
                                    >
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-3 gap-6"
                                        >
                                            <div class="text-center">
                                                <div
                                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"
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
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 mb-2"
                                                >
                                                    Jenjang Pendidikan
                                                </h4>
                                                <p
                                                    class="text-gray-700 font-medium"
                                                >
                                                    S1
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <div
                                                    class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3"
                                                >
                                                    <svg
                                                        class="w-6 h-6 text-green-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 mb-2"
                                                >
                                                    Jurusan
                                                </h4>
                                                <p
                                                    class="text-gray-700 text-sm leading-relaxed"
                                                >
                                                    Semua Jurusan, Manajemen
                                                    Bisnis, Manajemen SDM,
                                                    Administrasi, Psikologi
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <div
                                                    class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3"
                                                >
                                                    <svg
                                                        class="w-6 h-6 text-yellow-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 mb-2"
                                                >
                                                    IPK Minimal
                                                </h4>
                                                <p
                                                    class="text-gray-700 font-bold text-lg"
                                                >
                                                    3.0
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                <div class="mb-8">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-4 flex items-center"
                                    >
                                        <div
                                            class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                ></path>
                                            </svg>
                                        </div>
                                        Persyaratan Dokumen
                                    </h3>
                                    <div
                                        class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-100"
                                    >
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
                                        >
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"
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
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 text-sm"
                                                >
                                                    CV
                                                </h4>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3"
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
                                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 text-sm"
                                                >
                                                    KTP
                                                </h4>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3"
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
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 text-sm"
                                                >
                                                    Transkrip Nilai
                                                </h4>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center col-span-full sm:col-span-1"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3"
                                                >
                                                    <svg
                                                        class="w-5 h-5 text-yellow-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="font-semibold text-gray-900 text-sm"
                                                >
                                                    Ijazah/Surat Keterangan
                                                    Lulus
                                                </h4>
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    (untuk Fresh Graduate)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Section -->
                                <div class="mb-8">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-4 flex items-center"
                                    >
                                        <div
                                            class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-3"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        Tanggal Penting
                                    </h3>
                                    <div
                                        class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100"
                                    >
                                        <div class="space-y-4">
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between"
                                            >
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4"
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
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                            ></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="font-semibold text-gray-900"
                                                        >
                                                            Durasi Program
                                                        </h4>
                                                        <p
                                                            class="text-sm text-gray-600"
                                                        >
                                                            Lamanya program
                                                            magang
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="text-lg font-bold text-blue-600"
                                                        >6 bulan</span
                                                    >
                                                </div>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between"
                                            >
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4"
                                                    >
                                                        <svg
                                                            class="w-5 h-5 text-red-600"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3a4 4 0 118 0v4m-4 12v-2m0 0V9a2 2 0 012-2h6a2 2 0 012 2v3"
                                                            ></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="font-semibold text-gray-900"
                                                        >
                                                            Penutupan Lamaran
                                                        </h4>
                                                        <p
                                                            class="text-sm text-gray-600"
                                                        >
                                                            Batas akhir
                                                            pendaftaran
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="text-lg font-bold text-red-600"
                                                        >{{
                                                            division.application_deadline ||
                                                            "31 Agustus 2025"
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between"
                                            >
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4"
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
                                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                            ></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="font-semibold text-gray-900"
                                                        >
                                                            Pengumuman Hasil
                                                        </h4>
                                                        <p
                                                            class="text-sm text-gray-600"
                                                        >
                                                            Pengumuman lolos
                                                            seleksi
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="text-lg font-bold text-green-600"
                                                        >{{
                                                            division.selection_announcement ||
                                                            "31 Agustus 2025"
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Job Description Section -->
                                <div class="mb-8">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-4 flex items-center"
                                    >
                                        <div
                                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center mr-3"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                ></path>
                                            </svg>
                                        </div>
                                        Rincian Lowongan
                                    </h3>
                                    <div
                                        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-100"
                                    >
                                        <div
                                            class="bg-white p-6 rounded-lg shadow-sm border border-gray-100"
                                        >
                                            <h4
                                                class="text-lg font-bold text-indigo-900 mb-4 flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 text-indigo-600 mr-2"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"
                                                    ></path>
                                                </svg>
                                                Job Description
                                            </h4>
                                            <div class="space-y-4">
                                                <div
                                                    class="flex items-start p-4 bg-indigo-50 rounded-lg"
                                                >
                                                    <div
                                                        class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 mt-1"
                                                    >
                                                        1
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="text-gray-800 leading-relaxed"
                                                        >
                                                            Mendukung
                                                            implementasi
                                                            data-data yang ada
                                                            di
                                                            <strong
                                                                >People
                                                                Analytic</strong
                                                            >.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex items-start p-4 bg-indigo-50 rounded-lg"
                                                >
                                                    <div
                                                        class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 mt-1"
                                                    >
                                                        2
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="text-gray-800 leading-relaxed"
                                                        >
                                                            Membantu pembuatan
                                                            kertas kerja maupun
                                                            pengolahan data
                                                            secara sederhana
                                                            yang dibutuhkan.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Qualifications Section -->
                                <div class="mb-8">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-4 flex items-center"
                                    >
                                        <div
                                            class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center mr-3"
                                        >
                                            <svg
                                                class="w-4 h-4 text-white"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        Qualifications
                                    </h3>
                                    <div
                                        class="bg-gradient-to-r from-emerald-50 to-green-50 p-6 rounded-xl border border-emerald-100"
                                    >
                                        <div
                                            class="bg-white p-6 rounded-lg shadow-sm border border-gray-100"
                                        >
                                            <h4
                                                class="text-lg font-bold text-emerald-900 mb-4 flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 text-emerald-600 mr-2"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    ></path>
                                                </svg>
                                                Persyaratan Kandidat
                                            </h4>
                                            <div
                                                class="flex items-start p-4 bg-emerald-50 rounded-lg"
                                            >
                                                <div
                                                    class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 mt-1"
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
                                                            d="M5 13l4 4L19 7"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-gray-800 leading-relaxed"
                                                    >
                                                        Mahasiswa
                                                        <strong>S1</strong>
                                                        dalam bidang
                                                        <strong
                                                            >manajemen
                                                            bisnis</strong
                                                        >,
                                                        <strong
                                                            >manajemen
                                                            SDM</strong
                                                        >, dan
                                                        <strong
                                                            >administrasi</strong
                                                        >
                                                        atau jurusan lain yang
                                                        relevan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requirements Tab -->
                            <!-- Requirements Tab -->
                            <div
                                v-else-if="activeTab === 'requirements'"
                                class="animate-fadeIn"
                            >
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-4"
                                >
                                    Rincian Lowongan
                                </h3>

                                <div class="mb-6">
                                    <h4
                                        class="font-medium text-gray-900 mb-3 text-base"
                                    >
                                        Deskripsi Kerja :
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(
                                                desc, index
                                            ) in division.job_description"
                                            :key="desc"
                                            class="flex items-start"
                                        >
                                            <span
                                                class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-1"
                                            >
                                                {{ index + 1 }}
                                            </span>
                                            <p
                                                class="text-gray-700 leading-relaxed text-sm"
                                            >
                                                {{
                                                    desc.replace(
                                                        /^\d+\.\s*/,
                                                        ""
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <h4
                                        class="font-medium text-gray-900 mb-3 text-base"
                                    >
                                        Persyaratan :
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(
                                                requirement, index
                                            ) in division.requirements"
                                            :key="requirement"
                                            class="flex items-start"
                                        >
                                            <span
                                                class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-1"
                                            >
                                                {{ index + 1 }}
                                            </span>
                                            <p
                                                class="text-gray-700 leading-relaxed text-sm"
                                            >
                                                {{ requirement }}
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
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

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
const activeTab = ref("description");
const isLoading = ref(false);
const redirectCountdown = ref(0);
const countdownTimer = ref(null);
const notification = ref({
    show: false,
    type: "success", // 'success' or 'error'
    title: "",
    message: "",
});

// Methods
const checkAuthStatus = async () => {
    try {
        const response = await window.axios.get("/auth/user");
        console.log("Auth check successful:", response.data);
        return response.data;
    } catch (error) {
        console.log(
            "Auth check failed:",
            error.response?.status,
            error.response?.data
        );
        return null;
    }
};

const handleApply = async () => {
    console.log("=== HANDLE APPLY START ===");
    console.log("Division:", props.division);
    console.log("Auth user from props:", props.auth?.user);

    if (props.division.available_slots <= 0) {
        showNotification(
            "error",
            "Maaf",
            "Kuota untuk divisi ini sudah penuh."
        );
        return;
    }

    console.log("Starting authentication check...");
    isLoading.value = true;

    try {
        // First, check authentication by trying to get user data
        console.log("=== AUTHENTICATION CHECK ===");
        console.log("Checking authentication via web route...");
        console.log("Current cookies:", document.cookie);
        console.log("Axios default headers:", window.axios.defaults.headers);

        const userResponse = await window.axios.get("/auth/user");
        const user = userResponse.data;
        console.log("User authenticated successfully:", user);

        // Check if user has existing application for this division
        console.log("Checking existing application...");
        const existingApplicationResponse = await window.axios.get(
            `/applications/check/${props.division.id}`
        );
        console.log(
            "Existing application check:",
            existingApplicationResponse.data
        );

        if (existingApplicationResponse.data.hasApplication) {
            showNotification(
                "error",
                "❌ Lamaran Sudah Ada",
                "Anda sudah memiliki lamaran aktif untuk divisi ini. Silakan cek status lamaran di halaman profil Anda."
            );
            isLoading.value = false;
            return;
        }

        // Validate required documents
        console.log("Validating documents...");
        const requiredDocuments = [
            { field: "surat_pengantar_path", name: "Surat Pengantar" },
            { field: "cv_path", name: "Curriculum Vitae (CV)" },
            { field: "motivation_letter_path", name: "Motivation Letter" },
            { field: "transkrip_path", name: "Transkrip Nilai" },
            { field: "ktp_path", name: "KTP" },
            { field: "buku_rekening_path", name: "Buku Rekening Tabungan" },
            { field: "pas_foto_path", name: "Pas Foto 3x4 atau 4x6" },
        ];

        const missingDocuments = requiredDocuments.filter(
            (doc) => !user[doc.field]
        );
        console.log("Missing documents:", missingDocuments);

        if (missingDocuments.length > 0) {
            const missingNames = missingDocuments
                .map((doc) => doc.name)
                .join(", ");
            showNotification(
                "error",
                "Dokumen Belum Lengkap",
                `Silakan lengkapi dokumen berikut terlebih dahulu: ${missingNames}`
            );
            isLoading.value = false;

            // Show popup for 5 seconds before allowing any action
            console.log("Showing document validation error for 5 seconds...");
            setTimeout(() => {
                console.log("Document error timeout completed");
            }, 5000);

            return;
        }

        // Validate profile completion
        console.log("Validating profile...");
        const requiredProfile = [
            { field: "name", name: "Nama Lengkap" },
            { field: "phone", name: "Nomor Telepon" },
            { field: "address", name: "Alamat" },
            { field: "university", name: "Universitas" },
            { field: "major", name: "Jurusan" },
        ];

        const missingProfile = requiredProfile.filter(
            (field) => !user[field.field]
        );
        console.log("Missing profile:", missingProfile);

        if (missingProfile.length > 0) {
            const missingProfileNames = missingProfile
                .map((field) => field.name)
                .join(", ");
            showNotification(
                "error",
                "Profil Belum Lengkap",
                `Silakan lengkapi data profil berikut: ${missingProfileNames}. Anda akan diarahkan ke halaman profil dalam 3 detik.`
            );
            setTimeout(() => {
                // Use Inertia.js navigation instead of window.location
                window.location.href = `/profile/edit?division_id=${
                    props.division.id
                }&missing_fields=${missingProfile
                    .map((f) => f.field)
                    .join(",")}`;
            }, 3000);
            isLoading.value = false;
            return;
        }

        // All requirements met, submit application automatically
        console.log("All requirements met, submitting application...");
        const applicationResponse = await window.axios.post(
            "/profile/create-application",
            {
                division_id: props.division.id,
                motivation: `Saya tertarik bergabung dengan program magang di divisi ${props.division.name}. Saya berkomitmen untuk memberikan kontribusi terbaik dan belajar secara maksimal selama program magang berlangsung.`,
            }
        );

        console.log(
            "Application submitted successfully:",
            applicationResponse.data
        );
        showNotification(
            "success",
            "🎉 Lamaran Berhasil Dikirim!",
            `Selamat! Lamaran Anda untuk posisi magang di divisi ${props.division.name} telah berhasil dikirim. Tim HRD akan menghubungi Anda segera melalui email atau telepon. Anda dapat memantau status lamaran di halaman profil.`
        );
    } catch (error) {
        console.error("=== APPLICATION ERROR ===");
        console.error("Error details:", error);
        console.error("Response status:", error.response?.status);
        console.error("Response data:", error.response?.data);
        console.error("Request config:", error.config);

        // Handle authentication errors first (401 Unauthorized)
        if (error.response?.status === 401) {
            console.log("Authentication error - user not logged in");
            showNotification(
                "error",
                "🔐 Login Diperlukan",
                "Silakan login terlebih dahulu untuk mendaftar magang."
            );
            setTimeout(() => {
                window.location.href = `/login?redirect=/divisi/${props.division.id}`;
            }, 2000);
        } else if (error.response?.status === 422) {
            // Validation error or duplicate application
            const errorData = error.response.data;
            let errorMsg = "Ada kesalahan validasi data.";

            if (errorData.message) {
                errorMsg = errorData.message;

                // Handle specific duplicate application error
                if (errorMsg.includes("sudah memiliki lamaran aktif")) {
                    showNotification(
                        "error",
                        "❌ Lamaran Sudah Ada",
                        "Anda sudah memiliki lamaran aktif untuk divisi ini. Silakan cek status lamaran di halaman profil Anda."
                    );
                } else {
                    showNotification(
                        "error",
                        "❌ Gagal Mengirim Lamaran",
                        errorMsg
                    );
                }
            } else {
                showNotification(
                    "error",
                    "❌ Gagal Mengirim Lamaran",
                    errorMsg
                );
            }
        } else if (error.response?.status === 429) {
            // Too many requests
            showNotification(
                "error",
                "⏳ Terlalu Banyak Percobaan",
                "Anda telah mencoba terlalu banyak kali. Silakan tunggu beberapa menit sebelum mencoba lagi."
            );
        } else if (error.response?.status === 500) {
            // Server error
            showNotification(
                "error",
                "🔧 Gangguan Server",
                "Terjadi gangguan pada server. Tim teknis kami sedang memperbaikinya. Silakan coba lagi dalam beberapa menit."
            );
        } else {
            showNotification(
                "error",
                "❗ Terjadi Kesalahan",
                "Terjadi kesalahan yang tidak terduga. Silakan periksa koneksi internet Anda dan coba lagi."
            );
        }
    } finally {
        isLoading.value = false;
    }
};

const showNotification = (type, title, message) => {
    // Clear any existing timer
    if (countdownTimer.value) {
        clearInterval(countdownTimer.value);
        countdownTimer.value = null;
    }

    notification.value = {
        show: true,
        type,
        title,
        message,
    };

    // Start countdown for success notifications
    if (type === "success") {
        redirectCountdown.value = 3;
        countdownTimer.value = setInterval(() => {
            redirectCountdown.value--;
            if (redirectCountdown.value <= 0) {
                clearInterval(countdownTimer.value);
                goToProfile();
            }
        }, 1000);
    }
};

const closeNotification = () => {
    if (countdownTimer.value) {
        clearInterval(countdownTimer.value);
        countdownTimer.value = null;
    }
    redirectCountdown.value = 0;
    notification.value.show = false;
};

const goToProfile = () => {
    closeNotification();
    window.location.href = "/profile?from=application&success=application";
};
</script>

<style scoped>
@keyframes bounce-in {
    0% {
        transform: scale(0.3) translateY(-50px);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    70% {
        transform: scale(0.9);
        opacity: 0.9;
    }
    100% {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

.animate-bounce-in {
    animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Custom progress bar animation */
.progress-bar {
    transition: width 1s linear;
}
</style>
