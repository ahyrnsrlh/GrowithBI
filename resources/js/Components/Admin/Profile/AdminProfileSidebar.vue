<template>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Statistik Admin
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Divisi</span>
                    <span class="text-xl font-bold text-blue-600">{{
                        stats.total_divisions
                    }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Pendaftar</span>
                    <span class="text-xl font-bold text-green-600">{{
                        stats.total_applications
                    }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Diterima</span>
                    <span class="text-xl font-bold text-purple-600">{{
                        stats.accepted_applications
                    }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Keamanan</h3>
            <div class="space-y-4">
                <div
                    class="flex items-center justify-between p-3 bg-green-50 rounded-lg"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-green-600 mr-3"
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
                        <span class="text-sm font-medium text-green-800"
                            >Email Verified</span
                        >
                    </div>
                </div>

                <div
                    class="flex items-center justify-between p-3 bg-blue-50 rounded-lg"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-blue-600 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                        <span class="text-sm font-medium text-blue-800"
                            >Password Strong</span
                        >
                    </div>
                </div>

                <button
                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition-colors"
                >
                    Lihat Log Aktivitas
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferensi</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Notifikasi Email</span>
                    <label
                        class="relative inline-flex items-center cursor-pointer"
                    >
                        <input
                            v-model="preferences.email_notifications"
                            type="checkbox"
                            class="sr-only peer"
                        />
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                        ></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Auto Logout</span>
                    <label
                        class="relative inline-flex items-center cursor-pointer"
                    >
                        <input
                            v-model="preferences.auto_logout"
                            type="checkbox"
                            class="sr-only peer"
                        />
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                        ></div>
                    </label>
                </div>

                <button
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors"
                    @click="$emit('save-preferences')"
                >
                    Simpan Preferensi
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
    preferences: {
        type: Object,
        required: true,
    },
});

defineEmits(["save-preferences"]);
</script>
