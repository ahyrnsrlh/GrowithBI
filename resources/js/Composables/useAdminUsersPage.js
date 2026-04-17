import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

export function useAdminUsersPage(usersRef) {
    const filters = ref({
        role: "",
        status: "",
        search: "",
    });

    const showCreateModal = ref(false);

    const getRoleBadgeClass = (role) => {
        const classes = {
            admin: "bg-purple-100 text-purple-800",
            peserta: "bg-green-100 text-green-800",
        };

        return classes[role] || "bg-gray-100 text-gray-800";
    };

    const getRoleLabel = (role) => {
        const labels = {
            admin: "Admin",
            peserta: "Peserta",
        };

        return labels[role] || role;
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    };

    const pageNumbers = computed(() => {
        const current = usersRef.value.current_page;
        const last = usersRef.value.last_page;
        const delta = 2;
        const range = [];
        const rangeWithDots = [];

        for (
            let i = Math.max(2, current - delta);
            i <= Math.min(last - 1, current + delta);
            i++
        ) {
            range.push(i);
        }

        if (current - delta > 2) {
            rangeWithDots.push(1, "...");
        } else {
            rangeWithDots.push(1);
        }

        rangeWithDots.push(...range);

        if (current + delta < last - 1) {
            rangeWithDots.push("...", last);
        } else if (last > 1) {
            rangeWithDots.push(last);
        }

        return rangeWithDots;
    });

    const getPageUrl = (page) => {
        const url = new URL(window.location);
        url.searchParams.set("page", page);
        return url.toString();
    };

    const applyFilters = () => {
        router.get(route("admin.users"), filters.value, {
            preserveState: true,
            replace: true,
        });
    };

    const resetFilters = () => {
        filters.value = {
            role: "",
            status: "",
            search: "",
        };
        applyFilters();
    };

    const openCreateModal = () => {
        showCreateModal.value = true;
    };

    const editUser = (user) => {
        console.log("Edit user:", user);
    };

    const toggleUserStatus = (user) => {
        router.patch(
            route("admin.users.toggle-status", user.id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    return {
        filters,
        showCreateModal,
        getRoleBadgeClass,
        getRoleLabel,
        formatDate,
        pageNumbers,
        getPageUrl,
        applyFilters,
        resetFilters,
        openCreateModal,
        editUser,
        toggleUserStatus,
    };
}
