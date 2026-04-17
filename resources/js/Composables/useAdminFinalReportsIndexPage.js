import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

export function useAdminFinalReportsIndexPage(filters = {}) {
    const searchForm = reactive({
        search: filters.search || "",
        status: filters.status || "",
        division: filters.division || "",
    });

    const applyFilters = () => {
        router.get(route("admin.final-reports.index"), searchForm, {
            preserveState: true,
            replace: true,
        });
    };

    const resetFilters = () => {
        searchForm.search = "";
        searchForm.status = "";
        searchForm.division = "";
        applyFilters();
    };

    const exportReports = () => {
        const params = new URLSearchParams();

        if (searchForm.status) {
            params.append("status", searchForm.status);
        }

        if (searchForm.division) {
            params.append("division", searchForm.division);
        }

        window.open(
            `${route("admin.final-reports.export.all")}?${params.toString()}`,
            "_blank",
        );
    };

    const getStatusLabel = (status) => {
        switch (status) {
            case "submitted":
                return "Menunggu Review";
            case "approved":
                return "Disetujui";
            case "revision":
                return "Perlu Revisi";
            default:
                return status;
        }
    };

    const getStatusBadgeClass = (status) => {
        switch (status) {
            case "submitted":
                return "bg-yellow-100 text-yellow-800";
            case "approved":
                return "bg-green-100 text-green-800";
            case "revision":
                return "bg-red-100 text-red-800";
            default:
                return "bg-gray-100 text-gray-800";
        }
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    return {
        searchForm,
        applyFilters,
        resetFilters,
        exportReports,
        getStatusLabel,
        getStatusBadgeClass,
        formatDate,
    };
}
