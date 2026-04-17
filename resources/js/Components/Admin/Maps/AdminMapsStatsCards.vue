<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <UsersIcon class="h-6 w-6 text-white" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white/90">Total Hadir</p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ stats.total_attendances }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">
                    Total peserta hadir hari ini
                </p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <CheckCircleIcon class="h-6 w-6 text-white" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white/90">
                        Absensi Valid
                    </p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ stats.valid_attendances }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Dalam radius lokasi kantor</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-rose-500 via-red-500 to-pink-600 rounded-2xl p-6 shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <ExclamationTriangleIcon class="h-6 w-6 text-white" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white/90">Tidak Valid</p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ stats.invalid_attendances }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Di luar radius lokasi</p>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-600 rounded-2xl p-6 shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/25 hover:-translate-y-1 transition-all duration-300"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                >
                    <ClockIcon class="h-6 w-6 text-white" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white/90">Terlambat</p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ stats.late }}
                    </h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">Peserta yang terlambat</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    UsersIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_attendances: 0,
            valid_attendances: 0,
            invalid_attendances: 0,
            late: 0,
        }),
    },
});
</script>
