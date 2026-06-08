<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-4 text-center">
        <Link href="/" class="inline-flex items-center justify-center gap-3">
            <img
                src="/logo_web.webp"
                alt="GrowithBI Logo"
                width="540"
                height="131"
                class="h-10 w-auto object-contain"
                loading="eager"
                decoding="async"
            />
        </Link>
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl">
                {{ title }}
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                {{ subtitle }}
            </p>
        </div>
    </div>
</template>
