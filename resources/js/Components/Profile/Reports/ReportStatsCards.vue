<template>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-5 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 text-white"
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
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Total Laporan
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ reportStats?.total_reports || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Total laporan akhir</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-5 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 text-white"
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
                <div>
                    <p class="text-sm font-medium text-white/90">Pending</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ reportStats?.pending_reports || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Menunggu persetujuan</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-5 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 text-white"
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
                <div>
                    <p class="text-sm font-medium text-white/90">Disetujui</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ reportStats?.approved_reports || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Telah disetujui</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-5 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Perlu Revisi
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ reportStats?.revision_reports || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Perlu perbaikan</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    reportStats: { type: Object, default: () => ({}) },
});
</script>
