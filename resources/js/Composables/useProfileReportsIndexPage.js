import { router } from "@inertiajs/vue3";

export function useProfileReportsIndexPage() {
    const formatDate = (date) => {
        const options = {
            year: "numeric",
            month: "short",
            day: "numeric",
        };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    };

    const deleteReport = (reportId) => {
        if (confirm("Apakah Anda yakin ingin menghapus laporan ini?")) {
            router.delete(route("profile.reports.destroy", reportId));
        }
    };

    const getStatusClass = (status) => {
        const classes = {
            approved: "bg-green-100 text-green-800",
            submitted: "bg-yellow-100 text-yellow-800",
            revision: "bg-red-100 text-red-800",
            draft: "bg-gray-100 text-gray-800",
        };

        return classes[status] || "bg-gray-100 text-gray-800";
    };

    const getStatusText = (status) => {
        const texts = {
            approved: "Disetujui",
            submitted: "Menunggu Review",
            revision: "Perlu Revisi",
            draft: "Draft",
        };

        return texts[status] || "Draft";
    };

    return {
        formatDate,
        formatFileSize,
        deleteReport,
        getStatusClass,
        getStatusText,
    };
}
