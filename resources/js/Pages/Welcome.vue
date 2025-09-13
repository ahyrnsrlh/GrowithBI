<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed } from "vue";
import AOS from 'aos';

defineProps({ canLogin: Boolean, canRegister: Boolean, auth: Object });

const mobileMenuOpen = ref(false);
const divisions = ref([]);
const loadingDivisions = ref(true);
const openFaq = ref(null);
const isScrolled = ref(false);

// Handle navbar scroll effect
function handleScroll() {
    isScrolled.value = window.scrollY > 50;
}

function toggleFaq(i) {
    openFaq.value = openFaq.value === i ? null : i;
}

const fallbackDivisions = [
    {
        id: 1,
        name: "Data Analytics",
        description: "Analisis data perbankan dan visualisasi KPI.",
        is_active: true,
        quota: 5,
    },
    {
        id: 2,
        name: "Business Intelligence",
        description: "Dashboard interaktif Power BI & Tableau.",
        is_active: true,
        quota: 4,
    },
    {
        id: 3,
        name: "Risk Management",
        description: "Pemodelan risiko & analisis tren ekonomi.",
        is_active: false,
        quota: 0,
    },
    {
        id: 4,
        name: "Regulatory Compliance",
        description: "Data governance & kepatuhan regulasi.",
        is_active: true,
        quota: 3,
    },
];

const testimonials = ref([
    {
        id: 1,
        initials: "RD",
        name: "Reihan Denindra",
        company: "PT Rajawali Nusantara Indonesia (Persero)",
        tag: "Marketing & Product Development",
        tagColor: "blue",
        variant: "A",
        date: "11 September 2025",
        quote: "Program magang ini memberi lingkungan kerja suportif dan insight mendalam tentang perbankan serta business intelligence.",
    },
    {
        id: 2,
        initials: "SG",
        name: "Syarifah Geubrina Alayda",
        company: "PT Kimia Farma Tbk",
        tag: "Service & Quality Assurance",
        tagColor: "pink",
        variant: "B",
        date: "11 September 2025",
        quote: "Banyak pengalaman baru, relasi profesional, dan pelajaran profesionalisme serta tanggung jawab.",
    },
    {
        id: 3,
        initials: "MN",
        name: "M. Nailun Najib",
        company: "PT Telekomunikasi Indonesia",
        tag: "Portfolio & BizDev",
        tagColor: "green",
        variant: "C",
        date: "11 September 2025",
        quote: "Belajar teknologi, budaya kerja kolaboratif, dan membangun jaringan bernilai untuk karier masa depan.",
    },
    {
        id: 4,
        initials: "AF",
        name: "Amanda Fitria",
        company: "PT Bank Mandiri (Persero) Tbk",
        tag: "Data Analytics",
        tagColor: "blue",
        variant: "A",
        date: "10 September 2025",
        quote: "Pengalaman yang luar biasa! Saya belajar banyak tentang analisis data perbankan dan mendapat mentoring yang sangat baik.",
    },
    {
        id: 5,
        initials: "DH",
        name: "Dika Hermawan",
        company: "PT Astra International Tbk",
        tag: "Risk Management",
        tagColor: "green",
        variant: "B",
        date: "09 September 2025",
        quote: "Program ini memberikan pemahaman mendalam tentang manajemen risiko dan regulasi perbankan yang sangat berharga.",
    },
    {
        id: 6,
        initials: "NP",
        name: "Nanda Pratiwi",
        company: "PT Unilever Indonesia Tbk",
        tag: "Business Intelligence",
        tagColor: "pink",
        variant: "C",
        date: "08 September 2025",
        quote: "Sangat terkesan dengan fasilitas dan bimbingan dari mentor. Skill BI dan dashboard analytics saya meningkat drastis.",
    },
    {
        id: 7,
        initials: "AR",
        name: "Alif Rahman",
        company: "PT Pertamina (Persero)",
        tag: "Digital Transformation",
        tagColor: "blue",
        variant: "A",
        date: "07 September 2025",
        quote: "Magang di sini membuka wawasan tentang transformasi digital perbankan dan teknologi fintech terkini.",
    },
    {
        id: 8,
        initials: "LM",
        name: "Luna Maharani",
        company: "PT Indofood Sukses Makmur Tbk",
        tag: "Regulatory Compliance",
        tagColor: "green",
        variant: "B",
        date: "06 September 2025",
        quote: "Program yang sangat terstruktur dengan pembelajaran compliance dan governance yang sangat applicable di dunia kerja.",
    },
]);

