<template>
    <Head title="Profil Saya" />

    <AuthenticatedLayout>
        <!-- Toast Notifications -->
        <div v-if="showNotification" class="fixed top-4 right-4 z-50">
            <div
                :class="[
                    'p-4 rounded-lg shadow-lg border transition-all duration-300',
                    notificationType === 'success'
                        ? 'bg-green-50 border-green-200 text-green-800'
                        : 'bg-red-50 border-red-200 text-red-800',
                ]"
            >
                <div class="flex items-center">
                    <svg
                        v-if="notificationType === 'success'"
                        class="w-5 h-5 mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5 mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ notificationMessage }}
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-50">
            <!-- Header Section -->
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Profil Saya
                            </h1>
                            <p class="text-gray-600">
                                Kelola informasi profil dan lamaran magang Anda
                            </p>
                        </div>
                        <!-- Progress Badge -->
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">
                                    Kelengkapan Profil
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div
                                        class="w-24 bg-gray-200 rounded-full h-2"
                                    >
                                        <div
                                            :class="[
                                                'h-2 rounded-full transition-all duration-300',
                                                profileCompletion.percentage >=
                                                80
                                                    ? 'bg-green-500'
                                                    : profileCompletion.percentage >=
                                                      50
                                                    ? 'bg-yellow-500'
                                                    : 'bg-red-500',
                                            ]"
                                            :style="`width: ${profileCompletion.percentage}%`"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium"
                                        >{{
                                            profileCompletion.percentage
                                        }}%</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <nav
                            class="bg-gradient-to-b from-blue-800 to-indigo-900 rounded-lg shadow-lg border border-blue-700 p-6"
                        >
                            <div class="space-y-2">
                                <button
                                    @click="activeTab = 'profile'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'profile'
                                            ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                            : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                    ]"
                                >
                                    <i class="fas fa-user mr-3"></i>
                                    Informasi Pribadi
                                </button>
                                <button
                                    @click="activeTab = 'documents'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'documents'
                                            ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                            : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                    ]"
                                >
                                    <i class="fas fa-file-alt mr-3"></i>
                                    Dokumen Persyaratan
                                </button>
                                <button
                                    @click="activeTab = 'applications'"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                        activeTab === 'applications'
                                            ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                            : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                    ]"
                                >
                                    <i class="fas fa-briefcase mr-3"></i>
                                    Status Lamaran
                                    <span
                                        v-if="applications.length"
                                        class="ml-2 bg-white text-blue-600 text-xs px-2 py-1 rounded-full"
                                    >
                                        {{ applications.length }}
                                    </span>
                                </button>
                                <a
                                    :href="route('home') + '#divisions'"
                                    class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-blue-100 hover:bg-blue-700 hover:bg-opacity-30 block"
                                >
                                    <i
                                        class="fas fa-external-link-alt mr-3"
                                    ></i>
                                    Daftar Magang
                                </a>
                            </div>

                            <!-- Logbook & Laporan Section (Only for accepted participants) -->
                            <div
                                v-if="hasAcceptedApplication"
                                class="mt-6 pt-4 border-t border-blue-600 border-opacity-40"
                            >
                                <h3
                                    class="text-sm font-medium text-blue-100 mb-3"
                                >
                                    Logbook & Laporan
                                </h3>
                                <div class="space-y-1">
                                    <button
                                        @click="activeTab = 'logbook'"
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                            activeTab === 'logbook'
                                                ? 'bg-blue-700 bg-opacity-40 text-white'
                                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                        ]"
                                    >
                                        <i class="fas fa-book mr-3"></i>
                                        Logbook Harian
                                    </button>
                                    <button
                                        @click="activeTab = 'reports'"
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                            activeTab === 'reports'
                                                ? 'bg-blue-700 bg-opacity-40 text-white'
                                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                        ]"
                                    >
                                        <i class="fas fa-chart-line mr-3"></i>
                                        Laporan Akhir
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Links -->
                            <div
                                class="mt-8 pt-6 border-t border-blue-600 border-opacity-40"
                            >
                                <h3
                                    class="text-sm font-medium text-blue-100 mb-3"
                                >
                                    Navigasi Cepat
                                </h3>
                                <div class="space-y-2">
                                    <Link
                                        href="/"
                                        class="block text-sm text-blue-100 hover:text-white"
                                    >
                                        <i class="fas fa-home mr-2"></i>
                                        Beranda
                                    </Link>
                                    <Link
                                        href="/divisi"
                                        class="block text-sm text-blue-100 hover:text-white"
                                    >
                                        <i class="fas fa-building mr-2"></i>
                                        Lowongan Magang
                                    </Link>
                                    <Link
                                        href="/cek-status"
                                        class="block text-sm text-blue-100 hover:text-white"
                                    >
                                        <i class="fas fa-search mr-2"></i>
                                        Cek Status
                                    </Link>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <!-- Informasi Pribadi Tab -->
                        <div
                            v-if="activeTab === 'profile'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">
                                    Informasi Pribadi
                                </h2>
                                <button
                                    @click="editMode = !editMode"
                                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                >
                                    {{ editMode ? "Batal" : "Edit Profil" }}
                                </button>
                            </div>

                            <form @submit.prevent="updateProfile">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- Profile Photo -->
                                    <div
                                        class="md:col-span-2 flex items-center space-x-6"
                                    >
                                        <div class="relative">
                                            <img
                                                :src="
                                                    user.profile_photo_path
                                                        ? `/storage/${user.profile_photo_path}`
                                                        : '/images/default-avatar.png'
                                                "
                                                alt="Profile"
                                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                                            />
                                            <label
                                                v-if="editMode"
                                                class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 transition-colors"
                                            >
                                                <i
                                                    class="fas fa-camera text-xs"
                                                ></i>
                                                <input
                                                    type="file"
                                                    @change="uploadPhoto"
                                                    accept="image/*"
                                                    class="hidden"
                                                />
                                            </label>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                            </h3>
                                            <p class="text-gray-500">
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Nama Lengkap</label
                                        >
                                        <input
                                            v-model="profileForm.name"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Masukkan nama lengkap"
                                        />
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Email</label
                                        >
                                        <input
                                            v-model="profileForm.email"
                                            :disabled="!editMode"
                                            type="email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="email@example.com"
                                        />
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Nomor Telepon</label
                                        >
                                        <input
                                            v-model="profileForm.phone"
                                            :disabled="!editMode"
                                            type="tel"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="08xxxxxxxxxx"
                                        />
                                    </div>

                                    <!-- University -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Universitas</label
                                        >
                                        <input
                                            v-model="profileForm.university"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Nama universitas"
                                        />
                                    </div>

                                    <!-- Major -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Jurusan</label
                                        >
                                        <input
                                            v-model="profileForm.major"
                                            :disabled="!editMode"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Jurusan/Program studi"
                                        />
                                    </div>

                                    <!-- Semester -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Semester</label
                                        >
                                        <select
                                            v-model="profileForm.semester"
                                            :disabled="!editMode"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                        >
                                            <option value="">
                                                Pilih semester
                                            </option>
                                            <option
                                                v-for="i in 14"
                                                :key="i"
                                                :value="i"
                                            >
                                                Semester {{ i }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Alamat Lengkap</label
                                        >
                                        <textarea
                                            v-model="profileForm.address"
                                            :disabled="!editMode"
                                            rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="Alamat lengkap sesuai KTP"
                                        ></textarea>
                                    </div>
                                </div>

                                <div
                                    v-if="editMode"
                                    class="mt-6 flex justify-end space-x-4"
                                >
                                    <button
                                        type="button"
                                        @click="editMode = false"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="profileForm.processing"
                                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                                    >
                                        {{
                                            profileForm.processing
                                                ? "Menyimpan..."
                                                : "Simpan Perubahan"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Dokumen Persyaratan Tab -->
                        <div
                            v-if="activeTab === 'documents'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                        >
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-6"
                            >
                                Dokumen Persyaratan
                            </h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Surat Pengantar -->
                                <DocumentUpload
                                    title="Surat Pengantar"
                                    type="surat_pengantar"
                                    :current-file="user.surat_pengantar_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- CV -->
                                <DocumentUpload
                                    title="Curriculum Vitae (CV)"
                                    type="cv"
                                    :current-file="user.cv_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Motivation Letter -->
                                <DocumentUpload
                                    title="Motivation Letter"
                                    type="motivation_letter"
                                    :current-file="user.motivation_letter_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Transkrip -->
                                <DocumentUpload
                                    title="Transkrip Nilai"
                                    type="transkrip"
                                    :current-file="user.transkrip_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- KTP -->
                                <DocumentUpload
                                    title="KTP"
                                    type="ktp"
                                    :current-file="user.ktp_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- NPWP -->
                                <DocumentUpload
                                    title="NPWP"
                                    type="npwp"
                                    :current-file="user.npwp_path"
                                    @upload="uploadDocument"
                                />

                                <!-- Buku Rekening -->
                                <DocumentUpload
                                    title="Buku Rekening Tabungan"
                                    type="buku_rekening"
                                    :current-file="user.buku_rekening_path"
                                    @upload="uploadDocument"
                                    required
                                />

                                <!-- Pas Foto -->
                                <DocumentUpload
                                    title="Pas Foto 3x4 atau 4x6"
                                    type="pas_foto"
                                    :current-file="user.pas_foto_path"
                                    @upload="uploadDocument"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Status Lamaran Tab -->
                        <div
                            v-if="activeTab === 'applications'"
                            class="space-y-6"
                        >
                            <div
                                v-if="applications.length === 0"
                                class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center"
                            >
                                <div
                                    class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
                                >
                                    <i
                                        class="fas fa-briefcase text-2xl text-gray-400"
                                    ></i>
                                </div>
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-2"
                                >
                                    Belum Ada Lamaran
                                </h3>
                                <p class="text-gray-500 mb-6">
                                    Anda belum memiliki lamaran magang. Mulai
                                    lamar sekarang!
                                </p>
                                <a
                                    :href="route('home') + '#divisions'"
                                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Daftar Magang Sekarang
                                </a>
                            </div>

                            <div v-else class="space-y-4">
                                <ApplicationCard
                                    v-for="application in applications"
                                    :key="application.id"
                                    :application="application"
                                />
                            </div>
                        </div>

                        <!-- Logbook Tab -->
                        <div
                            v-if="activeTab === 'logbook'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200"
                        >
                            <!-- Header -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2
                                            class="text-xl font-semibold text-gray-900"
                                        >
                                            Logbook Harian
                                        </h2>
                                        <p class="text-gray-600 mt-1">
                                            Catat perkembangan dan kegiatan
                                            harian selama masa magang
                                        </p>
                                    </div>
                                    <button
                                        @click="showCreateLogbookModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
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
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Tambah Logbook
                                    </button>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="p-6 border-b border-gray-200">
                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                                >
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-blue-100 text-sm font-medium"
                                                >
                                                    Total
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        logbookStats?.total_logbooks ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-blue-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-orange-100 text-sm font-medium"
                                                >
                                                    Pending
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        logbookStats?.pending_logbooks ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-orange-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                    <div
                                        class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-green-100 text-sm font-medium"
                                                >
                                                    Disetujui
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    0
                                                </p>
                                            </div>
                                            <div
                                                class="bg-green-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                    <div
                                        class="bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-purple-100 text-sm font-medium"
                                                >
                                                    Revisi
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        logbookStats?.revision_logbooks ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-purple-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div
                                    v-if="logbooks.length === 0"
                                    class="text-center py-12"
                                >
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400 mb-4"
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
                                    <h3
                                        class="text-lg font-medium text-gray-900 mb-2"
                                    >
                                        Belum ada logbook
                                    </h3>
                                    <p class="text-gray-500 mb-4">
                                        Mulai catat kegiatan harian Anda dengan
                                        membuat logbook pertama.
                                    </p>
                                    <button
                                        @click="showCreateLogbookModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Buat Logbook
                                    </button>
                                </div>

                                <!-- Logbooks List -->
                                <div v-else class="space-y-6">
                                    <div
                                        v-for="logbook in logbooks"
                                        :key="logbook.id"
                                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300 group"
                                    >
                                        <!-- Header -->
                                        <div
                                            class="flex items-start justify-between mb-4"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg"
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
                                                <div>
                                                    <h4
                                                        class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors"
                                                    >
                                                        {{
                                                            formatDate(
                                                                logbook.date
                                                            )
                                                        }}
                                                    </h4>
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            logbook.division
                                                                ?.name ||
                                                            "Tidak ada divisi"
                                                        }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <!-- Duration Badge -->
                                                <div
                                                    class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg"
                                                >
                                                    <svg
                                                        class="w-4 h-4 text-gray-400 mr-1.5"
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
                                                    <span
                                                        class="text-sm font-medium text-gray-600"
                                                        >{{
                                                            logbook.duration
                                                        }}h</span
                                                    >
                                                </div>

                                                <!-- Status Badge -->
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold"
                                                    :class="{
                                                        'bg-amber-50 text-amber-700 border border-amber-200':
                                                            logbook.status ===
                                                            'submitted',
                                                        'bg-emerald-50 text-emerald-700 border border-emerald-200':
                                                            logbook.status ===
                                                            'approved',
                                                        'bg-red-50 text-red-700 border border-red-200':
                                                            logbook.status ===
                                                            'revision',
                                                        'bg-gray-50 text-gray-700 border border-gray-200':
                                                            logbook.status ===
                                                            'draft',
                                                    }"
                                                >
                                                    <div
                                                        class="w-2 h-2 rounded-full mr-2"
                                                        :class="{
                                                            'bg-amber-400':
                                                                logbook.status ===
                                                                'submitted',
                                                            'bg-emerald-400':
                                                                logbook.status ===
                                                                'approved',
                                                            'bg-red-400':
                                                                logbook.status ===
                                                                'revision',
                                                            'bg-gray-400':
                                                                logbook.status ===
                                                                'draft',
                                                        }"
                                                    ></div>
                                                    {{
                                                        logbook.status ===
                                                        "submitted"
                                                            ? "Pending"
                                                            : logbook.status ===
                                                              "approved"
                                                            ? "Disetujui"
                                                            : logbook.status ===
                                                              "revision"
                                                            ? "Revisi"
                                                            : "Draft"
                                                    }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="space-y-4">
                                            <div>
                                                <h5
                                                    class="text-base font-semibold text-gray-800 mb-2"
                                                >
                                                    {{ logbook.title }}
                                                </h5>
                                                <p
                                                    class="text-gray-600 text-sm leading-relaxed line-clamp-3"
                                                >
                                                    {{ logbook.activities }}
                                                </p>
                                            </div>

                                            <!-- Learning Points (if exists) -->
                                            <div
                                                v-if="logbook.learning_points"
                                                class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-400"
                                            >
                                                <h6
                                                    class="text-xs font-semibold text-blue-800 mb-1 uppercase tracking-wide"
                                                >
                                                    Poin Pembelajaran
                                                </h6>
                                                <p
                                                    class="text-sm text-blue-700"
                                                >
                                                    {{
                                                        logbook.learning_points
                                                    }}
                                                </p>
                                            </div>

                                            <!-- Challenges (if exists) -->
                                            <div
                                                v-if="logbook.challenges"
                                                class="bg-orange-50 rounded-lg p-3 border-l-4 border-orange-400"
                                            >
                                                <h6
                                                    class="text-xs font-semibold text-orange-800 mb-1 uppercase tracking-wide"
                                                >
                                                    Tantangan
                                                </h6>
                                                <p
                                                    class="text-sm text-orange-700"
                                                >
                                                    {{ logbook.challenges }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div
                                            class="flex items-center justify-between pt-4 border-t border-gray-100 mt-4"
                                        >
                                            <div
                                                class="flex items-center text-xs text-gray-500"
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
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    />
                                                </svg>
                                                Dibuat
                                                {{
                                                    formatDate(
                                                        logbook.created_at
                                                    )
                                                }}
                                            </div>

                                            <!-- Actions -->
                                            <div
                                                class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <button
                                                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
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
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                        />
                                                    </svg>
                                                </button>
                                                <button
                                                    class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
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
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- View More Button -->
                                <div
                                    v-if="logbooks.length >= 5"
                                    class="text-center pt-4"
                                >
                                    <p class="text-sm text-gray-500">
                                        Menampilkan 5 logbook terbaru
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Reports Tab -->
                        <div
                            v-if="activeTab === 'reports'"
                            class="bg-white rounded-lg shadow-sm border border-gray-200"
                        >
                            <!-- Header -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2
                                            class="text-xl font-semibold text-gray-900"
                                        >
                                            Laporan Akhir
                                        </h2>
                                        <p class="text-gray-600 mt-1">
                                            Kelola laporan dan statistik magang
                                            Anda
                                        </p>
                                    </div>
                                    <button
                                        @click="showCreateReportModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
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
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Buat Laporan
                                    </button>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="p-6 border-b border-gray-200">
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-4"
                                >
                                    Statistik Laporan Akhir
                                </h3>
                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                                >
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-blue-100 text-sm font-medium"
                                                >
                                                    Total Laporan
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        reportStats?.total_reports ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-blue-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                    <div
                                        class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-orange-100 text-sm font-medium"
                                                >
                                                    Pending
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        reportStats?.pending_reports ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-orange-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                    <div
                                        class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-green-100 text-sm font-medium"
                                                >
                                                    Disetujui
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        reportStats?.approved_reports ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-green-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                    <div
                                        class="bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-purple-100 text-sm font-medium"
                                                >
                                                    Perlu Revisi
                                                </p>
                                                <p class="text-3xl font-bold">
                                                    {{
                                                        reportStats?.revision_reports ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-purple-400 bg-opacity-30 rounded-full p-3"
                                            >
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
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div
                                    v-if="!reports || reports.length === 0"
                                    class="text-center py-12"
                                >
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400 mb-4"
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
                                    <h3
                                        class="text-lg font-medium text-gray-900 mb-2"
                                    >
                                        Belum ada laporan
                                    </h3>
                                    <p class="text-gray-500 mb-4">
                                        Upload laporan akhir berdasarkan
                                        kegiatan magang Anda.
                                    </p>
                                    <button
                                        @click="showCreateReportModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Buat Laporan
                                    </button>
                                </div>

                                <!-- Reports List -->
                                <div
                                    v-else-if="reports && reports.length > 0"
                                    class="space-y-6"
                                >
                                    <div
                                        v-for="report in reports"
                                        :key="report?.id || Math.random()"
                                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all duration-300 group"
                                    >
                                        <!-- Header -->
                                        <div
                                            class="flex items-start justify-between mb-4"
                                        >
                                            <div
                                                class="flex items-center space-x-4"
                                            >
                                                <div
                                                    class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg"
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
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors"
                                                    >
                                                        {{
                                                            report?.title ||
                                                            "Laporan Tanpa Judul"
                                                        }}
                                                    </h4>
                                                    <p
                                                        class="text-sm text-gray-500 mt-1"
                                                    >
                                                        Laporan Akhir Magang
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <span
                                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm border"
                                                :class="{
                                                    'bg-amber-50 text-amber-700 border-amber-200':
                                                        (report?.status ||
                                                            'submitted') ===
                                                        'submitted',
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200':
                                                        (report?.status ||
                                                            'submitted') ===
                                                        'approved',
                                                    'bg-red-50 text-red-700 border-red-200':
                                                        (report?.status ||
                                                            'submitted') ===
                                                        'revision',
                                                }"
                                            >
                                                <div
                                                    class="w-2.5 h-2.5 rounded-full mr-2"
                                                    :class="{
                                                        'bg-amber-400':
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'submitted',
                                                        'bg-emerald-400':
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'approved',
                                                        'bg-red-400':
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'revision',
                                                    }"
                                                ></div>
                                                {{
                                                    (report?.status ||
                                                        "submitted") ===
                                                    "submitted"
                                                        ? "Pending"
                                                        : (report?.status ||
                                                              "submitted") ===
                                                          "approved"
                                                        ? "Disetujui"
                                                        : "Perlu Revisi"
                                                }}
                                            </span>
                                        </div>

                                        <!-- Content -->
                                        <div class="space-y-4">
                                            <div
                                                class="bg-gray-50 rounded-xl p-4 border-l-4 border-emerald-400"
                                            >
                                                <h6
                                                    class="text-xs font-semibold text-emerald-800 mb-2 uppercase tracking-wide"
                                                >
                                                    Deskripsi Laporan
                                                </h6>
                                                <p
                                                    class="text-gray-700 leading-relaxed"
                                                >
                                                    {{
                                                        report?.description ||
                                                        "Tidak ada deskripsi"
                                                    }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div
                                            class="flex items-center justify-between pt-4 border-t border-gray-100 mt-6"
                                        >
                                            <div
                                                class="flex items-center text-sm text-gray-500"
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
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    />
                                                </svg>
                                                Dibuat
                                                {{
                                                    report?.created_at
                                                        ? formatDate(
                                                              report.created_at
                                                          )
                                                        : "N/A"
                                                }}
                                            </div>

                                            <!-- Actions -->
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <a
                                                    v-if="report?.file_path"
                                                    :href="`/storage/${report.file_path}`"
                                                    target="_blank"
                                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 shadow-sm transition-all duration-200 group-hover:shadow-md"
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
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                        />
                                                    </svg>
                                                    Download
                                                </a>

                                                <!-- View Button -->
                                                <button
                                                    class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100"
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
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </button>

                                                <!-- Share Button -->
                                                <button
                                                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100"
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
                                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                                        />
                                                    </svg>
                                                </button>
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

        <!-- Create Logbook Modal -->
        <div
            v-if="showCreateLogbookModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="showCreateLogbookModal = false"
        >
            <div
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center"
            >
                <div
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                ></div>

                <div
                    class="relative inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                >
                    <!-- Modal Header -->
                    <div
                        class="flex items-center justify-between mb-6 pb-4 border-b"
                    >
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Tambah Logbook Harian
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Catat aktivitas dan pencapaian hari ini
                            </p>
                        </div>
                        <button
                            @click="showCreateLogbookModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
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

                    <!-- Modal Form -->
                    <form @submit.prevent="submitLogbook" class="space-y-6">
                        <!-- Date and Hours Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="date"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date"
                                    v-model="createLogbookForm.date"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div
                                    v-if="createLogbookForm.errors.date"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createLogbookForm.errors.date }}
                                </div>
                            </div>

                            <div>
                                <label
                                    for="duration"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Durasi (jam)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="duration"
                                    v-model="createLogbookForm.duration"
                                    min="1"
                                    max="12"
                                    step="0.5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="8"
                                    required
                                />
                                <div
                                    v-if="createLogbookForm.errors.duration"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createLogbookForm.errors.duration }}
                                </div>
                            </div>
                        </div>

                        <!-- Activity Title -->
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Judul Aktivitas
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                v-model="createLogbookForm.title"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: Pengembangan Fitur Login System"
                                required
                            />
                            <div
                                v-if="createLogbookForm.errors.title"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createLogbookForm.errors.title }}
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div>
                            <label
                                for="activities"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Deskripsi Aktivitas
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="activities"
                                v-model="createLogbookForm.activities"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan secara detail aktivitas yang telah dilakukan hari ini..."
                                required
                            ></textarea>
                            <div
                                v-if="createLogbookForm.errors.activities"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createLogbookForm.errors.activities }}
                            </div>
                        </div>

                        <!-- Learning Points -->
                        <div>
                            <label
                                for="learning_points"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Pembelajaran yang Didapat
                            </label>
                            <textarea
                                id="learning_points"
                                v-model="createLogbookForm.learning_points"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Apa yang Anda pelajari hari ini..."
                            ></textarea>
                            <div
                                v-if="createLogbookForm.errors.learning_points"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createLogbookForm.errors.learning_points }}
                            </div>
                        </div>

                        <!-- Challenges -->
                        <div>
                            <label
                                for="challenges"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Kendala yang Dihadapi
                            </label>
                            <textarea
                                id="challenges"
                                v-model="createLogbookForm.challenges"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Kendala atau kesulitan yang dihadapi..."
                            ></textarea>
                            <div
                                v-if="createLogbookForm.errors.challenges"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createLogbookForm.errors.challenges }}
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button
                                type="button"
                                @click="showCreateLogbookModal = false"
                                class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                :disabled="createLogbookForm.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                            >
                                <span v-if="createLogbookForm.processing"
                                    >Menyimpan...</span
                                >
                                <span v-else>Simpan Logbook</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Report Modal -->
        <div
            v-if="showCreateReportModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="showCreateReportModal = false"
        >
            <div
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center"
            >
                <div
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                ></div>

                <div
                    class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                >
                    <!-- Modal Header -->
                    <div
                        class="flex items-center justify-between mb-6 pb-4 border-b"
                    >
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Upload Laporan Akhir
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Upload laporan akhir berdasarkan kegiatan magang
                                Anda
                            </p>
                        </div>
                        <button
                            @click="showCreateReportModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
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

                    <!-- Report Form -->
                    <form @submit.prevent="submitReport" class="space-y-6">
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Judul Laporan
                            </label>
                            <input
                                v-model="reportForm.title"
                                type="text"
                                id="title"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan judul laporan"
                            />
                        </div>

                        <div>
                            <label
                                for="description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Deskripsi
                            </label>
                            <textarea
                                v-model="reportForm.description"
                                id="description"
                                rows="4"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan ringkasan laporan Anda"
                            ></textarea>
                        </div>

                        <div>
                            <label
                                for="report_file"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                File Laporan (PDF)
                            </label>
                            <input
                                @change="handleFileChange"
                                type="file"
                                id="report_file"
                                accept=".pdf"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <p class="text-sm text-gray-500 mt-1">
                                File harus berformat PDF dan maksimal 10MB
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-3 pt-4">
                            <button
                                type="button"
                                @click="showCreateReportModal = false"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                :disabled="reportForm.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="reportForm.processing">
                                    <svg
                                        class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline"
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
                                    Menyimpan...
                                </span>
                                <span v-else>Simpan Laporan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DocumentUpload from "@/Components/DocumentUpload.vue";
