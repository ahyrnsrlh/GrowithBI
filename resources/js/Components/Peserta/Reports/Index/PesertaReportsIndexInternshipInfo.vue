<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Magang</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Divisi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ stats.division }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ formatDate(stats.start_date) }}
                </dd>
            </div>
        </div>
    </div>
</template>
