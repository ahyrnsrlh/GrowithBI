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
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Total Peserta
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ stats.total_participants || 0 }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Terdaftar di sistem</p>
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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Peserta Aktif
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ stats.active_participants || 0 }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Sedang magang</p>
            </div>
        </div>

        <div
            class="bg-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/20 hover:shadow-xl hover:shadow-violet-500/25 hover:-translate-y-1 transition-all duration-300"
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
                        Total Aplikasi
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ stats.total_applications || 0 }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Pendaftaran masuk</p>
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
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        Selesai Magang
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ stats.completed_participants || 0 }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Lulus program</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_participants: 0,
            active_participants: 0,
            total_applications: 0,
            completed_participants: 0,
        }),
    },
});
</script>
