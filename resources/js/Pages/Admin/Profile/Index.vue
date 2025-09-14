<template>
    <AdminLayout title="Profile Admin" subtitle="Kelola informasi profile dan pengaturan akun">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header Profile -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-8 text-white">
                    <div class="flex items-center space-x-6">
                        <!-- Avatar -->
                        <div class="relative">
                            <!-- Hidden file input -->
                            <input 
                                ref="photoInput"
                                type="file"
                                class="hidden"
                                accept="image/*"
                                @change="handlePhotoSelect"
                            />
                            
                            <!-- Avatar Display -->
                            <div 
                                v-if="photoPreview || auth.user.profile_photo_path"
                                class="w-24 h-24 rounded-full overflow-hidden border-4 border-white border-opacity-20"
                            >
                                <img 
                                    :src="photoPreview || `/storage/${auth.user.profile_photo_path}`"
                                    :alt="auth.user.name"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div 
                                v-else
                                class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl font-bold border-4 border-white border-opacity-20"
                            >
                                {{ getInitials(auth.user.name) }}
                            </div>
                            
                            <!-- Upload Button -->
                            <button 
                                @click="$refs.photoInput.click()"
                                class="absolute bottom-0 right-0 w-8 h-8 bg-white text-blue-600 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors"
                                title="Ubah foto profile"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            
                            <!-- Remove Photo Button (when photo exists) -->
                            <button 
                                v-if="photoPreview || auth.user.profile_photo_path"
                                @click="removePhoto"
                                class="absolute top-0 right-0 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors"
                                title="Hapus foto"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Info Profile -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold mb-2">{{ auth.user.name }}</h1>
                            <p class="text-blue-100 mb-1">{{ auth.user.email }}</p>
                            <div class="flex items-center space-x-4 text-sm text-blue-100">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Administrator
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Bergabung {{ formatDate(auth.user.created_at) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Informasi Profile</h2>
                                <button 
                                    @click="editMode = !editMode"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ editMode ? 'Batal' : 'Edit' }}
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="updateProfile" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                        <input 
                                            v-if="editMode"
                                            type="text" 
                                            v-model="profileForm.name"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan nama lengkap"
                                        />
                                        <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ auth.user.name }}</p>
                                        <div v-if="profileForm.errors.name" class="mt-1 text-sm text-red-600">
                                            {{ profileForm.errors.name }}
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input 
                                            v-if="editMode"
                                            type="email" 
                                            v-model="profileForm.email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan email"
                                        />
                                        <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ auth.user.email }}</p>
                                        <div v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">
                                            {{ profileForm.errors.email }}
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                        <input 
                                            v-if="editMode"
                                            type="tel" 
                                            v-model="profileForm.phone"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan nomor telepon"
                                        />
                                        <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ auth.user.phone || 'Belum diisi' }}</p>
                                        <div v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-600">
                                            {{ profileForm.errors.phone }}
                                        </div>
                                    </div>

                                    <!-- Position -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                                        <input 
                                            v-if="editMode"
                                            type="text" 
                                            v-model="profileForm.position"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan jabatan"
                                        />
                                        <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ auth.user.position || 'Belum diisi' }}</p>
                                        <div v-if="profileForm.errors.position" class="mt-1 text-sm text-red-600">
                                            {{ profileForm.errors.position }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Photo Upload -->
                                <div v-if="editMode">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                                    <div class="flex items-center space-x-4">
                                        <!-- Current/Preview Photo -->
                                        <div class="flex-shrink-0">
                                            <div 
                                                v-if="photoPreview || auth.user.profile_photo_path"
                                                class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-300"
                                            >
                                                <img 
                                                    :src="photoPreview || `/storage/${auth.user.profile_photo_path}`"
                                                    :alt="auth.user.name"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div 
                                                v-else
                                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-600"
                                            >
                                                {{ getInitials(auth.user.name) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Controls -->
                                        <div class="flex-1">
                                            <div class="flex space-x-2">
                                                <button
                                                    type="button"
                                                    @click="$refs.photoInput.click()"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors"
                                                >
                                                    {{ photoPreview || auth.user.profile_photo_path ? 'Ganti Foto' : 'Upload Foto' }}
                                                </button>
                                                <button
                                                    v-if="photoPreview || auth.user.profile_photo_path"
                                                    type="button"
                                                    @click="removePhoto"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition-colors"
                                                >
                                                    Hapus
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                JPG, PNG atau GIF. Maksimal 2MB.
                                            </p>
                                        </div>
                                    </div>
                                    <div v-if="profileForm.errors.profile_photo" class="mt-1 text-sm text-red-600">
                                        {{ profileForm.errors.profile_photo }}
                                    </div>
                                </div>

                                <!-- Bio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                    <textarea 
                                        v-if="editMode"
                                        v-model="profileForm.bio"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Ceritakan tentang diri Anda..."
                                    ></textarea>
                                    <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ auth.user.bio || 'Belum ada bio' }}</p>
                                    <div v-if="profileForm.errors.bio" class="mt-1 text-sm text-red-600">
                                        {{ profileForm.errors.bio }}
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div v-if="editMode" class="flex justify-end space-x-3">
                                    <button 
                                        type="button"
                                        @click="editMode = false"
                                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button 
                                        type="submit"
                                        :disabled="profileForm.processing"
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                                    >
                                        <svg v-if="profileForm.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Ganti Password</h2>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="updatePassword" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                    <input 
                                        type="password" 
                                        v-model="passwordForm.current_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan password saat ini"
                                    />
                                    <div v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">
                                        {{ passwordForm.errors.current_password }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                        <input 
                                            type="password" 
                                            v-model="passwordForm.password"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan password baru"
                                        />
                                        <div v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">
                                            {{ passwordForm.errors.password }}
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                        <input 
                                            type="password" 
                                            v-model="passwordForm.password_confirmation"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Konfirmasi password baru"
                                        />
                                        <div v-if="passwordForm.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                            {{ passwordForm.errors.password_confirmation }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button 
                                        type="submit"
                                        :disabled="passwordForm.processing"
                                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                                    >
                                        <svg v-if="passwordForm.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ passwordForm.processing ? 'Mengubah...' : 'Ganti Password' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Admin</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Divisi</span>
                                <span class="text-xl font-bold text-blue-600">{{ stats.total_divisions }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Pendaftar</span>
                                <span class="text-xl font-bold text-green-600">{{ stats.total_applications }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Diterima</span>
                                <span class="text-xl font-bold text-purple-600">{{ stats.accepted_applications }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Keamanan</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">Email Verified</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800">Password Strong</span>
                                </div>
                            </div>

                            <button class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition-colors">
                                Lihat Log Aktivitas
                            </button>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferensi</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Notifikasi Email</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="preferences.email_notifications" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Auto Logout</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="preferences.auto_logout" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <button 
                                @click="savePreferences"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors"
                            >
                                Simpan Preferensi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    stats: Object
});

const editMode = ref(false);
const photoPreview = ref(null);
const selectedPhoto = ref(null);

// Profile Form
const profileForm = useForm({
    name: props.auth.user.name,
    email: props.auth.user.email,
    phone: props.auth.user.phone || '',
    position: props.auth.user.position || '',
    bio: props.auth.user.bio || '',
    profile_photo: null
});

// Password Form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
});

// Preferences
const preferences = reactive({
    email_notifications: true,
    auto_logout: false
});

// Methods
const getInitials = (name) => {
    return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().slice(0, 2);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long'
    });
};

