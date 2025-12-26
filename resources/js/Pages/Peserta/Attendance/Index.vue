<template>
    <Head title="Absensi Online" />

    <!-- Header dengan Gradient Biru -->
    <div class="bg-[#2F4C9E] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-4 flex items-center space-x-2 text-blue-100">
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
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                </svg>
                <Link
                    :href="route('profile.edit')"
                    class="hover:text-white transition-colors cursor-pointer"
                >
                    Dashboard
                </Link>
                <span>›</span>
                <span class="text-white font-medium">Absensi Online</span>
            </nav>

            <!-- Title dengan Ikon -->
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <svg
                        class="w-8 h-8"
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
                <div>
                    <h1 class="text-3xl font-bold">Absensi Online</h1>
                    <p class="text-blue-100 mt-1">
                        Kelola kehadiran Anda dengan mudah
                    </p>
                </div>
            </div>

            <!-- Real-time Clock -->
            <div
                class="mt-4 inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg"
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
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                </svg>
                <span class="font-medium">{{ currentTime }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform translate-x-full opacity-0"
                enter-to-class="transform translate-x-0 opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform translate-x-0 opacity-100"
                leave-to-class="transform translate-x-full opacity-0"
            >
                <div
                    v-if="$page.props.flash.success && showSuccessToast"
                    class="fixed top-4 right-4 z-50 max-w-md"
                >
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-green-200 p-4 flex items-start space-x-3"
                    >
                        <div class="flex-shrink-0">
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Berhasil!
                            </p>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $page.props.flash.success }}
                            </p>
                        </div>
                        <button
                            @click="showSuccessToast = false"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600"
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
            </transition>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform translate-x-full opacity-0"
                enter-to-class="transform translate-x-0 opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform translate-x-0 opacity-100"
                leave-to-class="transform translate-x-full opacity-0"
            >
                <div
                    v-if="$page.props.flash.error && showErrorToast"
                    class="fixed top-4 right-4 z-50 max-w-md"
                >
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-red-200 p-4 flex items-start space-x-3"
                    >
                        <div class="flex-shrink-0">
                            <svg
                                class="w-6 h-6 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Gagal!
                            </p>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $page.props.flash.error }}
                            </p>
                        </div>
                        <button
                            @click="showErrorToast = false"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600"
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
            </transition>

            <!-- Status Hari Ini Card -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
                <div class="bg-[#2F4C9E] px-6 py-4 border-b border-[#274089]">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg
                            class="w-6 h-6 mr-2 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                            />
                        </svg>
                        Status Hari Ini
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Check-in Status -->
                        <div
                            class="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200 transition-all duration-300 hover:shadow-lg"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-2"
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
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                            />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-green-800"
                                            >Check-in</span
                                        >
                                    </div>
                                    <div
                                        class="text-3xl font-bold transition-colors duration-300"
                                        :class="
                                            todayAttendance?.check_in
                                                ? 'text-green-700'
                                                : 'text-gray-400'
                                        "
                                    >
                                        {{
                                            todayAttendance?.check_in
                                                ? formatTime(
                                                      todayAttendance.check_in
                                                  )
                                                : "--:--"
                                        }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        v-if="todayAttendance?.check_in"
                                        class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center"
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
                                    <div
                                        v-else
                                        class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6 text-gray-500"
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
                        </div>

                        <!-- Check-out Status -->
                        <div
                            class="relative bg-gradient-to-br from-[#E8EEF7] to-[#D6E3F5] rounded-xl p-6 border-2 border-[#B4C7E7] transition-all duration-300 hover:shadow-lg"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-2"
                                    >
                                        <svg
                                            class="w-5 h-5 text-[#2F4C9E]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-[#2F4C9E]"
                                            >Check-out</span
                                        >
                                    </div>
                                    <div
                                        class="text-3xl font-bold transition-colors duration-300"
                                        :class="
                                            todayAttendance?.check_out
                                                ? 'text-[#2F4C9E]'
                                                : 'text-gray-400'
                                        "
                                    >
                                        {{
                                            todayAttendance?.check_out
                                                ? formatTime(
                                                      todayAttendance.check_out
                                                  )
                                                : "--:--"
                                        }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        v-if="todayAttendance?.check_out"
                                        class="w-12 h-12 bg-[#2F4C9E] rounded-full flex items-center justify-center"
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
                                    <div
                                        v-else
                                        class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6 text-gray-500"
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
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Check-in Button -->
                        <div>
                            <button
                                @click="handleCheckIn"
                                :disabled="
                                    isProcessing ||
                                    (todayAttendance &&
                                        todayAttendance.check_in)
                                "
                                class="w-full group relative px-8 py-4 rounded-xl font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                                :class="
                                    todayAttendance?.check_in
                                        ? 'bg-gray-400'
                                        : 'bg-[#2F4C9E] hover:bg-[#274089]'
                                "
                            >
                                <svg
                                    v-if="
                                        isProcessing &&
                                        actionType === 'check-in'
                                    "
                                    class="animate-spin h-5 w-5 mr-2"
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
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                    />
                                </svg>
                                <span>
                                    {{
                                        isProcessing &&
                                        actionType === "check-in"
                                            ? "Memproses..."
                                            : todayAttendance?.check_in
                                            ? "Sudah Check-in"
                                            : "Check-in Sekarang"
                                    }}
                                </span>
                            </button>
                            <!-- Warning for check-in time -->
                            <div
                                v-if="
                                    !todayAttendance?.check_in &&
                                    !isWithinCheckInTime
                                "
                                class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg"
                            >
                                <p class="text-xs text-yellow-800 text-center">
                                    ⚠️ Check-in hanya diperbolehkan antara 07:30
                                    - 08:00 WIB
                                </p>
                            </div>
                        </div>

                        <!-- Check-out Button -->
                        <div>
                            <button
                                @click="handleCheckOut"
                                :disabled="
                                    isProcessing ||
                                    !todayAttendance?.check_in ||
                                    todayAttendance?.check_out
                                "
                                class="w-full group relative px-8 py-4 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                                :class="
                                    todayAttendance?.check_out
                                        ? 'bg-gray-400 text-white'
                                        : 'bg-[#DAE3F3] hover:bg-[#C5D9F1] text-[#2F4C9E]'
                                "
                            >
                                <svg
                                    v-if="
                                        isProcessing &&
                                        actionType === 'check-out'
                                    "
                                    class="animate-spin h-5 w-5 mr-2"
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
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                <span>
                                    {{
                                        isProcessing &&
                                        actionType === "check-out"
                                            ? "Memproses..."
                                            : todayAttendance?.check_out
                                            ? "Sudah Check-out"
                                            : "Check-out Sekarang"
                                    }}
                                </span>
                            </button>
                            <!-- Warning for check-out time -->
                            <div
                                v-if="
                                    todayAttendance?.check_in &&
                                    !todayAttendance?.check_out &&
                                    !isWithinCheckOutTime
                                "
                                class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg"
                            >
                                <p class="text-xs text-yellow-800 text-center">
                                    ⚠️ Check-out hanya diperbolehkan antara
                                    16:00 - 18:00 WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Absensi Card -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="bg-[#2F4C9E] px-6 py-4 border-b border-[#274089]">
                    <div class="flex items-center justify-between">
                        <h2
                            class="text-xl font-bold text-white flex items-center"
                        >
                            <svg
                                class="w-6 h-6 mr-2 text-white"
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
                            Riwayat Absensi
                        </h2>

                        <!-- Filter Status -->
                        <select
                            v-model="filterStatus"
                            class="px-4 py-2 border border-white/30 rounded-lg text-sm focus:ring-2 focus:ring-white/50 focus:border-white bg-white/10 text-white backdrop-blur-sm"
                        >
                            <option value="all" class="text-gray-900">
                                Semua Status
                            </option>
                            <option value="On-Time" class="text-gray-900">
                                Tepat Waktu
                            </option>
                            <option value="Late" class="text-gray-900">
                                Terlambat
                            </option>
                        </select>
                    </div>

                    <!-- Summary Stats -->
                    <div
                        v-if="monthlyStats"
                        class="mt-4 flex items-center space-x-6 text-sm"
                    >
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-white rounded-full"></div>
                            <span class="text-white"
                                >Total Hadir:
                                <span class="font-bold">{{
                                    monthlyStats.total
                                }}</span>
                                hari</span
                            >
                        </div>
                        <div class="flex items-center space-x-2">
                            <div
                                class="w-3 h-3 bg-green-300 rounded-full"
                            ></div>
                            <span class="text-white"
                                >Tepat Waktu:
                                <span class="font-bold">{{
                                    monthlyStats.onTime
                                }}</span></span
                            >
                        </div>
                        <div class="flex items-center space-x-2">
                            <div
                                class="w-3 h-3 bg-yellow-300 rounded-full"
                            ></div>
                            <span class="text-white"
                                >Terlambat:
                                <span class="font-bold">{{
                                    monthlyStats.late
                                }}</span></span
                            >
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div
                        v-if="
                            paginatedAttendance &&
                            paginatedAttendance.length > 0
                        "
                        class="space-y-3"
                    >
                        <!-- Modern Card List -->
                        <div
                            v-for="att in paginatedAttendance"
                            :key="att.id"
                            class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-200 hover:border-[#2F4C9E] hover:shadow-md transition-all duration-300"
                        >
                            <div class="flex items-center justify-between">
                                <!-- Date -->
                                <div class="flex items-center space-x-3 flex-1">
                                    <div class="bg-[#E8EEF7] p-3 rounded-lg">
                                        <svg
                                            class="w-5 h-5 text-[#2F4C9E]"
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
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ formatDate(att.date) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatDayName(att.date) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Check-in -->
                                <div class="flex items-center space-x-2 flex-1">
                                    <svg
                                        class="w-4 h-4 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Masuk
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            {{
                                                att.check_in
                                                    ? formatTime(att.check_in)
                                                    : "-"
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Check-out -->
                                <div class="flex items-center space-x-2 flex-1">
                                    <svg
                                        class="w-4 h-4 text-[#2F4C9E]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Pulang
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            {{
                                                att.check_out
                                                    ? formatTime(att.check_out)
                                                    : "-"
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex-shrink-0">
                                    <span
                                        v-if="att.status === 'On-Time'"
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Tepat Waktu
                                    </span>
                                    <span
                                        v-else-if="att.status === 'Late'"
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Terlambat
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200"
                                    >
                                        {{ att.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12">
                        <svg
                            class="w-16 h-16 text-gray-300 mx-auto mb-4"
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
                        <p class="text-gray-500 font-medium">
                            Belum ada riwayat absensi
                        </p>
                        <p class="text-gray-400 text-sm mt-2">
                            Lakukan check-in untuk memulai
                        </p>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="filteredAttendance.length > perPage"
                        class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4"
                    >
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ (currentPage - 1) * perPage + 1 }} -
                            {{
                                Math.min(
                                    currentPage * perPage,
                                    filteredAttendance.length
                                )
                            }}
                            dari {{ filteredAttendance.length }} data
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="currentPage--"
                                :disabled="currentPage === 1"
                                class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#E8EEF7] hover:border-[#2F4C9E] transition-colors"
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
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                            </button>

                            <div class="flex items-center space-x-1">
                                <button
                                    v-for="page in totalPages"
                                    :key="page"
                                    @click="currentPage = page"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                                    :class="
                                        page === currentPage
                                            ? 'bg-[#2F4C9E] text-white'
                                            : 'border border-gray-300 hover:bg-[#E8EEF7] hover:border-[#2F4C9E]'
                                    "
                                >
                                    {{ page }}
                                </button>
                            </div>

                            <button
                                @click="currentPage++"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#E8EEF7] hover:border-[#2F4C9E] transition-colors"
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
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <SimpleCameraModal
        :show="showCamera"
        :title="cameraTitle"
        @close="showCamera = false"
        @photo-captured="onPhotoCaptured"
    />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import SimpleCameraModal from "@/Components/SimpleCameraModal.vue";
import axios from "axios";

const props = defineProps({
    attendances: {
        type: Array,
        default: () => [],
    },
    attendanceHistory: {
        type: Array,
        default: () => [],
    },
    todayAttendance: {
        type: Object,
        default: null,
    },
    stats: Object,
    officeLocation: Object,
    currentDateTime: String,
});

// Use attendanceHistory if available, fallback to attendances
const attendanceList = computed(
    () => props.attendanceHistory || props.attendances || []
);

const showCamera = ref(false);
const actionType = ref("");
const isProcessing = ref(false);
const photoBase64 = ref(null);
const faceDescriptor = ref(null);
const userLocation = ref(null);

// Toast notifications
const showSuccessToast = ref(false);
const showErrorToast = ref(false);

// Real-time clock - USING SERVER TIME
const currentTime = ref("");
const serverTime = ref({
    iso: "",
    timestamp: 0,
    formatted: { date: "", time: "", datetime: "", timezone: "WIB", full: "" },
    year: 0,
    month: 0,
    day: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
});
const lastSyncTime = ref(0); // When last sync happened (browser timestamp)

// Check-in time range info
const checkInTimeRange = ref({
    check_in: { start: "07:30:00", end: "08:00:00" },
    check_out: { start: "16:00:00", end: "18:00:00" },
});

// Filter and Pagination
const filterStatus = ref("all");
const currentPage = ref(1);
const perPage = 5;

const cameraTitle = computed(() =>
    actionType.value === "check-in" ? "Foto Check-in" : "Foto Check-out"
);

// Filtered attendance based on status
const filteredAttendance = computed(() => {
    if (filterStatus.value === "all") {
        return attendanceList.value;
    }
    return attendanceList.value.filter(
        (att) => att.status === filterStatus.value
    );
});

// Paginated attendance
const paginatedAttendance = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredAttendance.value.slice(start, end);
});

// Total pages
const totalPages = computed(() => {
    return Math.ceil(filteredAttendance.value.length / perPage);
});

// Monthly stats
const monthlyStats = computed(() => {
    const total = attendanceList.value.length;
    const onTime = attendanceList.value.filter(
        (att) => att.status === "On-Time"
    ).length;
    const late = attendanceList.value.filter(
        (att) => att.status === "Late"
    ).length;
    return { total, onTime, late };
});

/**
 * Fetch server time from API
 * This prevents client-side time manipulation
 */
const fetchServerTime = async () => {
    try {
        const response = await axios.get("/api/server-time");
        const serverTimeData = response.data;

        console.log("=== SERVER TIME DEBUG ===");
        console.log("Raw response:", serverTimeData);

        // CRITICAL FIX: Don't create Date object from timestamp!
        // Date object will use browser's timezone
        // Instead, store the formatted time components directly from server
        serverTime.value = {
            iso: serverTimeData.time,
            timestamp: serverTimeData.timestamp,
            formatted: serverTimeData.formatted,
            year: parseInt(serverTimeData.formatted.date.split("-")[0]),
            month: parseInt(serverTimeData.formatted.date.split("-")[1]) - 1, // 0-indexed
            day: parseInt(serverTimeData.formatted.date.split("-")[2]),
            hours: parseInt(serverTimeData.formatted.time.split(":")[0]),
            minutes: parseInt(serverTimeData.formatted.time.split(":")[1]),
            seconds: parseInt(serverTimeData.formatted.time.split(":")[2]),
        };

        console.log("Parsed server time object:", serverTime.value);
        console.log("Server hours:", serverTime.value.hours);
        console.log("Server minutes:", serverTime.value.minutes);

        // Calculate when this sync happened (using browser time as reference point only)
        lastSyncTime.value = Date.now();

        console.log(
            "Last sync at:",
            new Date(lastSyncTime.value).toLocaleString()
        );
        console.log("=========================");

        // Update display
        updateClockDisplay();
    } catch (error) {
        console.error("Error fetching server time:", error);
        // Don't fallback - show error instead
        currentTime.value = "Tidak dapat terhubung ke server";
    }
};

/**
 * Fetch check-in time range configuration
 */
const fetchCheckInTimeRange = async () => {
    try {
        const response = await axios.get("/api/check-in-time-range");
        checkInTimeRange.value = response.data;
    } catch (error) {
        console.error("Error fetching check-in time range:", error);
    }
};

/**
 * Get current server time (synchronized)
 * Returns the actual server time, not client time
 * This simulates a clock without relying on browser time
 */
const getServerTime = () => {
    if (!serverTime.value || serverTime.value.timestamp === 0) {
        return null;
    }

    // Calculate how many seconds have passed since last sync
    const now = Date.now();
    const elapsedMs = now - lastSyncTime.value;
    const elapsedSeconds = Math.floor(elapsedMs / 1000);

    // Add elapsed seconds to server time
    let seconds = serverTime.value.seconds + elapsedSeconds;
    let minutes = serverTime.value.minutes;
    let hours = serverTime.value.hours;
    let day = serverTime.value.day;

    // Handle overflow
    if (seconds >= 60) {
        minutes += Math.floor(seconds / 60);
        seconds = seconds % 60;
    }

    if (minutes >= 60) {
        hours += Math.floor(minutes / 60);
        minutes = minutes % 60;
    }

    if (hours >= 24) {
        day += Math.floor(hours / 24);
        hours = hours % 24;
    }

    return {
        hours,
        minutes,
        seconds,
        day,
        month: serverTime.value.month,
        year: serverTime.value.year,
    };
};

/**
 * Update clock display with server time
 */
const updateClockDisplay = () => {
    const now = getServerTime();

    if (!now) {
        currentTime.value = "Memuat waktu server...";
        return;
    }

    const days = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
    ];
    const months = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    // Create a date object to get day of week (this won't affect time display)
    const dateObj = new Date(now.year, now.month, now.day);
    const dayName = days[dateObj.getDay()];
    const monthName = months[now.month];

    const formattedDate = `${dayName}, ${now.day} ${monthName} ${now.year}`;
    const formattedTime = `${String(now.hours).padStart(2, "0")}:${String(
        now.minutes
    ).padStart(2, "0")}:${String(now.seconds).padStart(2, "0")}`;

    currentTime.value = `${formattedDate} ${formattedTime} WIB`;
};

/**
 * Check if current time is within allowed check-in range
 */
const isWithinCheckInTime = computed(() => {
    const now = getServerTime();
    if (!now) return false;

    const currentTimeInMinutes = now.hours * 60 + now.minutes;

    const [startHour, startMin] = checkInTimeRange.value.check_in.start
        .split(":")
        .map(Number);
    const [endHour, endMin] = checkInTimeRange.value.check_in.end
        .split(":")
        .map(Number);

    const startTimeInMinutes = startHour * 60 + startMin;
    const endTimeInMinutes = endHour * 60 + endMin;

    console.log("Check-in validation:", {
        currentTime: `${now.hours}:${now.minutes}`,
        currentMinutes: currentTimeInMinutes,
        startTime: `${startHour}:${startMin}`,
        startMinutes: startTimeInMinutes,
        endTime: `${endHour}:${endMin}`,
        endMinutes: endTimeInMinutes,
        isWithin:
            currentTimeInMinutes >= startTimeInMinutes &&
            currentTimeInMinutes <= endTimeInMinutes,
    });

    return (
        currentTimeInMinutes >= startTimeInMinutes &&
        currentTimeInMinutes <= endTimeInMinutes
    );
});

/**
 * Check if current time is within allowed check-out range
 */
const isWithinCheckOutTime = computed(() => {
    const now = getServerTime();
    if (!now) return false;

    const currentTimeInMinutes = now.hours * 60 + now.minutes;

    const [startHour, startMin] = checkInTimeRange.value.check_out.start
        .split(":")
        .map(Number);
    const [endHour, endMin] = checkInTimeRange.value.check_out.end
        .split(":")
        .map(Number);

    const startTimeInMinutes = startHour * 60 + startMin;
    const endTimeInMinutes = endHour * 60 + endMin;

    return (
        currentTimeInMinutes >= startTimeInMinutes &&
        currentTimeInMinutes <= endTimeInMinutes
    );
});

// Watch for flash messages
watch(
    () => props.$page?.props?.flash?.success,
    (newValue) => {
        if (newValue) {
            showSuccessToast.value = true;
            setTimeout(() => {
                showSuccessToast.value = false;
            }, 5000);
        }
    }
);

watch(
    () => props.$page?.props?.flash?.error,
    (newValue) => {
        if (newValue) {
            showErrorToast.value = true;
            setTimeout(() => {
                showErrorToast.value = false;
            }, 5000);
        }
    }
);

// Reset to page 1 when filter changes
watch(filterStatus, () => {
    currentPage.value = 1;
});

let clockInterval;
let serverSyncInterval;

onMounted(() => {
    // Initial server time fetch
    fetchServerTime();
    fetchCheckInTimeRange();

    // Update clock display every second
    updateClockDisplay();
    clockInterval = setInterval(updateClockDisplay, 1000);

    // Sync with server every 60 seconds to prevent drift
    serverSyncInterval = setInterval(fetchServerTime, 60000);

    // Show toast if there's a flash message
    if (props.$page?.props?.flash?.success) {
        showSuccessToast.value = true;
        setTimeout(() => {
            showSuccessToast.value = false;
        }, 5000);
    }
    if (props.$page?.props?.flash?.error) {
        showErrorToast.value = true;
        setTimeout(() => {
            showErrorToast.value = false;
        }, 5000);
    }
});

onUnmounted(() => {
    if (clockInterval) {
        clearInterval(clockInterval);
    }
    if (serverSyncInterval) {
        clearInterval(serverSyncInterval);
    }
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        // Parse the date string
        let date;

        // If it's in YYYY-MM-DD format
        if (
            typeof dateString === "string" &&
            dateString.match(/^\d{4}-\d{2}-\d{2}/)
        ) {
            // Parse as local date to avoid timezone issues
            const parts = dateString.split(/[-\s:]/);
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            date = new Date(dateString);
        }

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return dateString;
        }

        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    } catch (error) {
        console.error("Error formatting date:", error);
        return dateString;
    }
};

const formatDayName = (dateString) => {
    if (!dateString) return "-";
    try {
        // Parse the date string
        let date;

        // If it's in YYYY-MM-DD format
        if (
            typeof dateString === "string" &&
            dateString.match(/^\d{4}-\d{2}-\d{2}/)
        ) {
            // Parse as local date to avoid timezone issues
            const parts = dateString.split(/[-\s:]/);
            date = new Date(parts[0], parts[1] - 1, parts[2]);
        } else {
            date = new Date(dateString);
        }

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return "";
        }

        return date.toLocaleDateString("id-ID", {
            weekday: "long",
        });
    } catch (error) {
        console.error("Error formatting day name:", error);
        return "";
    }
};

const formatTime = (timeString) => {
    if (!timeString) return "-";
    try {
        // If it's a full datetime string (YYYY-MM-DD HH:MM:SS)
        if (typeof timeString === "string" && timeString.includes(" ")) {
            const timePart = timeString.split(" ")[1];
            if (timePart) {
                return timePart.substring(0, 5); // Return HH:MM only
            }
        }

        // If it's already in HH:MM:SS format
        if (
            typeof timeString === "string" &&
            timeString.match(/^\d{2}:\d{2}:\d{2}$/)
        ) {
            return timeString.substring(0, 5); // Return HH:MM only
        }

        // Otherwise parse as Date object with Jakarta timezone
        const date = new Date(timeString);

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return timeString;
        }

        // Return time in Jakarta timezone
        return date.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
            timeZone: "Asia/Jakarta",
        });
    } catch (error) {
        console.error("Error formatting time:", error, timeString);
        return timeString;
    }
};

