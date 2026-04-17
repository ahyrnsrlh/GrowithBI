import { onMounted, onUnmounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";

export function useAdminApplicationsPage(props) {
    const searchQuery = ref(props.filters?.search || "");
    const selectedStatus = ref(props.filters?.status || "");
    const selectedDivision = ref(props.filters?.division || "");
    const selectedApplications = ref([]);
    const showStatusModal = ref(false);
    const showBulkModal = ref(false);
    const currentApplication = ref(null);

    const statusForm = reactive({
        status: "",
        admin_notes: "",
        rejection_reason: "",
        interview_date: "",
        interview_location: "",
    });

    const bulkForm = reactive({
        status: "",
        admin_notes: "",
    });

    let echoChannel = null;

    onMounted(() => {
        if (window.Echo) {
            echoChannel = window.Echo.private("admin.applications")
                .listen(".application.status.changed", () => {
                    router.reload({ only: ["applications", "stats"] });
                })
                .listen(".application.submitted", () => {
                    router.reload({ only: ["applications", "stats"] });
                });
        }
    });

    onUnmounted(() => {
        if (echoChannel) {
            echoChannel.stopListening(".application.status.changed");
            echoChannel.stopListening(".application.submitted");
        }
    });

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const applyFilters = () => {
        router.get(
            "/admin/applications",
            {
                search: searchQuery.value,
                status: selectedStatus.value,
                division: selectedDivision.value,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const search = () => {
        applyFilters();
    };

    const filterByStatus = () => {
        applyFilters();
    };

    const filterByDivision = () => {
        applyFilters();
    };

    const toggleAllApplications = () => {
        if (
            selectedApplications.value.length ===
            (props.applications?.data || []).length
        ) {
            selectedApplications.value = [];
        } else {
            selectedApplications.value = (props.applications?.data || []).map(
                (app) => app.id,
            );
        }
    };

    const openStatusModal = (application) => {
        currentApplication.value = application;
        statusForm.status = application.status;
        statusForm.admin_notes = application.admin_notes || "";
        statusForm.interview_date = application.interview_date || "";
        statusForm.interview_location = application.interview_location || "";
        statusForm.rejection_reason = application.rejection_reason || "";
        showStatusModal.value = true;
    };

    const closeStatusModal = () => {
        showStatusModal.value = false;
        statusForm.status = "";
        statusForm.admin_notes = "";
        statusForm.interview_date = "";
        statusForm.interview_location = "";
        statusForm.rejection_reason = "";
        currentApplication.value = null;
    };

    const updateStatus = () => {
        const formData = {
            status: statusForm.status,
            admin_notes: statusForm.admin_notes,
        };

        if (statusForm.status === "interview_scheduled") {
            formData.interview_date = statusForm.interview_date;
            formData.interview_location = statusForm.interview_location;
        }

        if (statusForm.status === "rejected") {
            formData.rejection_reason = statusForm.rejection_reason;
        }

        router.put(
            `/admin/applications/${currentApplication.value.id}`,
            formData,
            {
                onSuccess: () => {
                    closeStatusModal();
                },
                onError: (errors) => {
                    console.error("Status update failed:", errors);
                },
            },
        );
    };

    const bulkUpdateStatus = () => {
        router.post(
            "/admin/applications/bulk-update",
            {
                application_ids: selectedApplications.value,
                ...bulkForm,
            },
            {
                onSuccess: () => {
                    showBulkModal.value = false;
                    selectedApplications.value = [];
                    bulkForm.status = "";
                    bulkForm.admin_notes = "";
                },
            },
        );
    };

    return {
        searchQuery,
        selectedStatus,
        selectedDivision,
        selectedApplications,
        showStatusModal,
        showBulkModal,
        statusForm,
        bulkForm,
        formatDate,
        search,
        filterByStatus,
        filterByDivision,
        toggleAllApplications,
        openStatusModal,
        closeStatusModal,
        updateStatus,
        bulkUpdateStatus,
    };
}
