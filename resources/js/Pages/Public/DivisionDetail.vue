<template>
    <Head :title="division.name" />
    <GuestLayout variant="public">
        <!-- Modal Notification Overlay -->
        <div
            v-if="notification.show"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="
                                    notification.type === 'success'
                                        ? 'bg-green-100'
                                        : 'bg-red-100'
                                "
                            >
                                <svg
                                    v-if="notification.type === 'success'"
                                    class="h-6 w-6 text-green-600"
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
                                    class="h-6 w-6 text-red-600"
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
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{ notification.title }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ notification.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            @click="closeNotification"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            :class="
                                notification.type === 'success'
                                    ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                                    : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                            "
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content with Optimized Size -->
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
                <!-- Compact Back Navigation -->
                <div class="mb-6">
                    <Link
                        href="/divisions"
                        class="group inline-flex items-center text-gray-600 hover:text-blue-600 transition-all duration-200 bg-white px-3 py-2 rounded-lg shadow-sm hover:shadow-md"
                    >
                        <svg
                            class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200"
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
                        <span class="text-sm font-semibold"
                            >Kembali ke Daftar Divisi</span
                        >
                    </Link>
                </div>

                <!-- Compact Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Compact Left Sidebar -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-lg p-6 sticky top-4"
                        >
                            <!-- Compact Company Logo and Info -->
                            <div class="text-center mb-6">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg"
                                    style="
                                        background: linear-gradient(
                                            135deg,
                                            #002f6c 0%,
                                            #1e40af 100%
                                        );
                                    "
                                >
                                    <img
                                        src="/logo2.png"
                                        alt="Bank Indonesia Logo"
                                        class="w-10 h-10 object-contain"
                                    />
                                </div>
                                <h3
                                    class="text-base font-bold text-gray-900 mb-1"
                                    style="color: #002f6c"
                                >
                                    Bank Indonesia
                                </h3>
                                <p class="text-xs text-gray-600">
                                    Bank Sentral RI
                                </p>
                            </div>

                            <!-- Compact Job Title Section -->
                            <div class="mb-6">
                                <h1
                                    class="text-xl font-bold text-gray-900 mb-3 leading-tight"
                                    style="color: #002f6c"
                                >
                                    Program Magang {{ division.name }}
                                </h1>

                                <!-- Compact Location -->
                                <div
                                    class="flex items-center text-gray-600 mb-4 bg-gray-50 p-3 rounded-xl"
                                >
                                    <div
                                        class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center mr-2"
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
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium"
                                        >Bandar Lampung, Indonesia</span
                                    >
                                </div>

                                <!-- Quick Program Info -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6"
                                >
                                    <h4
                                        class="text-sm font-bold text-blue-800 mb-3"
                                    >
                                        Info Program
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span class="text-xs text-gray-600"
                                                >Durasi</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-blue-700"
                                                >6 bulan</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span class="text-xs text-gray-600"
                                                >Mode</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-blue-700"
                                                >Onsite</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Apply Button -->
                            <button
                                v-if="!auth.user"
                                @click="handleApply"
                                :disabled="
                                    division.available_slots <= 0 || isLoading
                                "
                                class="w-full group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold text-base px-5 py-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed overflow-hidden mb-4"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #002f6c 0%,
                                        #1e40af 100%
                                    );
                                "
                            >
                                <!-- Gradient overlay on hover -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                ></div>

                                <span
                                    v-if="!isLoading"
                                    class="relative flex items-center justify-center space-x-2"
                                >
                                    <svg
                                        class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        ></path>
                                    </svg>
                                    <span>{{
                                        division.available_slots <= 0
                                            ? "Kuota Penuh"
                                            : "Daftar Sekarang"
                                    }}</span>
                                </span>
                                <span
                                    v-else
                                    class="relative flex items-center justify-center space-x-2"
                                >
                                    <svg
                                        class="animate-spin h-5 w-5 text-white"
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
                                    <span>Processing...</span>
                                </span>
                            </button>

                            <div
                                v-else
                                class="text-center bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-4 border border-green-200 shadow-lg mb-4"
                            >
                                <div
                                    class="w-12 h-12 bg-green-500 rounded-full mx-auto mb-3 flex items-center justify-center shadow-lg"
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
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                </div>
                                <p class="text-gray-600 mb-3 text-sm">
                                    Login sebagai
                                    <span class="font-bold text-gray-900">{{
                                        auth.user.name
                                    }}</span>
                                </p>
                                <Link
                                    href="/dashboard"
                                    class="inline-flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm"
                                >
                                    <span>Dashboard</span>
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
                                            d="M13 7l5 5-5 5M6 12h12"
                                        />
                                    </svg>
                                </Link>
                            </div>

                            <!-- Compact Action Buttons -->
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    class="group bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 text-gray-700 hover:text-blue-700 font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-sm"
                                >
                                    <div
                                        class="flex items-center justify-center space-x-1"
                                    >
                                        <svg
                                            class="w-4 h-4 group-hover:scale-110 transition-transform duration-200"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                            />
                                        </svg>
                                        <span>Bagikan</span>
                                    </div>
                                </button>
                                <button
                                    class="group bg-gray-50 hover:bg-red-50 border border-gray-200 hover:border-red-200 text-gray-700 hover:text-red-700 font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-sm"
                                >
                                    <div
                                        class="flex items-center justify-center space-x-1"
                                    >
                                        <svg
                                            class="w-4 h-4 group-hover:scale-110 transition-transform duration-200"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                                            />
                                        </svg>
                                        <span>Simpan</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content Area - Compact -->
                    <div class="lg:col-span-3">
                        <!-- Compact Tab Navigation -->
                        <div
                            class="bg-white rounded-t-2xl border border-gray-100 shadow-lg overflow-hidden"
                        >
                            <div class="flex border-b border-gray-100">
                                <button
                                    @click="activeTab = 'description'"
                                    :class="[
                                        'flex-1 px-6 py-4 text-sm font-bold transition-all duration-300 relative',
                                        activeTab === 'description'
                                            ? 'text-blue-600 bg-blue-50'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
                                    ]"
                                >
                                    <div
                                        class="flex items-center justify-center space-x-2"
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
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                            />
                                        </svg>
                                        <span>Deskripsi Lowongan</span>
                                    </div>
                                    <div
                                        v-if="activeTab === 'description'"
                                        class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"
                                    ></div>
                                </button>
                                <button
                                    @click="activeTab = 'company'"
                                    :class="[
                                        'flex-1 px-6 py-4 text-sm font-bold transition-all duration-300 relative',
                                        activeTab === 'company'
                                            ? 'text-blue-600 bg-blue-50'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
                                    ]"
                                >
                                    <div
                                        class="flex items-center justify-center space-x-2"
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
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            />
                                        </svg>
                                        <span>Perusahaan</span>
                                    </div>
                                    <div
                                        v-if="activeTab === 'company'"
                                        class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"
                                    ></div>
                                </button>
                            </div>
                        </div>

                        <!-- Compact Tab Content -->
                        <div
                            class="bg-white rounded-b-2xl border border-gray-100 border-t-0 shadow-lg"
                        >
                            <!-- Description Tab -->
                            <div v-if="activeTab === 'description'" class="p-6">
                                <!-- Compact Job Description -->
                                <div class="mb-8">
                                    <div
                                        class="flex items-center space-x-3 mb-6"
                                    >
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg"
                                            style="
                                                background: linear-gradient(
                                                    135deg,
                                                    #002f6c 0%,
                                                    #1e40af 100%
                                                );
                                            "
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
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                            style="color: #002f6c"
                                        >
                                            Deskripsi Pekerjaan
                                        </h3>
                                    </div>
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-xl p-6 border border-gray-100"
                                    >
                                        <p
                                            class="text-gray-700 leading-relaxed mb-6 text-base"
                                        >
                                            {{ division.description }}
                                        </p>
                                        <div class="space-y-4">
                                            <h4
                                                class="text-base font-bold text-gray-800"
                                            >
                                                Tugas dan Tanggung Jawab:
                                            </h4>
                                            <div class="space-y-3">
                                                <div
                                                    v-for="(
                                                        desc, index
                                                    ) in division.job_description"
                                                    :key="index"
                                                    class="flex items-start gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300"
                                                >
                                                    <div
                                                        class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5 flex-shrink-0"
                                                    >
                                                        {{ index + 1 }}
                                                    </div>
                                                    <p
                                                        class="text-gray-700 text-sm leading-relaxed flex-1"
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
                                    </div>
                                </div>

                                <!-- Compact Requirements Section -->
                                <div class="mb-8">
                                    <div
                                        class="flex items-center space-x-3 mb-6"
                                    >
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg"
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
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                            style="color: #002f6c"
                                        >
                                            Persyaratan Dokumen
                                        </h3>
                                    </div>
                                    <div
                                        class="bg-white rounded-xl border border-gray-200 p-6"
                                    >
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-3 gap-8"
                                        >
                                            <!-- Portfolio -->
                                            <div>
                                                <h4
                                                    class="text-gray-700 font-medium text-sm mb-3"
                                                >
                                                    Portfolio
                                                </h4>
                                                <ul
                                                    class="space-y-2 text-sm text-gray-600"
                                                >
                                                    <li>• KTP</li>
                                                    <li>• KTM</li>
                                                </ul>
                                            </div>

                                            <!-- Dokumen Lainnya -->
                                            <div>
                                                <h4
                                                    class="text-gray-700 font-medium text-sm mb-3"
                                                >
                                                    Dokumen Lainnya
                                                </h4>
                                                <ul
                                                    class="space-y-2 text-sm text-gray-600"
                                                >
                                                    <li>• Sertifikat</li>
                                                    <li>
                                                        • Ijazah/Surat
                                                        Keterangan Lulus (bagi
                                                        Fresh Graduate)
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- CV & Transkrip -->
                                            <div>
                                                <h4
                                                    class="text-gray-700 font-medium text-sm mb-3"
                                                >
                                                    CV
                                                </h4>
                                                <ul
                                                    class="space-y-2 text-sm text-gray-600"
                                                >
                                                    <li>• Transkrip Nilai</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pendidikan -->
                                <div class="mb-8">
                                    <div
                                        class="flex items-center space-x-3 mb-6"
                                    >
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg"
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
                                                    d="M12 14l9-5-9-5-9 5 9 5z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                            style="color: #002f6c"
                                        >
                                            Pendidikan
                                        </h3>
                                    </div>
                                    <div
                                        class="bg-white rounded-xl border border-gray-200 p-6"
                                    >
                                        <div class="space-y-3">
                                            <div>
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >Jenjang pendidikan:
                                                </span>
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >S1.</span
                                                >
                                            </div>
                                            <div>
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >Jurusan:
                                                </span>
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >Sistem Informatika,
                                                    Teknologi Informasi.</span
                                                >
                                            </div>
                                            <div>
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >IPK minimal:
                                                </span>
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >3.1.</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Compact Important Dates -->
                                <div class="mb-8">
                                    <div
                                        class="flex items-center space-x-3 mb-6"
                                    >
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
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
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-xl font-bold text-gray-900"
                                            style="color: #002f6c"
                                        >
                                            Tanggal penting
                                        </h3>
                                    </div>
                                    <div
                                        class="bg-white rounded-xl border border-gray-200 p-6"
                                    >
                                        <div class="space-y-3">
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >Durasi</span
                                                >
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >: 6 bulan</span
                                                >
                                            </div>
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >Penutupan lamaran</span
                                                >
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >: 15 September 2025</span
                                                >
                                            </div>
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-gray-700 text-sm"
                                                    >Pengumuman lolos
                                                    seleksi</span
                                                >
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >: 5 Oktober 2025</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Company Tab -->
                            <div v-if="activeTab === 'company'" class="p-6">
                                <div class="space-y-6">
                                    <!-- Compact Company Overview -->
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-2xl p-6"
                                    >
                                        <div
                                            class="flex items-center space-x-3 mb-4"
                                        >
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg"
                                                style="
                                                    background: linear-gradient(
                                                        135deg,
                                                        #002f6c 0%,
                                                        #1e40af 100%
                                                    );
                                                "
                                            >
                                                <img
                                                    src="/logo2.png"
                                                    alt="Bank Indonesia"
                                                    class="w-6 h-6 object-contain"
                                                />
                                            </div>
                                            <div>
                                                <h3
                                                    class="text-xl font-bold text-blue-900"
                                                    style="color: #002f6c"
                                                >
                                                    Bank Indonesia
                                                </h3>
                                                <p
                                                    class="text-blue-700 font-semibold text-xs"
                                                >
                                                    Bank Sentral Republik
                                                    Indonesia
                                                </p>
                                            </div>
                                        </div>
                                        <p
                                            class="text-gray-700 leading-relaxed text-sm"
                                        >
                                            Bank Indonesia adalah bank sentral
                                            Republik Indonesia yang berkomitmen
                                            untuk mencapai dan memelihara
                                            kestabilan nilai rupiah melalui
                                            pemeliharaan kestabilan moneter dan
                                            pengembangan stabilitas sistem
                                            keuangan. Kami menyediakan program
                                            magang berkualitas tinggi yang
                                            memberikan pengalaman kerja nyata di
                                            bidang perbankan dan ekonomi
                                            nasional.
                                        </p>
                                    </div>

                                    <!-- Compact Mission & Vision Grid -->
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- Vision -->
                                        <div
                                            class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300"
                                        >
                                            <div
                                                class="flex items-center space-x-2 mb-4"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center shadow-lg"
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
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="text-xl font-bold text-green-800"
                                                >
                                                    Visi
                                                </h4>
                                            </div>
                                            <p
                                                class="text-gray-700 leading-relaxed font-medium text-sm"
                                            >
                                                Menjadi bank sentral yang
                                                kredibel dan efektif dalam
                                                mencapai dan memelihara
                                                kestabilan nilai rupiah guna
                                                mendukung pertumbuhan ekonomi
                                                yang berkelanjutan.
                                            </p>
                                        </div>

                                        <!-- Mission -->
                                        <div
                                            class="bg-gradient-to-br from-purple-50 to-violet-100 border border-purple-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300"
                                        >
                                            <div
                                                class="flex items-center space-x-2 mb-4"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg"
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
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                </div>
                                                <h4
                                                    class="text-xl font-bold text-purple-800"
                                                >
                                                    Misi
                                                </h4>
                                            </div>
                                            <ul class="space-y-2 text-gray-700">
                                                <li
                                                    class="flex items-start space-x-2"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"
                                                    ></div>
                                                    <span
                                                        class="font-medium text-sm"
                                                        >Mencapai dan memelihara
                                                        kestabilan nilai
                                                        rupiah</span
                                                    >
                                                </li>
                                                <li
                                                    class="flex items-start space-x-2"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"
                                                    ></div>
                                                    <span
                                                        class="font-medium text-sm"
                                                        >Mendorong kelancaran
                                                        sistem pembayaran</span
                                                    >
                                                </li>
                                                <li
                                                    class="flex items-start space-x-2"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"
                                                    ></div>
                                                    <span
                                                        class="font-medium text-sm"
                                                        >Mengatur dan mengawasi
                                                        bank</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Compact Key Highlights -->
                                    <div
                                        class="bg-gradient-to-br from-orange-50 to-red-100 border border-orange-200 rounded-xl p-6"
                                    >
                                        <div
                                            class="flex items-center space-x-3 mb-6"
                                        >
                                            <div
                                                class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg"
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
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                    />
                                                </svg>
                                            </div>
                                            <h4
                                                class="text-xl font-bold text-orange-800"
                                            >
                                                Keunggulan Program Magang
                                            </h4>
                                        </div>
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                        >
                                            <div
                                                class="bg-white p-4 rounded-lg border border-orange-200"
                                            >
                                                <h5
                                                    class="font-bold text-orange-800 mb-2 text-sm"
                                                >
                                                    💼 Pengalaman Profesional
                                                </h5>
                                                <p
                                                    class="text-gray-700 text-sm"
                                                >
                                                    Bekerja langsung dengan tim
                                                    profesional di bidang
                                                    ekonomi dan perbankan
                                                </p>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg border border-orange-200"
                                            >
                                                <h5
                                                    class="font-bold text-orange-800 mb-2 text-sm"
                                                >
                                                    🎓 Pengembangan Skill
                                                </h5>
                                                <p
                                                    class="text-gray-700 text-sm"
                                                >
                                                    Mengembangkan kemampuan
                                                    analisis data dan riset
                                                    ekonomi terkini
                                                </p>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg border border-orange-200"
                                            >
                                                <h5
                                                    class="font-bold text-orange-800 mb-2 text-sm"
                                                >
                                                    🌟 Networking
                                                </h5>
                                                <p
                                                    class="text-gray-700 text-sm"
                                                >
                                                    Membangun jaringan
                                                    profesional dengan ahli
                                                    ekonomi dan perbankan
                                                </p>
                                            </div>
                                            <div
                                                class="bg-white p-4 rounded-lg border border-orange-200"
                                            >
                                                <h5
                                                    class="font-bold text-orange-800 mb-2 text-sm"
                                                >
                                                    📈 Karir Prospektif
                                                </h5>
                                                <p
                                                    class="text-gray-700 text-sm"
                                                >
                                                    Peluang karir yang baik di
                                                    sektor perbankan dan ekonomi
                                                    nasional
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
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

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
const activeTab = ref("description");
const notification = ref({
    show: false,
    type: "success", // 'success' or 'error'
    title: "",
    message: "",
});

// Methods
const handleApply = async () => {
    if (props.division.available_slots <= 0) {
        showNotification(
            "error",
            "Maaf",
            "Kuota untuk divisi ini sudah penuh."
        );
        return;
    }

    isLoading.value = true;

    try {
        // Simulate API call delay
        await new Promise((resolve) => setTimeout(resolve, 1000));

        // For now, just redirect to application form
        window.location.href = `/divisions/${props.division.id}/apply`;
    } catch (error) {
        showNotification(
            "error",
            "Error",
            "Terjadi kesalahan saat memproses lamaran Anda."
        );
    } finally {
        isLoading.value = false;
    }
};

const showNotification = (type, title, message) => {
    notification.value = {
        show: true,
        type,
        title,
        message,
    };
};

const closeNotification = () => {
    notification.value.show = false;
};
</script>
