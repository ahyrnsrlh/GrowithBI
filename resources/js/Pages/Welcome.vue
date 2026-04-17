<script setup>
import { Head } from "@inertiajs/vue3";
import BenefitsSection from "@/Components/Welcome/BenefitsSection.vue";
import DivisionsSection from "@/Components/Welcome/DivisionsSection.vue";
import FaqSection from "@/Components/Welcome/FaqSection.vue";
import FloatingNavbar from "@/Components/Welcome/FloatingNavbar.vue";
import FooterSection from "@/Components/Welcome/FooterSection.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import TestimonialsSection from "@/Components/Welcome/TestimonialsSection.vue";
import { useWelcomePage } from "@/Composables/useWelcomePage";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object,
});

const {
    mobileMenuOpen,
    divisions,
    openFaq,
    selectedFaqCategory,
    isScrolled,
    faqCategories,
    faqs,
    testimonialsData,
    currentSlide,
    totalSlides,
    activeTestimonial,
    previousTestimonial,
    nextTestimonialData,
    toggleFaq,
    selectFaqCategory,
    nextSlide,
    prevSlide,
    goToSlide,
} = useWelcomePage();
</script>

<template>
    <Head title="Welcome" />

    <FloatingNavbar
        :canLogin="props.canLogin"
        :canRegister="props.canRegister"
        :auth="props.auth"
        :isScrolled="isScrolled"
        :mobileMenuOpen="mobileMenuOpen"
        @update:mobileMenuOpen="mobileMenuOpen = $event"
    />

    <div class="bg-white relative overflow-hidden">
        <div class="absolute inset-0">
            <div
                class="absolute inset-0 opacity-50"
                style="
                    background-image:
                        linear-gradient(
                            rgba(37, 99, 235, 0.6) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            90deg,
                            rgba(37, 99, 235, 0.6) 1px,
                            transparent 1px
                        );
                    background-size: 50px 50px;
                "
            ></div>
            <div
                class="absolute inset-0 opacity-35"
                style="
                    background-image:
                        linear-gradient(
                            rgba(67, 56, 202, 0.4) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            90deg,
                            rgba(67, 56, 202, 0.4) 1px,
                            transparent 1px
                        );
                    background-size: 25px 25px;
                "
            ></div>
            <div
                class="absolute inset-0 bg-gradient-to-br from-slate-50/80 via-blue-50/75 to-indigo-100/80"
            ></div>
        </div>

        <HeroSection :canRegister="props.canRegister" />
        <BenefitsSection />
        <DivisionsSection :divisions="divisions" />
        <TestimonialsSection
            :testimonials="testimonialsData"
            :currentSlide="currentSlide"
            :totalSlides="totalSlides"
            :activeTestimonial="activeTestimonial"
            :previousTestimonial="previousTestimonial"
            :nextTestimonialData="nextTestimonialData"
            @prev-slide="prevSlide"
            @next-slide="nextSlide"
            @go-to-slide="goToSlide"
        />
        <FaqSection
            :faqCategories="faqCategories"
            :selectedFaqCategory="selectedFaqCategory"
            :openFaq="openFaq"
            :faqs="faqs"
            @select-category="selectFaqCategory"
            @toggle-faq="toggleFaq"
        />
        <FooterSection />
    </div>
</template>
