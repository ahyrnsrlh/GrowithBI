<template>
    <Head title="Manajemen Absensi Peserta" />

    <AdminLayout title="Manajemen Absensi">
        <!-- Page Header -->
        <div class="mb-8">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 text-sm text-gray-500"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/admin/dashboard"
                                    class="hover:text-blue-600 transition-colors"
                                >
                                    Dashboard
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 mx-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span class="text-gray-700 font-medium"
                                        >Absensi</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Manajemen Absensi
                    </h1>
                    <p class="mt-1 text-gray-500">
                        Kelola dan pantau absensi peserta magang secara
                        real-time
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        @click="exportData"
                        class="inline-flex items-center gap-2 px-4 py-2.5 border-2 border-blue-600 text-blue-600 font-medium rounded-xl hover:bg-blue-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Kehadiran -->
            <div
                class="group bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-white/90 mb-1">
                        Total Kehadiran
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.present_days || 0 }}
                    </h3>
                    <p class="text-xs text-white mt-2">
                        Total absensi bulan ini
                    </p>
                </div>
            </div>

            <!-- Tingkat Kehadiran -->
            <div
                class="group bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                />
                            </svg>
                        </div>
                        <span
                            class="flex items-center gap-1 text-xs font-medium text-white bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-full"
                        >
                            <svg
                                class="w-3 h-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                />
                            </svg>
                            Rate
                        </span>
                    </div>
                    <p class="text-sm font-medium text-white/90 mb-1">
                        Tingkat Kehadiran
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.attendance_rate || 0 }}%
                    </h3>
                    <p class="text-xs text-white mt-2">
                        Persentase kehadiran aktif
                    </p>
                </div>
            </div>

            <!-- Ketepatan Waktu -->
            <div
                class="group bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-600 rounded-2xl p-6 shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <span
                            class="flex items-center gap-1 text-xs font-medium text-white bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-full"
                        >
                            On Time
                        </span>
                    </div>
                    <p class="text-sm font-medium text-white/90 mb-1">
                        Ketepatan Waktu
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.punctuality_rate || 0 }}%
                    </h3>
                    <p class="text-xs text-white mt-2">
                        Persentase tepat waktu
                    </p>
                </div>
            </div>

            <!-- Tidak Hadir -->
            <div
                class="group bg-gradient-to-br from-rose-500 via-red-500 to-pink-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
            >
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <span
                            v-if="stats?.absent_days > 0"
                            class="flex items-center gap-1 text-xs font-medium text-white bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-full animate-pulse"
                        >
                            Alert
                        </span>
                    </div>
                    <p class="text-sm font-medium text-white/90 mb-1">
                        Tidak Hadir
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.absent_days || 0 }}
                    </h3>
                    <p class="text-xs text-white mt-2">Total ketidakhadiran</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6"
        >
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg flex items-center justify-center"
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
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Filter Data
                    </h3>
                </div>
                <button
                    @click="showFilters = !showFilters"
                    class="md:hidden inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900"
                >
                    <span>{{ showFilters ? "Sembunyikan" : "Tampilkan" }}</span>
                    <svg
                        :class="[
                            'w-4 h-4 transition-transform',
                            showFilters ? 'rotate-180' : '',
                        ]"
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
                </button>
            </div>

            <div
                :class="[
                    'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4',
                    { 'hidden md:grid': !showFilters },
                ]"
            >
                <!-- Search -->
                <div class="xl:col-span-2">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Cari Peserta</label
                    >
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="w-5 h-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="filterForm.search"
                            @input="debounceSearch"
                            type="text"
                            placeholder="Nama atau email..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                        />
                    </div>
                </div>

                <!-- Date Range -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Tanggal Mulai</label
                    >
                    <input
                        v-model="filterForm.date_from"
                        @change="applyFilters"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Tanggal Akhir</label
                    >
                    <input
                        v-model="filterForm.date_to"
                        @change="applyFilters"
                        type="date"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                    />
                </div>

                <!-- Division Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Divisi</label
                    >
                    <select
                        v-model="filterForm.division_id"
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none cursor-pointer"
                    >
                        <option value="">Semua Divisi</option>
                        <option
                            v-for="division in divisions"
                            :key="division.id"
                            :value="division.id"
                        >
                            {{ division.name }}
                        </option>
                    </select>
                </div>

                <!-- Status Filter + Reset Button -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Status</label
                    >
                    <div class="flex items-center gap-2">
                        <select
                            v-model="filterForm.status"
                            @change="applyFilters"
                            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none cursor-pointer"
                        >
                            <option value="">Semua Status</option>
                            <option value="On-Time">Tepat Waktu</option>
                            <option value="Late">Terlambat</option>
                            <option value="Absent">Tidak Hadir</option>
                            <option value="Not-Checked-Out">
                                Belum Check-out
                            </option>
                        </select>
                        <!-- Reset Button -->
                        <button
                            @click="clearFilters"
                            :disabled="activeFiltersCount === 0"
                            :class="[
                                'inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap',
                                activeFiltersCount > 0
                                    ? 'text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200'
                                    : 'text-gray-400 bg-gray-50 border border-gray-100 cursor-not-allowed',
                            ]"
                            :title="
                                activeFiltersCount > 0
                                    ? activeFiltersCount + ' filter aktif'
                                    : 'Tidak ada filter aktif'
                            "
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
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                            <span class="hidden sm:inline">Reset</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <!-- Table Header -->
            <div
                class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-50 to-purple-100 rounded-xl flex items-center justify-center"
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
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Data Absensi
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ attendances?.total || 0 }} total records
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div v-if="isLoading" class="divide-y divide-gray-100">
                <div v-for="i in 5" :key="i" class="px-6 py-4 animate-pulse">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                            <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                        </div>
                        <div class="h-6 w-20 bg-gray-200 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div v-else class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/80 sticky top-0 z-10">
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Tanggal
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Peserta
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Check-in
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Check-out
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Foto
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Durasi
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="(attendance, index) in attendances?.data ||
                            []"
                            :key="attendance.id"
                            :class="[
                                'hover:bg-blue-50/50 transition-colors duration-150',
                                index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30',
                            ]"
                        >
                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ attendance.date_formatted }}
                                </div>
                            </td>

                            <!-- Participant -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm"
                                    >
                                        {{ getInitials(attendance.user?.name) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ attendance.user?.name || "-" }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ attendance.user?.email || "-" }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Division -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-700"
                                >
                                    {{ attendance.user?.division?.name || "-" }}
                                </span>
                            </td>

                            <!-- Check-in -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <svg
                                        class="w-4 h-4 text-green-500"
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
                                        class="text-sm font-medium text-gray-900"
                                        >{{ attendance.check_in || "-" }}</span
                                    >
                                </div>
                            </td>

                            <!-- Check-out -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <svg
                                        class="w-4 h-4 text-orange-500"
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
                                        class="text-sm font-medium text-gray-900"
                                        >{{ attendance.check_out || "-" }}</span
                                    >
                                </div>
                            </td>

                            <!-- Photo -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <!-- Check-in Photo -->
                                    <div
                                        v-if="attendance.photos?.checkin"
                                        class="relative group/photo"
                                    >
                                        <img
                                            :src="attendance.photos.checkin"
                                            alt="Check-in"
                                            class="w-10 h-10 rounded-lg object-cover cursor-pointer border-2 border-green-200 hover:border-green-400 hover:scale-110 transition-all duration-200 shadow-sm"
                                            @click="
                                                openPhotoModal(
                                                    attendance.photos.checkin,
                                                    'Check-in - ' +
                                                        attendance.user?.name
                                                )
                                            "
                                        />
                                        <div
                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/photo:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                        >
                                            Check-in
                                        </div>
                                    </div>
                                    <span
                                        v-else
                                        class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </span>

                                    <!-- Check-out Photo -->
                                    <div
                                        v-if="attendance.photos?.checkout"
                                        class="relative group/photo"
                                    >
                                        <img
                                            :src="attendance.photos.checkout"
                                            alt="Check-out"
                                            class="w-10 h-10 rounded-lg object-cover cursor-pointer border-2 border-orange-200 hover:border-orange-400 hover:scale-110 transition-all duration-200 shadow-sm"
                                            @click="
                                                openPhotoModal(
                                                    attendance.photos.checkout,
                                                    'Check-out - ' +
                                                        attendance.user?.name
                                                )
                                            "
                                        />
                                        <div
                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/photo:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                        >
                                            Check-out
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative group/status">
                                    <span
                                        :class="
                                            getStatusBadgeClass(
                                                attendance.status
                                            )
                                        "
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold"
                                    >
                                        <span
                                            :class="
                                                getStatusDotClass(
                                                    attendance.status
                                                )
                                            "
                                            class="w-1.5 h-1.5 rounded-full"
                                        ></span>
                                        {{ getStatusText(attendance.status) }}
                                    </span>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg py-1.5 px-3 opacity-0 group-hover/status:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20"
                                    >
                                        {{
                                            getStatusTooltip(attendance.status)
                                        }}
                                    </div>
                                </div>
                            </td>

                            <!-- Duration -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-700">
                                    {{
                                        formatDuration(
                                            attendance.working_duration
                                        )
                                    }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <Link
                                    :href="
                                        route(
                                            'admin.attendance.show',
                                            attendance.id
                                        )
                                    "
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                >
                                    Detail
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
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div
                v-if="
                    !isLoading &&
                    (!attendances?.data || attendances.data.length === 0)
                "
                class="text-center py-16"
            >
                <div
                    class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
                >
                    <svg
                        class="w-10 h-10 text-gray-400"
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
                <h3 class="text-lg font-medium text-gray-900 mb-1">
                    Tidak ada data absensi
                </h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto">
                    Tidak ada data absensi yang sesuai dengan filter yang
                    dipilih. Coba ubah filter atau reset untuk melihat semua
                    data.
                </p>
                <button
                    @click="clearFilters"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
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
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                    Reset Filter
                </button>
            </div>

            <!-- Pagination -->
            <div
                v-if="attendances?.data?.length"
                class="px-6 py-4 border-t border-gray-100 bg-gray-50/50"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <p class="text-sm text-gray-600">
                        Menampilkan
                        <span class="font-medium">{{ attendances.from }}</span>
                        -
                        <span class="font-medium">{{ attendances.to }}</span>
                        dari
                        <span class="font-medium">{{ attendances.total }}</span>
                        data
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-if="attendances.prev_page_url"
                            :href="attendances.prev_page_url"
                            preserve-scroll
                            preserve-state
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
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
                            Prev
                        </Link>

                        <template
                            v-for="link in paginationLinks"
                            :key="link.label"
                        >
                            <Link
                                v-if="
                                    link.url &&
                                    !link.label.includes('Previous') &&
                                    !link.label.includes('Next')
                                "
                                :href="link.url"
                                preserve-scroll
                                preserve-state
                                :class="[
                                    'inline-flex items-center justify-center w-10 h-10 text-sm font-medium rounded-lg transition-colors',
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50',
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else-if="
                                    !link.url &&
                                    !link.label.includes('Previous') &&
                                    !link.label.includes('Next') &&
                                    link.label === '...'
                                "
                                class="inline-flex items-center justify-center w-10 h-10 text-sm text-gray-400"
                            >
                                ...
                            </span>
                        </template>

                        <Link
                            v-if="attendances.next_page_url"
                            :href="attendances.next_page_url"
                            preserve-scroll
                            preserve-state
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Next
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
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Modal -->
        <TransitionRoot appear :show="showPhotoModal" as="template">
            <Dialog as="div" @close="closePhotoModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all"
                            >
                                <div class="relative">
                                    <!-- Close Button -->
                                    <button
                                        @click="closePhotoModal"
                                        class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors"
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

                                    <!-- Image -->
                                    <img
                                        :src="selectedPhoto"
                                        :alt="selectedPhotoTitle"
                                        class="w-full h-auto"
                                    />
                                </div>

                                <!-- Footer -->
                                <div
                                    class="p-4 border-t border-gray-100 bg-gray-50"
                                >
                                    <DialogTitle
                                        as="h3"
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        {{ selectedPhotoTitle }}
                                    </DialogTitle>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { debounce } from "lodash";

const props = defineProps({
    attendances: Object,
    divisions: Array,
    participants: Array,
    filters: Object,
    stats: Object,
});

// State
const isLoading = ref(false);
const showFilters = ref(true);
const showPhotoModal = ref(false);
const selectedPhoto = ref(null);
const selectedPhotoTitle = ref("");

// Filter form
const filterForm = reactive({
    search: props.filters?.search || "",
    date_from: props.filters?.date_from || "",
    date_to: props.filters?.date_to || "",
    division_id: props.filters?.division_id || "",
    participant_id: props.filters?.participant_id || "",
    status: props.filters?.status || "",
});

// Computed
const activeFiltersCount = computed(() => {
    return Object.values(filterForm).filter((v) => v !== "").length;
});

const paginationLinks = computed(() => {
    return props.attendances?.links || [];
});

// Methods
const getInitials = (name) => {
    if (!name) return "?";
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
};

const getStatusText = (status) => {
    const statusMap = {
        "On-Time": "Tepat Waktu",
        Late: "Terlambat",
        Absent: "Tidak Hadir",
        "Not-Checked-Out": "Belum Check-out",
    };
    return statusMap[status] || status;
};

const getStatusTooltip = (status) => {
    const tooltipMap = {
        "On-Time": "Peserta hadir tepat waktu",
        Late: "Peserta datang terlambat",
        Absent: "Peserta tidak hadir",
        "Not-Checked-Out": "Peserta belum melakukan check-out",
    };
    return tooltipMap[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        "On-Time": "bg-emerald-50 text-emerald-700 border border-emerald-200",
        Late: "bg-amber-50 text-amber-700 border border-amber-200",
        Absent: "bg-red-50 text-red-700 border border-red-200",
        "Not-Checked-Out": "bg-blue-50 text-blue-700 border border-blue-200",
    };
    return classes[status] || "bg-gray-50 text-gray-700 border border-gray-200";
};

const getStatusDotClass = (status) => {
    const dotClasses = {
        "On-Time": "bg-emerald-500",
        Late: "bg-amber-500",
        Absent: "bg-red-500",
        "Not-Checked-Out": "bg-blue-500",
    };
    return dotClasses[status] || "bg-gray-500";
};

const formatDuration = (minutes) => {
    if (!minutes) return "-";
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    if (hours === 0) return `${mins}m`;
    return `${hours}j ${mins}m`;
};

const openPhotoModal = (photoUrl, title) => {
    selectedPhoto.value = photoUrl;
    selectedPhotoTitle.value = title;
    showPhotoModal.value = true;
};

const closePhotoModal = () => {
    showPhotoModal.value = false;
    selectedPhoto.value = null;
    selectedPhotoTitle.value = "";
};

const applyFilters = () => {
    isLoading.value = true;
    router.get(route("admin.attendance.index"), filterForm, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const debounceSearch = debounce(() => {
    applyFilters();
}, 400);

const clearFilters = () => {
    Object.keys(filterForm).forEach((key) => {
        filterForm[key] = "";
    });
    applyFilters();
};

const exportData = () => {
    window.location.href = route("admin.attendance.export", filterForm);
};
</script>
