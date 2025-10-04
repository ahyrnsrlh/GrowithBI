<template>
    <div
        class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group"
    >
        <!-- Card Header - Modern Design -->
        <div
            class="px-5 py-4 bg-gradient-to-r from-[#F6F8FA] to-white border-b border-gray-100"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-md"
                    >
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-600">
                            {{ title }}
                            <span v-if="required" class="text-red-500 ml-1"
                                >*</span
                            >
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{
                                currentFile
                                    ? "Dokumen tersedia"
                                    : "Belum diupload"
                            }}
                        </p>
                    </div>
                </div>

                <!-- Status Badge -->
                <span
                    v-if="currentFile"
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-[#43A047] text-white flex items-center gap-1.5"
                >
                    <i class="fas fa-check-circle text-xs"></i>
                    Tersimpan
                </span>
                <span
                    v-else
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600"
                >
                    Kosong
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-5">
            <!-- Current File Display -->
            <div
                v-if="currentFile"
                class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg"
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-center flex-1 min-w-0">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3"
                        >
                            <i
                                class="fas fa-file-alt text-green-600 text-lg"
                            ></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-medium text-green-800 truncate"
                            >
                                {{ getFileName(currentFile) }}
                            </p>
                            <p class="text-xs text-green-600 mt-1"></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <a
                            :href="`/storage/${currentFile}`"
                            target="_blank"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-md hover:bg-blue-200 hover:border-blue-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        >
                            <i class="fas fa-eye mr-1.5"></i>
                            Lihat
                        </a>
                        <a
                            :href="`/storage/${currentFile}`"
                            download
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 border border-green-200 rounded-md hover:bg-green-200 hover:border-green-300 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                        >
                            <i class="fas fa-download mr-1.5"></i>
                            Unduh
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload Area -->
            <div
                @drop="handleDrop"
                @dragover.prevent
                @dragenter.prevent
                :class="[
                    'border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200',
                    isDragging
                        ? 'border-blue-400 bg-blue-50'
                        : 'border-gray-300 hover:border-gray-400 hover:bg-gray-50',
                ]"
            >
                <div class="flex flex-col items-center">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4"
                    >
                        <i
                            class="fas fa-cloud-upload-alt text-xl text-gray-400"
                        ></i>
                    </div>
                    <p class="text-sm font-medium text-gray-700 mb-2">
                        {{
                            currentFile
                                ? "Upload file baru untuk mengganti"
                                : "Drag & drop file atau klik untuk browse"
                        }}
                    </p>
                    <p class="text-xs text-gray-500 mb-6">
                        Format: PDF, JPG, PNG (Maksimal: 5MB)
                    </p>

                    <label
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg cursor-pointer hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        <i class="fas fa-folder-open mr-2"></i>
                        Pilih File
                        <input
                            type="file"
                            @change="handleFileSelect"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="hidden"
                        />
                    </label>
                </div>
            </div>

            <!-- Upload Progress -->
            <div v-if="uploading" class="mt-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700"
                        >Mengupload file...</span
                    >
                    <span class="text-sm font-medium text-blue-600"
                        >{{ uploadProgress }}%</span
                    >
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div
                        class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-out"
                        :style="`width: ${uploadProgress}%`"
                    ></div>
                </div>
            </div>

            <!-- Error Message -->
            <div
                v-if="errorMessage"
                class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg"
            >
                <div class="flex items-start text-red-700">
                    <i
                        class="fas fa-exclamation-triangle mr-2 mt-0.5 flex-shrink-0"
                    ></i>
                    <span class="text-sm">{{ errorMessage }}</span>
                </div>
            </div>

            <!-- File Requirements -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <div
                    class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-600"
                >
                    <span class="flex items-center"
                        ><i class="fas fa-info-circle mr-1.5 text-blue-500"></i
                        >Maksimal 5MB</span
                    >
                    <span class="flex items-center"
                        ><i class="fas fa-file-alt mr-1.5 text-green-500"></i
                        >PDF, JPG, PNG</span
                    >
                    <span v-if="required" class="flex items-center"
                        ><i class="fas fa-asterisk mr-1.5 text-red-500"></i
                        >Wajib diisi</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    title: String,
    type: String,
    currentFile: String,
    required: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["upload"]);

// State
const isDragging = ref(false);
const uploading = ref(false);
const uploadProgress = ref(0);
const errorMessage = ref("");

// Methods
const getFileName = (filePath) => {
    if (!filePath) return "";
    return filePath.split("/").pop();
};

const validateFile = (file) => {
    const allowedTypes = [
        "application/pdf",
        "image/jpeg",
        "image/jpg",
        "image/png",
    ];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!allowedTypes.includes(file.type)) {
        return "Format file tidak didukung. Gunakan PDF, JPG, atau PNG.";
    }

    if (file.size > maxSize) {
        return "Ukuran file terlalu besar. Maksimal 5MB.";
    }

    return null;
};

const processFile = (file) => {
    errorMessage.value = "";

    const error = validateFile(file);
    if (error) {
        errorMessage.value = error;
        return;
    }

    uploading.value = true;
    uploadProgress.value = 0;

    // Simulate upload progress
    const interval = setInterval(() => {
        uploadProgress.value += 10;
        if (uploadProgress.value >= 90) {
            clearInterval(interval);
        }
    }, 100);

    // Emit the upload event
    emit("upload", props.type, file);

    // Reset states after a delay
    setTimeout(() => {
        uploading.value = false;
        uploadProgress.value = 0;
        clearInterval(interval);
    }, 2000);
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        processFile(file);
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;

    const files = event.dataTransfer.files;
    if (files.length > 0) {
        processFile(files[0]);
    }
};

const handleDragEnter = () => {
    isDragging.value = true;
};

const handleDragLeave = () => {
    isDragging.value = false;
};

// Lifecycle
onMounted(() => {
    document.addEventListener("dragenter", handleDragEnter);
    document.addEventListener("dragleave", handleDragLeave);
});

onUnmounted(() => {
    document.removeEventListener("dragenter", handleDragEnter);
    document.removeEventListener("dragleave", handleDragLeave);
});
</script>
