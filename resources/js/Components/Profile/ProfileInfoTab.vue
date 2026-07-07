<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                Informasi Pribadi
            </h2>
            <button
                @click="$emit('toggle-edit')"
                class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
            >
                {{ editMode ? "Batal" : "Edit Profil" }}
            </button>
        </div>

        <form @submit.prevent="$emit('submit-profile')">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 flex items-center space-x-6">
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
                        <!-- Camera button: visible in editMode, OR always visible when face is not enrolled -->
                        <button
                            v-if="editMode || !faceEnrolled"
                            @click="showCameraModal = true"
                            type="button"
                            :class="[
                                'absolute bottom-0 right-0 p-2 rounded-full cursor-pointer transition-colors shadow',
                                !faceEnrolled
                                    ? 'bg-amber-500 hover:bg-amber-600 text-white ring-2 ring-amber-300 ring-offset-1'
                                    : 'bg-blue-600 hover:bg-blue-700 text-white'
                            ]"
                            :title="!faceEnrolled ? 'Daftarkan wajah — wajib sebelum presensi' : 'Ganti foto profil'"
                        >
                            <i class="fas fa-camera text-xs"></i>
                        </button>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ user.name }}
                        </h3>
                        <p class="text-gray-500">{{ user.email }}</p>
                        <!-- Face enrollment status badge -->
                        <div class="mt-2 flex items-center gap-1.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                :class="faceEnrolled
                                    ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                                    : 'bg-amber-100 text-amber-700 border border-amber-200'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                    :class="faceEnrolled ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse'"
                                />
                                <template v-if="faceEnrolled">
                                    Wajah Terdaftar
                                    <span v-if="faceRegisteredAt" class="text-emerald-600 font-normal">
                                        · {{ formatRegisteredAt(faceRegisteredAt) }}
                                    </span>
                                </template>
                                <template v-else>
                                    Wajah Belum Terdaftar — Klik kamera untuk daftar
                                </template>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        v-model="profileForm.name"
                        :disabled="!editMode"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="Masukkan nama lengkap"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input
                        v-model="profileForm.email"
                        :disabled="!editMode"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="email@example.com"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input
                        v-model="profileForm.phone"
                        :disabled="!editMode"
                        type="tel"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="08xxxxxxxxxx"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Universitas
                    </label>
                    <input
                        v-model="profileForm.university"
                        :disabled="!editMode"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="Nama universitas"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jurusan
                    </label>
                    <input
                        v-model="profileForm.major"
                        :disabled="!editMode"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="Jurusan/Program studi"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Semester
                    </label>
                    <select
                        v-model="profileForm.semester"
                        :disabled="!editMode"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                    >
                        <option value="">Pilih semester</option>
                        <option v-for="i in 14" :key="i" :value="i">
                            Semester {{ i }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        IPK
                    </label>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Lahir
                    </label>
                    <input
                        v-model="profileForm.birth_date"
                        :disabled="!editMode"
                        type="date"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin
                    </label>
                    <select
                        v-model="profileForm.gender"
                        :disabled="!editMode"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                    >
                        <option value="">Pilih jenis kelamin</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap
                    </label>
                    <textarea
                        v-model="profileForm.address"
                        :disabled="!editMode"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-50"
                        placeholder="Alamat lengkap sesuai KTP"
                    ></textarea>
                </div>
            </div>

            <div v-if="editMode" class="mt-6 flex justify-end space-x-4">
                <button
                    type="button"
                    @click="$emit('cancel-edit')"
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

        <ProfileCameraModal
            :show="showCameraModal"
            :is-submitting="isUploading"
            @close="showCameraModal = false"
            @capture="handleCapture"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ProfileCameraModal from './ProfileCameraModal.vue';

const props = defineProps({
    user:             { type: Object,  required: true },
    profileForm:      { type: Object,  required: true },
    editMode:         { type: Boolean, default: false },
    faceEnrolled:     { type: Boolean, default: false },
    faceRegisteredAt: { type: String,  default: null },
});

const emit = defineEmits(["toggle-edit", "cancel-edit", "submit-profile", "upload-photo"]);

const showCameraModal = ref(false);
const isUploading = ref(false);

/** Format the ISO registration timestamp to a short locale string */
const formatRegisteredAt = (iso) => {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const handleCapture = (data) => {
    isUploading.value = true;
    emit('upload-photo', data, (success) => {
        isUploading.value = false;
        if (success) {
            showCameraModal.value = false;
        }
    });
};
</script>
