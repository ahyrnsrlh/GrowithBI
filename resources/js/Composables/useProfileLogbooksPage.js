import { computed, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

export function useProfileLogbooksPage(logbooks, initialFilters = {}) {
    const showCreateModal = ref(false);
    const displayedCount = ref(9);

    const filters = ref({
        month: initialFilters.month || "",
        status: initialFilters.status || "",
    });

    const filteredLogbooks = computed(() => {
        if (!filters.value.status) {
            return logbooks || [];
        }

        return (logbooks || []).filter(
            (logbook) => logbook.status === filters.value.status,
        );
    });

    const displayedLogbooks = computed(() => {
        return filteredLogbooks.value.slice(0, displayedCount.value);
    });

    const hasMoreToShow = computed(() => {
        return displayedCount.value < filteredLogbooks.value.length;
    });

    const loadMore = () => {
        displayedCount.value += 6;
    };

    const setStatusFilter = (status) => {
        filters.value.status = status;
        displayedCount.value = 9;
    };

    const createForm = useForm({
        date: new Date().toISOString().split("T")[0],
        duration: 8,
        title: "",
        activities: "",
        learning_points: "",
        challenges: "",
        status: "submitted",
    });

    const submitLogbook = () => {
        createForm.post(route("profile.logbooks.store"), {
            preserveScroll: true,
            onSuccess: () => {
                showCreateModal.value = false;
                createForm.reset();
            },
        });
    };

    const formatDateShort = (date) => {
        const options = { day: "numeric", month: "long", year: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const getStatusClass = (status) => {
        const classes = {
            submitted: "bg-[#FFA726] text-white",
            approved: "bg-[#43A047] text-white",
            revision: "bg-[#AB47BC] text-white",
            draft: "bg-gray-500 text-white",
        };

        return classes[status] || "bg-gray-500 text-white";
    };

    const getStatusText = (status) => {
        const texts = {
            submitted: "Pending",
            approved: "Disetujui",
            revision: "Revisi",
            draft: "Draft",
        };

        return texts[status] || "Unknown";
    };

    return {
        showCreateModal,
        displayedCount,
        filters,
        filteredLogbooks,
        displayedLogbooks,
        hasMoreToShow,
        createForm,
        loadMore,
        setStatusFilter,
        submitLogbook,
        formatDateShort,
        getStatusClass,
        getStatusText,
    };
}
