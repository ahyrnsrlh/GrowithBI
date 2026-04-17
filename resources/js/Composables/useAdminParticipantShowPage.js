import { computed } from "vue";
import { router } from "@inertiajs/vue3";

export function useAdminParticipantShowPage(participant, applications) {
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    const getStatusClass = (status) => {
        const classes = {
            menunggu: "bg-yellow-100 text-yellow-800",
            in_review: "bg-blue-100 text-blue-800",
            interview_scheduled: "bg-purple-100 text-purple-800",
            accepted: "bg-green-100 text-green-800",
            rejected: "bg-red-100 text-red-800",
            expired: "bg-gray-100 text-gray-800",
        };
        return classes[status] || "bg-gray-100 text-gray-800";
    };

    const getStatusText = (status) => {
        const texts = {
            menunggu: "Menunggu",
            in_review: "Dalam Review",
            interview_scheduled: "Wawancara",
            accepted: "Diterima",
            rejected: "Ditolak",
            expired: "Kedaluwarsa",
        };
        return texts[status] || status;
    };

    const acceptedApplication = computed(() => {
        return applications.find((app) =>
            ["accepted", "letter_ready", "diterima"].includes(app.status),
        );
    });

    const acceptedCount = computed(() => {
        return applications.filter((app) =>
            ["accepted", "letter_ready", "diterima"].includes(app.status),
        ).length;
    });

    const rejectedCount = computed(() => {
        return applications.filter((app) => app.status === "rejected").length;
    });

    const toggleStatus = () => {
        router.put(route("admin.participants.update-status", participant.id), {
            is_active: !participant.is_active,
        });
    };

    return {
        formatDate,
        getStatusClass,
        getStatusText,
        acceptedApplication,
        acceptedCount,
        rejectedCount,
        toggleStatus,
    };
}
