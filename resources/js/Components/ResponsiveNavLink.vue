<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "block w-full ps-3 pe-4 py-2 border-l-4 border-blue-300 text-start text-base font-medium text-white bg-blue-700 bg-opacity-40 focus:outline-none focus:text-white focus:bg-blue-600 focus:bg-opacity-50 focus:border-blue-200 transition duration-150 ease-in-out"
        : "block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-blue-100 hover:text-white hover:bg-blue-700 hover:bg-opacity-30 hover:border-blue-300 focus:outline-none focus:text-white focus:bg-blue-700 focus:bg-opacity-30 focus:border-blue-300 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
