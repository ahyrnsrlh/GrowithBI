<template>
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Submit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="report in reportsData"
                        :key="report.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ report.title }}</div>
                                <div class="text-sm text-gray-500">{{ report.file_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ report.user?.name || "-" }}</div>
                            <div class="text-sm text-gray-500">{{ report.user?.email || "-" }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ report.application?.division?.name || "-" }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                :class="getStatusBadgeClass(report.status)"
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {{ getStatusLabel(report.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ formatDate(report.created_at) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <Link
                                    :href="route('admin.final-reports.show', report.id)"
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                >
                                    Detail
                                </Link>
                                <a
                                    :href="route('admin.final-reports.download', report.id)"
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

        <div v-if="reports.links" class="px-6 py-3 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan {{ reports.from }} - {{ reports.to }} dari {{ reports.total }} laporan
                </div>
                <div class="flex space-x-1">
                    <template v-for="(link, index) in reports.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-3 py-2 text-sm leading-4 border rounded hover:bg-gray-100"
                            :class="{
                                'bg-blue-500 text-white border-blue-500': link.active,
                                'text-gray-700 border-gray-300': !link.active,
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
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    reports: {
        type: Object,
        required: true,
    },
    getStatusLabel: {
        type: Function,
        required: true,
    },
    getStatusBadgeClass: {
        type: Function,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});

const reportsData = props.reports?.data || [];
</script>
