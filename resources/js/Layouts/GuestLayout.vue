<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

// Props untuk kontrol layout
defineProps({
    variant: {
        type: String,
        default: 'auth' // 'auth' untuk login/register, 'public' untuk halaman publik
    },
    container: {
        type: Boolean,
        default: false // true untuk membungkus dengan container max-width
    }
});
</script>

<template>
    <div>
        <!-- Layout untuk halaman auth (login/register) -->
        <template v-if="variant === 'auth'">
            <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
                <div class="mb-6">
                    <Link href="/">
                        <ApplicationLogo class="h-16 w-16 sm:h-20 sm:w-20 fill-current text-gray-500" />
                    </Link>
                </div>

                <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
                    <slot />
                </div>
            </div>
        </template>

        <!-- Layout untuk halaman publik (full-width responsive) -->
        <template v-else>
            <div class="min-h-screen bg-gray-100">
                <!-- Header dengan logo -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex justify-center">
                            <Link href="/" class="flex items-center">
                                <ApplicationLogo class="h-10 w-10 sm:h-12 sm:w-12 fill-current text-gray-500" />
                                <span class="ml-3 text-xl sm:text-2xl font-bold text-gray-900">GrowithBI</span>
                            </Link>
                        </div>
                    </div>
                </header>

                <!-- Main content area -->
                <main>
                    <template v-if="container">
                        <!-- Dengan container max-width -->
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <slot />
                        </div>
                    </template>
                    <template v-else>
                        <!-- Full width tanpa container -->
                        <slot />
                    </template>
                </main>
            </div>
        </template>
    </div>
</template>
