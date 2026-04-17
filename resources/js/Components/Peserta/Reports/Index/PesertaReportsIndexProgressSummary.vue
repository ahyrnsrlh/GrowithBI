<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
    completionRate: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">
                Ringkasan Progress
            </h4>

            <div class="space-y-4">
                <div
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                >
                    <div>
                        <h5 class="font-medium text-gray-900">
                            Tingkat Penyelesaian Logbook
                        </h5>
                        <p class="text-sm text-gray-500">
                            {{ stats.approved_logbooks }} dari
                            {{ stats.total_logbooks }} logbook disetujui
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ completionRate }}%
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                >
                    <div>
                        <h5 class="font-medium text-gray-900">
                            Total Jam Magang
                        </h5>
                        <p class="text-sm text-gray-500">
                            Waktu yang telah dihabiskan untuk kegiatan magang
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">
                            {{ stats.total_hours || 0 }} jam
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
