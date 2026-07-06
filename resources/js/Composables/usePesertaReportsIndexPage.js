import { ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

export function usePesertaReportsIndexPage() {
    const showDetailReportModal = ref(false);
    const showEditReportModal = ref(false);
    const selectedReportId = ref(null);

    const showNotification = ref(false);
    const notificationType = ref("success");
    const notificationMessage = ref("");

    const showToast = (type, message) => {
        notificationType.value = type;
        notificationMessage.value = message;
        showNotification.value = true;
        setTimeout(() => {
            showNotification.value = false;
        }, 3000);
    };

    const page = usePage();
    watch(
        () => page.props.flash,
        (flash) => {
            if (flash?.success) {
                showToast("success", flash.success);
            }
            if (flash?.error) {
                showToast("error", flash.error);
            }
        },
        { deep: true, immediate: true }
    );

    const viewReportDetail = (id) => {
        selectedReportId.value = id;
        showDetailReportModal.value = true;
    };

    const editReport = (id) => {
        selectedReportId.value = id;
        showEditReportModal.value = true;
    };

    const deleteReport = (id) => {
        if (confirm("Apakah Anda yakin ingin menghapus laporan ini? File laporan juga akan dihapus dari server.")) {
            router.delete(route("peserta.reports.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    // Let Inertia handle page reload automatically
                },
                onError: (errors) => {
                    console.error("Delete report errors:", errors);
                }
            });
        }
    };

    const handleReportEditSuccess = () => {
        showEditReportModal.value = false;
        router.reload({ preserveScroll: true });
    };

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
        showDetailReportModal,
        showEditReportModal,
        selectedReportId,
        viewReportDetail,
        editReport,
        deleteReport,
        handleReportEditSuccess,
        showNotification,
        notificationType,
        notificationMessage,
        showToast,
        formatDate,
        getStatusClass,
        getStatusText,
        getCompletionRate,
        generateReport,
    };
}
