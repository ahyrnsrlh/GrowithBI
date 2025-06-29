<template>
    <AdminLayout
        title="Laporan & Analytics"
        subtitle="Dashboard analytics dan laporan sistem magang"
    >
        <!-- Overview Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Aplikasi -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Aplikasi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_applications }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">
                            {{ stats.pending_applications }} menunggu review
                        </p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tingkat Penerimaan -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Tingkat Penerimaan
                        </p>
                        <p class="text-3xl font-bold">{{ acceptanceRate }}%</p>
                        <p class="text-green-100 text-xs mt-1">
                            {{ stats.accepted_applications }} dari
                            {{ stats.total_applications }}
                        </p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
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
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Divisi Aktif -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Divisi Aktif
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.active_divisions }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">
                            dari {{ stats.total_divisions }} total
                        </p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
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
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pembimbing Aktif -->
            <div
                class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            Pembimbing Aktif
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats.total_supervisors }}
                        </p>
                        <p class="text-orange-100 text-xs mt-1">
                            {{ stats.total_participants }} peserta dibimbing
                        </p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
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
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Status Distribution Chart -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Distribusi Status Aplikasi
                    </h3>
                    <button
                        @click="exportData('applications')"
                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
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
                        Export
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 bg-green-500 rounded-full mr-3"
                            ></div>
                            <span class="text-sm text-gray-700">Diterima</span>
                        </div>
                        <div class="flex items-center">
                            <span
                                class="text-sm font-medium text-gray-900 mr-2"
                                >{{ statusDistribution.accepted }}</span
                            >
                            <span class="text-xs text-gray-500"
                                >({{
                                    Math.round(
                                        (statusDistribution.accepted /
                                            stats.total_applications) *
                                            100
                                    )
                                }}%)</span
                            >
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-green-500 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${
                                    (statusDistribution.accepted /
                                        stats.total_applications) *
                                    100
                                }%`,
                            }"
                        ></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 bg-yellow-500 rounded-full mr-3"
                            ></div>
                            <span class="text-sm text-gray-700">Menunggu</span>
                        </div>
                        <div class="flex items-center">
                            <span
                                class="text-sm font-medium text-gray-900 mr-2"
                                >{{ statusDistribution.pending }}</span
                            >
                            <span class="text-xs text-gray-500"
                                >({{
                                    Math.round(
                                        (statusDistribution.pending /
                                            stats.total_applications) *
                                            100
                                    )
                                }}%)</span
                            >
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-yellow-500 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${
                                    (statusDistribution.pending /
                                        stats.total_applications) *
                                    100
                                }%`,
                            }"
                        ></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 bg-red-500 rounded-full mr-3"
                            ></div>
                            <span class="text-sm text-gray-700">Ditolak</span>
                        </div>
                        <div class="flex items-center">
                            <span
                                class="text-sm font-medium text-gray-900 mr-2"
                                >{{ statusDistribution.rejected }}</span
                            >
                            <span class="text-xs text-gray-500"
                                >({{
                                    Math.round(
                                        (statusDistribution.rejected /
                                            stats.total_applications) *
                                            100
                                    )
                                }}%)</span
                            >
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-red-500 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${
                                    (statusDistribution.rejected /
                                        stats.total_applications) *
                                    100
                                }%`,
                            }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Application Trends -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Tren Aplikasi (6 Bulan Terakhir)
                    </h3>
                    <button
                        @click="exportData('trends')"
                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
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
                        Export
                    </button>
                </div>
                <div class="space-y-4">
                    <div v-if="applicationTrends.length > 0">
                        <div
                            v-for="trend in applicationTrends.slice(-5)"
                            :key="trend.month"
                            class="flex items-center justify-between py-2"
                        >
                            <div>
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ formatMonth(trend.month) }}</span
                                >
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div
                                        class="text-sm font-medium text-green-600"
                                    >
                                        {{ trend.accepted }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Diterima
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="text-sm font-medium text-yellow-600"
                                    >
                                        {{ trend.pending }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Pending
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="text-sm font-medium text-red-600"
                                    >
                                        {{ trend.rejected }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Ditolak
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="text-sm font-bold text-gray-900"
                                    >
                                        {{ trend.total }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Total
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <div class="text-gray-400">
                            <svg
                                class="mx-auto h-12 w-12 mb-3"
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
                            <p class="text-sm">Belum ada data trend</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Division Performance -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Performa Divisi
                    </h3>
                    <button
                        @click="exportData('divisions')"
                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
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
                        Export
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th
                                    class="text-left py-3 text-xs font-medium text-gray-500 uppercase"
                                >
                                    Divisi
                                </th>
                                <th
                                    class="text-center py-3 text-xs font-medium text-gray-500 uppercase"
                                >
                                    Aplikasi
                                </th>
                                <th
                                    class="text-center py-3 text-xs font-medium text-gray-500 uppercase"
                                >
                                    Diterima
                                </th>
                                <th
                                    class="text-center py-3 text-xs font-medium text-gray-500 uppercase"
                                >
                                    Rate
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="division in divisionPerformance.slice(
                                    0,
                                    8
                                )"
                                :key="division.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="py-3">
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ division.name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ division.supervisor }}
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="py-3 text-center text-sm text-gray-900"
                                >
                                    {{ division.applications_count }}
                                </td>
                                <td
                                    class="py-3 text-center text-sm text-gray-900"
                                >
                                    {{ division.accepted_count }}
                                </td>
                                <td class="py-3 text-center">
                                    <span
                                        class="text-sm font-medium"
                                        :class="
                                            division.acceptance_rate >= 70
                                                ? 'text-green-600'
                                                : division.acceptance_rate >= 50
                                                ? 'text-yellow-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{ division.acceptance_rate }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Supervisors -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Pembimbing Terbaik
                    </h3>
                    <button
                        @click="exportData('supervisors')"
                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
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
                        Export
                    </button>
                </div>
                <div class="space-y-4">
                    <div
                        v-for="(supervisor, index) in topSupervisors"
                        :key="supervisor.id"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                                    :class="
                                        index === 0
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : index === 1
                                            ? 'bg-gray-100 text-gray-800'
                                            : index === 2
                                            ? 'bg-orange-100 text-orange-800'
                                            : 'bg-blue-100 text-blue-800'
                                    "
                                >
                                    {{ index + 1 }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ supervisor.name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ supervisor.divisions_count }} divisi
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ supervisor.accepted_count }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ supervisor.acceptance_rate }}% rate
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="topSupervisors.length === 0"
                        class="text-center py-8"
                    >
                        <div class="text-gray-400">
                            <svg
                                class="mx-auto h-8 w-8 mb-2"
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
                            <p class="text-xs">Belum ada data pembimbing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Actions -->
        <div
            class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6"
        >
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Export Data
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button
                    @click="exportData('applications')"
                    class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <div class="text-center">
                        <svg
                            class="w-8 h-8 text-blue-600 mx-auto mb-2"
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
                        <p class="text-sm font-medium text-gray-900">
                            Export Aplikasi
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ stats.total_applications }} data
                        </p>
                    </div>
                </button>

                <button
                    @click="exportData('divisions')"
                    class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <div class="text-center">
                        <svg
                            class="w-8 h-8 text-green-600 mx-auto mb-2"
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
                        <p class="text-sm font-medium text-gray-900">
                            Export Divisi
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ stats.total_divisions }} data
                        </p>
                    </div>
                </button>

                <button
                    @click="exportData('participants')"
                    class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <div class="text-center">
                        <svg
                            class="w-8 h-8 text-purple-600 mx-auto mb-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                            />
                        </svg>
                        <p class="text-sm font-medium text-gray-900">
                            Export Peserta
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ stats.total_participants }} data
                        </p>
                    </div>
                </button>

                <button
                    @click="exportData('supervisors')"
                    class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <div class="text-center">
                        <svg
                            class="w-8 h-8 text-yellow-600 mx-auto mb-2"
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
                        <p class="text-sm font-medium text-gray-900">
                            Export Pembimbing
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ stats.total_supervisors }} data
                        </p>
                    </div>
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { computed } from "vue";

const props = defineProps({
    stats: Object,
    applicationTrends: Array,
    divisionPerformance: Array,
    topSupervisors: Array,
    statusDistribution: Object,
});

const acceptanceRate = computed(() => {
    if (props.stats.total_applications === 0) return 0;
    return Math.round(
        (props.stats.accepted_applications / props.stats.total_applications) *
            100
    );
});

const formatMonth = (monthString) => {
    const [year, month] = monthString.split("-");
    const monthNames = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];
    return `${monthNames[parseInt(month) - 1]} ${year}`;
};

const exportData = (type) => {
    // TODO: Implement actual export functionality
    alert(`Export ${type} functionality will be implemented soon!`);
};
</script>
