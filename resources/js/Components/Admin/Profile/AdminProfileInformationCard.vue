<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Profile
                </h2>
                <button
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center"
                    @click="$emit('toggle-edit')"
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
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                    </svg>
                    {{ editMode ? "Batal" : "Edit" }}
                </button>
            </div>
        </div>

        <div class="p-6">
            <form class="space-y-6" @submit.prevent="$emit('submit-profile')">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Nama Lengkap</label
                        >
                        <input
                            v-if="editMode"
                            v-model="profileForm.name"
                            type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama lengkap"
                        />
                        <p
                            v-else
                            class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900"
                        >
                            {{ authUser.name }}
                        </p>
                        <div
                            v-if="profileForm.errors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ profileForm.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Email</label
                        >
                        <input
                            v-if="editMode"
                            v-model="profileForm.email"
                            type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan email"
                        />
                        <p
                            v-else
                            class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900"
                        >
                            {{ authUser.email }}
                        </p>
                        <div
                            v-if="profileForm.errors.email"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ profileForm.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Nomor Telepon</label
                        >
                        <input
                            v-if="editMode"
                            v-model="profileForm.phone"
                            type="tel"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nomor telepon"
                        />
                        <p
                            v-else
                            class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900"
                        >
                            {{ authUser.phone || "Belum diisi" }}
                        </p>
                        <div
                            v-if="profileForm.errors.phone"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ profileForm.errors.phone }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Jabatan</label
                        >
                        <input
                            v-if="editMode"
                            v-model="profileForm.position"
                            type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan jabatan"
                        />
                        <p
                            v-else
                            class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900"
                        >
                            {{ authUser.position || "Belum diisi" }}
                        </p>
                        <div
                            v-if="profileForm.errors.position"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ profileForm.errors.position }}
                        </div>
                    </div>
                </div>

                <div v-if="editMode">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Foto Profile</label
                    >
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                v-if="
                                    photoPreview || authUser.profile_photo_path
                                "
                                class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-300"
                            >
                                <img
                                    :src="
                                        photoPreview ||
                                        `/storage/${authUser.profile_photo_path}`
                                    "
                                    :alt="authUser.name"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-600"
                            >
                                {{ getInitials(authUser.name) }}
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex space-x-2">
                                <button
                                    type="button"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors"
                                    @click="$emit('select-photo-trigger')"
                                >
                                    {{
                                        photoPreview ||
                                        authUser.profile_photo_path
                                            ? "Ganti Foto"
                                            : "Upload Foto"
                                    }}
                                </button>
                                <button
                                    v-if="
                                        photoPreview ||
                                        authUser.profile_photo_path
                                    "
                                    type="button"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition-colors"
                                    @click="$emit('remove-photo')"
                                >
                                    Hapus
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                JPG, PNG atau GIF. Maksimal 2MB.
                            </p>
                        </div>
                    </div>
                    <div
                        v-if="profileForm.errors.profile_photo"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ profileForm.errors.profile_photo }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Bio</label
                    >
                    <textarea
                        v-if="editMode"
                        v-model="profileForm.bio"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ceritakan tentang diri Anda..."
                    ></textarea>
                    <p
                        v-else
                        class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900"
                    >
                        {{ authUser.bio || "Belum ada bio" }}
                    </p>
                    <div
                        v-if="profileForm.errors.bio"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ profileForm.errors.bio }}
                    </div>
                </div>

                <div v-if="editMode" class="flex justify-end space-x-3">
                    <button
                        type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        @click="$emit('cancel-edit')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="profileForm.processing"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                    >
                        <svg
                            v-if="profileForm.processing"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                        {{
                            profileForm.processing
                                ? "Menyimpan..."
                                : "Simpan Perubahan"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
defineProps({
    editMode: {
        type: Boolean,
        default: false,
    },
    authUser: {
        type: Object,
        required: true,
    },
    profileForm: {
        type: Object,
        required: true,
    },
    photoPreview: {
        type: String,
        default: null,
    },
    getInitials: {
        type: Function,
        required: true,
    },
});

defineEmits([
    "toggle-edit",
    "select-photo-trigger",
    "remove-photo",
    "submit-profile",
    "cancel-edit",
]);
</script>
