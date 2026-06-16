<template>
    <!-- ───────────────────────────────────────────────────────────────────────
         AttendanceDivisionChart
         Self-contained card: fetches data, listens to Echo, renders chart.
         No props required — all state is managed by useAttendanceDivisionChart.
    ──────────────────────────────────────────────────────────────────────── -->
    <div
        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300 flex flex-col"
    >
        <!-- ── Header ─────────────────────────────────────────────────────── -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                        Kehadiran per Divisi
                    </h3>

                    <!-- LIVE badge — only shown when WebSocket connected -->
                    <transition name="fade">
                        <span
                            v-if="isConnected"
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase select-none"
                            title="Data diperbarui secara real-time"
                        >
                            <span class="live-dot w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            LIVE
                        </span>
                        <span
                            v-else
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider bg-gray-100 text-gray-400 border border-gray-200 uppercase select-none"
                            title="WebSocket tidak terhubung"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            Offline
                        </span>
                    </transition>
                </div>

                <p class="text-sm text-gray-500 mt-0.5">
                    Jumlah peserta hadir hari ini per divisi
                </p>
            </div>

            <!-- Icon -->
            <div
                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-50 to-violet-100 rounded-xl flex items-center justify-center ml-3"
            >
                <svg
                    class="w-5 h-5 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </div>
        </div>

        <!-- ── Loading skeleton ────────────────────────────────────────────── -->
        <div v-if="isLoading" class="flex-1 flex flex-col gap-3 py-4">
            <div
                v-for="i in 4"
                :key="i"
                class="h-6 bg-gray-100 rounded-lg animate-pulse"
                :style="{ width: `${85 - i * 12}%` }"
            ></div>
            <div class="h-48 bg-gray-50 rounded-xl animate-pulse mt-2"></div>
        </div>

        <!-- ── Error state ─────────────────────────────────────────────────── -->
        <div
            v-else-if="fetchError"
            class="flex-1 flex flex-col items-center justify-center py-10 gap-3"
        >
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium">{{ fetchError }}</p>
            <button
                @click="fetchInitialData"
                class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold underline underline-offset-2 transition-colors"
            >
                Coba Lagi
            </button>
        </div>

        <!-- ── Empty state ─────────────────────────────────────────────────── -->
        <div
            v-else-if="chartLabels.length === 0"
            class="flex-1 flex flex-col items-center justify-center py-10 gap-3"
        >
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                    />
                </svg>
            </div>
            <p class="text-sm text-gray-400">Belum ada divisi aktif.</p>
        </div>

        <!-- ── Chart ───────────────────────────────────────────────────────── -->
        <div v-else class="flex-1 flex flex-col">
            <BarChart
                :data="chartData"
                :options="chartOptions"
                class="h-80"
            />

            <!-- Footer summary -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                    <span class="text-xs text-gray-500 font-medium">Total hadir hari ini</span>
                </div>
                <transition name="count-up" mode="out-in">
                    <span
                        :key="totalPresent"
                        class="text-sm font-bold text-indigo-700 tabular-nums"
                    >
                        {{ totalPresent }} peserta
                    </span>
                </transition>
            </div>
        </div>
    </div>
</template>

<script setup>
import BarChart from '@/Components/Charts/BarChart.vue';
import { useAttendanceDivisionChart } from '@/Composables/useAttendanceDivisionChart';

// ─── Composable ───────────────────────────────────────────────────────────────

const {
    chartData,
    chartLabels,
    totalPresent,
    isLoading,
    fetchError,
    isConnected,
    fetchInitialData,
} = useAttendanceDivisionChart();

// ─── Chart.js options ─────────────────────────────────────────────────────────

const chartOptions = {
    indexAxis:           'y',   // ← horizontal bar chart
    responsive:          true,
    maintainAspectRatio: false,
    animation: {
        duration: 500,
        easing:   'easeOutQuart',
    },
    interaction: {
        mode:      'index',
        intersect: false,
    },
    scales: {
        // Y axis = division names (category axis)
        y: {
            display: true,
            grid: {
                display: false,
            },
            ticks: {
                font:         { size: 11, weight: '500' },
                color:        '#374151',
                crossAlign:   'far',  // align labels to the right edge
            },
            border: { display: false },
        },
        // X axis = count values
        x: {
            display:     true,
            beginAtZero: true,
            grid: {
                color:     'rgba(107, 114, 128, 0.07)',
                lineWidth: 1,
            },
            ticks: {
                font:      { size: 10 },
                precision: 0,
                stepSize:  1,
                color:     '#9CA3AF',
            },
            border: { display: false },
        },
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: 'rgba(17, 24, 39, 0.92)',
            titleColor:      '#F9FAFB',
            bodyColor:       '#D1D5DB',
            borderColor:     'rgba(255,255,255,0.08)',
            borderWidth:     1,
            cornerRadius:    10,
            padding:         10,
            displayColors:   true,
            callbacks: {
                title: (items) => items[0]?.label ?? '',
                label: (item)  => ` ${item.formattedValue} peserta hadir`,
            },
        },
    },
};
</script>

<style scoped>
/* ── LIVE dot pulse animation ──────────────────────────────────────────────── */
.live-dot {
    animation: livePulse 1.8s ease-in-out infinite;
}

@keyframes livePulse {
    0%, 100% { opacity: 1;   transform: scale(1);    }
    50%       { opacity: 0.4; transform: scale(0.75); }
}

/* ── Fade transition for badge ────────────────────────────────────────────── */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity:   0;
    transform: scale(0.9);
}

/* ── Count-up transition for total badge ──────────────────────────────────── */
.count-up-enter-active,
.count-up-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.count-up-enter-from {
    opacity:   0;
    transform: translateY(-6px);
}
.count-up-leave-to {
    opacity:   0;
    transform: translateY(6px);
}
</style>