// Carousel functionality for testimonials
const currentSlide = ref(0);
const itemsPerSlide = ref(3);

const totalSlides = computed(() => {
    return Math.ceil(testimonials.value.length / itemsPerSlide.value);
});

const visibleTestimonials = computed(() => {
    const start = currentSlide.value * itemsPerSlide.value;
    const end = start + itemsPerSlide.value;
    return testimonials.value.slice(start, end);
});

const nextSlide = () => {
    if (currentSlide.value < totalSlides.value - 1) {
        currentSlide.value++;
    } else {
        currentSlide.value = 0;
    }
};

const prevSlide = () => {
    if (currentSlide.value > 0) {
        currentSlide.value--;
    } else {
        currentSlide.value = totalSlides.value - 1;
    }
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

onMounted(async () => {
    // Initialize AOS
    AOS.refresh();
    
    // Add scroll event listener for navbar
    window.addEventListener("scroll", handleScroll);

    try {
        const res = await fetch("/api/divisions");
        if (res.ok) {
            const data = await res.json();
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
</script>

<template>
    <Head title="Welcome" />

    <!-- Floating Navbar -->
    <nav
        :class="[
            'fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out',
            isScrolled ? 'py-2' : 'py-4',
        ]"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                :class="[
                    'flex items-center justify-between rounded-2xl transition-all duration-300 ease-in-out backdrop-blur-md',
                    isScrolled
                        ? 'bg-gradient-to-r from-blue-800 via-blue-700 to-indigo-800 shadow-lg shadow-black/10 border border-blue-600/20 px-6 py-3'
                        : 'bg-gradient-to-r from-blue-800 via-blue-700 to-indigo-800 shadow-md shadow-black/5 border border-blue-600/30 px-8 py-4',
                ]"
            >
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img
                        src="/logo.png"
                        alt="Logo"
                        class="w-8 h-8 object-contain"
                    />
                    <div class="flex flex-col">
                        <span class="font-bold text-lg leading-none text-white"
                            >GrowithBI</span
                        >
                        <span class="text-xs text-blue-100 leading-none"
                            >Bank Indonesia Lampung</span
                        >
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a
                        href="#features"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                        >Program</a
                    >
                    <a
                        href="#testimonials"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                        >Testimoni</a
                    >
                    <a
                        href="#divisions"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                        >Divisi</a
                    >
                    <a
                        href="#faq"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                        >FAQ</a
                    >
                    <a
                        href="#contact"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                        >Kontak</a
                    >
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <Link
                        v-if="canLogin && !auth?.user"
                        :href="route('login')"
                        class="text-white hover:text-blue-200 font-semibold transition-colors duration-200"
                    >
                        Masuk
                    </Link>
                    <Link
                        v-if="canRegister && !auth?.user"
                        :href="route('register')"
                        class="px-6 py-2.5 bg-white hover:bg-gray-100 text-blue-800 font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105"
                    >
                        Daftar
                    </Link>
                    <div v-if="auth?.user" class="flex items-center space-x-3">
                        <span class="text-white font-medium">{{
                            auth.user.name
                        }}</span>
                        <Link
                            :href="route('dashboard')"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 transition-all duration-200 transform hover:scale-105"
                        >
                            Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-lg text-white hover:text-blue-200 hover:bg-blue-700/50 transition-all duration-200"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            v-if="!mobileMenuOpen"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div
                v-if="mobileMenuOpen"
                class="md:hidden mt-4 p-6 bg-white/95 backdrop-blur-md rounded-2xl shadow-lg shadow-black/10 border border-white/20"
            >
                <div class="flex flex-col space-y-4">
                    <a
                        href="#features"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                        >Program</a
                    >
                    <a
                        href="#testimonials"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                        >Testimoni</a
                    >
                    <a
                        href="#divisions"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                        >Divisi</a
                    >
                    <a
                        href="#faq"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                        >FAQ</a
                    >
                    <a
                        href="#contact"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                        >Kontak</a
                    >

                    <div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
                        <Link
                            v-if="canLogin && !auth?.user"
                            :href="route('login')"
                            @click="mobileMenuOpen = false"
                            class="block text-center py-3 text-blue-600 hover:text-blue-700 font-semibold transition-colors duration-200"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister && !auth?.user"
                            :href="route('register')"
                            @click="mobileMenuOpen = false"
                            class="block text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 transition-all duration-200"
                        >
                            Daftar Sekarang
                        </Link>
                        <div v-if="auth?.user" class="space-y-3">
                            <div class="text-center text-gray-700 font-medium">
                                {{ auth.user.name }}
                            </div>
                            <Link
                                :href="route('dashboard')"
                                @click="mobileMenuOpen = false"
                                class="block text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 transition-all duration-200"
                            >
                                Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div
        class="bg-gray-50 relative"
        style="
            background-image: radial-gradient(
                circle at 1px 1px,
                rgba(99, 102, 241, 0.1) 1px,
                transparent 0
            );
            background-size: 20px 20px;
        "
    >
        <section
            class="relative overflow-hidden pt-32 pb-16 bg-gradient-to-br from-blue-50/90 via-white/90 to-indigo-50/90"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h1
                            class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-900 mb-6"
                        >
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                                >Be The Next Future Economic & Digital
                                Talent</span
                            >
                        </h1>
                        <p class="text-lg text-gray-600 mb-8 max-w-xl">
                            Bank Indonesia Provinsi Lampung membuka peluang bagi
                            mahasiswa dan fresh graduate untuk terlibat dalam
                            proyek nyata di bidang ekonomi, keuangan, dan
                            transformasi digital.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-600/30 transition"
                                >Daftar Sekarang</Link
                            >
                            <a
                                href="#divisions"
                                class="px-8 py-4 rounded-xl border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold transition"
                                >Lihat Program</a
                            >
                        </div>
                    </div>
                    <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <div
                            class="aspect-[4/3] rounded-2xl overflow-hidden shadow-xl"
                        >
                            <img
                                src="/hero.png"
                                alt="Hero Banner"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                    <div
                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium mb-6"
                    >
                        ✨ Benefit Magang di Bank Indonesia
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                            >Apa yang Bank Indonesia Tawarkan untuk Kamu?</span
                        >
                    </h2>
                    <p
                        class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed"
                    >
                        Raih pengalaman magang terbaik dengan berbagai
                        keuntungan eksklusif yang akan mengakselerasi karier
                        Anda
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="100"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Pengalaman Kerja Nyata
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Belajar langsung dari dunia kerja profesional.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="200"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Pengembangan Skill & Kompetensi
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Asah kemampuan teknis dan soft skill untuk masa
                            depan.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="300"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Bimbingan dari Mentor Berpengalaman
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dapatkan arahan langsung dari para ahli di
                            bidangnya.
                        </p>
                    </div>

                    <!-- Benefit 4 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="400"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20a3 3 0 01-3-3v-2a3 3 0 013-3m3-3a3 3 0 110-6 3 3 0 010 6m0 3a3 3 0 110-6 3 3 0 010 6m3 3h1m-4 0h1m-4 0v-2a3 3 0 013-3m-3 3H7m3-3v3"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Jaringan & Relasi Profesional
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Bangun koneksi berharga untuk karier yang lebih
                            luas.
                        </p>
                    </div>

                    <!-- Benefit 5 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="500"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Sertifikat Magang Resmi
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Raih sertifikat resmi sebagai bukti pengalamanmu.
                        </p>
                    </div>

                    <!-- Benefit 6 -->
                    <div
                        class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/20 backdrop-blur-sm hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="600"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Uang Saku Magang
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Mendapatkan dukungan finansial berupa uang saku
                            selama program magang berlangsung.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="divisions" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                            >✨ Bidang Magang di Bank Indonesia Provinsi
                            Lampung</span
                        >
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Temukan Divisi yang Sesuai dengan Minat dan Keahlianmu
                    </p>
                </div>
                <div
                    v-if="divisions.length"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="(division, index) in divisions"
                        :key="division.id"
                        class="group bg-white rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100 overflow-hidden"
                        data-aos="zoom-in" 
                        data-aos-duration="600" 
                        :data-aos-delay="index * 100"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-center mb-4">
                                <div
                                    class="w-32 h-32 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                                >
                                    <img
                                        src="/storage/BI_Logo.png"
                                        alt="Bank Indonesia Logo"
                                        class="w-full h-full object-contain"
                                    />
                                </div>
                            </div>
                            <h3
                                class="text-lg font-bold text-gray-900 mb-2 text-center leading-tight"
                            >
                                {{ division.name }}
                            </h3>
                            <p
                                class="text-gray-600 mb-4 text-center leading-relaxed text-sm line-clamp-3"
                            >
                                {{ division.description }}
                            </p>
                            <div class="flex justify-center mb-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-xs font-medium',
                                        division.is_active
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-600',
                                    ]"
                                    >{{
                                        division.is_active
                                            ? "Tersedia"
                                            : "Tidak Tersedia"
                                    }}</span
                                >
                            </div>
                        </div>
                        <div
                            class="px-6 pb-6 border-t border-gray-50 bg-gray-50/30"
                        >
                            <div
                                class="flex items-center justify-center gap-4 mb-4 pt-4 text-xs text-gray-600"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        /></svg
                                    ><span class="font-medium">{{
                                        division.quota
                                    }}</span
                                    >&nbsp;posisi
                                </div>
                                <div class="flex items-center">
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        /></svg
                                    ><span class="font-medium">6</span
                                    >&nbsp;bulan
                                </div>
                            </div>
                            <Link
                                :href="
                                    route('public.division.detail', division.id)
                                "
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg font-medium text-center transition block text-sm hover:shadow-md"
                                >Lihat Detail</Link
                            >
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="n in 8"
                        :key="n"
                        class="bg-white rounded-xl shadow-md border border-gray-100 p-6 animate-pulse"
                    >
                        <div
                            class="w-16 h-16 bg-gray-200 rounded-lg mb-4 mx-auto"
                        ></div>
                        <div class="h-5 bg-gray-200 rounded mb-3"></div>
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded mb-6"></div>
                        <div class="h-8 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                            >Apa Kata Mereka?</span
                        >
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Cerita peserta tentang pengalaman mereka mengikuti
                        program.
                    </p>
                </div>

                <!-- Carousel Container -->
                <div class="relative" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <!-- Left Arrow -->
                    <button
                        @click="prevSlide"
                        class="absolute left-0 top-1/3 -translate-y-1/2 -translate-x-8 z-10 w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-lg border border-blue-200 flex items-center justify-center text-white transition-all duration-200 hover:scale-110"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                    </button>

                    <!-- Right Arrow -->
                    <button
                        @click="nextSlide"
                        class="absolute right-0 top-1/3 -translate-y-1/2 translate-x-8 z-10 w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-lg border border-blue-200 flex items-center justify-center text-white transition-all duration-200 hover:scale-110"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>

                    <!-- Testimonials Grid -->
                    <div class="overflow-hidden px-12">
                        <div
                            class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 transition-transform duration-500 ease-in-out mb-12"
                        >
                            <div
                                v-for="t in visibleTestimonials"
                                :key="t.id"
                                class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 flex flex-col"
                            >
                                <div class="flex items-center mb-6">
                                    <div
                                        :class="[
                                            'w-14 h-14 rounded-full flex items-center justify-center mr-4 font-bold text-white shadow-md',
                                            t.tagColor === 'blue'
                                                ? 'bg-gradient-to-br from-blue-500 to-indigo-600'
                                                : t.tagColor === 'pink'
                                                ? 'bg-gradient-to-br from-pink-500 to-rose-500'
                                                : t.tagColor === 'green'
                                                ? 'bg-gradient-to-br from-green-500 to-emerald-600'
                                                : 'bg-gray-500',
                                        ]"
                                    >
                                        {{ t.initials }}
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="font-semibold text-gray-900 leading-tight"
                                        >
                                            {{ t.name }}
                                        </h4>
                                        <p
                                            class="text-xs text-gray-600 font-medium"
                                        >
                                            {{ t.company }}
                                        </p>
                                        <p
                                            :class="[
                                                'mt-1 text-[11px] inline-block px-2 py-1 rounded-full font-medium',
                                                t.tagColor === 'blue'
                                                    ? 'bg-blue-50 text-blue-600'
                                                    : t.tagColor === 'pink'
                                                    ? 'bg-pink-50 text-pink-600'
                                                    : t.tagColor === 'green'
                                                    ? 'bg-green-50 text-green-600'
                                                    : 'bg-gray-100 text-gray-600',
                                            ]"
                                        >
                                            {{ t.tag }}
                                        </p>
                                        <p
                                            class="text-[11px] text-gray-400 mt-1"
                                        >
                                            {{ t.date }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mb-5 flex text-yellow-400">
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
                                </div>
                                <p
                                    class="text-gray-700 leading-relaxed text-sm flex-1"
                                >
                                    "{{ t.quote }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Dots -->
                    <div class="flex justify-center mt-8 space-x-2">
                        <button
                            v-for="(slide, index) in totalSlides"
                            :key="index"
                            @click="goToSlide(index)"
                            :class="[
                                'w-3 h-3 rounded-full transition-all duration-200',
                                currentSlide === index
                                    ? 'bg-blue-600 scale-110'
                                    : 'bg-gray-300 hover:bg-blue-300',
                            ]"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold mb-4">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                            >Pertanyaan yang Sering Ditanyakan</span
                        >
                    </h2>
                    <p class="text-lg text-gray-600">
                        Beberapa hal yang sering ditanyakan peserta.
                    </p>
                </div>
                <div class="space-y-4">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="100"
                    >
                        <button
                            @click="toggleFaq(1)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900"
                                >Bagaimana cara mendaftar?</span
                            >
                            <i
                                :class="[
                                    'fas text-sm',
                                    openFaq === 1
                                        ? 'fa-minus text-blue-600'
                                        : 'fa-plus text-gray-400',
                                ]"
                            ></i>
                        </button>
                        <div
                            v-show="openFaq === 1"
                            class="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                        >
                            Daftar melalui halaman program, pilih divisi, isi
                            formulir, unggah dokumen, dan pantau status.
                        </div>
                    </div>
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="200"
                    >
                        <button
                            @click="toggleFaq(2)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900"
                                >Dokumen apa saja yang dibutuhkan?</span
                            >
                            <i
                                :class="[
                                    'fas text-sm',
                                    openFaq === 2
                                        ? 'fa-minus text-blue-600'
                                        : 'fa-plus text-gray-400',
                                ]"
                            ></i>
                        </button>
                        <div
                            v-show="openFaq === 2"
                            class="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                        >
                            Surat pengantar kampus, CV, motivation letter,
                            transkrip nilai, dan KTP.
                        </div>
                    </div>
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="300"
                    >
                        <button
                            @click="toggleFaq(3)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900"
                                >Berapa lama durasi magang?</span
                            >
                            <i
                                :class="[
                                    'fas text-sm',
                                    openFaq === 3
                                        ? 'fa-minus text-blue-600'
                                        : 'fa-plus text-gray-400',
                                ]"
                            ></i>
                        </button>
                        <div
                            v-show="openFaq === 3"
                            class="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                        >
                            Umumnya 3–6 bulan, bisa disesuaikan kebutuhan
                            akademik/project.
                        </div>
                    </div>
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="400"
                    >
                        <button
                            @click="toggleFaq(4)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900"
                                >Apakah ada kompensasi atau gaji selama
                                magang?</span
                            >
                            <i
                                :class="[
                                    'fas text-sm',
                                    openFaq === 4
                                        ? 'fa-minus text-blue-600'
                                        : 'fa-plus text-gray-400',
                                ]"
                            ></i>
                        </button>
                        <div
                            v-show="openFaq === 4"
                            class="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                        >
                            Ya, peserta magang akan mendapatkan uang
                            saku/transportasi sesuai dengan ketentuan yang
                            berlaku di Bank Indonesia.
                        </div>
                    </div>
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="500"
                    >
                        <button
                            @click="toggleFaq(5)"
                            class="w-full px-6 py-5 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900"
                                >Bagaimana proses seleksi dan kapan
                                pengumumannya?</span
                            >
                            <i
                                :class="[
                                    'fas text-sm',
                                    openFaq === 5
                                        ? 'fa-minus text-blue-600'
                                        : 'fa-plus text-gray-400',
                                ]"
                            ></i>
                        </button>
                        <div
                            v-show="openFaq === 5"
                            class="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                        >
                            Seleksi melalui tahap administratif, wawancara, dan
                            tes kemampuan. Pengumuman hasil seleksi akan
                            diinformasikan melalui email dan website resmi dalam
                            2-3 minggu setelah pendaftaran ditutup.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer
            id="contact"
            class="bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900 text-white py-16"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2" data-aos="fade-right" data-aos-duration="800">
                        <div class="flex items-center mb-6">
                            <img
                                src="/logo.png"
                                alt="GrowithBI Logo"
                                class="w-12 h-12 mr-3"
                            />
                            <h3 class="text-2xl font-bold text-white">
                                GrowithBI
                            </h3>
                        </div>
                        <p class="text-blue-100 mb-6 max-w-md">
                            Program magang Bank Indonesia KPW Lampung untuk
                            mengembangkan talenta Business Intelligence & Data
                            Analytics.
                        </p>
                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-10 h-10 bg-blue-700 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors shadow-md"
                                ><svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"
                                    /></svg
                            ></a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-blue-700 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors shadow-md"
                                ><svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                                    /></svg
                            ></a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-blue-700 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors shadow-md"
                                ><svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.84-.282 1.073-1.045 2.414-1.565 3.235C9.329 23.651 10.646 24.013 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"
                                    /></svg
                            ></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-6 text-white">
                            Program
                        </h4>
                        <ul class="space-y-3 text-blue-100">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Data Analytics</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Business Intelligence</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Data Science</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Machine Learning</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-6 text-white">
                            Kontak
                        </h4>
                        <ul class="space-y-3 text-blue-100 text-sm">
                            <li class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3 text-blue-200"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    /></svg
                                >growithbi@bi.go.id
                            </li>
                            <li class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3 text-blue-200"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    /></svg
                                >(0721) 123 4567
                            </li>
                            <li class="flex items-start">
                                <svg
                                    class="w-5 h-5 mr-3 mt-1 text-blue-200"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    /></svg
                                >Bank Indonesia KPW Lampung<br />Jl. Wolter
                                Monginsidi No. 2<br />Bandar Lampung 35115
                            </li>
                        </ul>
                    </div>
                </div>
                <div
                    class="border-t border-blue-700 mt-12 pt-8 text-center text-blue-200 text-sm"
                >
                    &copy; 2025 GrowithBI - Bank Indonesia KPW Lampung. All
                    rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-12px);
    }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
