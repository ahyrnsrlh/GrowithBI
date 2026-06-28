<template>
    <section id="testimonials" class="py-20 sm:py-24 lg:py-28 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-[32px] border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)] overflow-hidden"
                data-aos="fade-up"
                data-aos-duration="900"
            >
                <div class="px-6 sm:px-10 lg:px-14 pt-10 sm:pt-12 lg:pt-14">
                    <div class="max-w-3xl mx-auto text-center">
                        <span
                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-1.5 text-[11px] font-semibold tracking-[0.18em] text-slate-600 uppercase"
                        >
                            Cerita alumni
                        </span>
                        <h2
                            class="mt-3 text-1xl sm:text-4xl lg:text-4xl font-semibold tracking-tight text-slate-900"
                        >
                            Apa Kata Mereka?
                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base leading-7 text-slate-600"
                        >
                            Pengalaman nyata peserta GrowithBI saat belajar,
                            berkontribusi, dan berkembang bersama Bank Indonesia
                            Provinsi Lampung.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Alumni stories
                            </p>
                            <p
                                class="mt-2 text-2xl font-semibold text-slate-900"
                            >
                                {{ testimonials.length }}+
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Average rating
                            </p>
                            <p
                                class="mt-2 text-2xl font-semibold text-slate-900"
                            >
                                5.0/5
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Divisions featured
                            </p>
                            <p
                                class="mt-2 text-2xl font-semibold text-slate-900"
                            >
                                4+
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Mentored experience
                            </p>
                            <p
                                class="mt-2 text-2xl font-semibold text-slate-900"
                            >
                                Real-time
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="px-6 sm:px-10 lg:px-14 pb-10 sm:pb-12 lg:pb-14 mt-10"
                >
                    <div class="flex items-center justify-center">
                        <div
                            class="flex flex-wrap items-center justify-center gap-3"
                        >
                            <button
                                v-for="(t, index) in testimonials"
                                :key="t.id"
                                @click="$emit('go-to-slide', index)"
                                :class="[
                                    'group relative flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full border transition-all duration-300',
                                    currentSlide === index
                                        ? 'border-blue-600 bg-blue-600 text-white shadow-[0_10px_24px_rgba(37,99,235,0.22)] scale-105'
                                        : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50',
                                ]"
                            >
                                <span class="text-xs sm:text-sm font-semibold">
                                    {{ t.initials }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <div
                        class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-[0.9fr_1.3fr_0.9fr]"
                    >
                        <div
                            v-if="previousTestimonial"
                            class="rounded-3xl border border-slate-200 bg-slate-50 p-5 sm:p-6"
                        >
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500"
                                >
                                    Next up
                                </p>
                                <span
                                    class="text-[11px] rounded-full border border-slate-200 bg-white px-3 py-1 text-slate-500"
                                >
                                    Preview
                                </span>
                            </div>
                            <div class="mt-5 flex items-center gap-3">
                                <div
                                    :class="[
                                        'flex h-11 w-11 items-center justify-center rounded-full text-sm font-semibold text-white',
                                        getAvatarClass(
                                            previousTestimonial.tagColor,
                                        ),
                                    ]"
                                >
                                    {{ previousTestimonial.initials }}
                                </div>
                                <div>
                                    <h3
                                        class="text-sm font-semibold text-slate-900"
                                    >
                                        {{ previousTestimonial.name }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        {{ previousTestimonial.company }}
                                    </p>
                                </div>
                            </div>
                            <p
                                class="mt-4 text-sm leading-6 text-slate-600 line-clamp-6"
                            >
                                {{ previousTestimonial.quote }}
                            </p>
                        </div>

                        <div
                            class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 shadow-[0_16px_40px_rgba(15,23,42,0.06)]"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        :class="[
                                            'flex h-14 w-14 items-center justify-center rounded-2xl text-sm font-semibold text-white',
                                            getAvatarClass(
                                                activeTestimonial.tagColor,
                                            ),
                                        ]"
                                    >
                                        {{ activeTestimonial.initials }}
                                    </div>
                                    <div>
                                        <div
                                            class="flex flex-wrap items-center gap-2"
                                        >
                                            <h3
                                                class="text-lg font-semibold text-slate-900 leading-tight"
                                            >
                                                {{ activeTestimonial.name }}
                                            </h3>
                                            <span
                                                class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-medium text-slate-600"
                                            >
                                                {{ activeTestimonial.tag }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ activeTestimonial.company }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="hidden sm:flex items-center gap-1 rounded-full border border-amber-100 bg-amber-50 px-3 py-1.5 text-amber-700"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        />
                                    </svg>
                                    <span class="text-sm font-semibold"
                                        >5.0</span
                                    >
                                </div>
                            </div>

                            <blockquote
                                class="mt-6 text-base sm:text-lg leading-8 text-slate-700"
                            >
                                “{{ activeTestimonial.quote }}”
                            </blockquote>

                            <div
                                class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5"
                            >
                                <p class="text-sm text-slate-500">
                                    {{ activeTestimonial.date }}
                                </p>
                                <div
                                    class="flex items-center gap-2 text-slate-400"
                                >
                                    <span
                                        class="h-2 w-2 rounded-full bg-blue-600"
                                    ></span>
                                    <span
                                        class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500"
                                    >
                                        Alumni spotlight
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="nextTestimonialData"
                            class="rounded-3xl border border-slate-200 bg-slate-50 p-5 sm:p-6"
                        >
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500"
                                >
                                    Up next
                                </p>
                                <span
                                    class="text-[11px] rounded-full border border-slate-200 bg-white px-3 py-1 text-slate-500"
                                >
                                    Preview
                                </span>
                            </div>
                            <div class="mt-5 flex items-center gap-3">
                                <div
                                    :class="[
                                        'flex h-11 w-11 items-center justify-center rounded-full text-sm font-semibold text-white',
                                        getAvatarClass(
                                            nextTestimonialData.tagColor,
                                        ),
                                    ]"
                                >
                                    {{ nextTestimonialData.initials }}
                                </div>
                                <div>
                                    <h3
                                        class="text-sm font-semibold text-slate-900"
                                    >
                                        {{ nextTestimonialData.name }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        {{ nextTestimonialData.company }}
                                    </p>
                                </div>
                            </div>
                            <p
                                class="mt-4 text-sm leading-6 text-slate-600 line-clamp-6"
                            >
                                {{ nextTestimonialData.quote }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 flex items-center justify-center gap-3">
                        <button
                            @click="$emit('prev-slide')"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition-colors hover:border-slate-300 hover:bg-slate-50"
                            aria-label="Previous testimonial"
                        >
                            <svg
                                class="h-5 w-5"
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

                        <div
                            class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2"
                        >
                            <span class="text-xs font-medium text-slate-500">
                                {{
                                    currentSlide + 1 < 10
                                        ? "0" + (currentSlide + 1)
                                        : currentSlide + 1
                                }}
                            </span>
                            <span
                                class="h-1 w-8 rounded-full bg-slate-200"
                            ></span>
                            <span class="text-xs font-medium text-slate-500">
                                {{
                                    totalSlides < 10
                                        ? "0" + totalSlides
                                        : totalSlides
                                }}
                            </span>
                        </div>

                        <button
                            @click="$emit('next-slide')"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition-colors hover:border-slate-300 hover:bg-slate-50"
                            aria-label="Next testimonial"
                        >
                            <svg
                                class="h-5 w-5"
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

                    <div class="mt-6 flex justify-center gap-1 sm:gap-2">
                        <button
                            v-for="(_, index) in totalSlides"
                            :key="index"
                            @click="$emit('go-to-slide', index)"
                            class="p-2 min-h-[44px] min-w-[44px] flex items-center justify-center group focus:outline-none"
                            :aria-label="`Go to testimonial ${index + 1}`"
                        >
                            <span
                                :class="[
                                    'h-2.5 rounded-full transition-all duration-300',
                                    currentSlide === index
                                        ? 'w-8 bg-blue-600'
                                        : 'w-2.5 bg-slate-200 group-hover:bg-slate-300',
                                ]"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const props = defineProps({
    testimonials: { type: Array, default: () => [] },
    currentSlide: { type: Number, default: 0 },
    totalSlides: { type: Number, default: 0 },
    activeTestimonial: { type: Object, required: true },
    previousTestimonial: { type: Object, default: null },
    nextTestimonialData: { type: Object, default: null },
});

defineEmits(["prev-slide", "next-slide", "go-to-slide"]);

const getAvatarClass = (tagColor) => {
    const palette = {
        blue: "bg-blue-700",
        pink: "bg-rose-600",
        green: "bg-emerald-700",
    };

    return palette[tagColor] || "bg-slate-700";
};
</script>
