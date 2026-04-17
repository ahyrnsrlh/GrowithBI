import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";

export function usePesertaLogbookShowPage(logbook) {
    const commentForm = useForm({
        comment: "",
    });

    const canEdit = computed(() => {
        return ["draft", "revision"].includes(logbook.status);
    });

    const allComments = computed(() => {
        let comments = [...(logbook.comments || [])];

        if (
            logbook.mentor_feedback &&
            logbook.status !== "draft" &&
            logbook.status !== "submitted"
        ) {
            const mentorComment = {
                id: "mentor-feedback",
                comment: logbook.mentor_feedback,
                created_at: logbook.reviewed_at || logbook.updated_at,
                is_mentor_feedback: true,
                user: logbook.reviewer || {
                    id: "mentor",
                    name: "Pembimbing",
                    role: "mentor",
                },
            };

            comments.unshift(mentorComment);
        }

        return comments.sort(
            (first, second) =>
                new Date(second.created_at) - new Date(first.created_at),
        );
    });

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                weekday: "long",
                day: "numeric",
                month: "long",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        } catch {
            return "-";
        }
    };

    const formatDateShort = (dateString) => {
        if (!dateString) {
            return "-";
        }
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                day: "numeric",
                month: "short",
                year: "numeric",
            });
        } catch {
            return "-";
        }
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) {
            return "0 Bytes";
        }
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
    };

    const getStatusBadgeClass = (status) => {
        const classes = {
            draft: "bg-gray-100 text-gray-700 border border-gray-300",
            submitted: "bg-yellow-100 text-yellow-800 border border-yellow-300",
            approved: "bg-green-100 text-green-800 border border-green-300",
            revision: "bg-purple-100 text-purple-800 border border-purple-300",
            rejected: "bg-red-100 text-red-800 border border-red-300",
        };

        return (
            classes[status] ||
            "bg-gray-100 text-gray-700 border border-gray-300"
        );
    };

    const getStatusDotClass = (status) => {
        const classes = {
            draft: "bg-gray-500",
            submitted: "bg-yellow-500 animate-pulse",
            approved: "bg-green-500",
            revision: "bg-purple-500",
            rejected: "bg-red-500",
        };

        return classes[status] || "bg-gray-500";
    };

    const getStatusText = (status) => {
        const texts = {
            draft: "Draft",
            submitted: "Menunggu Review",
            approved: "Disetujui",
            revision: "Perlu Revisi",
            rejected: "Ditolak",
        };

        return texts[status] || "Unknown";
    };

    const getProgressPercentage = (status) => {
        const progress = {
            draft: 0,
            submitted: 50,
            approved: 100,
            revision: 25,
            rejected: 0,
        };

        return progress[status] || 0;
    };

    const addComment = () => {
        commentForm.post(route("peserta.logbooks.comments.store", logbook.id), {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset();
            },
        });
    };

    return {
        commentForm,
        canEdit,
        allComments,
        formatDate,
        formatDateShort,
        formatFileSize,
        getStatusBadgeClass,
        getStatusDotClass,
        getStatusText,
        getProgressPercentage,
        addComment,
    };
}
