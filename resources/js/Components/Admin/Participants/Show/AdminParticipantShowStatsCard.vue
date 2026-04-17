<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Total Aplikasi</span>
                <span class="text-sm font-medium text-gray-900">{{
                    totalApplications
                }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Diterima</span>
                <span class="text-sm font-medium text-green-600">{{
                    acceptedCount
                }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Ditolak</span>
                <span class="text-sm font-medium text-red-600">{{
                    rejectedCount
                }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Status Akun</span>
                <span
                    class="text-sm font-medium"
                    :class="
                        participant.is_active
                            ? 'text-green-600'
                            : 'text-red-600'
                    "
                >
                    {{ participant.is_active ? "Aktif" : "Tidak Aktif" }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    participant: {
        type: Object,
        required: true,
    },
    totalApplications: {
        type: Number,
        required: true,
    },
    acceptedCount: {
        type: Number,
        required: true,
    },
    rejectedCount: {
        type: Number,
        required: true,
    },
});
</script>