import ApplicationCard from "@/Components/ApplicationCard.vue";

const props = defineProps({
    user: Object,
    applications: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    logbooks: { type: Array, default: () => [] },
    logbookStats: { type: Object, default: () => ({}) },
    reports: { type: Array, default: () => [] },
    reportStats: { type: Object, default: () => ({}) },
    profileCompletion: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

// Reactive user data to handle updates
const user = reactive({ ...props.user });

// State
const activeTab = ref("profile");
const editMode = ref(false);
const showNotification = ref(false);
const notificationType = ref("success");
const notificationMessage = ref("");

// Modal state for logbook creation
const showCreateLogbookModal = ref(false);

// Modal state for report creation
const showCreateReportModal = ref(false);

// Computed property to check if user has accepted application
const hasAcceptedApplication = computed(() => {
    return props.applications.some((app) => app.status === "diterima");
});

// Computed property to get accepted application details
const acceptedApplication = computed(() => {
    return props.applications.find((app) => app.status === "diterima");
});

// Format date helper
const formatDate = (date) => {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date(date).toLocaleDateString("id-ID", options);
};

// Forms
const profileForm = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone,
    address: user.address,
    university: user.university,
    major: user.major,
    semester: user.semester,
});

// Logbook creation form
const createLogbookForm = useForm({
    date: new Date().toISOString().split("T")[0], // Today's date
    duration: 8,
    title: "",
    activities: "",
    learning_points: "",
    challenges: "",
    status: "submitted", // Default to submitted
});

