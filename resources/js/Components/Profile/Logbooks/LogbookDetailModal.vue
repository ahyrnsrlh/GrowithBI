<template>
    <BaseModal
        :show="show"
        :title="loading ? 'Memuat Detail Logbook...' : logbookData?.title || 'Detail Logbook'"
        subtitle="Lihat detail aktivitas harian dan ulasan pembimbing"
        maxWidth="4xl"
        @close="$emit('close')"
    >
        <!-- Loading State -->
        <div v-if="loading" class="py-12 flex flex-col items-center justify-center space-y-4">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            <p class="text-sm text-gray-500 font-medium">Mengambil data logbook...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error || !logbookData" class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-1">Gagal Memuat Data</h4>
            <p class="text-sm text-gray-500 mb-4">Maaf, terjadi kesalahan saat mengambil data logbook.</p>
            <button
                @click="fetchLogbook"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors shadow-sm"
            >
                Coba Lagi
            </button>
        </div>

        <!-- Data Content -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Activities -->
                <div>
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Aktivitas & Kegiatan</h4>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-gray-800 text-sm whitespace-pre-line leading-relaxed">
                        {{ logbookData.activities || 'Tidak ada deskripsi aktivitas.' }}
                    </div>
                </div>

                <!-- Learning Points & Challenges -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Poin Pembelajaran</h4>
                        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 text-blue-900 text-sm whitespace-pre-line leading-relaxed min-h-[100px]">
                            {{ logbookData.learning_points || 'Tidak ada poin pembelajaran yang dicatat.' }}
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Hambatan & Tantangan</h4>
                        <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100 text-purple-900 text-sm whitespace-pre-line leading-relaxed min-h-[100px]">
                            {{ logbookData.challenges || 'Tidak ada hambatan yang dicatat.' }}
                        </div>
                    </div>
                </div>

                <!-- Discussion & Comments -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Diskusi & Komentar ({{ logbookData.comments?.length || 0 }})</h4>
                    
                    <!-- Comments List -->
                    <div v-if="logbookData.comments?.length > 0" class="space-y-4 max-h-[250px] overflow-y-auto pr-2 mb-4">
                        <div
                            v-for="comment in logbookData.comments"
                            :key="comment.id"
                            class="p-3.5 rounded-xl border border-gray-100 bg-white shadow-sm flex flex-col space-y-1"
                        >
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-xs text-gray-900">
                                    {{ comment.user?.name }}
                                </span>
                                <span
                                    :class="[
                                        'px-2 py-0.5 rounded text-[10px] font-bold uppercase',
                                        comment.user?.role === 'admin' || comment.user?.role === 'mentor'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-gray-100 text-gray-800'
                                    ]"
                                >
                                    {{ comment.user?.role === 'mentor' ? 'Pembimbing' : comment.user?.role }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ comment.comment }}</p>
                            <span class="text-[10px] text-gray-400 self-end mt-1">
                                {{ formatDateTime(comment.created_at) }}
                            </span>
                        </div>
                    </div>

                    <div v-else class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400 text-xs mb-4">
                        Belum ada komentar dalam logbook ini.
                    </div>

                    <!-- Comment Form -->
                    <form @submit.prevent="submitComment" class="flex gap-2 items-start">
                        <textarea
                            v-model="commentForm.comment"
                            rows="2"
                            placeholder="Tulis tanggapan atau pertanyaan..."
                            required
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm resize-none"
                        ></textarea>
                        <button
                            type="submit"
                            :disabled="commentForm.processing"
                            class="px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 font-semibold rounded-xl text-sm transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Kirim
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info Panel (1 Col) -->
            <div class="space-y-6">
                <!-- Status Box -->
                <div class="bg-gray-50 rounded-xl border border-gray-100 p-5 space-y-4">
                    <div>
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block mb-1">Status Logbook</span>
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-semibold inline-block',
                                getStatusClass(logbookData.status)
                            ]"
                        >
                            {{ getStatusText(logbookData.status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-200/60 pt-3">
                        <div>
                            <span class="text-[10px] text-gray-400 font-semibold uppercase block">Tanggal</span>
                            <span class="text-xs font-bold text-gray-700">{{ formatDateShort(logbookData.date) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-gray-400 font-semibold uppercase block">Durasi</span>
                            <span class="text-xs font-bold text-gray-700">{{ logbookData.duration }} jam</span>
                        </div>
                    </div>

                    <div v-if="logbookData.division" class="border-t border-gray-200/60 pt-3">
                        <span class="text-[10px] text-gray-400 font-semibold uppercase block">Divisi Magang</span>
                        <span class="text-xs font-bold text-gray-700 block mt-0.5">{{ logbookData.division.name }}</span>
                    </div>
                </div>

                <!-- Review / Feedback Card -->
                <div v-if="logbookData.mentor_feedback || logbookData.reviewer" class="bg-yellow-50/50 rounded-xl border border-yellow-100 p-5 space-y-3">
                    <h5 class="text-xs font-bold text-yellow-800 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Ulasan Pembimbing
                    </h5>
                    
                    <div class="space-y-2 text-xs">
                        <p class="text-yellow-900 leading-relaxed italic bg-white/70 p-3 rounded-lg border border-yellow-100/50">
                            "{{ logbookData.mentor_feedback || 'Tidak ada catatan ulasan khusus.' }}"
                        </p>
                        <div class="flex items-center justify-between text-[10px] text-yellow-700/80 pt-1">
                            <span>Oleh: {{ logbookData.reviewer?.name || 'Pembimbing' }}</span>
                            <span v-if="logbookData.reviewed_at">{{ formatDateShort(logbookData.reviewed_at) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="bg-gray-50 rounded-xl border border-gray-100 p-5">
                    <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider block mb-3">Lampiran Dokumentasi</h5>
                    <div v-if="logbookData.attachments?.length > 0" class="space-y-2">
                        <a
                            v-for="(file, idx) in logbookData.attachments"
                            :key="idx"
                            :href="'/storage/' + file.path"
                            target="_blank"
                            class="flex items-center gap-2.5 p-2.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition-colors text-xs font-medium text-blue-600 hover:text-blue-700 truncate"
                        >
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="truncate flex-1">{{ file.name }}</span>
                        </a>
                    </div>
                    <div v-else class="text-center py-4 text-gray-400 text-xs">
                        Tidak ada file lampiran.
                    </div>
                </div>
            </div>
        </div>
    </BaseModal>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import BaseModal from '@/Components/BaseModal.vue';

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

defineEmits(['close']);

const loading = ref(false);
const error = ref(false);
const logbookData = ref(null);

const commentForm = useForm({
    comment: ''
});

const getStatusClass = (status) => {
    const classes = {
        submitted: 'bg-[#FFA726] text-white',
        approved: 'bg-[#43A047] text-white',
        revision: 'bg-[#AB47BC] text-white',
        draft: 'bg-gray-500 text-white',
    };
    return classes[status] || 'bg-gray-500 text-white';
};

const getStatusText = (status) => {
    const texts = {
        submitted: 'Pending',
        approved: 'Disetujui',
        revision: 'Revisi',
        draft: 'Draft',
    };
    return texts[status] || 'Unknown';
};

const formatDateShort = (dateString) => {
    if (!dateString) return '-';
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const fetchLogbook = async () => {
    if (!props.logbookId) return;
    loading.value = true;
    error.value = false;
    try {
        const response = await axios.get(route('profile.logbooks.show', props.logbookId), {
            headers: { Accept: 'application/json' }
        });
        logbookData.value = response.data.logbook;
    } catch (err) {
        console.error(err);
        error.value = true;
    } finally {
        loading.value = false;
    }
};

const submitComment = () => {
    if (!commentForm.comment.trim()) return;
    commentForm.post(route('peserta.logbooks.comments.store', logbookData.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
            fetchLogbook(); // Refresh data to show comment
        }
    });
};

watch(() => props.show, (newVal) => {
    if (newVal && props.logbookId) {
        fetchLogbook();
    } else {
        logbookData.value = null;
    }
});
</script>
