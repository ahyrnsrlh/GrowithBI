<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getProgressPercent: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["confirm-delete"]);
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
    >
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ division.name }}
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-2">
                        {{ division.description }}
                    </p>
                </div>
                <div class="ml-4">
                    <span
                        :class="[
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                            division.is_active
                                ? 'bg-green-100 text-green-800'
                                : 'bg-gray-100 text-gray-800',
                        ]"
                    >
                        {{ division.is_active ? "Aktif" : "Nonaktif" }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">
                        {{ division.applications_count }}
                    </p>
                    <p class="text-xs text-gray-500">Pendaftar</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">
                        {{ division.accepted_count }}
                    </p>
                    <p class="text-xs text-gray-500">Diterima</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-600">
                        {{ division.quota }}
                    </p>
                    <p class="text-xs text-gray-500">Kuota</p>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ Math.round(getProgressPercent(division)) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div
                        class="bg-blue-600 h-2 rounded-full"
                        :style="`width: ${getProgressPercent(division)}%`"
                    ></div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Pembimbing:</span>
                <span class="text-sm font-medium text-gray-900"
                    >GrowithBI Admin</span
                >
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Periode:</span>
                <span class="text-sm font-medium text-gray-900">
                    {{ formatDate(division.start_date) }} -
                    {{ formatDate(division.end_date) }}
                </span>
            </div>

            <div
                class="flex items-center justify-between pt-4 border-t border-gray-100"
            >
                <Link
                    :href="`/admin/divisions/${division.id}`"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                >
                    Detail
                </Link>
                <div class="flex items-center space-x-2">
                    <Link
                        :href="`/admin/divisions/${division.id}/edit`"
                        class="text-indigo-600 hover:text-indigo-700 text-sm"
                    >
                        Edit
                    </Link>
                    <button
                        @click="emit('confirm-delete', division)"
                        class="text-red-600 hover:text-red-700 text-sm"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
