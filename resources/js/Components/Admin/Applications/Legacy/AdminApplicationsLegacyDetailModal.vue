<template>
    <div
        v-if="selectedApplication"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        @click="$emit('close')"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            @click.stop
        >
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Detail Aplikasi Magang
                    </h3>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <XMarkIcon class="h-6 w-6" />
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Nama Lengkap</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.name }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Email</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.email }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Telepon</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.phone }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Universitas</label
                            >
                            <p class="mt-1 text-sm text-gray-900">
                                {{ selectedApplication.university }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Alamat</label
                        >
                        <p class="mt-1 text-sm text-gray-900">
                            {{ selectedApplication.address }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Motivasi</label
                        >
                        <p class="mt-1 text-sm text-gray-900">
                            {{ selectedApplication.motivation }}
                        </p>
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button
                            v-if="selectedApplication.status === 'pending'"
                            @click="$emit('approve', selectedApplication)"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Terima Aplikasi
                        </button>
                        <button
                            v-if="selectedApplication.status === 'pending'"
                            @click="$emit('reject', selectedApplication)"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Tolak Aplikasi
                        </button>
                        <button
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { XMarkIcon } from "@heroicons/vue/24/outline";

defineProps({
    selectedApplication: {
        type: Object,
        default: null,
    },
});

defineEmits(["close", "approve", "reject"]);
</script>
