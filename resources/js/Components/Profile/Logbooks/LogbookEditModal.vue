<template>
    <BaseModal
        :show="show"
        :title="loading ? 'Memuat Data Logbook...' : 'Edit Logbook Harian'"
        subtitle="Ubah dan perbaiki rincian logbook aktivitas Anda"
        maxWidth="max-w-2xl"
        @close="handleClose"
    >
        <!-- Loading State -->
        <div v-if="loading" class="py-12 flex flex-col items-center justify-center space-y-4">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            <p class="text-sm text-gray-500 font-medium">Mengambil data logbook...</p>
        </div>

        <!-- Form content -->
        <form v-else @submit.prevent="submitForm(form.status)" class="space-y-6">
            <!-- Alert for Revision -->
            <div v-if="revisionComment" class="bg-purple-50 rounded-xl p-4 border border-purple-100 text-sm">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <span class="font-bold text-purple-800">Catatan Revisi Pembimbing:</span>
                        <p class="text-purple-700 mt-1 italic">"{{ revisionComment }}"</p>
                    </div>
                </div>
            </div>

            <!-- Basic Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.date"
                        type="date"
                        required
                        :max="today"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                    />
                    <div v-if="form.errors.date" class="text-red-600 text-xs mt-1">
                        {{ form.errors.date }}
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Durasi Kegiatan (Jam) <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model.number="form.duration"
                        type="number"
                        step="0.5"
                        min="0.5"
                        max="24"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                    />
                    <div v-if="form.errors.duration" class="text-red-600 text-xs mt-1">
                        {{ form.errors.duration }}
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Judul Kegiatan <span class="text-red-500">*</span>
                </label>
                <input
                    v-model="form.title"
                    type="text"
                    required
                    placeholder="Contoh: Implementasi API Gateway atau Analisis Data"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm"
                />
                <div v-if="form.errors.title" class="text-red-600 text-xs mt-1">
                    {{ form.errors.title }}
                </div>
            </div>

            <!-- Activities -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi Detail Kegiatan <span class="text-red-500">*</span>
                </label>
                <textarea
                    v-model="form.activities"
                    rows="4"
                    required
                    placeholder="Jelaskan detail apa saja yang Anda lakukan..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                ></textarea>
                <div v-if="form.errors.activities" class="text-red-600 text-xs mt-1">
                    {{ form.errors.activities }}
                </div>
            </div>

            <!-- Learning & Challenges -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Learning Points -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Poin Pembelajaran
                    </label>
                    <textarea
                        v-model="form.learning_points"
                        rows="3"
                        placeholder="Apa pembelajaran atau skill baru yang didapat hari ini? (opsional)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                    ></textarea>
                    <div v-if="form.errors.learning_points" class="text-red-600 text-xs mt-1">
                        {{ form.errors.learning_points }}
                    </div>
                </div>

                <!-- Challenges -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kendala / Tantangan
                    </label>
                    <textarea
                        v-model="form.challenges"
                        rows="3"
                        placeholder="Apa kendala yang Anda hadapi dan bagaimana solusinya? (opsional)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all text-sm resize-none"
                    ></textarea>
                    <div v-if="form.errors.challenges" class="text-red-600 text-xs mt-1">
                        {{ form.errors.challenges }}
                    </div>
                </div>
            </div>

            <!-- Documentation Attachments -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200/80">
                <label class="block text-sm font-bold text-gray-700 mb-3">Lampiran Dokumentasi</label>
                
                <!-- Existing Files -->
                <div v-if="existingFiles.length > 0" class="mb-4">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">File Tersimpan</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div
                            v-for="(file, idx) in existingFiles"
                            :key="idx"
                            class="flex items-center justify-between p-2.5 rounded-lg border border-gray-200 bg-white shadow-sm text-xs"
                        >
                            <span class="truncate font-medium text-gray-700 flex-1 pr-2">{{ file.name }}</span>
                            <button
                                type="button"
                                @click="removeExistingFile(idx)"
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors"
                                title="Hapus file ini"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- New Files Queue -->
                <div v-if="newFiles.length > 0" class="mb-4 border-t border-gray-200 pt-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">File Baru Ditambahkan (Belum Disimpan)</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div
                            v-for="(file, idx) in newFiles"
                            :key="idx"
                            class="flex items-center justify-between p-2.5 rounded-lg border border-blue-200 bg-blue-50/30 text-xs"
                        >
                            <span class="truncate font-medium text-blue-700 flex-1 pr-2">{{ file.name }}</span>
                            <button
                                type="button"
                                @click="removeNewFile(idx)"
                                class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-1 rounded transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add Files Button -->
                <div>
                    <label
                        class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-xl p-4 bg-white cursor-pointer hover:bg-blue-50/10 transition-all group"
                    >
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-medium text-gray-600 group-hover:text-blue-600 transition-colors">Unggah File Baru</span>
                        <input
                            type="file"
                            multiple
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="hidden"
                            @change="handleFileChange"
                        />
                    </label>
                    <span class="text-[10px] text-gray-400 block mt-1.5">Mendukung file: PDF, Word (DOC/DOCX), Gambar (JPG/PNG). Maks: 5MB per file.</span>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button
                    type="button"
                    @click="handleClose"
                    class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-xl text-sm transition-colors"
                >
                    Batal
                </button>
                <button
                    type="button"
                    :disabled="form.processing"
                    @click="submitForm('draft')"
                    class="px-5 py-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 font-semibold rounded-xl text-sm transition-all shadow-sm"
                >
                    <span v-if="form.processing && form.status === 'draft'">Menyimpan Draft...</span>
                    <span v-else>Simpan Draft</span>
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    @click="form.status = 'submitted'"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing && form.status === 'submitted'">Mengirim...</span>
                    <span v-else>Simpan & Kirim</span>
                </button>
            </div>
        </form>
    </BaseModal>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import BaseModal from '@/Components/BaseModal.vue';
