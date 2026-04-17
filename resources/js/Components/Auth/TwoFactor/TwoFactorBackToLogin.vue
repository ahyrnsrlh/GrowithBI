<template>
    <div class="mt-5 text-center">
        <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-sm text-gray-600 hover:text-gray-900 transition-colors"
        >
            ← Kembali ke Login
        </Link>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
