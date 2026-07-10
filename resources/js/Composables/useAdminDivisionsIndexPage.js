import { router } from "@inertiajs/vue3";
import SwalPlugin from "@/Plugins/sweetalert";

export function useAdminDivisionsIndexPage() {
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
        SwalPlugin.confirmDestructive(
            "Hapus Divisi",
            `Apakah Anda yakin ingin menghapus divisi "${division?.name || 'N/A'}"? Seluruh data terkait akan ikut terhapus dan tindakan ini tidak dapat dibatalkan.`
        ).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/admin/divisions/${division.id}`);
            }
        });
    };

    return {
        showDeleteModal: false,
        divisionToDelete: null,
        formatDate,
        getProgressPercent,
        confirmDelete,
        closeDeleteModal: () => {},
        deleteDivision: () => {},
    };
}
