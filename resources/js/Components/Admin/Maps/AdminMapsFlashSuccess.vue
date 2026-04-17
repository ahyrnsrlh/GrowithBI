<template>
    <div
        v-if="message"
        class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4"
    >
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0"
            >
                <CheckCircleIcon class="h-5 w-5 text-emerald-600" />
            </div>
            <p class="text-emerald-800 font-medium text-sm">{{ message }}</p>
        </div>
    </div>
</template>

<script setup>
import { CheckCircleIcon } from "@heroicons/vue/24/outline";

defineProps({
    message: {
        type: String,
        default: "",
    },
});
</script>
