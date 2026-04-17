<template>
    <div
        class="sticky top-20 z-30 bg-white/95 backdrop-blur-sm border border-gray-200 rounded-xl shadow-sm mb-6 p-4"
    >
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
        >
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700">Filter:</span>
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="statusModel = ''"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                            statusModel === ''
                                ? 'bg-blue-600 text-white shadow-md'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        Semua
                    </button>
                    <button
                        @click="statusModel = 'submitted'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                            statusModel === 'submitted'
                                ? 'bg-[#FFA726] text-white shadow-md'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        Pending
                    </button>
                    <button
                        @click="statusModel = 'approved'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                            statusModel === 'approved'
                                ? 'bg-[#43A047] text-white shadow-md'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        Disetujui
                    </button>
                    <button
                        @click="statusModel = 'revision'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                            statusModel === 'revision'
                                ? 'bg-[#AB47BC] text-white shadow-md'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                        ]"
                    >
                        Revisi
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">
                    Menampilkan
                    <span class="font-semibold">{{ displayedCount }}</span>
                    dari
                    <span class="font-semibold">{{ filteredCount }}</span>
                    logbook
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    status: {
        type: String,
        default: "",
    },
    displayedCount: {
        type: Number,
        default: 0,
    },
    filteredCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(["update:status"]);

const statusModel = computed({
    get: () => props.status,
    set: (value) => emit("update:status", value),
});
</script>
