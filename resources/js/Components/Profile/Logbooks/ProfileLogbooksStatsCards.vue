<template>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-blue-100 text-xs font-medium uppercase tracking-wide"
                    >
                        Total
                    </p>
                    <p class="text-3xl font-bold mt-1">
                        {{ stats.total_logbooks || 0 }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                    <svg
                        class="w-6 h-6"
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
            class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl shadow-md p-5 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-orange-100 text-xs font-medium uppercase tracking-wide"
                    >
                        Pending
                    </p>
                    <p class="text-3xl font-bold mt-1">
                        {{ stats.pending_logbooks || 0 }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                    <svg
                        class="w-6 h-6"
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
            class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md p-5 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-green-100 text-xs font-medium uppercase tracking-wide"
                    >
                        Disetujui
                    </p>
                    <p class="text-3xl font-bold mt-1">
                        {{ stats.approved_logbooks || 0 }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                    <svg
                        class="w-6 h-6"
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
            class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-5 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-purple-100 text-xs font-medium uppercase tracking-wide"
                    >
                        Revisi
                    </p>
                    <p class="text-3xl font-bold mt-1">
                        {{ stats.revision_logbooks || 0 }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-2.5">
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
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
