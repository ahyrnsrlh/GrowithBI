import { computed, onUnmounted, ref } from "vue";
import { router } from "@inertiajs/vue3";

const routeHelper = (() => {
    try {
        return window.route || (() => "#");
    } catch (error) {
        console.warn("Route helper not available:", error);
        return () => "#";
    }
})();

export function useAdminParticipantsPage(props) {
    const safeParticipants = computed(() => {
        try {
            return props.participants?.data || [];
        } catch (error) {
            console.warn("Error accessing participants data:", error);
            return [];
        }
    });

    const safeStats = computed(() => {
        try {
            return {
                total_participants: props.stats?.total_participants || 0,
                active_participants: props.stats?.active_participants || 0,
                total_applications: props.stats?.total_applications || 0,
                completed_participants:
                    props.stats?.completed_participants || 0,
            };
        } catch (error) {
            console.warn("Error accessing stats data:", error);
            return {
                total_participants: 0,
                active_participants: 0,
                total_applications: 0,
                completed_participants: 0,
            };
        }
    });

    const safeDivisions = computed(() => {
        try {
            return Array.isArray(props.divisions) ? props.divisions : [];
        } catch (error) {
            console.warn("Error accessing divisions data:", error);
            return [];
        }
    });

    const safeFilters = computed(() => {
        try {
            return {
                search: props.filters?.search || "",
                status: props.filters?.status || "",
                division: props.filters?.division || "",
            };
        } catch (error) {
            console.warn("Error accessing filters data:", error);
            return { search: "", status: "", division: "" };
        }
    });

    const searchQuery = ref(safeFilters.value.search || "");
    const statusFilter = ref(safeFilters.value.status || "");
    const divisionFilter = ref(safeFilters.value.division || "");

    let searchTimeout = null;

    onUnmounted(() => {
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
    });

    const safeRoute = (name, params = null) => {
        if (!routeHelper || typeof routeHelper !== "function") {
            console.warn("Route helper not available");
            return "#";
        }

        try {
            return routeHelper(name, params);
        } catch (error) {
            console.warn("Route generation failed:", error);
            return "#";
        }
    };

    const search = () => {
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        searchTimeout = setTimeout(() => {
            router.get(
                safeRoute("admin.participants.index"),
                {
                    search: searchQuery.value,
                    status: statusFilter.value,
                    division: divisionFilter.value,
                },
                {
                    preserveState: true,
                    replace: true,
                },
            );
        }, 300);
    };

    const filter = () => {
        router.get(
            safeRoute("admin.participants.index"),
            {
                search: searchQuery.value,
                status: statusFilter.value,
                division: divisionFilter.value,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const formatDate = (date) => {
        if (!date) {
            return "-";
        }

        try {
            return new Date(date).toLocaleDateString("id-ID", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        } catch {
            return "-";
        }
    };

    const getAcceptedApplication = (participant) => {
        if (
            !participant?.applications ||
            !Array.isArray(participant.applications)
        ) {
            return null;
        }

        return participant.applications.find((app) =>
            ["accepted", "letter_ready", "diterima"].includes(app?.status),
        );
    };

    const getLastApplicationStatusClass = (participant) => {
        if (
            !participant?.applications ||
            !Array.isArray(participant.applications) ||
            participant.applications.length === 0
        ) {
            return "bg-gray-100 text-gray-800";
        }

        const lastApp = participant.applications[0];
        if (!lastApp?.status) {
            return "bg-gray-100 text-gray-800";
        }

        const classes = {
            menunggu: "bg-yellow-100 text-yellow-800",
            in_review: "bg-blue-100 text-blue-800",
            interview_scheduled: "bg-purple-100 text-purple-800",
            accepted: "bg-green-100 text-green-800",
            rejected: "bg-red-100 text-red-800",
            expired: "bg-gray-100 text-gray-800",
        };

        return classes[lastApp.status] || "bg-gray-100 text-gray-800";
    };

    const getLastApplicationStatusText = (participant) => {
        if (
            !participant?.applications ||
            !Array.isArray(participant.applications) ||
            participant.applications.length === 0
        ) {
            return "Belum mendaftar";
        }

        const lastApp = participant.applications[0];
        if (!lastApp?.status) {
            return "Belum mendaftar";
        }

        const texts = {
            menunggu: "Menunggu",
            in_review: "Dalam Review",
            interview_scheduled: "Wawancara",
            accepted: "Diterima",
            rejected: "Ditolak",
            expired: "Kedaluwarsa",
        };

        return texts[lastApp.status] || lastApp.status;
    };

    const toggleStatus = (participant) => {
        if (!participant?.id) {
            return;
        }

        router.put(
            safeRoute("admin.participants.update-status", participant.id),
            {
                is_active: !participant.is_active,
            },
            {
                preserveState: true,
            },
        );
    };

    const exportData = () => {
        alert("Fitur export akan segera ditambahkan!");
    };

    return {
        safeParticipants,
        safeStats,
        safeDivisions,
        searchQuery,
        statusFilter,
        divisionFilter,
        search,
        filter,
        formatDate,
        getAcceptedApplication,
        getLastApplicationStatusClass,
        getLastApplicationStatusText,
        toggleStatus,
        exportData,
        safeRoute,
    };
}
