import { computed, ref } from "vue";

export function usePesertaLogbookIndexPage(logbooks) {
    const searchQuery = ref("");
    const statusFilter = ref("");
    const monthFilter = ref("");

    const filteredLogbooks = computed(() => {
        return (logbooks || []).filter((logbook) => {
            const title = logbook.title || "";
            const activities = logbook.activities || "";

            const matchesSearch =
                !searchQuery.value ||
                title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                activities
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase());

            const matchesStatus =
                !statusFilter.value || logbook.status === statusFilter.value;

            const matchesMonth =
                !monthFilter.value ||
                (logbook.date && logbook.date.startsWith(monthFilter.value));

            return matchesSearch && matchesStatus && matchesMonth;
        });
    });

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

    return {
        searchQuery,
        statusFilter,
        monthFilter,
        filteredLogbooks,
        formatDate,
        getStatusClass,
        getStatusText,
    };
}
