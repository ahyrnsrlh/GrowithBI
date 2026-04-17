<template>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div
            class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">
                        Total Logbook
                    </p>
                    <p class="text-3xl font-bold">
                        {{ stats.total_logbooks || 0 }}
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
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold">
                        {{ stats.pending_logbooks || 0 }}
                    </p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
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
            class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Disetujui</p>
                    <p class="text-3xl font-bold">
                        {{ stats.approved_logbooks || 0 }}
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
                    <p class="text-red-100 text-sm font-medium">Revisi</p>
                    <p class="text-3xl font-bold">
                        {{ stats.revision_logbooks || 0 }}
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
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_logbooks: 0,
            pending_logbooks: 0,
            approved_logbooks: 0,
            revision_logbooks: 0,
        }),
    },
});
</script>
