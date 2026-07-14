import { computed, onUnmounted, ref } from "vue";
import SwalPlugin from "@/Plugins/sweetalert";

export function useDocumentCard(props, emit) {
    const showPreview = ref(false);
    const fileInput = ref(null);
    const isLoading = ref(false);
    const isDownloading = ref(false);
    const previewLoading = ref(false);
    const previewError = ref(null);
    const previewUrl = ref(null);

    const getDocumentUrl = computed(() => {
        if (!props.documentPath) {
            return null;
        }

        if (
            props.documentPath.startsWith("http://") ||
            props.documentPath.startsWith("https://")
        ) {
            return props.documentPath;
        }

        let path = props.documentPath;
        if (path.startsWith("/")) {
            path = path.slice(1);
        }

        if (path.startsWith("storage/")) {
            return `/${path}`;
        }

        return `/storage/${path}`;
    });

    const hasDocument = computed(() => Boolean(props.documentPath));

    const isPDF = computed(() => {
        if (!props.documentPath) {
            return false;
        }

        return props.documentPath.toLowerCase().endsWith(".pdf");
    });

    const triggerFileInput = () => {
        if (fileInput.value) {
            fileInput.value.click();
        }
    };

    const handleKeydown = (event) => {
        if (event.key === "Escape") {
            closePreview();
        }
    };

    const viewDocument = async () => {
        if (!props.documentPath) {
            return;
        }

        isLoading.value = true;
        previewLoading.value = true;
        previewError.value = null;
        previewUrl.value = getDocumentUrl.value;
        showPreview.value = true;
        document.addEventListener("keydown", handleKeydown);
        isLoading.value = false;
    };

    const onPreviewLoad = () => {
        previewLoading.value = false;
        previewError.value = null;
    };

    const onPreviewError = () => {
        previewLoading.value = false;
        previewError.value =
            "File tidak ditemukan atau tidak dapat ditampilkan";
    };

    const retryPreview = () => {
        previewLoading.value = true;
        previewError.value = null;
        previewUrl.value = `${getDocumentUrl.value}?t=${Date.now()}`;
    };

    const closePreview = () => {
        showPreview.value = false;
        previewUrl.value = null;
        previewError.value = null;
        document.removeEventListener("keydown", handleKeydown);
    };

    const openInNewTab = () => {
        if (getDocumentUrl.value) {
            window.open(getDocumentUrl.value, "_blank", "noopener,noreferrer");
        }
    };

    const downloadDocument = async () => {
        if (!props.documentPath || isDownloading.value) {
            return;
        }

        isDownloading.value = true;

        try {
            const response = await fetch(getDocumentUrl.value);

            if (!response.ok) {
                throw new Error("File tidak ditemukan");
            }

            const blob = await response.blob();
            const filename =
                props.documentPath.split("/").pop() ||
                `${props.documentType}.pdf`;

            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = downloadUrl;
            link.download = filename;
            link.style.display = "none";

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(downloadUrl);
        } catch (error) {
            console.error("Download error:", error);
            SwalPlugin.toastError(`Gagal mengunduh dokumen. ${error.message}`);
        } finally {
            isDownloading.value = false;
        }
    };

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            SwalPlugin.toastError("Ukuran file terlalu besar. Maksimal 5MB.");
            event.target.value = "";
            return;
        }

        const allowedTypes = [
            "application/pdf",
            "image/jpeg",
            "image/jpg",
            "image/png",
        ];

        if (!allowedTypes.includes(file.type)) {
            SwalPlugin.toastError("Format file tidak didukung. Gunakan PDF, JPG, atau PNG.");
            event.target.value = "";
            return;
        }

        if (props.documentPath) {
            SwalPlugin.confirmAction(
                "Ganti File",
                "Apakah Anda yakin ingin mengganti file yang sudah ada?",
                "Ya, Ganti"
            ).then((result) => {
                if (result.isConfirmed) {
                    emit("upload", props.documentType, file);
                }
                event.target.value = "";
            });
        } else {
            emit("upload", props.documentType, file);
            event.target.value = "";
        }
    };

    onUnmounted(() => {
        document.removeEventListener("keydown", handleKeydown);
    });

    return {
        showPreview,
        fileInput,
        isLoading,
        isDownloading,
        previewLoading,
        previewError,
        previewUrl,
        hasDocument,
        isPDF,
        triggerFileInput,
        viewDocument,
        onPreviewLoad,
        onPreviewError,
        retryPreview,
        closePreview,
        openInNewTab,
        downloadDocument,
        handleFileChange,
    };
}
