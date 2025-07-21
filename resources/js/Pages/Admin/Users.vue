<template>
    <Head title="Kelola Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Pengguna
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Daftar Pengguna
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Kelola semua pengguna dalam sistem
                                </p>
                            </div>
                            <button
                                @click="showCreateModal = true"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                            >
                                <PlusIcon class="h-5 w-5 mr-2" />
                                Tambah Pengguna
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Filter Role</label
                                >
                                <select
                                    v-model="filters.role"
                                    @change="applyFilters"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="peserta">Peserta</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Filter Status</label
                                >
                                <select
                                    v-model="filters.status"
                                    @change="applyFilters"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">
                                        Tidak Aktif
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Cari</label
                                >
                                <input
                                    v-model="filters.search"
                                    @input="applyFilters"
                                    type="text"
                                    placeholder="Nama atau email..."
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="resetFilters"
                                    class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors"
                                >
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Pengguna
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Role
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Bergabung
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="user in users.data"
                                    :key="user.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10"
                                            >
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center"
                                                >
                                                    <span
                                                        class="text-white font-medium text-sm"
                                                    >
                                                        {{
                                                            user.name
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ user.name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ user.email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="
                                                getRoleBadgeClass(user.role)
                                            "
                                        >
                                            {{ getRoleLabel(user.role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="
                                                user.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            "
                                        >
                                            {{
                                                user.is_active
                                                    ? "Aktif"
                                                    : "Tidak Aktif"
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ formatDate(user.created_at) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2"
                                    >
                                        <button
                                            @click="editUser(user)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="toggleUserStatus(user)"
                                            :class="
                                                user.is_active
                                                    ? 'text-red-600 hover:text-red-900'
                                                    : 'text-green-600 hover:text-green-900'
                                            "
                                        >
                                            {{
                                                user.is_active
                                                    ? "Nonaktifkan"
                                                    : "Aktifkan"
                                            }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link
                                    v-if="users.prev_page_url"
                                    :href="users.prev_page_url"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Previous
                                </Link>
                                <Link
                                    v-if="users.next_page_url"
                                    :href="users.next_page_url"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Next
                                </Link>
                            </div>
                            <div
                                class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                            >
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Menampilkan
                                        <span class="font-medium">{{
                                            users.from
                                        }}</span>
                                        sampai
                                        <span class="font-medium">{{
                                            users.to
                                        }}</span>
                                        dari
                                        <span class="font-medium">{{
                                            users.total
                                        }}</span>
                                        hasil
                                    </p>
                                </div>
                                <div v-if="users.last_page > 1">
                                    <nav
                                        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    >
                                        <Link
                                            v-if="users.prev_page_url"
                                            :href="users.prev_page_url"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                        >
                                            <ChevronLeftIcon class="h-5 w-5" />
                                        </Link>
                                        <span
                                            v-for="page in pageNumbers"
                                            :key="page"
                                        >
                                            <Link
                                                v-if="page !== '...'"
                                                :href="getPageUrl(page)"
                                                :class="
                                                    page === users.current_page
                                                        ? 'bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                                "
                                            >
                                                {{ page }}
                                            </Link>
                                            <span
                                                v-else
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                            >
                                                ...
                                            </span>
                                        </span>
                                        <Link
                                            v-if="users.next_page_url"
                                            :href="users.next_page_url"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                        >
                                            <ChevronRightIcon class="h-5 w-5" />
                                        </Link>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    PlusIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { ref, computed } from "vue";

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

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
    const current = props.users.current_page;
    const last = props.users.last_page;
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

const editUser = (user) => {
    // Implement edit user functionality
    console.log("Edit user:", user);
};

const toggleUserStatus = (user) => {
    router.patch(
        route("admin.users.toggle-status", user.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Handle success
            },
        }
    );
};
</script>
