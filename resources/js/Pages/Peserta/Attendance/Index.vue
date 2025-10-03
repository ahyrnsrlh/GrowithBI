<template>
    <Head title="Absensi Online - GrowithBI" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-gray-50">
        <!-- Header Profesional Bank Indonesia -->
        <div class="sticky top-0 z-50 bg-[#002855] shadow-lg border-b-4 border-[#C9A227]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left: Back Button + Title -->
                    <div class="flex items-center space-x-4">
                        <Link
                            :href="route('profile.edit')"
                            class="text-white/80 hover:text-white transition-colors flex items-center space-x-2 group"
                        >
                            <svg class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span class="text-sm font-medium">Kembali</span>
                        </Link>
                        <div class="h-6 w-px bg-white/20"></div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <svg class="h-6 w-6 text-[#C9A227]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white">Absensi Online</h1>
                                <p class="text-xs text-blue-200">Kelola absensi harian dengan mudah dan akurat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Date Time + BI Logo -->
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:block bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2.5 border border-white/20">
                            <div class="text-xs font-medium text-blue-200 mb-0.5">{{ todayFormatted }}</div>
                            <div class="text-lg font-bold text-white">{{ formattedCurrentTime }}</div>
                        </div>
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border-2 border-[#C9A227] shadow-lg">
                            <span class="text-[#002855] font-bold text-sm">BI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            <div
                v-if="$page.props.flash.success"
                class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4 flex items-center space-x-3 shadow-sm animate-fade-in"
            >
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-emerald-800">{{ $page.props.flash.success }}</p>
            </div>

            <div
                v-if="$page.props.flash.error"
                class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 flex items-center space-x-3 shadow-sm animate-fade-in"
            >
                <svg class="h-5 w-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Status & Actions -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Hari Ini Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-[#002855] to-[#003d7a] px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-sm font-semibold text-blue-200 uppercase tracking-wide">Status Hari Ini</h2>
                                    <p class="text-lg font-bold text-white mt-1">{{ todayFormatted }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                    <svg class="h-7 w-7 text-[#C9A227]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div v-if="todayAttendance" class="space-y-4">
                                <!-- Check-in & Check-out Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Check-in -->
                                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200 shadow-sm">
                                        <div class="flex items-center space-x-2 mb-3">
                                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-md">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Check-in</span>
                                        </div>
                                        <p class="text-2xl font-bold text-emerald-900">{{ todayAttendance.check_in || "-" }}</p>
                                        <p class="text-xs text-emerald-600 mt-1">Jam Masuk</p>
                                    </div>

                                    <!-- Check-out -->
                                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200 shadow-sm">
                                        <div class="flex items-center space-x-2 mb-3">
                                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center shadow-md">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-semibold text-red-700 uppercase tracking-wide">Check-out</span>
                                        </div>
                                        <p class="text-2xl font-bold text-red-900">{{ todayAttendance.check_out || "-" }}</p>
                                        <p class="text-xs text-red-600 mt-1">Jam Keluar</p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex justify-center pt-2">
                                    <span
                                        :class="getStatusBadgeClass(todayAttendance.status)"
                                        class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm"
                                    >
                                        <span class="w-2 h-2 rounded-full mr-2 animate-pulse" :class="getStatusDotClass(todayAttendance.status)"></span>
                                        {{ getStatusText(todayAttendance.status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div v-else class="text-center py-10">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum Ada Absensi Hari Ini</h3>
                                <p class="text-sm text-gray-500">Lakukan check-in untuk memulai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Check-in Button -->
                        <button
                            @click="handleCheckIn"
                            :disabled="loading || (todayAttendance && todayAttendance.check_in)"
                            class="group relative bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 disabled:from-gray-300 disabled:to-gray-400 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 disabled:cursor-not-allowed overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            <div class="relative">
                                <div class="flex items-center justify-center mb-3">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold mb-1">Check-in</h3>
                                <p class="text-sm text-emerald-100">Mulai absensi hari ini</p>
                            </div>
                        </button>

                        <!-- Check-out Button -->
                        <button
                            @click="handleCheckOut"
                            :disabled="loading || !todayAttendance || !todayAttendance.check_in || todayAttendance.check_out"
                            class="group relative bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 disabled:from-gray-300 disabled:to-gray-400 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 disabled:cursor-not-allowed overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            <div class="relative">
                                <div class="flex items-center justify-center mb-3">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold mb-1">Check-out</h3>
                                <p class="text-sm text-red-100">Akhiri absensi hari ini</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Statistics -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-4 flex items-center">
                            <svg class="h-5 w-5 text-[#C9A227] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Statistik Bulan Ini
                        </h3>

                        <div class="space-y-3">
                            <!-- Tepat Waktu -->
                            <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-emerald-700">Tepat Waktu</p>
                                        <p class="text-2xl font-bold text-emerald-900">{{ stats.onTime || 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Terlambat -->
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-xl border border-orange-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-orange-700">Terlambat</p>
                                        <p class="text-2xl font-bold text-orange-900">{{ stats.late || 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tidak Hadir -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-700">Tidak Hadir</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ stats.absent || 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-4">Grafik Kehadiran</h3>
                        <div class="h-48 flex items-end justify-around space-x-2">
                            <div class="flex-1 flex flex-col items-center">
                                <div
                                    class="w-full bg-gradient-to-t from-emerald-500 to-emerald-400 rounded-t-lg shadow-md transition-all duration-500 hover:from-emerald-600 hover:to-emerald-500"
                                    :style="{ height: getBarHeight(stats.onTime || 0) }"
                                ></div>
                                <p class="text-xs font-semibold text-emerald-700 mt-2">Hadir</p>
                            </div>
                            <div class="flex-1 flex flex-col items-center">
                                <div
                                    class="w-full bg-gradient-to-t from-orange-500 to-orange-400 rounded-t-lg shadow-md transition-all duration-500 hover:from-orange-600 hover:to-orange-500"
                                    :style="{ height: getBarHeight(stats.late || 0) }"
                                ></div>
                                <p class="text-xs font-semibold text-orange-700 mt-2">Terlambat</p>
                            </div>
                            <div class="flex-1 flex flex-col items-center">
                                <div
                                    class="w-full bg-gradient-to-t from-gray-400 to-gray-300 rounded-t-lg shadow-md transition-all duration-500 hover:from-gray-500 hover:to-gray-400"
                                    :style="{ height: getBarHeight(stats.absent || 0) }"
                                ></div>
                                <p class="text-xs font-semibold text-gray-700 mt-2">Tidak Hadir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Absensi -->
            <div class="mt-6 bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#002855] to-[#003d7a] px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="h-5 w-5 text-[#C9A227] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Absensi
                    </h3>
                    <p class="text-sm text-blue-200 mt-1">10 Absensi Terakhir</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Keluar</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="attendance in recentAttendances" :key="attendance.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ formatDate(attendance.date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ attendance.check_in || "-" }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ attendance.check_out || "-" }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        :class="getTableStatusBadgeClass(attendance.status)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="getStatusDotClass(attendance.status)"></span>
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!recentAttendances || recentAttendances.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Belum ada riwayat absensi
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Camera Modal -->
        <CameraModal
            v-if="showCamera"
            :type="cameraType"
            @close="showCamera = false"
            @captured="handlePhotoCaptured"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import CameraModal from "@/Components/CameraModal.vue";

const props = defineProps({
    todayAttendance: Object,
    recentAttendances: Array,
    stats: Object,
});

const currentTime = ref(new Date());
const loading = ref(false);
const showCamera = ref(false);
const cameraType = ref("");

const formattedCurrentTime = computed(() => {
    return currentTime.value.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
});

const todayFormatted = computed(() => {
    return currentTime.value.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

let timeInterval;

onMounted(() => {
    timeInterval = setInterval(() => {
        currentTime.value = new Date();
    }, 1000);
});

onUnmounted(() => {
    if (timeInterval) {
        clearInterval(timeInterval);
    }
});

const handleCheckIn = () => {
    cameraType.value = "check-in";
    showCamera.value = true;
};

const handleCheckOut = () => {
    cameraType.value = "check-out";
    showCamera.value = true;
};

const handlePhotoCaptured = (photoData) => {
    loading.value = true;
    const formData = new FormData();
    formData.append("photo", photoData.blob);
    formData.append("type", cameraType.value);

    router.post(route("profile.attendance.store"), formData, {
        onFinish: () => {
            loading.value = false;
            showCamera.value = false;
        },
    });
};

const getStatusText = (status) => {
    const statusMap = {
        present: "✅ Tepat Waktu",
        late: "⚠️ Terlambat",
        absent: "❌ Tidak Hadir",
    };
    return statusMap[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        present: "bg-emerald-100 text-emerald-800 border border-emerald-200",
        late: "bg-orange-100 text-orange-800 border border-orange-200",
        absent: "bg-gray-100 text-gray-800 border border-gray-200",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getTableStatusBadgeClass = (status) => {
    const classes = {
        present: "bg-emerald-50 text-emerald-700 border border-emerald-200",
        late: "bg-orange-50 text-orange-700 border border-orange-200",
        absent: "bg-gray-50 text-gray-700 border border-gray-200",
    };
    return classes[status] || "bg-gray-50 text-gray-700";
};

const getStatusDotClass = (status) => {
    const classes = {
        present: "bg-emerald-500",
        late: "bg-orange-500",
        absent: "bg-gray-400",
    };
    return classes[status] || "bg-gray-400";
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getBarHeight = (value) => {
    const max = Math.max(props.stats.onTime || 0, props.stats.late || 0, props.stats.absent || 0, 1);
    const percentage = (value / max) * 100;
    return `${Math.max(percentage, 10)}%`;
};
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
