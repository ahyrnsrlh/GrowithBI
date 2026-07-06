<template>
    <BaseModal :show="show" @close="handleClose">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Ubah Laporan Akhir</h3>
                    <p class="text-xs text-gray-500 mt-1">Perbarui judul, deskripsi, atau file dokumen Anda</p>
                </div>
            </div>

            <!-- Loader State -->
            <div v-if="loading" class="py-12 flex flex-col items-center justify-center">
                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-sm text-gray-500">Memuat data laporan...</p>
            </div>

            <!-- Edit Form -->
            <form v-else @submit.prevent="submit" class="mt-6 space-y-5">
                <!-- Title Input -->
                <div>
                    <label for="edit-title" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="edit-title"
                        v-model="form.title"
                        required
                        class="w-full px-4 py-2.5 border border-gray-350 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Contoh: Laporan Akhir Magang Divisi IT"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-xs text-red-600">{{ form.errors.title }}</p>
                </div>

                <!-- Description Textarea -->
                <div>
                    <label for="edit-desc" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Deskripsi / Ringkasan Laporan
                    </label>
                    <textarea
                        id="edit-desc"
                        v-model="form.description"
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-350 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Jelaskan secara singkat isi laporan akhir Anda..."
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- File Attachment Area -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        File Laporan (PDF)
                    </label>
                    <div class="space-y-3">
                        <!-- Current file preview if no new file is queued -->
                        <div
                            v-if="currentFile && !form.report_file"
                            class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50/20 text-xs"
                        >
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-green-900 truncate">{{ currentFile }}</p>
                                <p class="text-[10px] text-green-600">Dokumen tersimpan saat ini (Tetap digunakan jika tidak mengunggah file baru)</p>
                            </div>
                        </div>

                        <!-- New Queued File Preview -->
                        <div
                            v-if="form.report_file"
                            class="flex items-center justify-between p-3 rounded-xl border border-blue-200 bg-blue-50/30 text-xs"
                        >
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-blue-800 truncate">{{ form.report_file.name }}</p>
                                    <p class="text-[10px] text-blue-500">File baru siap diunggah &bull; {{ formatBytes(form.report_file.size) }}</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="cancelNewFile"
                                class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Dropzone input -->
                        <label
                            class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-xl p-4 bg-white cursor-pointer hover:bg-blue-50/10 transition-all group"
                        >
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600 transition-colors">Unggah File PDF Baru</span>
                            <input
                                type="file"
                                accept=".pdf"
                                class="hidden"
                                @change="handleFileChange"
                            />
                        </label>
                        <span class="text-[10px] text-gray-400 block">Hanya mendukung format PDF. Ukuran maks: 10MB.</span>
                        <p v-if="form.errors.report_file" class="mt-1 text-xs text-red-600">{{ form.errors.report_file }}</p>
                    </div>
                </div>

                <!-- Action Footer Buttons -->
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button
                        type="button"
                        @click="handleClose"
                        class="px-5 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 text-xs font-bold transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all disabled:opacity-50 flex items-center gap-1.5"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </BaseModal>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import BaseModal from "@/Components/BaseModal.vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    reportId: { type: [Number, String], default: null },
});

const emit = defineEmits(["close", "success"]);

const loading = ref(false);
const currentFile = ref("");

const form = useForm({
    title: "",
    description: "",
    report_file: null,
    _method: "PUT",
});

const fetchReportDetails = () => {
    if (!props.reportId) return;

    loading.value = true;

    window.axios
        .get(route("peserta.reports.show", props.reportId))
        .then((response) => {
            const report = response.data?.report || response.data;
            if (report) {
                form.title = report.title || "";
                form.description = report.description || "";
                form.report_file = null;
                currentFile.value = report.file_name || "";
            }
        })
        .catch((err) => {
            console.error("Failed to load report for editing:", err);
        })
        .finally(() => {
            loading.value = false;
        });
};

watch(
    () => props.show,
    (isShown) => {
        if (isShown) {
            form.clearErrors();
            form.reset();
            currentFile.value = "";
            fetchReportDetails();
        }
    }
);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        if (file.type !== "application/pdf") {
            form.setError("report_file", "Hanya dokumen PDF yang diperbolehkan.");
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            form.setError("report_file", "Ukuran file tidak boleh melebihi 10MB.");
            return;
        }
        form.clearErrors("report_file");
        form.report_file = file;
    }
};

const cancelNewFile = () => {
    form.report_file = null;
};

const handleClose = () => {
    if (!form.processing) {
        emit("close");
    }
};

const submit = () => {
    form.post(route("peserta.reports.update", props.reportId), {
        forceFormData: true,
        onSuccess: () => {
            emit("success");
        },
        onError: (err) => {
            console.error("Edit submission errors:", err);
        },
    });
};

const formatBytes = (bytes, decimals = 2) => {
    if (!bytes) return "0 Bytes";
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};
</script>
