<template>
    <Head title="Kelola Aplikasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Aplikasi Magang
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <DocumentTextIcon
                                    class="h-6 w-6 text-blue-600"
                                />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Total Aplikasi
                                </p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ stats.total }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <ClockIcon class="h-6 w-6 text-yellow-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Menunggu Review
                                </p>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{ stats.pending }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <CheckCircleIcon
                                    class="h-6 w-6 text-green-600"
                                />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Diterima
                                </p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ stats.approved }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full">
                                <XCircleIcon class="h-6 w-6 text-red-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">
                                    Ditolak
                                </p>
                                <p class="text-2xl font-bold text-red-600">
                                    {{ stats.rejected }}
                                </p>
                            </div>
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
                                    >Filter Status</label
                                >
                                <select
                                    v-model="filters.status"
                                    @change="applyFilters"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="pending">
                                        Menunggu Review
                                    </option>
                                    <option value="approved">Diterima</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Filter Divisi</label
                                >
                                <select
                                    v-model="filters.division_id"
                                    @change="applyFilters"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Semua Divisi</option>
                                    <option
                                        v-for="division in divisions"
                                        :key="division.id"
                                        :value="division.id"
                                    >
                                        {{ division.name }}
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

                <!-- Applications Table -->
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
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ application.email }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
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
                                            Semester
                                            {{ application.semester }} (IPK:
                                            {{ application.gpa }})
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ application.division?.name }}
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        <div>
                                            {{
                                                formatDate(
                                                    application.start_date
                                                )
                                            }}
                                        </div>
                                        <div>
                                            {{
                                                formatDate(application.end_date)
                                            }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="
                                                getStatusBadgeClass(
                                                    application.status
                                                )
                                            "
                                        >
                                            {{
                                                getStatusLabel(
                                                    application.status
                                                )
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <div class="flex space-x-2">
                                            <button
                                                @click="
                                                    viewApplication(application)
                                                "
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Detail
                                            </button>
                                            <button
                                                v-if="
                                                    application.status ===
                                                    'pending'
                                                "
                                                @click="
                                                    approveApplication(
                                                        application
                                                    )
                                                "
                                                class="text-green-600 hover:text-green-900"
                                            >
                                                Terima
                                            </button>
                                            <button
                                                v-if="
                                                    application.status ===
                                                    'pending'
                                                "
                                                @click="
                                                    rejectApplication(
                                                        application
                                                    )
                                                "
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

                    <!-- Pagination -->
                    <div
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
            </div>
        </div>

        <!-- Application Detail Modal -->
        <div
            v-if="selectedApplication"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click="closeModal"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                @click.stop
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Detail Aplikasi Magang
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <XMarkIcon class="h-6 w-6" />
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nama Lengkap</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedApplication.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Email</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedApplication.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Telepon</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedApplication.phone }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Universitas</label
                                >
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ selectedApplication.university }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Alamat</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.address }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Motivasi</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.motivation }}
                            </p>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button
                                v-if="selectedApplication.status === 'pending'"
                                @click="approveApplication(selectedApplication)"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                            >
                                Terima Aplikasi
                            </button>
                            <button
                                v-if="selectedApplication.status === 'pending'"
                                @click="rejectApplication(selectedApplication)"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                            >
                                Tolak Aplikasi
                            </button>
                            <button
                                @click="closeModal"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors"
                            >
                                Tutup
                            </button>
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
    DocumentTextIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { ref, computed } from "vue";

const props = defineProps({
    applications: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            approved: 0,
            rejected: 0,
        }),
    },
});

const filters = ref({
    status: "",
    division_id: "",
    search: "",
});

const selectedApplication = ref(null);

const getStatusBadgeClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        approved: "bg-green-100 text-green-800",
        rejected: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusLabel = (status) => {
    const labels = {
        pending: "Menunggu Review",
        approved: "Diterima",
        rejected: "Ditolak",
    };
    return labels[status] || status;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const applyFilters = () => {
    router.get(route("admin.applications"), filters.value, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filters.value = {
        status: "",
        division_id: "",
        search: "",
    };
    applyFilters();
};

const viewApplication = (application) => {
    selectedApplication.value = application;
};

const closeModal = () => {
    selectedApplication.value = null;
};

const approveApplication = (application) => {
    router.patch(
        route("admin.applications.approve", application.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        }
    );
};

const rejectApplication = (application) => {
    router.patch(
        route("admin.applications.reject", application.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        }
    );
};
</script>