// Methods
const showToast = (type, message) => {
    notificationType.value = type;
    notificationMessage.value = message;
    showNotification.value = true;
    setTimeout(() => {
        showNotification.value = false;
    }, 5000);
};

const updateProfile = () => {
    profileForm.patch(route("profile.update"), {
        onSuccess: (page) => {
            editMode.value = false;
            // Update reactive user data with the latest data from server
            Object.assign(user, page.props.user);
            // Update form data as well
            profileForm.data = {
                name: user.name,
                email: user.email,
                phone: user.phone,
                address: user.address,
                university: user.university,
                major: user.major,
                semester: user.semester,
            };
            showToast("success", "Profil berhasil diperbarui!");
        },
        onError: (errors) => {
            console.error("Profile update errors:", errors);
            showToast("error", "Gagal memperbarui profil!");
        },
    });
};

const uploadPhoto = (event) => {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append("photo", file);

        window.axios
            .post(route("profile.upload-photo"), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then(() => {
                window.location.reload();
            })
            .catch(() => {
                showToast("error", "Gagal mengunggah foto!");
            });
    }
};

const submitLogbook = () => {
    createLogbookForm.post(route("profile.logbooks.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateLogbookModal.value = false;
            createLogbookForm.reset();
            showToast("success", "Logbook berhasil ditambahkan!");
            // Refresh the page to update logbook data
            setTimeout(() => {
                router.reload({ only: ["logbooks", "logbookStats"] });
            }, 1000);
        },
        onError: (errors) => {
            console.error("Logbook submission errors:", errors);
            if (errors.error) {
                showToast("error", errors.error);
            } else if (errors.date) {
                showToast("error", errors.date);
            } else {
                showToast(
                    "error",
                    "Gagal menambahkan logbook! Periksa semua field yang wajib diisi."
                );
            }
        },
    });
};

