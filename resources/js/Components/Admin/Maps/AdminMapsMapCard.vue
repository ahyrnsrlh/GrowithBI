<template>
    <div
        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6"
    >
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
                    >
                        <MapPinIcon class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">
                            Peta Lokasi Absensi
                        </h2>
                        <p class="text-sm text-white/80">
                            Monitoring real-time lokasi peserta
                        </p>
                    </div>
                </div>
                <button
                    @click="$emit('refresh')"
                    :disabled="isRefreshing"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 disabled:bg-white/10 backdrop-blur-sm text-white text-sm font-medium rounded-xl transition-all duration-200 border border-white/20 disabled:cursor-not-allowed"
                >
                    <ArrowPathIcon
                        :class="['h-4 w-4', isRefreshing ? 'animate-spin' : '']"
                    />
                    {{ isRefreshing ? "Memuat..." : "Refresh" }}
                </button>
            </div>
        </div>

        <div class="px-6 py-3 bg-gray-50/50 border-b border-gray-100">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span
                        class="w-3 h-3 bg-blue-600 rounded-full ring-2 ring-blue-200"
                    ></span>
                    <span class="text-gray-600"
                        >Kantor ({{
                            officeLocation?.name ||
                            "Bank Indonesia KPw Lampung"
                        }})</span
                    >
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-emerald-200"
                    ></span>
                    <span class="text-gray-600">Absensi Valid</span>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="w-3 h-3 bg-rose-500 rounded-full ring-2 ring-rose-200"
                    ></span>
                    <span class="text-gray-600">Absensi Tidak Valid</span>
                </div>
            </div>
        </div>

        <div class="relative">
            <div id="map" class="w-full h-[450px]"></div>

            <div
                v-if="isLoading"
                class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center"
            >
                <div class="text-center">
                    <div
                        class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"
                    ></div>
                    <p class="text-sm text-gray-600 mt-3 font-medium">
                        Memuat peta...
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { MapPinIcon, ArrowPathIcon } from "@heroicons/vue/24/outline";

defineProps({
    officeLocation: {
        type: Object,
        default: () => ({}),
    },
    isRefreshing: {
        type: Boolean,
        default: false,
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["refresh"]);
</script>
