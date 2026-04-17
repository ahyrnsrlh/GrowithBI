<template>
    <div class="mt-5 text-center">
        <p class="text-sm text-gray-600 mb-2">Tidak menerima kode?</p>
        <button
            type="button"
            @click="$emit('resend')"
            :disabled="!canResendNow || isResending"
            class="text-sm font-medium text-blue-600 hover:text-blue-700 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors"
        >
            <span v-if="isResending">
                <svg
                    class="animate-spin inline-block w-4 h-4 mr-1"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                Mengirim...
            </span>
            <span v-else-if="!canResendNow"
                >Kirim ulang kode ({{ resendCooldown }}s)</span
            >
            <span v-else>Kirim Ulang Kode</span>
        </button>
    </div>
</template>

<script setup>
defineProps({
    canResendNow: {
        type: Boolean,
        default: false,
    },
    isResending: {
        type: Boolean,
        default: false,
    },
    resendCooldown: {
        type: Number,
        default: 0,
    },
});

defineEmits(["resend"]);
</script>