import SwalPlugin from '@/Plugins/sweetalert';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    logbookId: {
        type: Number,
        default: null
    }
});

const emit = defineEmits(['close', 'success']);

const loading = ref(false);
const revisionComment = ref('');
const existingFiles = ref([]);
const newFiles = ref([]);

const today = computed(() => new Date().toISOString().split('T')[0]);

const form = useForm({
    _method: 'PUT',
    title: '',
    date: '',
    duration: 8,
    activities: '',
    learning_points: '',
    challenges: '',
    attachments: [],
    removed_files: [],
    status: 'submitted'
});

const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    newFiles.value = [...newFiles.value, ...files];
};

const removeNewFile = (idx) => {
    newFiles.value.splice(idx, 1);
};

const removeExistingFile = (idx) => {
    const file = existingFiles.value[idx];
    form.removed_files.push(file.path);
    existingFiles.value.splice(idx, 1);
};

const handleClose = () => {
    const hasChanges =
        form.isDirty ||
        newFiles.value.length > 0 ||
        form.removed_files.length > 0;

    if (hasChanges) {
        SwalPlugin.confirmAction(
            "Batal Edit",
            "Anda memiliki perubahan yang belum disimpan. Yakin ingin membatalkan?",
            "Ya, Batalkan"
        ).then((result) => {
            if (result.isConfirmed) {
                emit('close');
            }
        });
    } else {
        emit('close');
    }
};

const fetchLogbook = async () => {
    if (!props.logbookId) return;
    loading.value = true;
    try {
        const response = await axios.get(route('profile.logbooks.show', props.logbookId), {
            headers: { Accept: 'application/json' }
        });
        const lb = response.data.logbook;
        
        form.title = lb.title || '';
        form.date = lb.date ? lb.date.split('T')[0] : '';
        form.duration = lb.duration || 8;
        form.activities = lb.activities || '';
        form.learning_points = lb.learning_points || '';
        form.challenges = lb.challenges || '';
        form.status = lb.status || 'submitted';
        form.removed_files = [];
        
        existingFiles.value = lb.attachments || [];
        newFiles.value = [];
        
        // Find latest revision comment if available
        if (lb.status === 'revision') {
            revisionComment.value = lb.mentor_feedback || '';
        } else {
            revisionComment.value = '';
        }
        
        // Clear errors and reset dirty states
        form.clearErrors();
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const submitForm = (status) => {
    form.status = status;
    form.attachments = newFiles.value;
    
    form.post(route('peserta.logbooks.update', props.logbookId), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            newFiles.value = [];
            emit('success');
        },
        onError: (errs) => {
            console.error('Validation errors:', errs);
        }
    });
};

watch(() => props.show, (newVal) => {
    if (newVal && props.logbookId) {
        fetchLogbook();
    } else {
        // Reset states
        existingFiles.value = [];
        newFiles.value = [];
        revisionComment.value = '';
    }
});
</script>
