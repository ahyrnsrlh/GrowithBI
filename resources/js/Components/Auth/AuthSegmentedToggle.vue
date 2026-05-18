<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    active: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="inline-flex rounded-full bg-slate-100 p-1 text-sm shadow-inner mx-auto"
    >
        <Link
            :href="route('login')"
            class="min-w-[84px] rounded-full px-5 py-2 text-center font-semibold transition"
            :class="
                active === 'login'
                    ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200'
                    : 'text-slate-500 hover:text-slate-700'
            "
        >
            Masuk
        </Link>
        <Link
            :href="route('register')"
            class="min-w-[84px] rounded-full px-5 py-2 text-center font-semibold transition"
            :class="
                active === 'register'
                    ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200'
                    : 'text-slate-500 hover:text-slate-700'
            "
        >
            Daftar
        </Link>
    </div>
</template>