const submitReport = () => {
    reportForm.post(route("profile.reports.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateReportModal.value = false;
            reportForm.reset();
            showToast("success", "Laporan berhasil diupload!");
            // Refresh the page to update report data
            setTimeout(() => {
                router.reload({ only: ["reports", "reportStats"] });
            }, 1000);
        },
        onError: (errors) => {
            console.error("Report submission errors:", errors);
            if (errors.error) {
                showToast("error", errors.error);
            } else if (errors.title) {
                showToast("error", errors.title);
            } else if (errors.report_file) {
                showToast("error", errors.report_file);
            } else {
                showToast(
                    "error",
                    "Gagal upload laporan! Periksa semua field yang wajib diisi."
                );
            }
        },
    });
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        reportForm.report_file = file;
    }
};

const uploadDocument = (type, file) => {
    const formData = new FormData();
    formData.append("document_type", type);
    formData.append("document", file);

    window.axios
        .post(route("profile.upload-document"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Accept: "application/json",
            },
        })
        .then((response) => {
            // Update reactive user data in-place without page reload
            if (response.data.user) {
                Object.assign(user, response.data.user);
            }
            showToast(
                "success",
                response.data.message || "Dokumen berhasil diunggah!"
            );
        })
        .catch((error) => {
            const message =
                error.response?.data?.message || "Gagal mengunggah dokumen!";
            showToast("error", message);
        });
};

