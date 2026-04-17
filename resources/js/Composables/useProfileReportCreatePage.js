import { useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

export function useProfileReportCreatePage() {
    const selectedFile = ref(null);
    const isDragging = ref(false);
    const uploading = ref(false);
    const fileInputRef = ref(null);

    const form = useForm({
        title: "",
        description: "",
        report_file: null,
    });

    onMounted(() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken && window.axios) {
            window.axios.defaults.headers.common["X-CSRF-TOKEN"] =
                csrfToken.getAttribute("content");
        }
    });

    const setFileInputRef = (el) => {
        fileInputRef.value = el;
    };

    const openFilePicker = () => {
        if (fileInputRef.value) {
            fileInputRef.value.click();
        }
    };

    const validateFile = (file, resetInput = false) => {
        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];

        if (!allowedTypes.includes(file.type)) {
            alert(
                "File type tidak didukung. Silakan pilih file PDF, Word (.doc/.docx), atau Excel (.xls/.xlsx)",
            );
            if (resetInput && fileInputRef.value) {
                fileInputRef.value.value = "";
            }
            return false;
        }

        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert("Ukuran file terlalu besar. Maksimal 10MB.");
            if (resetInput && fileInputRef.value) {
                fileInputRef.value.value = "";
            }
            return false;
        }

        return true;
    };

    const handleFileSelect = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        if (!validateFile(file, true)) {
            return;
        }

        selectedFile.value = file;
        form.report_file = file;
    };

    const handleDrop = (event) => {
        event.preventDefault();
        isDragging.value = false;

        const file = event.dataTransfer.files[0];
        if (!file) return;

        if (!validateFile(file)) {
            return;
        }

        selectedFile.value = file;
        form.report_file = file;
    };

    const handleDragEnter = () => {
        isDragging.value = true;
    };

    const handleDragLeave = () => {
        isDragging.value = false;
    };

    const removeFile = () => {
        selectedFile.value = null;
        form.report_file = null;
        if (fileInputRef.value) {
            fileInputRef.value.value = "";
        }
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    };

    const submit = () => {
        if (uploading.value) return;

        uploading.value = true;

        form.post(route("profile.reports.store"), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                uploading.value = false;
            },
            onError: (errors) => {
                uploading.value = false;
                console.error("Upload error:", errors);

                if (
                    errors.message &&
                    (errors.message.includes("419") ||
                        errors.message.includes("CSRF"))
                ) {
                    alert(
                        "Session telah expired. Halaman akan di-refresh untuk memperbarui session.",
                    );
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    return;
                }

                if (errors.report_file) {
                    alert("Error file upload: " + errors.report_file);
                } else if (errors.message) {
                    alert("Error: " + errors.message);
                } else {
                    alert("Terjadi kesalahan saat upload. Silakan coba lagi.");
                }
            },
            onBefore: () => {
                if (!form.title) {
                    alert("Judul laporan harus diisi");
                    uploading.value = false;
                    return false;
                }

                if (!form.report_file) {
                    alert("File laporan harus dipilih");
                    uploading.value = false;
                    return false;
                }

                form.clearErrors();
                return true;
            },
            onFinish: () => {
                uploading.value = false;
            },
        });
    };

    return {
        form,
        selectedFile,
        isDragging,
        uploading,
        setFileInputRef,
        openFilePicker,
        handleFileSelect,
        handleDrop,
        handleDragEnter,
        handleDragLeave,
        removeFile,
        formatFileSize,
        submit,
    };
}
