<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div
                    class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"
                >
                    <span class="text-2xl font-bold text-blue-600">
                        {{ participant.name.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ participant.name }}
                    </h1>
                    <div class="flex items-center space-x-4 mt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="
                                participant.is_active
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800'
                            "
                        >
                            {{
                                participant.is_active ? "Aktif" : "Tidak Aktif"
                            }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Bergabung {{ formatDate(participant.created_at) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button
                    @click="$emit('toggle-status')"
                    :class="[
                        'px-4 py-2 rounded-lg transition-colors duration-200 flex items-center',
                        participant.is_active
                            ? 'bg-red-600 text-white hover:bg-red-700'
                            : 'bg-green-600 text-white hover:bg-green-700',
                    ]"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            :d="
                                participant.is_active
                                    ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636'
                                    : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                            "
                        />
                    </svg>
                    {{ participant.is_active ? "Nonaktifkan" : "Aktifkan" }}
                </button>
                <Link
                    :href="route('admin.participants.index')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                    Kembali
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    participant: {
        type: Object,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});

defineEmits(["toggle-status"]);
</script>
