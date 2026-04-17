<template>
    <section id="testimonials" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="relative overflow-hidden rounded-[2rem] border border-blue-200/60 bg-gradient-to-br from-blue-50 via-white to-indigo-50 px-5 py-10 sm:px-10 lg:px-14 lg:py-14 shadow-[0_30px_80px_rgba(30,64,175,0.12)]"
                data-aos="fade-up"
                data-aos-duration="900"
            >
                <div
                    class="absolute -top-24 -left-24 w-64 h-64 rounded-full bg-blue-300/35 blur-3xl"
                ></div>
                <div
                    class="absolute -bottom-24 -right-20 w-72 h-72 rounded-full bg-indigo-300/25 blur-3xl"
                ></div>

                <div class="relative text-center">
                    <span
                        class="inline-flex items-center rounded-full border border-blue-200/70 bg-white/80 px-4 py-1 text-[11px] tracking-[0.16em] text-blue-700 uppercase mb-5"
                    >
                        Cerita Alumni
                    </span>
                    <h2
                        class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 leading-tight"
                    >
                        Apa Kata Mereka?
                    </h2>
                    <p
                        class="mt-4 text-slate-600 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed"
                    >
                        Pengalaman nyata peserta GrowithBI saat belajar,
                        berkontribusi, dan berkembang bersama Bank Indonesia
                        Provinsi Lampung.
                    </p>
                </div>

                <div class="relative mt-8 sm:mt-10">
                    <div
                        class="flex items-center justify-start sm:justify-center gap-3 sm:gap-4 overflow-x-auto pb-2"
                    >
                        <button
                            v-for="(t, index) in testimonials"
                            :key="t.id"
                            @click="$emit('go-to-slide', index)"
                            :class="[
                                'relative flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 transition-all duration-300',
                                currentSlide === index
                                    ? 'border-blue-300 scale-110 shadow-[0_0_0_4px_rgba(59,130,246,0.25)]'
                                    : 'border-blue-200 opacity-80 hover:opacity-100',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-full h-full rounded-full flex items-center justify-center text-xs sm:text-sm font-bold text-white',
                                    t.tagColor === 'blue'
                                        ? 'bg-gradient-to-br from-blue-500 to-indigo-600'
                                        : t.tagColor === 'pink'
                                          ? 'bg-gradient-to-br from-fuchsia-500 to-rose-500'
                                          : 'bg-gradient-to-br from-emerald-500 to-teal-600',
                                ]"
                            >
                                {{ t.initials }}
                            </div>
                        </button>
                    </div>
                </div>

                <div
                    class="relative mt-8 grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch"
                >
                    <div
                        v-if="previousTestimonial"
                        class="hidden lg:flex flex-col rounded-2xl border border-slate-200 bg-white/70 p-5 opacity-70"
                    >
                        <p
                            class="text-xs uppercase tracking-wide text-blue-600/80 mb-3"
                        >
                            Kategori
                        </p>
                        <h3 class="text-slate-800 font-semibold">
                            {{ previousTestimonial.tag }}
                        </h3>
                        <p class="text-slate-500 text-sm mt-1">
                            {{ previousTestimonial.name }}
                        </p>
                        <p class="text-slate-600 text-sm mt-5 line-clamp-4">
                            "{{ previousTestimonial.quote }}"
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-blue-300/60 bg-white p-5 sm:p-6 shadow-[0_12px_40px_rgba(30,64,175,0.15)]"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    :class="[
                                        'w-12 h-12 rounded-full flex items-center justify-center text-white font-bold',
                                        activeTestimonial.tagColor === 'blue'
                                            ? 'bg-gradient-to-br from-blue-500 to-indigo-600'
                                            : activeTestimonial.tagColor ===
                                                'pink'
                                              ? 'bg-gradient-to-br from-fuchsia-500 to-rose-500'
                                              : 'bg-gradient-to-br from-emerald-500 to-teal-600',
                                    ]"
                                >
                                    {{ activeTestimonial.initials }}
                                </div>
                                <div>
                                    <h4
                                        class="text-slate-900 font-semibold leading-tight"
                                    >
                                        {{ activeTestimonial.name }}
                                    </h4>
                                    <p class="text-slate-500 text-sm">
                                        {{ activeTestimonial.company }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="text-[11px] px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-700 font-medium"
                            >
                                {{ activeTestimonial.tag }}
                            </span>
                        </div>

                        <div
                            class="mt-5 flex items-center gap-1 text-amber-300"
                        >
                            <svg
                                v-for="n in 5"
                                :key="n"
                                class="w-4 h-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                />
                            </svg>
                            <span
                                class="text-slate-800 text-sm font-semibold ml-1"
                            >
                                5.0
                            </span>
                        </div>

                        <p
                            class="mt-4 text-slate-700 text-sm sm:text-base leading-relaxed"
                        >
                            "{{ activeTestimonial.quote }}"
                        </p>

                        <p class="mt-4 text-xs text-slate-500">
                            {{ activeTestimonial.date }}
                        </p>
                    </div>

                    <div
                        v-if="nextTestimonialData"
                        class="hidden lg:flex flex-col rounded-2xl border border-slate-200 bg-white/70 p-5 opacity-70"
                    >
                        <p
                            class="text-xs uppercase tracking-wide text-blue-600/80 mb-3"
                        >
                            Kategori
                        </p>
                        <h3 class="text-slate-800 font-semibold">
                            {{ nextTestimonialData.tag }}
                        </h3>
                        <p class="text-slate-500 text-sm mt-1">
                            {{ nextTestimonialData.name }}
                        </p>
                        <p class="text-slate-600 text-sm mt-5 line-clamp-4">
                            "{{ nextTestimonialData.quote }}"
                        </p>
                    </div>
                </div>

                <div
                    class="relative mt-8 flex items-center justify-center gap-3"
                >
                    <button
                        @click="$emit('prev-slide')"
                        class="w-11 h-11 rounded-xl border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition-all duration-300"
                    >
                        <svg
                            class="w-5 h-5 mx-auto"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>
                    <button
                        @click="$emit('next-slide')"
                        class="w-11 h-11 rounded-xl border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition-all duration-300"
                    >
                        <svg
                            class="w-5 h-5 mx-auto"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>

                <div class="relative mt-5 flex justify-center gap-2">
                    <button
                        v-for="(_, index) in totalSlides"
                        :key="index"
                        @click="$emit('go-to-slide', index)"
                        :class="[
                            'h-2 rounded-full transition-all duration-300',
                            currentSlide === index
                                ? 'w-8 bg-blue-300'
                                : 'w-2 bg-blue-200 hover:bg-blue-300',
                        ]"
                    ></button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    testimonials: { type: Array, default: () => [] },
    currentSlide: { type: Number, default: 0 },
    totalSlides: { type: Number, default: 0 },
    activeTestimonial: { type: Object, required: true },
    previousTestimonial: { type: Object, default: null },
    nextTestimonialData: { type: Object, default: null },
});

defineEmits(["prev-slide", "next-slide", "go-to-slide"]);
</script>
