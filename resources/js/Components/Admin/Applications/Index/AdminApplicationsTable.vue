<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input
                                type="checkbox"
                                @change="$emit('toggle-all')"
                                :checked="isAllSelected"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Pendaftar
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Divisi
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Tanggal Apply
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
                        v-for="application in applications.data"
                        :key="application.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            <input
                                type="checkbox"
                                :value="application.id"
                                v-model="selectedModel"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            />
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-white font-medium text-sm"
                                    >
                                        {{
                                            application.user?.name?.charAt(0) ||
                                            "N"
                                        }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ application.user?.name || "N/A" }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ application.user?.email || "N/A" }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ application.division.name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <StatusBadge
                                :status="application.status"
                                :statusInfo="application.status_info"
                            />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ formatDate(application.created_at) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/admin/applications/${application.id}`"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Detail
                                </Link>
                                <button
                                    @click="
                                        $emit('open-status-modal', application)
                                    "
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Update Status
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="applications.links && applications.links.length > 3"
            class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
        >
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <Link
                        v-if="applications.prev_page_url"
                        :href="applications.prev_page_url"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="applications.next_page_url"
                        :href="applications.next_page_url"
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
                            Showing {{ applications.from }} to
                            {{ applications.to }} of
                            {{ applications.total }} results
                        </p>
                    </div>
                    <div>
                        <nav
                            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                        >
                            <template
                                v-for="(link, index) in applications.links"
                                :key="index"
                            >
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    v-html="link.label"
                                    :class="[
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                        link.active
                                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                    ]"
                                />
                                <span
                                    v-else
                                    v-html="link.label"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500"
                                />
                            </template>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import StatusBadge from "@/Components/StatusBadge.vue";

const props = defineProps({
    applications: {
        type: Object,
        required: true,
    },
    selectedApplications: {
        type: Array,
        default: () => [],
    },
    formatDate: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits([
    "toggle-all",
    "update:selectedApplications",
    "open-status-modal",
]);

const selectedModel = computed({
    get: () => props.selectedApplications,
    set: (value) => emit("update:selectedApplications", value),
});

const isAllSelected = computed(() => {
    const total = props.applications.data?.length || 0;
    return total > 0 && props.selectedApplications.length === total;
});
</script>
