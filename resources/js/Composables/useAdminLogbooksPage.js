import { computed, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useAdminLogbooksPage(logbooks) {
    const searchQuery = ref("");
    const statusFilter = ref("");
    const divisionFilter = ref("");
    const monthFilter = ref("");
    const selectedLogbooks = ref([]);

    const currentPage = ref(1);
    const itemsPerPage = ref(10);

    const filteredLogbooks = computed(() => {
        return (logbooks || []).filter((logbook) => {
            const userName = logbook?.user?.name?.toLowerCase() || "";
            const title = logbook?.title?.toLowerCase() || "";
            const activities = logbook?.activities?.toLowerCase() || "";

            const matchesSearch =
                !searchQuery.value ||
                userName.includes(searchQuery.value.toLowerCase()) ||
                title.includes(searchQuery.value.toLowerCase()) ||
                activities.includes(searchQuery.value.toLowerCase());

            const matchesStatus =
                !statusFilter.value || logbook.status === statusFilter.value;

            const matchesDivision =
                !divisionFilter.value ||
                (logbook?.user?.division &&
                    logbook.user.division.id ===
                        Number.parseInt(divisionFilter.value, 10));

            const matchesMonth =
                !monthFilter.value ||
                (logbook.date && logbook.date.startsWith(monthFilter.value));

            return (
                matchesSearch &&
                matchesStatus &&
                matchesDivision &&
                matchesMonth
            );
        });
    });

    const totalPages = computed(() => {
        return Math.ceil(filteredLogbooks.value.length / itemsPerPage.value);
    });

    const paginatedLogbooks = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        const end = start + itemsPerPage.value;
        return filteredLogbooks.value.slice(start, end);
    });

    const paginationRange = computed(() => {
        const range = [];
        const delta = 2;
        const left = currentPage.value - delta;
        const right = currentPage.value + delta;

        for (let i = 1; i <= totalPages.value; i += 1) {
            if (
                i === 1 ||
                i === totalPages.value ||
                (i >= left && i <= right)
            ) {
                range.push(i);
            } else if (range[range.length - 1] !== "...") {
                range.push("...");
            }
        }

        return range;
    });

    const isAllSelected = computed(() => {
        return (
            paginatedLogbooks.value.length > 0 &&
            selectedLogbooks.value.length === paginatedLogbooks.value.length
        );
    });

    watch([searchQuery, statusFilter, divisionFilter, monthFilter], () => {
        currentPage.value = 1;
        selectedLogbooks.value = [];
    });

    watch(filteredLogbooks, (items) => {
        if (items.length === 0) {
            currentPage.value = 1;
            return;
        }

        if (currentPage.value > totalPages.value) {
            currentPage.value = totalPages.value;
        }
    });

    const goToPage = (page) => {
        if (page >= 1 && page <= totalPages.value) {
            currentPage.value = page;
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    };

    const changeItemsPerPage = (value) => {
        itemsPerPage.value = Number(value);
        currentPage.value = 1;
    };

    const toggleSelectAll = () => {
        if (isAllSelected.value) {
            selectedLogbooks.value = [];
        } else {
            selectedLogbooks.value = paginatedLogbooks.value.map(
                (logbook) => logbook.id,
            );
        }
    };

    const resetFilters = () => {
        searchQuery.value = "";
        statusFilter.value = "";
        divisionFilter.value = "";
        monthFilter.value = "";
        selectedLogbooks.value = [];
        currentPage.value = 1;
    };

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }

        try {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                weekday: "short",
                day: "numeric",
                month: "short",
                year: "numeric",
            });
        } catch {
            return "-";
        }
    };

    const getStatusClass = (status) => {
        const classes = {
            draft: "bg-gray-100 text-gray-800",
            submitted: "bg-yellow-100 text-yellow-800",
            approved: "bg-green-100 text-green-800",
            revision: "bg-red-100 text-red-800",
            rejected: "bg-red-100 text-red-800",
        };

        return classes[status] || "bg-gray-100 text-gray-800";
    };

    const getStatusText = (status) => {
        const texts = {
            draft: "Draft",
            submitted: "Menunggu Review",
            approved: "Disetujui",
            revision: "Perlu Revisi",
            rejected: "Ditolak",
        };

        return texts[status] || "Unknown";
    };

    const quickApprove = (logbookId) => {
        router.put(
            route("admin.logbooks.approve", logbookId),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    selectedLogbooks.value = selectedLogbooks.value.filter(
                        (id) => id !== logbookId,
                    );
                },
            },
        );
    };

    const bulkAction = (action) => {
        if (selectedLogbooks.value.length === 0) {
            return;
        }

        router.post(
            route("admin.logbooks.bulk-update"),
            {
                logbook_ids: selectedLogbooks.value,
                action,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    selectedLogbooks.value = [];
                },
            },
        );
    };

    return {
        searchQuery,
        statusFilter,
        divisionFilter,
        monthFilter,
        selectedLogbooks,
        currentPage,
        itemsPerPage,
        filteredLogbooks,
        paginatedLogbooks,
        totalPages,
        paginationRange,
        isAllSelected,
        goToPage,
        changeItemsPerPage,
        toggleSelectAll,
        resetFilters,
        formatDate,
        getStatusClass,
        getStatusText,
        quickApprove,
        bulkAction,
    };
}
