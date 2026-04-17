<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    userEmail: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["close"]);
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 transform transition-all animate-[fadeInScale_0.3s_ease-out] relative overflow-hidden"
        >
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-green-50 opacity-50"
            ></div>

            <div class="relative z-10">
                <div class="text-center mb-6">
                    <div
                        class="mx-auto w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-4 shadow-lg"
                    >
                        <svg
                            class="w-10 h-10 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="3"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Berhasil! 🎉
                    </h3>

                    <p class="text-gray-600 text-sm leading-relaxed">
                        Akun Anda telah dibuat
                    </p>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-4 mb-6 shadow-sm"
                >
                    <div class="text-center">
                        <p
                            class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1"
                        >
                            Email Terdaftar
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">
                            {{ userEmail }}
                        </p>
                    </div>
                </div>

                <button
                    @click="emit('close')"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    Lanjut ke Login
                </button>

                <p
                    class="text-xs text-gray-400 text-center mt-4 flex items-center justify-center"
                >
                    <svg
                        class="w-3 h-3 mr-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Otomatis dialihkan dalam 3 detik
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