const handleCheckIn = async () => {
    actionType.value = "check-in";
    await getLocation();
};

const handleCheckOut = async () => {
    actionType.value = "check-out";
    await getLocation();
};

const getLocation = async () => {
    if (!navigator.geolocation) {
        alert("Geolocation tidak didukung");
        return;
    }

    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
            });
        });

        userLocation.value = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
        };

        showCamera.value = true;
    } catch (error) {
        alert(
            "Gagal mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi."
        );
    }
};

const onPhotoCaptured = (data) => {
    // Handle both old format (string) and new format (object with photo + faceDescriptor)
    if (typeof data === "string") {
        // Old format: just base64 photo
        photoBase64.value = data;
    } else {
        // New format: { photo, faceDescriptor }
        photoBase64.value = data.photo;
        faceDescriptor.value = data.faceDescriptor;
    }
    submit();
};

const submit = () => {
    if (!photoBase64.value || !userLocation.value) {
        alert("Data tidak lengkap");
        return;
    }

    if (!faceDescriptor.value || faceDescriptor.value.length !== 128) {
        alert("Face descriptor tidak valid. Silakan ambil foto ulang.");
        return;
    }

    const routeName =
        actionType.value === "check-in"
            ? "profile.attendance.check-in"
            : "profile.attendance.check-out";

    isProcessing.value = true;

    router.post(
        route(routeName),
        {
            photo: photoBase64.value,
            face_descriptor: faceDescriptor.value,
            latitude: userLocation.value.latitude,
            longitude: userLocation.value.longitude,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                isProcessing.value = false;
                photoBase64.value = null;
                faceDescriptor.value = null;
                userLocation.value = null;
                actionType.value = "";
            },
            onError: (errors) => {
                isProcessing.value = false;
                alert(
                    errors.error ||
                        errors.photo ||
                        errors.face_descriptor ||
                        "Gagal menyimpan absensi"
                );
            },
            onFinish: () => {
                isProcessing.value = false;
            },
        }
    );
};
</script>

<style scoped>
/* Custom animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Add subtle batik pattern to header (optional) */
.bg-gradient-to-r.from-blue-700.to-blue-600 {
    position: relative;
}

.bg-gradient-to-r.from-blue-700.to-blue-600::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
    pointer-events: none;
}
</style>
