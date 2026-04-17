<template>
    <div class="text-center mb-8">
        <Link href="/" class="inline-flex justify-center mb-4">
            <img
                src="/storage/logo_web2.png"
                alt="GrowithBI Logo"
                class="h-12 w-auto object-contain"
            />
        </Link>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Verifikasi Dua Faktor
        </h2>
        <p class="text-sm text-gray-600">
            Masukkan kode 6 digit yang telah dikirim ke
        </p>
        <p class="text-blue-600 font-semibold mt-1">{{ email }}</p>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    email: {
        type: String,
        required: true,
    },
});
</script>
