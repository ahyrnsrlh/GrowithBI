<template>
    <Head title="Profil Saya" />

    <div>
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
            <!-- Header Navigation -->
            <div
                class="sticky top-0 z-50 bg-gradient-to-r from-blue-800 to-indigo-900 border-b border-blue-600 shadow-lg"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <!-- Logo BI + Text -->
                        <div class="flex items-center space-x-3">
                            <Link :href="route('home')">
                                <img
                                    src="/logo.png"
                                    alt="Bank Indonesia"
                                    class="h-10 w-auto"
                                />
                            </Link>
                            <div class="hidden sm:block">
                                <h1 class="text-white font-bold text-lg">
                                    GrowithBI
                                </h1>
                                <p class="text-blue-200 text-xs">
                                    Bank Indonesia Lampung
                                </p>
                            </div>
                        </div>

                        <!-- Right Section: Notification Bell & User Dropdown -->
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <NotificationBell
                                v-if="$page.props.auth?.user"
                                :userId="$page.props.auth.user.id"
                            />

                            <!-- User Dropdown -->
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-transparent px-3 py-2 text-sm font-medium leading-4 text-blue-100 transition duration-150 ease-in-out hover:text-white focus:outline-none"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <!-- End Right Section -->
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <div class="lg:sticky lg:top-24">
                            <nav
                                class="bg-gradient-to-b from-blue-800 to-indigo-900 rounded-lg shadow-lg border border-blue-700 p-6 max-h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar"
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
                                        <Link
                                            :href="
                                                route(
                                                    'profile.attendance.index'
                                                )
                                            "
                                            class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors text-blue-100 hover:bg-blue-700 hover:bg-opacity-30 block"
                                        >
                                            <i class="fas fa-clock mr-3"></i>
                                            Absensi Online
                                        </Link>
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
                                            <i
                                                class="fas fa-chart-line mr-3"
                                            ></i>
                                            Laporan Akhir
                                        </button>
                                    </div>
                                </div>

                                <!-- Logout Section -->
                                <div
                                    class="mt-8 pt-6 border-t border-blue-600 border-opacity-40"
                                >
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-red-200 hover:bg-red-700 hover:bg-opacity-30 hover:text-white flex items-center"
                                    >
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Log Out
                                    </Link>
                                </div>
                            </nav>
                        </div>
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

                                    <!-- IPK/GPA -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >IPK</label
                                        >
                                        <input
                                            v-model="profileForm.gpa"
                                            :disabled="!editMode"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="4"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                            placeholder="contoh: 3.75"
                                        />
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Tanggal Lahir</label
                                        >
                                        <input
                                            v-model="profileForm.birth_date"
                                            :disabled="!editMode"
                                            type="date"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                        />
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                            >Jenis Kelamin</label
                                        >
                                        <select
                                            v-model="profileForm.gender"
                                            :disabled="!editMode"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                                        >
                                            <option value="">
                                                Pilih jenis kelamin
                                            </option>
                                            <option value="male">
                                                Laki-laki
                                            </option>
                                            <option value="female">
                                                Perempuan
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
                            class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-100"
                        >
                            <!-- Header Modern -->
                            <div
                                class="p-6 border-b border-gray-100 bg-white rounded-t-xl"
                            >
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                                >
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"
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
                                            <h2
                                                class="text-2xl font-bold text-blue-600"
                                            >
                                                Dokumen Persyaratan
                                            </h2>
                                            <p
                                                class="text-sm text-gray-600 mt-1"
                                            >
                                                Lengkapi semua dokumen yang
                                                diperlukan untuk proses magang
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Progress Badge -->
                                    <div
                                        class="flex items-center gap-3 bg-blue-50 px-4 py-2.5 rounded-lg border border-blue-100"
                                    >
                                        <div class="flex items-center gap-2">
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
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                class="text-sm font-semibold text-blue-900"
                                            >
                                                {{ documentProgress }}% Complete
                                            </span>
                                        </div>
                                        <div
                                            class="w-24 h-2 bg-blue-200 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500"
                                                :style="{
                                                    width:
                                                        documentProgress + '%',
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Grid with Modern Cards -->
                            <div class="p-6 bg-gray-50">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"
                                >
                                    <!-- Surat Pengantar -->
                                    <DocumentUpload
                                        title="Surat Pengantar"
                                        type="surat_pengantar"
                                        :current-file="
                                            user.surat_pengantar_path
                                        "
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
                                        :current-file="
                                            user.motivation_letter_path
                                        "
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
                            class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-100"
                        >
                            <!-- Header dengan BI Theme -->
                            <div
                                class="p-6 border-b border-gray-100 bg-white rounded-t-xl"
                            >
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                                >
                                    <div>
                                        <h2
                                            class="text-2xl font-bold text-blue-600"
                                        >
                                            Logbook Harian
                                        </h2>
                                        <p class="text-gray-600 mt-1 text-sm">
                                            Catat perkembangan dan kegiatan
                                            harian selama masa magang
                                        </p>
                                    </div>
                                    <button
                                        @click="showCreateLogbookModal = true"
                                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
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

                            <!-- Statistics - Compact Modern Cards -->
                            <div class="p-6 border-b border-gray-100 bg-white">
                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                                >
                                    <div
                                        class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-blue-100 text-xs font-medium uppercase tracking-wide"
                                                >
                                                    Total
                                                </p>
                                                <p
                                                    class="text-3xl font-bold mt-1"
                                                >
                                                    {{
                                                        logbookStats?.total_logbooks ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-white bg-opacity-20 rounded-lg p-2.5"
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
                            <div class="p-6 bg-gray-50">
                                <!-- Empty State -->
                                <div
                                    v-if="logbooks.length === 0"
                                    class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16"
                                >
                                    <div
                                        class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center"
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
                                                stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-2"
                                    >
                                        Belum Ada Logbook
                                    </h3>
                                    <p
                                        class="text-gray-600 mb-6 max-w-md mx-auto"
                                    >
                                        Mulai catat kegiatan harian Anda dengan
                                        membuat logbook pertama.
                                    </p>
                                    <button
                                        @click="showCreateLogbookModal = true"
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
                                        Buat Logbook Pertama
                                    </button>
                                </div>

                                <!-- Logbooks Grid - Modern Card Layout -->
                                <div v-else>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <template
                                            v-for="logbook in logbooks"
                                            :key="logbook?.id || Math.random()"
                                        >
                                            <div
                                                v-if="logbook?.id"
                                                class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group"
                                            >
                                                <!-- Card Header - Compact Design -->
                                                <div
                                                    class="px-5 py-4 bg-gradient-to-r from-[#F6F8FA] to-white border-b border-gray-100"
                                                >
                                                    <div
                                                        class="flex items-start justify-between gap-3"
                                                    >
                                                        <div
                                                            class="flex-1 min-w-0"
                                                        >
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
                                                                <span
                                                                    class="font-medium truncate"
                                                                    >{{
                                                                        formatDate(
                                                                            logbook?.date
                                                                        )
                                                                    }}</span
                                                                >
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
                                                                <span
                                                                    class="font-semibold"
                                                                    >{{
                                                                        logbook?.duration ||
                                                                        0
                                                                    }}h</span
                                                                >
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="px-3 py-1 rounded-full text-xs font-semibold flex-shrink-0"
                                                            :class="{
                                                                'bg-[#FFA726] text-white':
                                                                    logbook?.status ===
                                                                    'submitted',
                                                                'bg-[#43A047] text-white':
                                                                    logbook?.status ===
                                                                    'approved',
                                                                'bg-[#AB47BC] text-white':
                                                                    logbook?.status ===
                                                                    'revision',
                                                                'bg-gray-500 text-white':
                                                                    logbook?.status ===
                                                                    'draft',
                                                            }"
                                                        >
                                                            {{
                                                                logbook?.status ===
                                                                "submitted"
                                                                    ? "Pending"
                                                                    : logbook?.status ===
                                                                      "approved"
                                                                    ? "Disetujui"
                                                                    : logbook?.status ===
                                                                      "revision"
                                                                    ? "Revisi"
                                                                    : "Draft"
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Card Body -->
                                                <div class="px-5 py-4">
                                                    <h3
                                                        class="text-base font-bold text-blue-600 mb-2 line-clamp-2 group-hover:text-blue-700 transition-colors"
                                                    >
                                                        {{
                                                            logbook?.title ||
                                                            "Aktivitas Harian"
                                                        }}
                                                    </h3>
                                                    <p
                                                        class="text-sm text-gray-600 line-clamp-2 leading-relaxed mb-3"
                                                    >
                                                        {{
                                                            logbook?.activities ||
                                                            "Tidak ada deskripsi"
                                                        }}
                                                    </p>

                                                    <!-- Learning Points Preview -->
                                                    <div
                                                        v-if="
                                                            logbook?.learning_points
                                                        "
                                                        class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-100"
                                                    >
                                                        <div
                                                            class="flex items-start gap-2"
                                                        >
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
                                                                {{
                                                                    logbook?.learning_points
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card Footer -->
                                                <div
                                                    class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between"
                                                >
                                                    <div
                                                        class="text-xs text-gray-500"
                                                    >
                                                        {{
                                                            logbook?.division
                                                                ?.name ||
                                                            "Tidak ada divisi"
                                                        }}
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <button
                                                            @click="
                                                                viewLogbookDetail(
                                                                    logbook.id
                                                                )
                                                            "
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
                                                        </button>
                                                        <button
                                                            v-if="
                                                                logbook?.status !==
                                                                'approved'
                                                            "
                                                            @click="
                                                                editLogbook(
                                                                    logbook.id
                                                                )
                                                            "
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
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- View More Info -->
                                    <div
                                        v-if="logbooks.length >= 4"
                                        class="text-center mt-6 pt-4 border-t border-gray-200"
                                    >
                                        <p class="text-sm text-gray-600">
                                            Menampilkan
                                            {{ logbooks.length }} logbook
                                            terbaru
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports Tab - Modern Design -->
                        <div
                            v-if="activeTab === 'reports'"
                            class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-100"
                        >
                            <!-- Header Modern -->
                            <div
                                class="p-6 border-b border-gray-100 bg-white rounded-t-xl"
                            >
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                                >
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"
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
                                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <h2
                                                class="text-2xl font-bold text-blue-600"
                                            >
                                                Laporan Akhir
                                            </h2>
                                            <p
                                                class="text-sm text-gray-600 mt-1"
                                            >
                                                Kelola laporan dan statistik
                                                magang Anda
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        @click="showCreateReportModal = true"
                                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
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
                                        Buat Laporan
                                    </button>
                                </div>
                            </div>

                            <!-- Statistics - Compact Modern Cards -->
                            <div class="p-6 border-b border-gray-100 bg-white">
                                <h3
                                    class="text-lg font-semibold text-blue-600 mb-4"
                                >
                                    Statistik Laporan Akhir
                                </h3>
                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                                >
                                    <div
                                        class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-md p-5 text-white"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <p
                                                    class="text-emerald-100 text-xs font-medium uppercase tracking-wide"
                                                >
                                                    Total Laporan
                                                </p>
                                                <p
                                                    class="text-3xl font-bold mt-1"
                                                >
                                                    {{
                                                        reportStats?.total_reports ||
                                                        0
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-white bg-opacity-20 rounded-lg p-2.5"
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
                            <div class="p-6 bg-gray-50">
                                <!-- Empty State -->
                                <div
                                    v-if="!reports || reports.length === 0"
                                    class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16"
                                >
                                    <div
                                        class="w-20 h-20 mx-auto mb-6 bg-emerald-100 rounded-full flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-10 h-10 text-emerald-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-2"
                                    >
                                        Belum Ada Laporan
                                    </h3>
                                    <p
                                        class="text-gray-600 mb-6 max-w-md mx-auto"
                                    >
                                        Upload laporan akhir berdasarkan
                                        kegiatan magang Anda.
                                    </p>
                                    <button
                                        @click="showCreateReportModal = true"
                                        class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl"
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
                                        Buat Laporan Pertama
                                    </button>
                                </div>

                                <!-- Reports Grid - Modern Card Layout -->
                                <div v-else-if="reports && reports.length > 0">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <div
                                            v-for="report in reports"
                                            :key="report?.id || Math.random()"
                                            class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group"
                                        >
                                            <!-- Card Header - Compact with gradient -->
                                            <div
                                                class="px-5 py-4 bg-gradient-to-r from-[#F6F8FA] to-white border-b border-gray-100"
                                            >
                                                <div
                                                    class="flex items-start justify-between gap-3"
                                                >
                                                    <div class="flex-1 min-w-0">
                                                        <div
                                                            class="flex items-center gap-2 mb-1"
                                                        >
                                                            <div
                                                                class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center flex-shrink-0"
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
                                                                    />
                                                                </svg>
                                                            </div>
                                                            <h4
                                                                class="text-base font-bold text-blue-600 group-hover:text-blue-600 transition-colors truncate"
                                                            >
                                                                {{
                                                                    report?.title ||
                                                                    "Laporan Tanpa Judul"
                                                                }}
                                                            </h4>
                                                        </div>
                                                        <p
                                                            class="text-xs text-gray-500 ml-10"
                                                        >
                                                            Laporan Akhir Magang
                                                        </p>
                                                    </div>

                                                    <!-- Status Badge with BI Colors -->
                                                    <span
                                                        v-if="
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'submitted'
                                                        "
                                                        class="px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                                                    >
                                                        <div
                                                            class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"
                                                        ></div>
                                                        Pending
                                                    </span>
                                                    <span
                                                        v-else-if="
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'approved'
                                                        "
                                                        class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                                                    >
                                                        <div
                                                            class="w-2 h-2 rounded-full bg-green-400"
                                                        ></div>
                                                        Disetujui
                                                    </span>
                                                    <span
                                                        v-else-if="
                                                            (report?.status ||
                                                                'submitted') ===
                                                            'revision'
                                                        "
                                                        class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold flex items-center gap-1.5 flex-shrink-0"
                                                    >
                                                        <div
                                                            class="w-2 h-2 rounded-full bg-purple-400"
                                                        ></div>
                                                        Perlu Revisi
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Card Body - Description -->
                                            <div class="p-5 space-y-3">
                                                <div
                                                    v-if="report?.description"
                                                    class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-400"
                                                >
                                                    <p
                                                        class="text-sm text-blue-900 line-clamp-3"
                                                    >
                                                        {{ report.description }}
                                                    </p>
                                                </div>

                                                <!-- Document Info if exists -->
                                                <div
                                                    v-if="report?.file_path"
                                                    class="bg-green-50 rounded-lg p-3 flex items-center gap-3"
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
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                            />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-xs text-green-900 font-semibold"
                                                        >
                                                            Dokumen Tersimpan
                                                        </p>
                                                        <p
                                                            class="text-xs text-green-600 truncate"
                                                        >
                                                            {{
                                                                report.file_path
                                                                    .split("/")
                                                                    .pop()
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Footer - Actions -->
                                            <div
                                                class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-xs text-gray-500 flex items-center gap-1.5"
                                                >
                                                    <svg
                                                        class="w-3.5 h-3.5"
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
                                                    {{
                                                        report?.created_at
                                                            ? formatDate(
                                                                  report.created_at
                                                              )
                                                            : "N/A"
                                                    }}
                                                </span>
                                                <div class="flex gap-2">
                                                    <a
                                                        v-if="report?.file_path"
                                                        :href="`/storage/${report.file_path}`"
                                                        target="_blank"
                                                        class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center gap-1.5"
                                                    >
                                                        <svg
                                                            class="w-3.5 h-3.5"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                            />
                                                        </svg>
                                                        Download
                                                    </a>
                                                    <button
                                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                                        title="Lihat Detail"
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
                                        Tanggal
                                        <span class="text-red-500">*</span>
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
                                    v-if="
                                        createLogbookForm.errors.learning_points
                                    "
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{
                                        createLogbookForm.errors.learning_points
                                    }}
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
                            <div
                                class="flex justify-end space-x-3 pt-4 border-t"
                            >
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
                                    Upload laporan akhir berdasarkan kegiatan
                                    magang Anda
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
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import DocumentUpload from "@/Components/DocumentUpload.vue";
import ApplicationCard from "@/Components/ApplicationCard.vue";
import NotificationBell from "@/Components/NotificationBell.vue";

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

// Computed property for document upload progress
const documentProgress = computed(() => {
    const totalDocuments = 8; // Total required documents
    const uploadedDocuments = [
        user.surat_pengantar_path,
        user.cv_path,
        user.motivation_letter_path,
        user.transkrip_path,
        user.ktp_path,
        user.npwp_path,
        user.buku_rekening_path,
        user.pas_foto_path,
    ].filter(Boolean).length;

    return Math.round((uploadedDocuments / totalDocuments) * 100);
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
    gpa: user.gpa,
    birth_date: user.birth_date,
    gender: user.gender,
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

// Report creation form
const reportForm = useForm({
    title: "",
    description: "",
    report_file: null,
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

// Logbook actions
const viewLogbookDetail = (logbookId) => {
    router.visit(route("profile.logbooks.show", logbookId));
};

const editLogbook = (logbookId) => {
    router.visit(route("profile.logbooks.show", logbookId), {
        data: { edit: true },
    });
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
                gpa: user.gpa,
                birth_date: user.birth_date,
                gender: user.gender,
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
    // Debug: Check what data we're receiving
    console.log("Profile data received:", {
        reports: props.reports,
        reportCount: props.reports ? props.reports.length : 0,
        logbooks: props.logbooks,
        logbookCount: props.logbooks ? props.logbooks.length : 0,
        applications: props.applications,
        user: props.user,
    });

    if (props.status) {
        showToast("success", props.status);
    }

    // Check for URL parameters
    const urlParams = new URLSearchParams(window.location.search);

    // Check for tab parameter to switch to specific tab
    const tab = urlParams.get("tab");
    if (
        tab &&
        ["profile", "documents", "applications", "logbook", "reports"].includes(
            tab
        )
    ) {
        activeTab.value = tab;
    }

    // Check for missing fields from URL parameters
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

<style scoped>
/* Custom scrollbar for sidebar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.5) rgba(59, 130, 246, 0.1);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(59, 130, 246, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.7);
}
</style>
