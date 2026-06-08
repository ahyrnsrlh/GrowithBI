<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="text-center mb-8">
        <Link
            href="/"
            class="inline-flex items-center justify-center gap-3 mb-4"
        >
            <img
                src="/logo_web.webp"
                alt="GrowithBI Logo"
                width="540"
                height="131"
                class="h-10 w-auto object-contain"
                loading="eager"
                decoding="async"
            />
            <span class="text-sm font-semibold tracking-wide text-slate-700">
                GrowithBI
            </span>
        </Link>
        <p class="text-slate-500 text-sm">
            Pendaftaran peserta magang berbasis data dan kolaborasi.
        </p>
    </div>
</template>
