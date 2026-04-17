<template>
    <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-24">
            <nav
                class="bg-gradient-to-b from-blue-800 to-indigo-900 rounded-lg shadow-lg border border-blue-700 p-6 max-h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar"
            >
                <div class="space-y-2">
                    <button
                        @click="$emit('update:activeTab', 'profile')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'profile'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                        ]"
                    >
                        <i class="fas fa-user mr-3"></i>
                        Informasi Pribadi
                    </button>
                    <button
                        @click="$emit('update:activeTab', 'documents')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'documents'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                        ]"
                    >
                        <i class="fas fa-file-alt mr-3"></i>
                        Dokumen Persyaratan
                    </button>
                    <button
                        @click="$emit('update:activeTab', 'applications')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'applications'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                        ]"
                    >
                        <i class="fas fa-briefcase mr-3"></i>
                        Status Lamaran
                        <span
                            v-if="applicationsCount"
                            class="ml-2 bg-white text-blue-600 text-xs px-2 py-1 rounded-full"
                        >
                            {{ applicationsCount }}
                        </span>
                    </button>
                    <a
                        :href="route('home') + '#divisions'"
                        class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-blue-100 hover:bg-blue-700 hover:bg-opacity-30 block"
                    >
                        <i class="fas fa-external-link-alt mr-3"></i>
                        Daftar Magang
                    </a>
                </div>

                <div
                    v-if="hasAcceptedApplication"
                    class="mt-6 pt-4 border-t border-blue-600 border-opacity-40"
                >
                    <h3 class="text-sm font-medium text-blue-100 mb-3">
                        Logbook & Laporan
                    </h3>
                    <div class="space-y-1">
                        <button
                            @click="$emit('update:activeTab', 'attendance')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'attendance'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                            ]"
                        >
                            <i class="fas fa-clock mr-3"></i>
                            Absensi Online
                        </button>
                        <button
                            @click="$emit('update:activeTab', 'logbook')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'logbook'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                            ]"
                        >
                            <i class="fas fa-book mr-3"></i>
                            Logbook Harian
                        </button>
                        <button
                            @click="$emit('update:activeTab', 'reports')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'reports'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                            ]"
                        >
                            <i class="fas fa-chart-line mr-3"></i>
                            Laporan Akhir
                        </button>
                    </div>
                </div>

                <div
                    class="mt-8 pt-6 border-t border-blue-600 border-opacity-40"
                >
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-red-200 hover:bg-red-700 hover:bg-opacity-30 hover:text-white flex items-center"
                    >
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Log Out
                    </Link>
                </div>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    activeTab: { type: String, required: true },
    applicationsCount: { type: Number, default: 0 },
    hasAcceptedApplication: { type: Boolean, default: false },
});

defineEmits(["update:activeTab"]);
</script>

<style scoped>
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.5) rgba(59, 130, 246, 0.1);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(59, 130, 246, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.7);
}
</style>
