<template>
    <section id="faq" class="relative z-10 py-20 sm:py-24 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-[32px] border border-slate-200 bg-slate-50 shadow-[0_24px_80px_rgba(15,23,42,0.06)] overflow-hidden"
                data-aos="fade-up"
                data-aos-duration="800"
            >
                <div
                    class="px-6 sm:px-10 lg:px-14 pt-10 sm:pt-12 lg:pt-14 text-center"
                >
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600"
                    >
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        Tanya jawab
                    </span>
                    <h2
                        class="mt-5 text-3xl sm:text-4xl lg:text-4xl font-semibold tracking-tight text-slate-900"
                    >
                        Pertanyaan yang Sering Ditanyakan
                    </h2>
                    <p
                        class="mt-4 mx-auto max-w-3xl text-sm sm:text-base leading-7 text-slate-600"
                    >
                        Temukan jawaban untuk pertanyaan umum seputar program
                        GrowithBI, proses pendaftaran, dan hal-hal yang perlu
                        Anda siapkan.
                    </p>
                </div>

                <div
                    class="px-6 sm:px-10 lg:px-14 pb-10 sm:pb-12 lg:pb-14 mt-10"
                >
                    <div
                        class="grid gap-6 lg:grid-cols-[300px_minmax(0,1fr)] lg:items-start"
                    >
                        <aside
                            class="rounded-3xl border border-slate-200 bg-white p-5 sm:p-6 lg:sticky lg:top-24"
                            data-aos="fade-right"
                            data-aos-duration="800"
                        >
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-5 w-5 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"
                                    />
                                </svg>
                                <h3
                                    class="text-base font-semibold text-slate-900"
                                >
                                    Kategori
                                </h3>
                            </div>

                            <div class="mt-5 space-y-2">
                                <button
                                    v-for="cat in faqCategories"
                                    :key="cat.id"
                                    @click="$emit('select-category', cat.id)"
                                    :class="[
                                        'w-full rounded-2xl px-4 py-3 text-left text-sm font-medium transition-colors duration-200',
                                        selectedFaqCategory === cat.id
                                            ? 'bg-blue-600 text-white shadow-[0_10px_24px_rgba(37,99,235,0.2)]'
                                            : 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50',
                                    ]"
                                >
                                    {{ cat.name }}
                                </button>
                            </div>

                            <div
                                class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500"
                                >
                                    Bantuan cepat
                                </p>
                                <p
                                    class="mt-2 text-sm leading-6 text-slate-600"
                                >
                                    Tidak menemukan jawaban yang Anda cari?
                                    Scroll ke bawah untuk menghubungi tim kami.
                                </p>
                            </div>
                        </aside>

                        <div
                            class="space-y-4"
                            data-aos="fade-left"
                            data-aos-duration="800"
                        >
                            <div
                                v-for="(item, index) in faqs[
                                    selectedFaqCategory
                                ] || []"
                                :key="index"
                                class="rounded-3xl border border-slate-200 bg-white shadow-sm transition-shadow duration-300 hover:shadow-md overflow-hidden"
                            >
                                <button
                                    @click="$emit('toggle-faq', index)"
                                    class="flex w-full items-center justify-between gap-4 px-5 py-5 text-left sm:px-6"
                                >
                                    <span
                                        class="flex-1 text-sm sm:text-base font-semibold leading-7 text-slate-900"
                                    >
                                        {{ item.q }}
                                    </span>
                                    <span
                                        :class="[
                                            'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border transition-colors duration-200',
                                            openFaq === index
                                                ? 'border-blue-600 bg-blue-600 text-white'
                                                : 'border-slate-200 bg-slate-50 text-slate-600',
                                        ]"
                                    >
                                        <svg
                                            :class="[
                                                'h-5 w-5 transition-transform duration-300',
                                                openFaq === index &&
                                                    'rotate-180',
                                            ]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </span>
                                </button>

                                <div
                                    v-show="openFaq === index"
                                    class="border-t border-slate-100 bg-slate-50 px-5 pb-5 pt-4 sm:px-6 text-sm sm:text-base leading-7 text-slate-600"
                                >
                                    {{ item.a }}
                                </div>
                            </div>

                            <div
                                class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8"
                                data-aos="fade-up"
                            >
                                <div
                                    class="flex flex-col items-center text-center"
                                >
                                    <div
                                        class="flex h-14 w-14 items-center justify-center rounded-full border border-blue-100 bg-blue-50 text-blue-600"
                                    >
                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M12 20h.01"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="mt-4 text-lg font-semibold text-slate-900"
                                    >
                                        Masih ada pertanyaan?
                                    </h3>
                                    <p
                                        class="mt-2 max-w-xl text-sm sm:text-base leading-7 text-slate-600"
                                    >
                                        Tim GrowithBI siap membantu jika Anda
                                        butuh penjelasan tambahan terkait
                                        program, pendaftaran, atau persyaratan.
                                    </p>
                                    <a
                                        href="#contact"
                                        class="mt-5 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800"
                                    >
                                        Hubungi Kami
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
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
