export function usePesertaReportsIndexPage() {
    const formatDate = (date) => {
        if (!date) {
            return "-";
        }

        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
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

    const getCompletionRate = (stats = {}) => {
        const approved = Number(stats.approved_logbooks || 0);
        const total = Math.max(Number(stats.total_logbooks || 0), 1);

        return Math.round((approved / total) * 100);
    };

    const generateReport = () => {
        // Placeholder until backend report generation endpoint is available.
        alert("Fitur generate laporan akan segera tersedia!");
    };

    return {
        formatDate,
        getStatusClass,
        getStatusText,
        getCompletionRate,
        generateReport,
    };
}
