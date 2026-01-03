<script setup>
import { Link, Head } from "@inertiajs/vue3";
import { computed } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NotificationBell from "@/Components/NotificationBell.vue";

const props = defineProps({
    title: {
        type: String,
        default: "Profile",
    },
    hasAcceptedApplication: {
        type: Boolean,
        default: false,
    },
    applicationsCount: {
        type: Number,
        default: 0,
    },
});

// Get current route name to highlight active navigation
const currentRoute = computed(() => {
    return route().current();
});

const isActiveRoute = (routeName) => {
    return (
        currentRoute.value === routeName ||
        currentRoute.value?.startsWith(routeName)
    );
};
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-gray-50">
        <!-- Header Navigation -->
        <div
            class="sticky top-0 z-50 bg-gradient-to-r from-blue-800 to-indigo-900 border-b border-blue-600 shadow-lg"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center">
                        <img
                            src="/storage/logo_web.png"
                            alt="GrowithBI Bank Indonesia Lampung"
                            class="h-9 sm:h-10 w-auto object-contain"
                        />
                    </Link>

                    <!-- Right Section: Notification Bell & User Dropdown -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <NotificationBell
                            v-if="$page.props.auth?.user"
                            :userId="$page.props.auth.user.id"
                        />

                        <!-- User Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-transparent px-3 py-2 text-sm font-medium leading-4 text-blue-100 transition duration-150 ease-in-out hover:text-white focus:outline-none"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                    <!-- End Right Section -->
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24">
                        <nav
                            class="bg-gradient-to-b from-blue-800 to-indigo-900 rounded-lg shadow-lg border border-blue-700 p-6 max-h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar"
                        >
                            <div class="space-y-2">
                                <Link
                                    :href="route('profile.edit')"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors block',
                                        isActiveRoute('profile.edit')
                                            ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                            : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                    ]"
                                >
                                    <i class="fas fa-user mr-3"></i>
                                    Profil Saya
                                </Link>
                                <a
                                    :href="route('home') + '#divisions'"
                                    class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors text-blue-100 hover:bg-blue-700 hover:bg-opacity-30 block"
                                >
                                    <i
                                        class="fas fa-external-link-alt mr-3"
                                    ></i>
                                    Daftar Magang
                                </a>
                            </div>

                            <!-- Logbook & Laporan Section (Only for accepted participants) -->
                            <div
                                v-if="hasAcceptedApplication"
                                class="mt-6 pt-4 border-t border-blue-600 border-opacity-40"
                            >
                                <h3
                                    class="text-sm font-medium text-blue-100 mb-3"
                                >
                                    Logbook & Laporan
                                </h3>
                                <div class="space-y-1">
                                    <Link
                                        :href="
                                            route('profile.attendance.index')
                                        "
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors block',
                                            isActiveRoute('profile.attendance')
                                                ? 'bg-blue-700 bg-opacity-40 text-white border border-blue-500'
                                                : 'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                        ]"
                                    >
                                        <i class="fas fa-clock mr-3"></i>
                                        Absensi Online
                                    </Link>
                                    <Link
                                        :href="
                                            route('profile.edit') +
                                            '?tab=logbook'
                                        "
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors block',
                                            'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                        ]"
                                    >
                                        <i class="fas fa-book mr-3"></i>
                                        Logbook Harian
                                    </Link>
                                    <Link
                                        :href="
                                            route('profile.edit') +
                                            '?tab=reports'
                                        "
                                        :class="[
                                            'w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-colors block',
                                            'text-blue-100 hover:bg-blue-700 hover:bg-opacity-30',
                                        ]"
                                    >
                                        <i class="fas fa-chart-line mr-3"></i>
                                        Laporan Akhir
                                    </Link>
                                </div>
                            </div>

                            <!-- Logout Section -->
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

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>
