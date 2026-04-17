<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
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
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Total Pendaftar
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.total_applications || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Total pendaftar saat ini</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-amber-500 via-orange-500 to-orange-600 rounded-2xl p-6 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/25 hover:-translate-y-1 transition-all duration-300"
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
                    <p class="text-sm font-medium text-white/90">
                        Menunggu Review
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.pending_applications || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Perlu ditinjau segera</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
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
                    <p class="text-sm font-medium text-white/90">Diterima</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.accepted_applications || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">
                    Tingkat penerimaan {{ acceptanceRate }}%
                </p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-rose-500 via-red-500 to-pink-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
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
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">Ditolak</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.rejected_applications || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">
                    Tingkat penolakan {{ rejectionRate }}%
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    acceptanceRate: {
        type: Number,
        default: 0,
    },
    rejectionRate: {
        type: Number,
        default: 0,
    },
});
</script>
