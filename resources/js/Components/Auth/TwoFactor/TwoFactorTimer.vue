<template>
    <div class="text-center mb-6">
        <div
            v-if="!isExpired"
            class="inline-flex items-center px-3 py-2 bg-blue-50 rounded-lg text-sm text-blue-700"
        >
            <svg
                class="w-4 h-4 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            Kode berlaku
            <span class="font-semibold ml-1">{{ formattedCountdown }}</span>
        </div>
        <div
            v-else
            class="inline-flex items-center px-3 py-2 bg-red-50 rounded-lg text-sm text-red-700"
        >
            <svg
                class="w-4 h-4 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            Kode sudah kadaluarsa
        </div>
    </div>
</template>

<script setup>
defineProps({
    isExpired: {
        type: Boolean,
        default: false,
    },
    formattedCountdown: {
        type: String,
        required: true,
    },
});
</script>