const updateProfile = () => {
    // If there's a new photo, append it to the form
    if (selectedPhoto.value) {
        profileForm.profile_photo = selectedPhoto.value;
    }
    
    profileForm.post('/admin/profile', {
        forceFormData: true, // Force multipart/form-data for file upload
        onSuccess: (page) => {
            editMode.value = false;
            photoPreview.value = null;
            selectedPhoto.value = null;
            // Update the current user data with the response
            if (page.props.auth && page.props.auth.user) {
                Object.assign(props.auth.user, page.props.auth.user);
            }
        },
        onError: (errors) => {
            console.error('Profile update errors:', errors);
        }
    });
};

const handlePhotoSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar');
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            return;
        }
        
        selectedPhoto.value = file;
        
        // Create preview URL
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removePhoto = () => {
    photoPreview.value = null;
    selectedPhoto.value = null;
    profileForm.profile_photo = null;
    
    // If user has existing photo, send request to remove it
    if (props.auth.user.profile_photo_path) {
        const deleteForm = useForm({});
        deleteForm.delete('/admin/profile/photo', {
            preserveScroll: true,
            onSuccess: () => {
                // Update the user data locally
                props.auth.user.profile_photo_path = null;
            }
        });
    }
};

const updatePassword = () => {
    passwordForm.put('/admin/profile/password', {
        onSuccess: () => {
            passwordForm.reset();
        }
    });
};

const savePreferences = () => {
    const form = useForm(preferences);
    form.put('/admin/profile/preferences', {
        onSuccess: () => {
            console.log('Preferences saved successfully');
        }
    });
};
</script>