import { ref, computed, onMounted, onUnmounted } from "vue";
import AOS from "aos";
import {
    faqCategories,
    faqs,
    fallbackDivisions,
    testimonials,
} from "@/Constants/welcomePageData";

export function useWelcomePage() {
    const mobileMenuOpen = ref(false);
    const divisions = ref([]);
    const loadingDivisions = ref(true);
    const openFaq = ref(null);
    const selectedFaqCategory = ref("umum");
    const isScrolled = ref(false);

    const testimonialsData = ref(testimonials);
    const currentSlide = ref(0);

    const handleScroll = () => {
        isScrolled.value = window.scrollY > 50;
    };

    const toggleFaq = (index) => {
        openFaq.value = openFaq.value === index ? null : index;
    };

    const selectFaqCategory = (categoryId) => {
        selectedFaqCategory.value = categoryId;
        openFaq.value = null;
    };

    const totalSlides = computed(() => testimonialsData.value.length);

    const activeTestimonial = computed(
        () =>
            testimonialsData.value[currentSlide.value] ||
            testimonialsData.value[0],
    );

    const previousTestimonial = computed(() => {
        const length = testimonialsData.value.length;
        if (!length) {
            return null;
        }
        const prevIndex = (currentSlide.value - 1 + length) % length;
        return testimonialsData.value[prevIndex];
    });

    const nextTestimonialData = computed(() => {
        const length = testimonialsData.value.length;
        if (!length) {
            return null;
        }
        const nextIndex = (currentSlide.value + 1) % length;
        return testimonialsData.value[nextIndex];
    });

    const nextSlide = () => {
        const length = testimonialsData.value.length;
        if (!length) {
            return;
        }
        currentSlide.value = (currentSlide.value + 1) % length;
    };

    const prevSlide = () => {
        const length = testimonialsData.value.length;
        if (!length) {
            return;
        }
        currentSlide.value = (currentSlide.value - 1 + length) % length;
    };

    const goToSlide = (index) => {
        const length = testimonialsData.value.length;
        if (!length) {
            return;
        }
        currentSlide.value = ((index % length) + length) % length;
    };

    onMounted(async () => {
        AOS.refresh();
        window.addEventListener("scroll", handleScroll);

        try {
            const response = await fetch("/api/divisions");
            if (response.ok) {
                const data = await response.json();
                divisions.value = data?.divisions || data || fallbackDivisions;
            } else {
                divisions.value = fallbackDivisions;
            }
        } catch {
            divisions.value = fallbackDivisions;
        } finally {
            loadingDivisions.value = false;
        }
    });

    onUnmounted(() => {
        window.removeEventListener("scroll", handleScroll);
    });

    return {
        mobileMenuOpen,
        divisions,
        loadingDivisions,
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
    };
}
