<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-100">
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
                <Link href="/" class="flex items-center">
                    <ApplicationLogo class="h-12 w-12 fill-current text-gray-500" />
                    <span class="ml-3 text-xl font-semibold text-gray-900">GrowithBI</span>
                </Link>
            </div>
        </div>

        <div class="flex-1 w-full">
            <slot />
        </div>
    </div>
</template>
