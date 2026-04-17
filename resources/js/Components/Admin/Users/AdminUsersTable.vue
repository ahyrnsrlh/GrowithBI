<script setup>
import { Link } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/outline";

defineProps({
    users: {
        type: Object,
        required: true,
    },
    getRoleBadgeClass: {
        type: Function,
        required: true,
    },
    getRoleLabel: {
        type: Function,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    pageNumbers: {
        type: Array,
        required: true,
    },
    getPageUrl: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["edit-user", "toggle-user-status"]);
</script>

<template>
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
                                <div class="flex-shrink-0 h-10 w-10">
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
                                    <div class="text-sm text-gray-500">
                                        {{ user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="getRoleBadgeClass(user.role)"
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
                                {{ user.is_active ? "Aktif" : "Tidak Aktif" }}
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
                                @click="emit('edit-user', user)"
                                class="text-blue-600 hover:text-blue-900"
                            >
                                Edit
                            </button>
                            <button
                                @click="emit('toggle-user-status', user)"
                                :class="
                                    user.is_active
                                        ? 'text-red-600 hover:text-red-900'
                                        : 'text-green-600 hover:text-green-900'
                                "
                            >
                                {{
                                    user.is_active ? "Nonaktifkan" : "Aktifkan"
                                }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
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
                            <span class="font-medium">{{ users.from }}</span>
                            sampai
                            <span class="font-medium">{{ users.to }}</span> dari
                            <span class="font-medium">{{ users.total }}</span>
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
                            <span v-for="page in pageNumbers" :key="page">
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
</template>
