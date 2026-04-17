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
        <p class="text-sm text-gray-600">
            Already have an account?
            <Link
                :href="href"
                class="font-medium text-blue-600 hover:text-blue-700"
            >
                Sign in
            </Link>
        </p>
    </div>
</template>
