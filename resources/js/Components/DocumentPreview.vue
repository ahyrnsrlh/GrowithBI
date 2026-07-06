<template>
    <div
        class="relative w-full bg-gray-100/50 rounded-xl overflow-hidden flex flex-col justify-center items-center min-h-[50vh] border border-gray-200/60 p-4"
    >
        <!-- Loading State -->
        <div
            v-if="loading"
            class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50/80 z-20"
        >
            <svg
                class="w-12 h-12 text-indigo-600 animate-spin"
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
            <p class="mt-4 text-sm font-medium text-gray-600">
                Memuat dokumen...
            </p>
        </div>

        <!-- Error State -->
        <div
            v-if="error"
            class="flex flex-col items-center justify-center p-8 text-center max-w-md"
        >
            <div
                class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4"
            >
                <svg
                    class="w-8 h-8 text-red-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    ></path>
                </svg>
            </div>
            <h4 class="text-base font-semibold text-gray-900 mb-1">
                Gagal memuat pratinjau
            </h4>
            <p class="text-sm text-gray-500 mb-4">{{ error }}</p>
            <button
                @click="retryLoad"
                class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm"
            >
                Coba Lagi
            </button>
        </div>

        <!-- PDF Viewer -->
        <div
            v-else-if="fileType === 'pdf'"
            class="w-full max-h-[70vh] overflow-y-auto scrollbar-thin bg-gray-500/10 rounded-lg p-2 flex justify-center"
        >
            <VuePdfEmbed
                v-if="url"
                :source="url"
                class="w-full max-w-3xl shadow-lg rounded"
                @loaded="onLoadSuccess"
                @rendering-failed="onLoadError"
            />
        </div>

        <!-- Image Viewer -->
        <div
            v-else-if="fileType === 'image'"
            class="w-full flex justify-center items-center max-h-[70vh] overflow-hidden"
        >
            <img
                :src="url"
                :alt="fileName"
                class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-sm transition-all duration-300"
                @load="onLoadSuccess"
                @error="onLoadError"
            />
        </div>

        <!-- Unsupported File State -->
        <div
            v-else-if="fileType === 'unsupported'"
            class="flex flex-col items-center justify-center p-8 text-center max-w-md"
        >
            <div
                class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mb-4"
            >
                <svg
                    class="w-8 h-8 text-amber-500"
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
            </div>
            <h4 class="text-base font-semibold text-gray-900 mb-1">
                Pratinjau Tidak Tersedia
            </h4>
            <p class="text-sm text-gray-500">
                Pratinjau tidak didukung untuk tipe file ini. Silakan unduh
                dokumen untuk melihatnya.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import VuePdfEmbed from "vue-pdf-embed";

const props = defineProps({
    url: { type: String, required: true },
    fileName: { type: String, default: "" },
});

const loading = ref(true);
const error = ref(null);

const fileExtension = computed(() => {
    if (!props.url) return "";
    const cleanUrl = props.url.split("?")[0];
    return cleanUrl.split(".").pop().toLowerCase();
});

const fileType = computed(() => {
    const ext = fileExtension.value;
    if (!ext) return "unsupported";
    if (ext === "pdf") return "pdf";
    if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) return "image";
    return "unsupported";
});

const onLoadSuccess = () => {
    loading.value = false;
    error.value = null;
};

const onLoadError = (err) => {
    loading.value = false;
    error.value =
        "Dokumen gagal dimuat. Pastikan URL valid dan file dapat diakses.";
    console.error("Document preview loading error:", err);
};

const retryLoad = () => {
    loading.value = true;
    error.value = null;
};

// If file type is unsupported, stop loading instantly
watch(
    fileType,
    (newType) => {
        if (newType === "unsupported") {
            loading.value = false;
        } else {
            loading.value = true;
        }
    },
    { immediate: true },
);

onMounted(() => {
    if (fileType.value === "unsupported") {
        loading.value = false;
    }
});
</script>
