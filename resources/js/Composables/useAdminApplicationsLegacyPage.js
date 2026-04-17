import { ref } from "vue";
import { router } from "@inertiajs/vue3";

export function useAdminApplicationsLegacyPage() {
    const filters = ref({
        status: "",
        division_id: "",
        search: "",
    });

    const selectedApplication = ref(null);

    const getStatusBadgeClass = (status) => {
        const classes = {
            pending: "bg-yellow-100 text-yellow-800",
            approved: "bg-green-100 text-green-800",
            rejected: "bg-red-100 text-red-800",
        };

        return classes[status] || "bg-gray-100 text-gray-800";
    };

    const getStatusLabel = (status) => {
        const labels = {
            pending: "Menunggu Review",
            approved: "Diterima",
            rejected: "Ditolak",
        };

        return labels[status] || status;
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    };

    const applyFilters = () => {
        router.get(route("admin.applications"), filters.value, {
            preserveState: true,
            replace: true,
        });
    };

    const resetFilters = () => {
        filters.value = {
            status: "",
            division_id: "",
            search: "",
        };
        applyFilters();
    };

    const viewApplication = (application) => {
        selectedApplication.value = application;
    };

    const closeModal = () => {
        selectedApplication.value = null;
    };

    const approveApplication = (application) => {
        router.patch(
            route("admin.applications.approve", application.id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            },
        );
    };

    const rejectApplication = (application) => {
        router.patch(
            route("admin.applications.reject", application.id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            },
        );
    };

    return {
        filters,
        selectedApplication,
        getStatusBadgeClass,
        getStatusLabel,
        formatDate,
        applyFilters,
        resetFilters,
        viewApplication,
        closeModal,
        approveApplication,
        rejectApplication,
    };
}
