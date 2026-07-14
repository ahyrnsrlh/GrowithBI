import { ref, reactive, watch } from "vue";
import { router } from "@inertiajs/vue3";
import SwalPlugin from "@/Plugins/sweetalert";

const ACCEPTED_STATUSES = ["accepted", "letter_ready", "diterima"];

export function useAdminApplicationShowPage(application, page) {
    const showStatusModal = ref(false);
    const showLetterUpload = ref(false);
    const showSuccessModal = ref(false);
    const showDeleteModal = ref(false);
    const successMessage = ref("");
    const selectedFile = ref(null);
    const isUploading = ref(false);
    const uploadError = ref("");
    const uploadInputKey = ref(0);

    const statusForm = reactive({
        status: application.status,
        admin_notes: application.admin_notes || "",
        rejection_reason: application.rejection_reason || "",
        interview_date: application.interview_date
            ? new Date(application.interview_date).toISOString().split("T")[0]
            : "",
        interview_time: application.interview_time || "",
        interview_location: application.interview_location || "",
        interview_location_detail: application.interview_location_detail || "",
    });

    watch(
        () => page.props.flash,
        (flash) => {
            if (flash?.success) {
                const isLetterRelated = flash.success
                    .toLowerCase()
                    .includes("surat penerimaan");
                if (isLetterRelated) {
                    successMessage.value = flash.success;
                    showSuccessModal.value = true;
                }
            }
        },
        { immediate: true },
    );

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    };

    const formatDateTime = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const getDaysAgo = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    };

    const isAcceptedStatus = (status) => ACCEPTED_STATUSES.includes(status);

    const getStatusBadgeClass = (status) => {
        if (status === "menunggu") {
            return "bg-yellow-100 text-yellow-800";
        }
        if (status === "in_review") {
            return "bg-blue-100 text-blue-800";
        }
        if (status === "interview_scheduled") {
            return "bg-purple-100 text-purple-800";
        }
        if (isAcceptedStatus(status)) {
            return "bg-green-100 text-green-800";
        }
        if (status === "rejected") {
            return "bg-red-100 text-red-800";
        }
        if (status === "cancelled") {
            return "bg-gray-100 text-gray-800 border border-gray-200";
        }
        return "bg-gray-100 text-gray-800";
    };

    const getStatusTextClass = (status) => {
        if (status === "menunggu") {
            return "text-yellow-600";
        }
        if (status === "in_review") {
            return "text-blue-600";
        }
        if (status === "interview_scheduled") {
            return "text-purple-600";
        }
        if (isAcceptedStatus(status)) {
            return "text-green-600";
        }
        if (status === "rejected") {
            return "text-red-600";
        }
        if (status === "cancelled") {
            return "text-gray-500";
        }
        return "text-gray-600";
    };

    const updateStatus = () => {
        SwalPlugin.confirmAction(
            "Perbarui Status",
            `Apakah Anda yakin ingin mengubah status pendaftaran ini menjadi "${statusForm.status.replace(/_/g, ' ')}"?`,
            "Ya, Perbarui"
        ).then((result) => {
            if (result.isConfirmed) {
                router.put(`/admin/applications/${application.id}`, statusForm, {
                    onSuccess: () => {
                        showStatusModal.value = false;
                    },
                });
            }
        });
    };

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        uploadError.value = "";

        if (!file) {
            selectedFile.value = null;
            return;
        }

        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        ];
        if (!allowedTypes.includes(file.type)) {
            uploadError.value =
                "Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.";
            selectedFile.value = null;
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            uploadError.value = "Ukuran file terlalu besar. Maksimal 5MB.";
            selectedFile.value = null;
            return;
        }

        selectedFile.value = file;
    };

    const resetLetterUploadState = () => {
        selectedFile.value = null;
        uploadError.value = "";
        uploadInputKey.value += 1;
    };

    const uploadAcceptanceLetter = () => {
        if (!selectedFile.value) {
            uploadError.value = "Pilih file terlebih dahulu.";
            return;
        }

        isUploading.value = true;
        uploadError.value = "";

        const formData = new FormData();
        formData.append("acceptance_letter", selectedFile.value);

        router.post(
            `/admin/applications/${application.id}/upload-acceptance-letter`,
            formData,
            {
                onSuccess: () => {
                    showLetterUpload.value = false;
                    resetLetterUploadState();
                },
                onError: (errors) => {
                    if (errors.acceptance_letter) {
                        uploadError.value = errors.acceptance_letter;
                    } else {
                        uploadError.value =
                            "Terjadi kesalahan saat mengupload file.";
                    }
                },
                onFinish: () => {
                    isUploading.value = false;
                },
            },
        );
    };

    const closeLetterUpload = () => {
        showLetterUpload.value = false;
        resetLetterUploadState();
    };

    const deleteApplication = () => {
        SwalPlugin.confirmDestructive(
            "Hapus Pendaftar",
            `Apakah Anda yakin ingin menghapus data pendaftar "${application.user?.name || 'N/A'}"? Seluruh data terkait akan ikut terhapus dan tindakan ini tidak dapat dibatalkan.`
        ).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/admin/applications/${application.id}`, {
                    onError: (errors) => {
                        console.error("Failed to delete application", errors);
                        SwalPlugin.toastError("Gagal menghapus pendaftaran.");
                    },
                });
            }
        });
    };

    const openDeleteModal = () => {
        deleteApplication();
    };

    const closeDeleteModal = () => {};

    const showEvaluationModal = ref(false);
    const evaluationForm = reactive({
        competency_score: 0,
        motivation_score: 0,
        interview_score: 0,
        reviewer_notes: "",
    });

    const openEvaluationModal = (evaluationData) => {
        if (evaluationData) {
            const comp = evaluationData.criteria.find(c => c.name === 'Competency');
            const mot = evaluationData.criteria.find(c => c.name === 'Motivation Letter');
            const intr = evaluationData.criteria.find(c => c.name === 'Interview');

            evaluationForm.competency_score = comp ? comp.raw_score : 0;
            evaluationForm.motivation_score = mot ? mot.raw_score : 0;
            evaluationForm.interview_score = intr ? intr.raw_score : 0;
            evaluationForm.reviewer_notes = evaluationData.reviewer_notes || "";
        } else {
            evaluationForm.competency_score = 0;
            evaluationForm.motivation_score = 0;
            evaluationForm.interview_score = 0;
            evaluationForm.reviewer_notes = "";
        }
        showEvaluationModal.value = true;
    };

    const submitEvaluation = () => {
        router.post(`/admin/applications/${application.id}/evaluate`, evaluationForm, {
            onSuccess: () => {
                showEvaluationModal.value = false;
                SwalPlugin.toastSuccess("Evaluasi berhasil disimpan.");
            },
            onError: (errs) => {
                console.error("Failed to save evaluation", errs);
                SwalPlugin.toastError(Object.values(errs)[0] || "Gagal menyimpan evaluasi.");
            }
        });
    };

    return {
        showStatusModal,
        showLetterUpload,
        showSuccessModal,
        showDeleteModal,
        successMessage,
        selectedFile,
        isUploading,
        uploadError,
        uploadInputKey,
        statusForm,
        formatDate,
        formatDateTime,
        getDaysAgo,
        isAcceptedStatus,
        getStatusBadgeClass,
        getStatusTextClass,
        updateStatus,
        handleFileChange,
        uploadAcceptanceLetter,
        closeLetterUpload,
        openDeleteModal,
        closeDeleteModal,
        deleteApplication,
        showEvaluationModal,
        evaluationForm,
        openEvaluationModal,
        submitEvaluation,
    };
}
