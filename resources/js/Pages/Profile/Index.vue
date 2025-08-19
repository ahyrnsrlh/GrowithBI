<template>
    <Head title="GrowithBI" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil Saya
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="relative">
                                        <div
                                            v-if="user.profile_photo_path"
                                            class="w-16 h-16 rounded-full overflow-hidden"
                                        >
                                            <img
                                                :src="
                                                    '/storage/' +
                                                    user.profile_photo_path
                                                "
                                                :alt="user.name"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div
                                            v-else
                                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"
                                        >
                                            <svg
                                                class="w-8 h-8 text-blue-600"
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
                                        <button
                                            @click="showPhotoModal = true"
                                            class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs hover:bg-blue-700 transition-colors"
                                            title="Ubah foto profil"
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
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                                ></path>
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                                ></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            {{ user.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                </div>

                                <nav class="space-y-2">
                                    <button
                                        @click="activeTab = 'profile'"
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                            activeTab === 'profile'
                                                ? 'bg-blue-50 text-blue-700'
                                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                                        ]"
                                    >
                                        <svg
                                            class="w-4 h-4 inline mr-2"
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
                                        Informasi Pribadi
                                    </button>

                                    <button
                                        @click="activeTab = 'application'"
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                            activeTab === 'application'
                                                ? 'bg-blue-50 text-blue-700'
                                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                                        ]"
                                    >
                                        <svg
                                            class="w-4 h-4 inline mr-2"
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
                                        Status Lamaran
                                    </button>

                                    <button
                                        @click="activeTab = 'documents'"
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                            activeTab === 'documents'
                                                ? 'bg-blue-50 text-blue-700'
                                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                                        ]"
                                    >
                                        <svg
                                            class="w-4 h-4 inline mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        Dokumen Persyaratan
                                    </button>

                                    <Link
                                        :href="route('home') + '#divisions'"
                                        class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50 block"
                                    >
                                        <svg
                                            class="w-4 h-4 inline mr-2"
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
                                        Daftar Magang
                                    </Link>
                                </nav>

                                <!-- Profile Completion -->
                                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-blue-900 mb-2"
                                    >
                                        Kelengkapan Profil
                                    </h4>
                                    <div
                                        class="w-full bg-blue-200 rounded-full h-2 mb-2"
                                    >
                                        <div
                                            class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                            :style="{
                                                width:
                                                    profileCompletion.percentage +
                                                    '%',
                                            }"
                                        ></div>
                                    </div>
                                    <p class="text-xs text-blue-700">
                                        {{ profileCompletion.percentage }}% ({{
                                            profileCompletion.completed
                                        }}/{{ profileCompletion.total }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <!-- Profile Information Tab -->
                        <div
                            v-if="activeTab === 'profile'"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <div
                                    class="flex justify-between items-center mb-6"
                                >
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Informasi Pribadi
                                    </h3>
                                    <button
                                        @click="editMode = !editMode"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        {{ editMode ? "Batal" : "Edit" }}
                                    </button>
                                </div>

                                <form
                                    v-if="editMode"
                                    @submit.prevent="updateProfile"
                                    class="space-y-6"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Nama Lengkap</label
                                            >
                                            <input
                                                v-model="profileForm.name"
                                                type="text"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Email</label
                                            >
                                            <input
                                                v-model="profileForm.email"
                                                type="email"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Nomor Telepon</label
                                            >
                                            <input
                                                v-model="profileForm.phone"
                                                type="tel"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Alamat</label
                                            >
                                            <textarea
                                                v-model="profileForm.address"
                                                rows="3"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            @click="editMode = false"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            Batal
                                        </button>
                                        <button
                                            type="submit"
                                            :disabled="profileForm.processing"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25"
                                        >
                                            {{
                                                profileForm.processing
                                                    ? "Menyimpan..."
                                                    : "Simpan Perubahan"
                                            }}
                                        </button>
                                    </div>
                                </form>

                                <div v-else class="space-y-6">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                                >Foto Profil</label
                                            >
                                            <div class="mt-2">
                                                <div
                                                    v-if="
                                                        user.profile_photo_path
                                                    "
                                                    class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200"
                                                >
                                                    <img
                                                        :src="
                                                            '/storage/' +
                                                            user.profile_photo_path
                                                        "
                                                        :alt="user.name"
                                                        class="w-full h-full object-cover"
                                                    />
                                                </div>
                                                <div
                                                    v-else
                                                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center border-4 border-gray-200"
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
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                                >Nama Lengkap</label
                                            >
                                            <p
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{ user.name || "-" }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                                >Email</label
                                            >
                                            <p
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{ user.email || "-" }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                                >Nomor Telepon</label
                                            >
                                            <p
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{ user.phone || "-" }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                                >Alamat</label
                                            >
                                            <p
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{ user.address || "-" }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Status Tab -->
                        <div
                            v-if="activeTab === 'application'"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-6"
                                >
                                    Status Lamaran Magang
                                </h3>

                                <!-- Application Progress -->
                                <div
                                    v-if="applications.length > 0"
                                    class="mb-8"
                                >
                                    <div
                                        class="flex items-center justify-between mb-4"
                                    >
                                        <div
                                            v-for="(
                                                step, index
                                            ) in applicationStatus.steps"
                                            :key="index"
                                            class="flex-1"
                                        >
                                            <div class="flex items-center">
                                                <div
                                                    :class="[
                                                        'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium',
                                                        step.status ===
                                                        'completed'
                                                            ? 'bg-green-100 text-green-800'
                                                            : step.status ===
                                                              'rejected'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-gray-100 text-gray-600',
                                                    ]"
                                                >
                                                    <svg
                                                        v-if="
                                                            step.status ===
                                                            'completed'
                                                        "
                                                        class="w-5 h-5"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"
                                                        ></path>
                                                    </svg>
                                                    <svg
                                                        v-else-if="
                                                            step.status ===
                                                            'rejected'
                                                        "
                                                        class="w-5 h-5"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"
                                                        ></path>
                                                    </svg>
                                                    <span v-else>{{
                                                        index + 1
                                                    }}</span>
                                                </div>
                                                <div
                                                    v-if="
                                                        index <
                                                        applicationStatus.steps
                                                            .length -
                                                            1
                                                    "
                                                    :class="[
                                                        'flex-1 h-1 ml-4',
                                                        step.status ===
                                                        'completed'
                                                            ? 'bg-green-200'
                                                            : 'bg-gray-200',
                                                    ]"
                                                ></div>
                                            </div>
                                            <p
                                                class="mt-2 text-xs text-gray-500"
                                            >
                                                {{ step.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Applications -->
                                <div class="space-y-4">
                                    <div
                                        v-for="application in applications"
                                        :key="application.id"
                                        class="border border-gray-200 rounded-lg p-4"
                                    >
                                        <div
                                            class="flex justify-between items-start"
                                        >
                                            <div>
                                                <h4
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{
                                                        application.division
                                                            .name
                                                    }}
                                                </h4>
                                                <p
                                                    class="text-sm text-gray-600"
                                                >
                                                    Diajukan pada
                                                    {{
                                                        formatDate(
                                                            application.created_at
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <span
                                                :class="[
                                                    'px-2 py-1 text-xs font-medium rounded-full',
                                                    application.status ===
                                                    'menunggu'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : application.status ===
                                                          'proses'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : application.status ===
                                                          'wawancara'
                                                        ? 'bg-purple-100 text-purple-800'
                                                        : application.status ===
                                                          'diterima'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                ]"
                                            >
                                                {{
                                                    getStatusText(
                                                        application.status
                                                    )
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        v-if="applications.length === 0"
                                        class="text-center py-8"
                                    >
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
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
                                        <h3
                                            class="mt-2 text-sm font-medium text-gray-900"
                                        >
                                            Belum ada lamaran
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Mulai daftar magang di divisi yang
                                            tersedia.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div
                            v-if="activeTab === 'documents'"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-6"
                                >
                                    Dokumen Persyaratan
                                </h3>

                                <form
                                    @submit.prevent="uploadDocuments"
                                    class="space-y-6"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                                >KTP/Kartu Identitas</label
                                            >
                                            <div
                                                v-if="user.ktp_path"
                                                class="mb-2"
                                            >
                                                <a
                                                    :href="
                                                        '/storage/' +
                                                        user.ktp_path
                                                    "
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                                >
                                                    📄 Lihat dokumen
                                                </a>
                                            </div>
                                            <input
                                                type="file"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                @change="
                                                    handleFileChange(
                                                        'ktp',
                                                        $event
                                                    )
                                                "
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                                >Curriculum Vitae (CV)</label
                                            >
                                            <div
                                                v-if="user.cv_path"
                                                class="mb-2"
                                            >
                                                <a
                                                    :href="
                                                        '/storage/' +
                                                        user.cv_path
                                                    "
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                                >
                                                    📄 Lihat dokumen
                                                </a>
                                            </div>
                                            <input
                                                type="file"
                                                accept=".pdf,.doc,.docx"
                                                @change="
                                                    handleFileChange(
                                                        'cv',
                                                        $event
                                                    )
                                                "
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                                >Surat Lamaran</label
                                            >
                                            <div
                                                v-if="user.surat_lamaran_path"
                                                class="mb-2"
                                            >
                                                <a
                                                    :href="
                                                        '/storage/' +
                                                        user.surat_lamaran_path
                                                    "
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                                >
                                                    📄 Lihat dokumen
                                                </a>
                                            </div>
                                            <input
                                                type="file"
                                                accept=".pdf,.doc,.docx"
                                                @change="
                                                    handleFileChange(
                                                        'surat_lamaran',
                                                        $event
                                                    )
                                                "
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                                >Transkrip Nilai</label
                                            >
                                            <div
                                                v-if="user.transkrip_path"
                                                class="mb-2"
                                            >
                                                <a
                                                    :href="
                                                        '/storage/' +
                                                        user.transkrip_path
                                                    "
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                                >
                                                    📄 Lihat dokumen
                                                </a>
                                            </div>
                                            <input
                                                type="file"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                @change="
                                                    handleFileChange(
                                                        'transkrip',
                                                        $event
                                                    )
                                                "
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                                >Foto Formal</label
                                            >
                                            <div
                                                v-if="user.foto_path"
                                                class="mb-2"
                                            >
                                                <a
                                                    :href="
                                                        '/storage/' +
                                                        user.foto_path
                                                    "
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                                >
                                                    📄 Lihat dokumen
                                                </a>
                                            </div>
                                            <input
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                @change="
                                                    handleFileChange(
                                                        'foto',
                                                        $event
                                                    )
                                                "
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            />
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            :disabled="
                                                documentForm.processing ||
                                                !hasDocumentsToUpload
                                            "
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25"
                                        >
                                            {{
                                                documentForm.processing
                                                    ? "Mengunggah..."
                                                    : "Unggah Dokumen"
                                            }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div
            v-if="showToast"
            :class="[
                'fixed bottom-4 right-4 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transition-all duration-300 z-50',
                showToast
                    ? 'translate-y-0 opacity-100'
                    : 'translate-y-2 opacity-0',
            ]"
        >
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg
                                v-if="toastType === 'success'"
                                class="h-6 w-6 text-green-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="h-6 w-6 text-red-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">
                                {{ toastMessage }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button
                                @click="hideToast"
                                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span class="sr-only">Close</span>
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Upload Modal -->
        <div
            v-if="showPhotoModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    aria-hidden="true"
                    @click="showPhotoModal = false"
                ></div>

                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                            >
                                <svg
                                    class="h-6 w-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                    ></path>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                    ></path>
                                </svg>
                            </div>
                            <div
                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="modal-title"
                                >
                                    Upload Foto Profil
                                </h3>
                                <div class="mt-4">
                                    <!-- File Input -->
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Pilih Foto
                                        </label>
                                        <input
                                            type="file"
                                            @change="handleProfilePhotoChange"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                    </div>

                                    <!-- Preview -->
                                    <div
                                        v-if="profilePhotoPreview"
                                        class="mb-4"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Preview
                                        </label>
                                        <div class="flex justify-center">
                                            <img
                                                :src="profilePhotoPreview"
                                                alt="Preview"
                                                class="w-32 h-32 rounded-full object-cover border-4 border-blue-100"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            @click="uploadProfilePhoto"
                            :disabled="
                                !profilePhotoFile || profilePhotoForm.processing
                            "
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="profilePhotoForm.processing"
                                >Uploading...</span
                            >
                            <span v-else>Upload</span>
                        </button>
                        <button
                            @click="
                                showPhotoModal = false;
                                clearProfilePhoto();
                            "
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    user: Object,
    applications: Array,
    availableDivisions: Array,
    profileCompletion: Object,
    applicationStatus: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

// Reactive data
const activeTab = ref("profile");
const editMode = ref(false);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");
const documentsToUpload = ref({});
const showPhotoModal = ref(false);

// Forms
const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    address: props.user.address,
});

const documentForm = useForm({
    documents: {},
});

const profilePhotoForm = useForm({
    profile_photo: null,
});

// Profile photo upload data
const profilePhotoFile = ref(null);
const profilePhotoPreview = ref(null);

// Computed
const hasDocumentsToUpload = computed(() => {
    return Object.keys(documentsToUpload.value).length > 0;
});

// Methods
const updateProfile = () => {
    profileForm.patch(route("profile.update"), {
        onSuccess: () => {
            editMode.value = false;
            showToastMessage("Profil berhasil diperbarui!", "success");
        },
        onError: () => {
            showToastMessage("Gagal memperbarui profil", "error");
        },
    });
};

const handleFileChange = (type, event) => {
    const file = event.target.files[0];
    if (file) {
        documentsToUpload.value[type] = file;
    } else {
        delete documentsToUpload.value[type];
    }
};

const uploadDocuments = () => {
    const formData = new FormData();

    Object.entries(documentsToUpload.value).forEach(([type, file]) => {
        formData.append(`documents[${type}]`, file);
    });

    documentForm.post(route("profile.upload-documents"), {
        data: formData,
        forceFormData: true,
        onSuccess: () => {
            documentsToUpload.value = {};
            showToastMessage("Dokumen berhasil diunggah!", "success");
        },
        onError: () => {
            showToastMessage("Gagal mengunggah dokumen", "error");
        },
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getStatusText = (status) => {
    const statusMap = {
        menunggu: "Menunggu",
        proses: "Diproses",
        wawancara: "Wawancara",
        diterima: "Diterima",
        ditolak: "Ditolak",
    };
    return statusMap[status] || status;
};

const showToastMessage = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => {
        hideToast();
    }, 5000);
};

const hideToast = () => {
    showToast.value = false;
};

// Profile photo methods
const handleProfilePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        profilePhotoFile.value = file;
        profilePhotoForm.profile_photo = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePhotoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const clearProfilePhoto = () => {
    profilePhotoFile.value = null;
    profilePhotoPreview.value = null;
    profilePhotoForm.profile_photo = null;
    // Reset file input
    const input = document.querySelector('input[type="file"]');
    if (input) input.value = "";
};

const uploadProfilePhoto = () => {
    if (!profilePhotoFile.value) return;

    profilePhotoForm.post(route("profile.upload-photo"), {
        onSuccess: () => {
            clearProfilePhoto();
            showPhotoModal.value = false;
            showToastMessage("Foto profil berhasil diperbarui!", "success");
        },
        onError: () => {
            showToastMessage("Gagal mengunggah foto profil", "error");
        },
    });
};

// Watch for flash messages
watch(
    () => props.status,
    (newStatus) => {
        if (newStatus) {
            showToastMessage(newStatus, "success");
        }
    }
);

onMounted(() => {
    // Check for flash messages from server
    if (props.status) {
        showToastMessage(props.status, "success");
    }
});
</script>