// Helper function to get status text
const getStatusText = (status) => {
    const statusMap = {
        menunggu: "Pending",
        dalam_review: "Sedang Direview",
        wawancara: "Tahap Wawancara",
        diterima: "Diterima",
        ditolak: "Ditolak",
    };
    return statusMap[status] || status;
};

// Check for flash messages and handle navigation
onMounted(() => {
    if (props.status) {
        showToast("success", props.status);
    }

    // Check for missing fields from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const missingFields = urlParams.get("missing_fields");
    if (missingFields) {
        const fieldNames = missingFields.split(",");
        const fieldLabels = {
            phone: "Nomor Telepon",
            address: "Alamat",
            university: "Universitas",
            major: "Jurusan",
            semester: "Semester",
        };
        const missingLabels = fieldNames
            .map((field) => fieldLabels[field] || field)
            .join(", ");
        showToast("error", `Harap lengkapi: ${missingLabels}`);

        // Auto switch to profile tab and enable edit mode
        activeTab.value = "profile";
        editMode.value = true;
    }

    // Check if user came from successful application submission
    if (
        urlParams.get("from") === "application" ||
        urlParams.get("success") === "application"
    ) {
        // Switch to applications tab automatically
        activeTab.value = "applications";
        showToast(
            "success",
            "Selamat! Lamaran Anda telah berhasil dikirim. Anda dapat memantau statusnya di sini."
        );
    }

    // Check if there are new applications to highlight
    if (props.applications && props.applications.length > 0) {
        const latestApplication = props.applications[0];
        const applicationTime = new Date(latestApplication.created_at);
        const currentTime = new Date();
        const timeDiff = (currentTime - applicationTime) / (1000 * 60); // minutes

        // If application was submitted in the last 5 minutes, highlight it
        if (timeDiff < 5) {
            setTimeout(() => {
                activeTab.value = "applications";
                showToast(
                    "info",
                    `Status lamaran terbaru: ${getStatusText(
                        latestApplication.status
                    )}`
                );
            }, 1000);
        }
    }
});
</script>
