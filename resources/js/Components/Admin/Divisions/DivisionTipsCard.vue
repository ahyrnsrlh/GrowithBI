<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    tips: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg
                class="flex-shrink-0 w-5 h-5 text-blue-400"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"
                />
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">{{ title }}</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li v-for="(tip, index) in tips" :key="index">
                            {{ tip }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
