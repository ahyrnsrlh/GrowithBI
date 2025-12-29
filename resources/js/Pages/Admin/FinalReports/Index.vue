<template>
    <AdminLayout>
        <Head title="Manajemen Laporan Akhir" />

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Manajemen Laporan Akhir
            </h1>
            <p class="text-gray-600">
                Kelola dan review laporan akhir peserta magang
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Laporan
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats?.total || 0 }}
                        </p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            Menunggu Review
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats?.submitted || 0 }}
                        </p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Disetujui
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats?.approved || 0 }}
                        </p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">
                            Perlu Revisi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ stats?.revision || 0 }}
                        </p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Cari Laporan</label
                    >
                    <input
                        v-model="searchForm.search"
                        type="text"
                        placeholder="Cari berdasarkan judul atau nama peserta..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div class="min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Status</label
                    >
                    <select
                        v-model="searchForm.status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="submitted">Menunggu Review</option>
                        <option value="approved">Disetujui</option>
                        <option value="revision">Perlu Revisi</option>
                    </select>
                </div>

                <div class="min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Divisi</label
                    >
                    <select
                        v-model="searchForm.division"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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

                <div class="flex items-end">
                    <button
                        @click="applyFilters"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Filter
                    </button>
                </div>

                <div class="flex items-end">
                    <button
                        @click="resetFilters"
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Reset
                    </button>
                </div>

                <div class="flex items-end">
                    <button
                        @click="exportReports"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Export CSV
                    </button>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Laporan
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Peserta
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
                                Tanggal Submit
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
                            v-for="report in reports?.data || []"
                            :key="report.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                <div>
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ report.title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ report.file_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ report.user?.name || "-" }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ report.user?.email || "-" }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{
                                        report.application?.division?.name ||
                                        "-"
                                    }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="{
                                        'bg-yellow-100 text-yellow-800':
                                            report.status === 'submitted',
                                        'bg-green-100 text-green-800':
                                            report.status === 'approved',
                                        'bg-red-100 text-red-800':
                                            report.status === 'revision',
                                    }"
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ getStatusLabel(report.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ formatDate(report.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <Link
                                        :href="
                                            route(
                                                'admin.final-reports.show',
                                                report.id
                                            )
                                        "
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                    >
                                        Detail
                                    </Link>
                                    <a
                                        :href="
                                            route(
                                                'admin.final-reports.download',
                                                report.id
                                            )
                                        "
                                        class="text-green-600 hover:text-green-900 text-sm font-medium"
                                    >
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="reports.links"
                class="px-6 py-3 border-t border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ reports.from }} - {{ reports.to }} dari
                        {{ reports.total }} laporan
                    </div>
                    <div class="flex space-x-1">
                        <template
                            v-for="(link, index) in reports.links"
                            :key="index"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-2 text-sm leading-4 border rounded hover:bg-gray-100"
                                :class="{
                                    'bg-blue-500 text-white border-blue-500':
                                        link.active,
                                    'text-gray-700 border-gray-300':
                                        !link.active,
                                }"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="!reports?.data || reports.data.length === 0"
            class="text-center py-12"
        >
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                Tidak ada laporan
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Belum ada laporan akhir yang disubmit oleh peserta.
            </p>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    reports: Object,
    divisions: Array,
    stats: Object,
    filters: Object,
});

const searchForm = reactive({
    search: props.filters.search || "",
    status: props.filters.status || "",
    division: props.filters.division || "",
});

const applyFilters = () => {
    router.get(route("admin.final-reports.index"), searchForm, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    searchForm.search = "";
    searchForm.status = "";
    searchForm.division = "";
    applyFilters();
};

const exportReports = () => {
    const params = new URLSearchParams();
    if (searchForm.status) params.append("status", searchForm.status);
    if (searchForm.division) params.append("division", searchForm.division);

    window.open(
        `${route("admin.final-reports.export.all")}?${params.toString()}`,
        "_blank"
    );
};

const getStatusLabel = (status) => {
    switch (status) {
        case "submitted":
            return "Menunggu Review";
        case "approved":
            return "Disetujui";
        case "revision":
            return "Perlu Revisi";
        default:
            return status;
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
