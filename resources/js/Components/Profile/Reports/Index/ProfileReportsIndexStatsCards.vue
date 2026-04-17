<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
});
</script>

<template>
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
                        {{ stats.total_reports || 0 }}
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
                    <p class="text-orange-100 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold">
                        {{ stats.pending_reports || 0 }}
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
                    <p class="text-green-100 text-sm font-medium">Disetujui</p>
                    <p class="text-3xl font-bold">
                        {{ stats.approved_reports || 0 }}
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
            class="bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">
                        Perlu Revisi
                    </p>
                    <p class="text-3xl font-bold">
                        {{ stats.revision_reports || 0 }}
                    </p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
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
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
