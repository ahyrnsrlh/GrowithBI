<template>
    <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-24">
            <div class="flex items-center justify-between mb-4 lg:hidden">
                <div class="text-sm font-semibold text-gray-800">
                    Dashboard
                </div>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm shadow-blue-600/30"
                    @click="toggleMenu"
                    :aria-expanded="isOpen"
                    aria-controls="profile-sidebar"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    Menu
                </button>
            </div>

            <div
                v-if="isOpen"
                class="fixed inset-0 z-40 bg-black/40 lg:hidden"
                @click="isOpen = false"
            ></div>

            <nav
                id="profile-sidebar"
                :class="[
                    'bg-gradient-to-b from-blue-800 to-indigo-900 rounded-lg shadow-lg border border-blue-700 p-6 max-h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar transition-transform duration-300',
                    isOpen ? 'translate-x-0' : '-translate-x-full',
                    'fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] lg:static lg:w-auto lg:max-w-none lg:translate-x-0',
                ]"
            >
                <div class="space-y-2">
                    <button
                        @click="selectTab('profile')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'profile'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
                        ]"
                    >
                        <i class="fas fa-user mr-3"></i>
                        Informasi Pribadi
                    </button>
                    <button
                        @click="selectTab('documents')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'documents'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
                        ]"
                    >
                        <i class="fas fa-file-alt mr-3"></i>
                        Dokumen Persyaratan
                    </button>
                    <button
                        @click="selectTab('applications')"
                        :class="[
                            'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                            activeTab === 'applications'
                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
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
                        @click="isOpen = false"
                        class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-white hover:bg-blue-700 hover:bg-opacity-30 block"
                    >
                        <i class="fas fa-external-link-alt mr-3"></i>
                        Daftar Magang
                    </a>
                </div>

                <div
                    v-if="hasAcceptedApplication"
                    class="mt-6 pt-4 border-t border-blue-600 border-opacity-40"
                >
                    <h3 class="text-sm font-medium text-white mb-3">
                        Logbook & Laporan
                    </h3>
                    <div class="space-y-1">
                        <button
                            @click="selectTab('attendance')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'attendance'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
                            ]"
                        >
                            <i class="fas fa-clock mr-3"></i>
                            Presensi Online
                        </button>
                        <button
                            @click="selectTab('logbook')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'logbook'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
                            ]"
                        >
                            <i class="fas fa-book mr-3"></i>
                            Logbook Harian
                        </button>
                        <button
                            @click="selectTab('reports')"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                activeTab === 'reports'
                                    ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                    : 'text-white hover:bg-blue-700 hover:bg-opacity-30',
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
                        @click="isOpen = false"
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
import { ref } from "vue";

defineProps({
    activeTab: { type: String, required: true },
    applicationsCount: { type: Number, default: 0 },
    hasAcceptedApplication: { type: Boolean, default: false },
});

const emit = defineEmits(["update:activeTab"]);

const isOpen = ref(false);

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const selectTab = (tab) => {
    emit("update:activeTab", tab);
    isOpen.value = false;
};
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
