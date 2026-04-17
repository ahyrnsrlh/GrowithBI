export function usePesertaReportsShowPage() {
    const formatDate = (date) => {
        if (!date) {
            return "-";
        }

        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const getStatusClass = (status) => {
        const classes = {
            draft: "bg-gray-100 text-gray-800",
            submitted: "bg-yellow-100 text-yellow-800",
            approved: "bg-green-100 text-green-800",
            revision: "bg-red-100 text-red-800",
        };

        return classes[status] || "bg-gray-100 text-gray-800";
    };

    const getStatusText = (status) => {
        const texts = {
            draft: "Draft",
            submitted: "Menunggu Review",
            approved: "Disetujui",
            revision: "Perlu Revisi",
        };

        return texts[status] || "Unknown";
    };

    return {
        formatDate,
        getStatusClass,
        getStatusText,
    };
}
