<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div
            class="bg-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
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
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">
                        Total Kehadiran
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.present_days || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white">Total absensi bulan ini</p>
            </div>
        </div>

        <div
            class="bg-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
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
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">
                        Tingkat Kehadiran
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.attendance_rate || 0 }}%
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white">Persentase kehadiran aktif</p>
            </div>
        </div>

        <div
            class="bg-amber-500 rounded-2xl p-6 shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/25 hover:-translate-y-1 transition-all duration-300"
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
                    <p class="text-sm font-medium text-white">
                        Ketepatan Waktu
                    </p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.punctuality_rate || 0 }}%
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white">Persentase tepat waktu</p>
            </div>
        </div>

        <div
            class="bg-rose-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
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
                    <p class="text-sm font-medium text-white">Tidak Hadir</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ stats?.absent_days || 0 }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white">Total ketidakhadiran</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: { type: Object, default: () => ({}) },
});
</script>
