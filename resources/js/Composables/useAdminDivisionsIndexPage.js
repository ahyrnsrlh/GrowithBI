import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export function useAdminDivisionsIndexPage() {
    const showDeleteModal = ref(false);
    const divisionToDelete = ref(null);

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }

        const date = new Date(dateString);

        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    };

    const getProgressPercent = (division) => {
        if (!division?.quota) {
            return 0;
        }

        return Math.min((division.accepted_count / division.quota) * 100, 100);
    };

    const confirmDelete = (division) => {
        divisionToDelete.value = division;
        showDeleteModal.value = true;
    };

    const closeDeleteModal = () => {
        showDeleteModal.value = false;
        divisionToDelete.value = null;
    };

    const deleteDivision = () => {
        if (!divisionToDelete.value?.id) {
            return;
        }

        router.delete(`/admin/divisions/${divisionToDelete.value.id}`, {
            onSuccess: closeDeleteModal,
        });
    };

    return {
        showDeleteModal,
        divisionToDelete,
        formatDate,
        getProgressPercent,
        confirmDelete,
        closeDeleteModal,
        deleteDivision,
    };
}
