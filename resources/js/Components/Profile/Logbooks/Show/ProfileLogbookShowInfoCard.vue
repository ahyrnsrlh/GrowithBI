<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    formatDateShort: {
        type: Function,
        required: true,
    },
    getStatusBadgeClass: {
        type: Function,
        required: true,
    },
    getStatusDotClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3
                class="text-sm font-semibold text-gray-900 uppercase tracking-wide"
            >
                Informasi
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2"
                    >Status</label
                >
                <span
                    :class="getStatusBadgeClass(logbook.status)"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold"
                >
                    <span
                        class="w-2 h-2 rounded-full mr-2"
                        :class="getStatusDotClass(logbook.status)"
                    ></span>
                    {{ getStatusText(logbook.status) }}
                </span>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Tanggal</label
                >
                <p class="text-sm text-gray-900">
                    {{ formatDate(logbook.date) }}
                </p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Durasi</label
                >
                <p class="text-sm text-gray-900">
                    {{ logbook.duration }} jam kerja
                </p>
            </div>
            <div v-if="logbook.division">
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Divisi</label
                >
                <p class="text-sm text-gray-900">{{ logbook.division.name }}</p>
            </div>
            <div class="pt-4 border-t border-gray-200 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1"
                        >Dibuat</label
                    >
                    <p class="text-xs text-gray-700">
                        {{ formatDateShort(logbook.created_at) }}
                    </p>
                </div>
                <div v-if="logbook.updated_at !== logbook.created_at">
                    <label class="block text-xs font-medium text-gray-500 mb-1"
                        >Terakhir Diubah</label
                    >
                    <p class="text-xs text-gray-700">
                        {{ formatDateShort(logbook.updated_at) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
