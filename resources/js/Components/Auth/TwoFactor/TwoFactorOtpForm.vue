<template>
    <form @submit.prevent="$emit('submit')" class="space-y-5">
        <div
            v-if="form.errors.code"
            class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg"
        >
            <div class="flex items-center">
                <svg
                    class="w-4 h-4 text-red-600 mr-2"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span class="text-sm text-red-800">{{ form.errors.code }}</span>
            </div>
        </div>

        <div>
            <label
                class="block text-sm font-semibold text-gray-700 mb-3 text-center"
                >Kode Verifikasi</label
            >
            <div
                class="flex justify-center gap-2"
                @paste="$emit('paste', $event)"
            >
                <input
                    v-for="(digit, index) in otpDigits"
                    :key="index"
                    :ref="(el) => setOtpInputRef(index, el)"
                    type="text"
                    inputmode="numeric"
                    maxlength="6"
                    :value="digit"
                    @input="$emit('otp-input', index, $event)"
                    @keydown="$emit('otp-keydown', index, $event)"
                    :disabled="form.processing"
                    class="w-12 h-14 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="{
                        'border-red-500 focus:border-red-500 focus:ring-red-500':
                            form.errors.code,
                    }"
                />
            </div>
        </div>

        <div v-if="trustedDeviceEnabled" class="flex items-start space-x-2">
            <input
                id="trust_device"
                type="checkbox"
                v-model="form.trust_device"
                class="h-4 w-4 mt-0.5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="trust_device" class="text-sm text-gray-700">
                Percaya perangkat ini
                <span class="text-xs text-gray-500 block mt-0.5">
                    Anda tidak akan diminta kode verifikasi selama 30 hari
                </span>
            </label>
        </div>

        <button
            type="submit"
            :disabled="form.processing || fullCode.length !== 6 || isExpired"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
        >
            <svg
                v-if="form.processing"
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
            {{ form.processing ? "Memverifikasi..." : "Verifikasi" }}
        </button>
    </form>
</template>

<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    otpDigits: {
        type: Array,
        default: () => [],
    },
    setOtpInputRef: {
        type: Function,
        required: true,
    },
    trustedDeviceEnabled: {
        type: Boolean,
        default: false,
    },
    fullCode: {
        type: String,
        required: true,
    },
    isExpired: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["submit", "paste", "otp-input", "otp-keydown"]);
</script>
