<template>
    <div
        class="sticky top-0 z-50 bg-gradient-to-r from-blue-800 to-indigo-900 border-b border-blue-600 shadow-lg"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <Link :href="route('home')" class="flex items-center ml-8">
                    <img
                        src="/logo_web_small.webp" srcset="/logo_web_small.webp 1x, /logo_web.webp 2x"
                        alt="GrowithBI Bank Indonesia Lampung"
                        width="540"
                        height="131"
                        class="h-8 w-auto object-contain"
                        loading="eager"
                        decoding="async"
                    />
                </Link>

                <div class="flex items-center space-x-4">
                    <NotificationBell v-if="user" :userId="user.id" />

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-transparent bg-transparent px-2 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:text-white focus:outline-none"
                                    aria-label="Open profile menu"
                                >
                                    <span
                                        class="h-8 w-8 rounded-full overflow-hidden border border-white/20 bg-blue-700"
                                    >
                                        <img
                                            v-if="user?.profile_photo_path"
                                            :src="`/storage/${user.profile_photo_path}`"
                                            :alt="user?.name || 'User'"
                                            class="h-full w-full object-cover"
                                        />
                                        <span
                                            v-else
                                            class="flex h-full w-full items-center justify-center text-xs font-semibold text-white"
                                        >
                                            {{ user?.name?.charAt(0) || "U" }}
                                        </span>
                                    </span>
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
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NotificationBell from "@/Components/NotificationBell.vue";

defineProps({
    user: { type: Object, default: null },
});
</script>
