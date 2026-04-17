<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Pelamar
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Universitas
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Divisi
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Periode
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
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
                                                application.name
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
                                        {{ application.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ application.email }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ application.phone }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ application.university }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ application.major }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Semester {{ application.semester }} (IPK:
                                {{ application.gpa }})
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ application.division?.name }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            <div>{{ formatDate(application.start_date) }}</div>
                            <div>{{ formatDate(application.end_date) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="getStatusBadgeClass(application.status)"
                            >
                                {{ getStatusLabel(application.status) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <div class="flex space-x-2">
                                <button
                                    @click="$emit('view', application)"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Detail
                                </button>
                                <button
                                    v-if="application.status === 'pending'"
                                    @click="$emit('approve', application)"
                                    class="text-green-600 hover:text-green-900"
                                >
                                    Terima
                                </button>
                                <button
                                    v-if="application.status === 'pending'"
                                    @click="$emit('reject', application)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
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
                            Menampilkan
                            <span class="font-medium">{{
                                applications.from
                            }}</span>
                            sampai
                            <span class="font-medium">{{
                                applications.to
                            }}</span>
                            dari
                            <span class="font-medium">{{
                                applications.total
                            }}</span>
                            hasil
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    applications: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getStatusBadgeClass: {
        type: Function,
        required: true,
    },
    getStatusLabel: {
        type: Function,
        required: true,
    },
});

defineEmits(["view", "approve", "reject"]);
</script>
