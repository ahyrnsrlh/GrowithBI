<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="mt-6 text-center">
        <p class="text-sm text-slate-500">
            Sudah punya akun?
            <Link
                :href="href"
                class="font-semibold text-blue-600 hover:text-blue-700"
            >
                Masuk sekarang
            </Link>
        </p>
    </div>
</template>
