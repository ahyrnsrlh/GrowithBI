<template>
    <div
        v-if="acceptedApplication"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
    >
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            Progress Magang
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">0</p>
                <p class="text-xs text-gray-500">Logbook</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">0</p>
                <p class="text-xs text-gray-500">Disetujui</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg">
                <p class="text-2xl font-bold text-yellow-600">0</p>
                <p class="text-xs text-gray-500">Pending</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg">
                <p class="text-2xl font-bold text-purple-600">0%</p>
                <p class="text-xs text-gray-500">Kehadiran</p>
            </div>
        </div>
        <div class="mt-4 text-center text-sm text-gray-500">
            Fitur tracking progress akan segera tersedia
        </div>
    </div>
</template>

<script setup>
defineProps({
    acceptedApplication: {
        type: Object,
        default: null,
    },
});
</script>
