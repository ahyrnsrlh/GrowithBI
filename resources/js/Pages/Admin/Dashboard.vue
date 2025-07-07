<template>
    <AdminLayout
        title="Dashboard"
        subtitle="Selamat datang di sistem manajemen internship GrowithBI"
        :pendingCount="safeStats.pending_applications"
        :notificationCount="safeStats.pending_applications"
    >
        <!-- Hero Section dengan Enhanced Design -->
        <div class="relative mb-8 overflow-hidden">
            <div
                class="bg-gradient-to-br from-blue-600 via-blue-600 to-blue-600 rounded-2xl p-8 border-2 border-blue-600 shadow-xl"
            >
                <div class="relative z-10">
                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div>
                            <h1
                                class="text-3xl lg:text-4xl font-bold mb-2 text-white"
                            >
                                Halo, Admin! 👋
                            </h1>
                            <p class="text-white text-lg mb-4 font-medium">
                                Kelola program magang dengan mudah dan efisien
                            </p>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >Sistem Online</span
                                    >
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 bg-yellow-500 rounded-full mr-2"
                                        :class="{
                                            'animate-pulse':
                                                safeStats.pending_applications >
                                                0,
                                        }"
                                    ></div>
                                    <span class="text-white text-sm font-medium"
                                        >{{
                                            safeStats.pending_applications
                                        }}
                                        Perlu Review</span
                                    >
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="w-3 h-3 rounded-full mr-2"
                                        :class="{
                                            'bg-green-500 animate-pulse':
                                                systemStatus === 'success',
                                            'bg-yellow-500 animate-pulse':
                                                systemStatus === 'info',
                                            'bg-red-500 animate-pulse':
                                                systemStatus === 'warning',
                                        }"
                                    ></div>
                                    <span
                                        class="text-white text-sm font-medium"
                                        >{{ systemStatusText }}</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 lg:mt-0">
                            <div class="flex space-x-3">
                                <Link
                                    :href="
                                        safeRoute('admin.applications.index', {
                                            status: 'menunggu',
                                        })
                                    "
                                    class="inline-flex items-center px-6 py-3 bg-white text-blue-700 border-2 border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
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
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                    Review Pendaftar
                                    <span
                                        v-if="
                                            safeStats.pending_applications > 0
                                        "
                                        class="ml-2 px-2 py-1 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full"
                                    >
                                        {{ safeStats.pending_applications }}
                                    </span>
                                </Link>
                                <Link
                                    :href="safeRoute('admin.reports.index')"
                                    class="inline-flex items-center px-6 py-3 bg-white text-blue-700 border-2 border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 font-medium hover:shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
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
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                        />
                                    </svg>
                                    Lihat Laporan
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Background Pattern -->
                <div class="absolute inset-0 bg-blue-600 bg-opacity-5">
                    <div
                        class="absolute top-0 left-0 w-40 h-40 bg-blue-600 bg-opacity-10 rounded-full -translate-x-20 -translate-y-20"
                    ></div>
                    <div
                        class="absolute top-10 right-10 w-32 h-32 bg-blue-500 bg-opacity-5 rounded-full"
                    ></div>
                    <div
                        class="absolute bottom-0 right-0 w-48 h-48 bg-blue-600 bg-opacity-10 rounded-full translate-x-24 translate-y-24"
                    ></div>
                    <div
                        class="absolute top-1/2 left-1/4 w-24 h-24 bg-blue-400 bg-opacity-5 rounded-full"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                title="Klik untuk melihat detail Pendaftar"
                @click="router.visit(safeRoute('admin.applications.index'))"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Pendaftaran
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.total_applications }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">
                            +12% dari bulan lalu
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

            <!-- Pending Applications -->
            <div
                class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                title="Klik untuk review Pendaftar pending"
                @click="
                    router.visit(
                        safeRoute('admin.applications.index', {
                            status: 'menunggu',
                        })
                    )
                "
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">
                            Menunggu Review
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.pending_applications }}
                        </p>
                        <p class="text-yellow-100 text-xs mt-1">
                            {{ pendingRate }}% dari total Pendaftar
                        </p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
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
                </div>
            </div>

            <!-- Accepted Applications -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                title="Klik untuk melihat Pendaftar yang diterima"
                @click="
                    router.visit(
                        safeRoute('admin.applications.index', {
                            status: 'diterima',
                        })
                    )
                "
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Diterima
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.accepted_applications }}
                        </p>
                        <p class="text-green-100 text-xs mt-1">
                            {{ acceptanceRate }}% tingkat penerimaan
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

            <!-- Active Divisions -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                title="Klik untuk mengelola divisi"
                @click="router.visit(safeRoute('admin.divisions.index'))"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Divisi Aktif
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.active_divisions }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">
                            {{ safeStats.total_supervisors }} pembimbing
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
        </div>

        <!-- Enhanced Content Grid dengan Better Mobile Support -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
            <!-- Recent Applications (Enhanced) -->
            <div
                class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Pendaftaran Terbaru
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Pantau Pendaftar yang baru masuk
                            </p>
                        </div>
                        <Link
                            :href="safeRoute('admin.applications.index')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 font-medium"
                        >
                            <span class="mr-2">Lihat Semua</span>
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
                <div class="p-6">
                    <div class="space-y-4">
                        <div
                            v-for="(application, index) in recentApplications"
                            :key="application.id"
                            class="group flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200 transform hover:-translate-y-1"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg"
                                    >
                                        <span
                                            class="text-white font-bold text-lg"
                                            >{{
                                                application.name.charAt(0)
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-sm"
                                    >
                                        <span
                                            class="text-xs font-bold text-gray-600"
                                            >{{ index + 1 }}</span
                                        >
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="text-base font-semibold text-gray-900 group-hover:text-blue-600 transition-colors"
                                    >
                                        {{ application.name }}
                                    </p>
                                    <div
                                        class="flex items-center space-x-2 mt-1"
                                    >
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{ application.division }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{
                                            formatDate(application.applied_at)
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm transition-all duration-200',
                                        application.status === 'menunggu'
                                            ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white hover:from-yellow-500 hover:to-orange-500 cursor-pointer'
                                            : application.status === 'diterima'
                                            ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white hover:from-green-500 hover:to-emerald-600'
                                            : 'bg-gradient-to-r from-red-400 to-red-500 text-white hover:from-red-500 hover:to-red-600',
                                    ]"
                                    :title="`Status: ${application.status}`"
                                >
                                    <div
                                        class="w-2 h-2 bg-white bg-opacity-70 rounded-full mr-2 animate-pulse"
                                        v-if="application.status === 'menunggu'"
                                    ></div>
                                    <div
                                        class="w-2 h-2 bg-white bg-opacity-70 rounded-full mr-2"
                                        v-else
                                    ></div>
                                    {{ application.status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Empty State -->
                    <div
                        v-if="!recentApplications.length"
                        class="text-center py-16"
                    >
                        <div class="relative">
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg"
                            >
                                <svg
                                    class="w-12 h-12 text-blue-500"
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
                            <div
                                class="absolute top-0 left-1/2 w-20 h-20 bg-blue-200 rounded-full opacity-20 transform -translate-x-1/2 animate-ping"
                            ></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Belum ada Pendaftar baru hari ini
                        </h3>
                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                            Pendaftar pendaftaran magang baru akan muncul di
                            sini secara real-time
                        </p>
                        <Link
                            :href="safeRoute('admin.applications.index')"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg"
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
                            Lihat Semua Pendaftar
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Division Overview (Enhanced) -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Overview Divisi
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Status kuota per divisi
                            </p>
                        </div>
                        <Link
                            :href="safeRoute('admin.divisions.index')"
                            class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 font-medium"
                        >
                            <span class="mr-1">Kelola</span>
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
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                        </Link>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div
                            v-for="division in divisionsData"
                            :key="division.id"
                            class="group border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-white to-gray-50"
                        >
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4
                                        class="font-bold text-gray-900 text-lg group-hover:text-purple-600 transition-colors"
                                    >
                                        {{ division.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ division.supervisor }}
                                    </p>
                                </div>
                                <div
                                    class="w-3 h-3 rounded-full"
                                    :class="[
                                        division.accepted >=
                                        division.max_interns
                                            ? 'bg-green-400'
                                            : division.accepted >=
                                              division.max_interns * 0.8
                                            ? 'bg-yellow-400'
                                            : 'bg-red-400',
                                    ]"
                                ></div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2"
                                    >
                                        <span
                                            class="text-xl font-bold text-blue-600"
                                            >{{ division.applications }}</span
                                        >
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 font-medium"
                                    >
                                        Pendaftar
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2"
                                    >
                                        <span
                                            class="text-xl font-bold text-green-600"
                                            >{{ division.accepted }}</span
                                        >
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 font-medium"
                                    >
                                        Diterima
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2"
                                    >
                                        <span
                                            class="text-xl font-bold text-purple-600"
                                            >{{ division.max_interns }}</span
                                        >
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 font-medium"
                                    >
                                        Kuota
                                    </p>
                                </div>
                            </div>

                            <!-- Enhanced Progress Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-700"
                                        >Progress Kuota</span
                                    >
                                    <span
                                        :class="[
                                            'font-bold',
                                            Math.round(
                                                (division.accepted /
                                                    division.max_interns) *
                                                    100
                                            ) >= 100
                                                ? 'text-green-600'
                                                : Math.round(
                                                      (division.accepted /
                                                          division.max_interns) *
                                                          100
                                                  ) >= 80
                                                ? 'text-yellow-600'
                                                : 'text-gray-600',
                                        ]"
                                    >
                                        {{
                                            Math.round(
                                                (division.accepted /
                                                    division.max_interns) *
                                                    100
                                            )
                                        }}%
                                    </span>
                                </div>
                                <div
                                    class="w-full bg-gray-200 rounded-full h-3 shadow-inner"
                                >
                                    <div
                                        class="h-3 rounded-full transition-all duration-500 ease-out"
                                        :class="[
                                            Math.round(
                                                (division.accepted /
                                                    division.max_interns) *
                                                    100
                                            ) >= 100
                                                ? 'bg-gradient-to-r from-green-400 to-green-500'
                                                : Math.round(
                                                      (division.accepted /
                                                          division.max_interns) *
                                                          100
                                                  ) >= 80
                                                ? 'bg-gradient-to-r from-yellow-400 to-orange-400'
                                                : 'bg-gradient-to-r from-blue-400 to-purple-500',
                                        ]"
                                        :style="`width: ${Math.min(
                                            (division.accepted /
                                                division.max_interns) *
                                                100,
                                            100
                                        )}%`"
                                    ></div>
                                </div>
                                <div
                                    class="flex justify-between text-xs text-gray-500"
                                >
                                    <span
                                        >{{ division.accepted }} /
                                        {{ division.max_interns }} Slot</span
                                    >
                                    <span
                                        v-if="
                                            division.max_interns -
                                                division.accepted >
                                            0
                                        "
                                        class="text-blue-600 font-medium"
                                    >
                                        {{
                                            division.max_interns -
                                            division.accepted
                                        }}
                                        tersisa
                                    </span>
                                    <span
                                        v-else
                                        class="text-green-600 font-medium"
                                    >
                                        Kuota terpenuhi ✓
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!divisionsData.length" class="text-center py-8">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3"
                        >
                            <svg
                                class="w-8 h-8 text-purple-400"
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
                        <p class="text-gray-500">
                            Belum ada divisi yang dibuat
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Actions -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <div
                class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Aksi Cepat
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Navigasi cepat ke fitur utama
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-2 h-2 bg-green-400 rounded-full animate-pulse"
                        ></div>
                        <span class="text-sm text-gray-500"
                            >Sistem siap digunakan</span
                        >
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
                >
                    <!-- Review Applications -->
                    <Link
                        :href="
                            safeRoute('admin.applications.index', {
                                status: 'menunggu',
                            })
                        "
                        class="group relative bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:border-yellow-300"
                    >
                        <div class="absolute top-4 right-4">
                            <div
                                class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center"
                            >
                                <span class="text-white font-bold text-sm">{{
                                    safeStats.pending_applications
                                }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
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
                            <h4
                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-yellow-700"
                            >
                                Review Pendaftar
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Tinjau pendaftaran baru
                            </p>
                            <div
                                class="inline-flex items-center text-sm font-medium text-yellow-700"
                            >
                                <span
                                    >{{
                                        safeStats.pending_applications
                                    }}
                                    menunggu</span
                                >
                                <svg
                                    class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
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
                            </div>
                        </div>
                        <div
                            class="absolute inset-0 bg-yellow-400 opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity"
                        ></div>
                    </Link>

                    <!-- Add Division -->
                    <Link
                        :href="safeRoute('admin.divisions.create')"
                        class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:border-blue-300"
                    >
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                            </div>
                            <h4
                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-700"
                            >
                                Tambah Divisi
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Buat divisi baru
                            </p>
                            <div
                                class="inline-flex items-center text-sm font-medium text-blue-700"
                            >
                                <span>Kelola organisasi</span>
                                <svg
                                    class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
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
                            </div>
                        </div>
                        <div
                            class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity"
                        ></div>
                    </Link>

                    <!-- Manage Supervisors -->
                    <Link
                        :href="safeRoute('admin.supervisors.index')"
                        class="group relative bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:border-green-300"
                    >
                        <div class="absolute top-4 right-4">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center"
                            >
                                <span class="text-white font-bold text-sm">{{
                                    safeStats.total_supervisors
                                }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
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
                            <h4
                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-green-700"
                            >
                                Kelola Pembimbing
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Manajemen mentor
                            </p>
                            <div
                                class="inline-flex items-center text-sm font-medium text-green-700"
                            >
                                <span
                                    >{{
                                        safeStats.total_supervisors
                                    }}
                                    pembimbing</span
                                >
                                <svg
                                    class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
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
                            </div>
                        </div>
                        <div
                            class="absolute inset-0 bg-green-400 opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity"
                        ></div>
                    </Link>

                    <!-- View Reports -->
                    <Link
                        :href="safeRoute('admin.reports.index')"
                        class="group relative bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:border-purple-300"
                    >
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
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
                            <h4
                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-700"
                            >
                                Laporan & Analytics
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Analisis data sistem
                            </p>
                            <div
                                class="inline-flex items-center text-sm font-medium text-purple-700"
                            >
                                <span>Lihat insights</span>
                                <svg
                                    class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
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
                            </div>
                        </div>
                        <div
                            class="absolute inset-0 bg-purple-400 opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity"
                        ></div>
                    </Link>
                </div>

                <!-- Enhanced Additional Actions Row -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">
                            Menu Tambahan
                        </h4>
                        <p class="text-sm text-gray-500">
                            Akses cepat ke semua fitur sistem
                        </p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 lg:gap-4">
                        <Link
                            :href="safeRoute('admin.participants.index')"
                            class="group flex items-center justify-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:bg-gradient-to-br hover:from-blue-100 hover:to-indigo-100 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 hover:shadow-lg"
                        >
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-200 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-blue-600 group-hover:text-blue-700"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-700 group-hover:text-blue-700 transition-colors"
                                    >Peserta</span
                                >
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ safeStats.total_participants || 0 }}
                                    aktif
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="safeRoute('admin.applications.index')"
                            class="group flex items-center justify-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:bg-gradient-to-br hover:from-green-100 hover:to-emerald-100 hover:border-green-300 transition-all duration-200 transform hover:scale-105 hover:shadow-lg"
                        >
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-green-200 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-green-600 group-hover:text-green-700"
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
                                <span
                                    class="text-sm font-semibold text-gray-700 group-hover:text-green-700 transition-colors"
                                    >Pendaftar</span
                                >
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ safeStats.total_applications || 0 }}
                                    total
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="safeRoute('admin.divisions.index')"
                            class="group flex items-center justify-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl hover:bg-gradient-to-br hover:from-purple-100 hover:to-pink-100 hover:border-purple-300 transition-all duration-200 transform hover:scale-105 hover:shadow-lg"
                        >
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-purple-200 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-purple-600 group-hover:text-purple-700"
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
                                <span
                                    class="text-sm font-semibold text-gray-700 group-hover:text-purple-700 transition-colors"
                                    >Divisi</span
                                >
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ safeStats.active_divisions || 0 }} aktif
                                </div>
                            </div>
                        </Link>

                        <button
                            @click="refreshData"
                            class="group flex items-center justify-center p-4 bg-gradient-to-br from-gray-50 to-slate-50 border border-gray-200 rounded-xl hover:bg-gradient-to-br hover:from-gray-100 hover:to-slate-100 hover:border-gray-300 transition-all duration-200 transform hover:scale-105 hover:shadow-lg"
                        >
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-gray-200 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-gray-600 group-hover:text-gray-700 transition-transform group-hover:rotate-180 duration-300"
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
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-700 group-hover:text-gray-800 transition-colors"
                                    >Refresh Data</span
                                >
                                <div class="text-xs text-gray-500 mt-1">
                                    Update terbaru
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_applications: 0,
            pending_applications: 0,
            accepted_applications: 0,
            active_divisions: 0,
            total_supervisors: 0,
            total_participants: 0,
        }),
    },
    recentApplications: {
        type: Array,
        default: () => [],
    },
    divisionsData: {
        type: Array,
        default: () => [],
    },
});

// Helper untuk route yang aman
const safeRoute = (routeName, params = {}) => {
    try {
        return router.urlFor
            ? router.urlFor(routeName, params)
            : route(routeName, params);
    } catch (error) {
        console.warn(`Route "${routeName}" not found, using fallback URL`);
        // Fallback URLs berdasarkan nama route
        const fallbackRoutes = {
            "admin.applications.index": "/admin/applications",
            "admin.participants.index": "/admin/participants",
            "admin.supervisors.index": "/admin/supervisors",
            "admin.divisions.index": "/admin/divisions",
            "admin.divisions.create": "/admin/divisions/create",
            "admin.reports.index": "/admin/reports",
        };

        let fallbackUrl = fallbackRoutes[routeName] || "#";

        // Tambahkan parameter jika ada
        if (params && Object.keys(params).length > 0) {
            const searchParams = new URLSearchParams(params);
            fallbackUrl += `?${searchParams.toString()}`;
        }

        return fallbackUrl;
    }
};

// Computed properties untuk data yang lebih aman
const safeStats = computed(() => ({
    total_applications: props.stats?.total_applications || 0,
    pending_applications: props.stats?.pending_applications || 0,
    accepted_applications: props.stats?.accepted_applications || 0,
    active_divisions: props.stats?.active_divisions || 0,
    total_supervisors: props.stats?.total_supervisors || 0,
    total_participants: props.stats?.total_participants || 0,
}));

const acceptanceRate = computed(() => {
    if (safeStats.value.total_applications === 0) return 0;
    return Math.round(
        (safeStats.value.accepted_applications /
            safeStats.value.total_applications) *
            100
    );
});

const pendingRate = computed(() => {
    if (safeStats.value.total_applications === 0) return 0;
    return Math.round(
        (safeStats.value.pending_applications /
            safeStats.value.total_applications) *
            100
    );
});

const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch (error) {
        return "-";
    }
};

// Helper untuk menentukan status sistem
const systemStatus = computed(() => {
    if (safeStats.value.pending_applications > 10) return "warning";
    if (safeStats.value.pending_applications > 0) return "info";
    return "success";
});

const systemStatusText = computed(() => {
    switch (systemStatus.value) {
        case "warning":
            return "Banyak Pendaftar pending";
        case "info":
            return "Ada Pendaftar perlu review";
        case "success":
            return "Semua terkendali";
        default:
            return "Normal";
    }
});

// Function untuk refresh data
const refreshData = () => {
    router.reload({
        only: ["stats", "recentApplications", "divisionsData"],
        preserveScroll: true,
    });
};

// Animation helper untuk smooth loading
const isLoading = computed(() => false); // Bisa dihubungkan dengan loading state dari Inertia
</script>
