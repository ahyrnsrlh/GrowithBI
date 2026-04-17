import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

export function useAdminLogbookShowPage(logbook) {
    const processing = ref(false);
    const reviewForm = reactive({
        status: "",
        feedback: "",
    });

    const submitReview = () => {
        if (!reviewForm.status || !reviewForm.feedback.trim()) {
            alert("Silakan pilih status dan berikan feedback");
            return;
        }

        processing.value = true;

        useForm({
            status: reviewForm.status,
            feedback: reviewForm.feedback,
        }).put(route("admin.logbooks.update-status", logbook.id), {
            onSuccess: () => {
                reviewForm.feedback = "";
                reviewForm.status = "";
                alert("Status logbook berhasil diperbarui!");
            },
            onError: (errors) => {
                let errorMessage = "Terjadi kesalahan: ";
                if (errors.error) {
                    errorMessage += errors.error;
                } else if (errors.status) {
                    errorMessage += errors.status[0];
                } else if (errors.feedback) {
                    errorMessage += errors.feedback[0];
                } else {
                    errorMessage += Object.values(errors).join(", ");
                }

                alert(errorMessage);
            },
            onFinish: () => {
                processing.value = false;
            },
        });
    };

    const submitWithStatus = (status) => {
        reviewForm.status = status;
        submitReview();
    };

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    const formatDateTime = (date) => {
        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const getStatusClass = (status) => {
        switch (status) {
            case "draft":
                return "bg-gray-100 text-gray-800";
            case "submitted":
                return "bg-blue-100 text-blue-800";
            case "approved":
                return "bg-green-100 text-green-800";
            case "rejected":
                return "bg-red-100 text-red-800";
            case "revision":
                return "bg-yellow-100 text-yellow-800";
            default:
                return "bg-gray-100 text-gray-800";
        }
    };

    const getStatusText = (status) => {
        switch (status) {
            case "draft":
                return "Draft";
            case "submitted":
                return "Diajukan";
            case "approved":
                return "Disetujui";
            case "rejected":
                return "Ditolak";
            case "revision":
                return "Perlu Revisi";
            default:
                return "Tidak Diketahui";
        }
    };

    const getCommentTypeClass = (type) => {
        switch (type) {
            case "feedback":
                return "bg-blue-100 text-blue-800";
            case "revision_request":
                return "bg-yellow-100 text-yellow-800";
            default:
                return "bg-gray-100 text-gray-800";
        }
    };

    const getCommentTypeText = (type) => {
        switch (type) {
            case "feedback":
                return "Feedback";
            case "revision_request":
                return "Revisi";
            default:
                return "Komentar";
        }
    };

    return {
        processing,
        reviewForm,
        submitWithStatus,
        formatDate,
        formatDateTime,
        getStatusClass,
        getStatusText,
        getCommentTypeClass,
        getCommentTypeText,
    };
}
