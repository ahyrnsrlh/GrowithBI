<template>
    <section
        id="faq"
        class="py-24 bg-gradient-to-b from-white to-blue-50/30 relative z-10"
    >
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="text-center mb-16"
                data-aos="fade-up"
                data-aos-duration="800"
            >
                <div
                    class="inline-flex items-center gap-3 bg-blue-100/80 backdrop-blur-sm border border-blue-200/50 text-blue-700 px-6 py-3 rounded-full text-sm font-semibold mb-6 shadow-lg"
                >
                    <div
                        class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"
                    ></div>
                    Tanya Jawab
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    <span class="text-gray-900">Pertanyaan yang </span>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"
                    >
                        Sering Ditanyakan
                    </span>
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-light"
                >
                    Temukan jawaban untuk pertanyaan-pertanyaan umum tentang
                    GrowithBI dan bagaimana program ini dapat menguntungkan
                    karir Anda.
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div
                    class="md:col-span-1"
                    data-aos="fade-right"
                    data-aos-duration="800"
                >
                    <div
                        class="bg-white/80 backdrop-blur-lg border border-white/40 rounded-2xl shadow-lg p-6 sticky top-24"
                    >
                        <h3
                            class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"
                                ></path>
                            </svg>
                            Kategori
                        </h3>
                        <div class="space-y-2">
                            <button
                                v-for="cat in faqCategories"
                                :key="cat.id"
                                @click="$emit('select-category', cat.id)"
                                :class="[
                                    'w-full text-left px-4 py-3 rounded-lg font-medium transition-all duration-300 text-sm',
                                    selectedFaqCategory === cat.id
                                        ? 'bg-blue-600 text-white shadow-md'
                                        : 'text-gray-700 hover:bg-blue-50 border border-transparent hover:border-blue-200',
                                ]"
                            >
                                {{ cat.name }}
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="md:col-span-3 space-y-4"
                    data-aos="fade-left"
                    data-aos-duration="800"
                >
                    <div
                        v-for="(item, index) in faqs[selectedFaqCategory]"
                        :key="index"
                        class="group bg-white/80 backdrop-blur-lg border border-white/40 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden"
                    >
                        <button
                            @click="$emit('toggle-faq', index)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-blue-50/50 transition-colors duration-300"
                        >
                            <span
                                class="font-semibold text-gray-900 text-base group-hover:text-blue-700 transition-colors duration-300 flex-1 pr-4"
                            >
                                {{ item.q }}
                            </span>
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 group-hover:bg-blue-200 transition-all duration-300 flex-shrink-0"
                            >
                                <svg
                                    :class="[
                                        'w-5 h-5 text-blue-600 transition-transform duration-300',
                                        openFaq === index && 'rotate-180',
                                    ]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div
                            v-show="openFaq === index"
                            class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/30 animate-in fade-in-50 duration-300"
                        >
                            {{ item.a }}
                        </div>
                    </div>

                    <div
                        class="mt-8 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 text-center"
                        data-aos="fade-up"
                    >
                        <div class="mb-4">
                            <svg
                                class="w-12 h-12 text-blue-600 mx-auto"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2">
                            Masih punya pertanyaan?
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Tidak menemukan jawaban yang Anda cari? Tim kami
                            siap membantu Anda.
                        </p>
                        <a
                            href="#contact"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300 text-sm"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                                ></path>
                                <path
                                    d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                                ></path>
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    faqCategories: { type: Array, default: () => [] },
    selectedFaqCategory: { type: String, default: "umum" },
    openFaq: { type: Number, default: null },
    faqs: { type: Object, required: true },
});

defineEmits(["select-category", "toggle-faq"]);
</script>
