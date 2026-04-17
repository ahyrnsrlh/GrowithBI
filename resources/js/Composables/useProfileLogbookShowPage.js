export function useProfileLogbookShowPage() {
    const formatDate = (date) => {
        if (!date) return "-";
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const formatDateShort = (date) => {
        if (!date) return "-";
        const options = { day: "numeric", month: "short", year: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const getStatusText = (status) => {
        const statusMap = {
            draft: "Draft",
            submitted: "Menunggu Review",
            approved: "Disetujui",
            revision: "Perlu Revisi",
        };
        return statusMap[status] || status;
    };

    const getStatusBadgeClass = (status) => {
        const classes = {
            draft: "bg-gray-100 text-gray-700 border border-gray-300",
            submitted: "bg-yellow-100 text-yellow-800 border border-yellow-300",
            approved: "bg-green-100 text-green-800 border border-green-300",
            revision: "bg-purple-100 text-purple-800 border border-purple-300",
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
        };
        return classes[status] || "bg-gray-500";
    };

    const getProgressPercentage = (status) => {
        const progress = {
            draft: 0,
            submitted: 50,
            approved: 100,
            revision: 25,
        };
        return progress[status] || 0;
    };

    return {
        formatDate,
        formatDateShort,
        getStatusText,
        getStatusBadgeClass,
        getStatusDotClass,
        getProgressPercentage,
    };
}
