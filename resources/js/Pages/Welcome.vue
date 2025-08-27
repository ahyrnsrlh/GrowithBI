<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    auth: {
        type: Object,
        default: null,
    },
});

const mobileMenuOpen = ref(false);
const divisions = ref([]);
const openFaq = ref(null);

// FAQ toggle function
const toggleFaq = (faqId) => {
    openFaq.value = openFaq.value === faqId ? null : faqId;
};

onMounted(async () => {
    try {
        // Fetch divisions data
        const response = await fetch("/api/divisions");
        if (response.ok) {
            divisions.value = await response.json();
        }
    } catch (error) {
        console.error("Error fetching divisions:", error);
        // Set some dummy data if API fails
        divisions.value = [
            {
                id: 1,
                name: "Business Intelligence Banking",
                description:
                    "Pelajari sistem BI perbankan, dashboard analitik, dan reporting untuk mendukung pengambilan keputusan strategis Bank Indonesia.",
                icon: "fas fa-chart-line",
                quota: 8,
                is_active: true,
            },
            {
                id: 2,
                name: "Data Analytics & Research",
                description:
                    "Kuasai analisis data ekonomi dan keuangan menggunakan tools modern untuk mendukung riset kebijakan moneter.",
                icon: "fas fa-search-dollar",
                quota: 6,
                is_active: true,
            },
            {
                id: 3,
                name: "Financial Technology",
                description:
                    "Eksplorasi teknologi keuangan digital dan inovasi sistem pembayaran dalam ekosistem perbankan Indonesia.",
                icon: "fas fa-mobile-alt",
                quota: 5,
                is_active: true,
            },
        ];
    }
});
</script>

<template>
    <Head title="GrowithBI - Platform Magang Business Intelligence" />

    <div class="min-h-screen relative">
        <!-- Grid Background -->
        <div class="fixed inset-0 z-0">
            <div
                class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/50"
            ></div>
            <div
                class="absolute inset-0"
                style="
                    background-image: radial-gradient(
                        circle at 1px 1px,
                        rgba(59, 130, 246, 0.15) 1px,
                        transparent 0
                    );
                    background-size: 40px 40px;
                "
            ></div>
        </div>

        <!-- Navigation -->
        <nav
            class="relative z-50 bg-white/90 backdrop-blur-md border-b border-gray-200/50 sticky top-0"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"
                                />
                            </svg>
                        </div>
                        <h1 class="ml-3 text-xl font-bold text-gray-900">
                            GrowithBI
                        </h1>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a
                            href="#features"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >Kriteria</a
                        >
                        <a
                            href="#about"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >Benefit</a
                        >
                        <a
                            href="#divisions"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >Program</a
                        >
                        <a
                            href="#faq"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >FAQ</a
                        >
                        <a
                            href="#contact"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >Kontak</a
                        >

                        <!-- Authenticated User Menu -->
                        <div
                            v-if="auth?.user"
                            class="flex items-center space-x-4"
                        >
                            <div
                                class="flex items-center space-x-3 px-3 py-2 bg-green-50 rounded-lg border border-green-200"
                            >
                                <div
                                    class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-white text-sm font-semibold"
                                        >{{
                                            auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}</span
                                    >
                                </div>
                                <span class="text-green-700 font-medium">{{
                                    auth.user.name
                                }}</span>
                            </div>
                            <Link
                                :href="route('dashboard')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all"
                            >
                                Dashboard
                            </Link>
                        </div>

                        <!-- Guest User Menu -->
                        <div v-else class="flex items-center space-x-4">
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                            >
                                Login
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all shadow-md hover:shadow-lg"
                            >
                                Daftar Sekarang
                            </Link>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-600 hover:text-blue-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div
                    v-show="mobileMenuOpen"
                    class="md:hidden py-4 border-t border-gray-200"
                >
                    <div class="flex flex-col space-y-4">
                        <a
                            href="#features"
                            class="text-gray-600 hover:text-blue-600"
                            >Kriteria</a
                        >
                        <a
                            href="#about"
                            class="text-gray-600 hover:text-blue-600"
                            >Benefit</a
                        >
                        <a
                            href="#divisions"
                            class="text-gray-600 hover:text-blue-600"
                            >Program</a
                        >
                        <a href="#faq" class="text-gray-600 hover:text-blue-600"
                            >FAQ</a
                        >
                        <a
                            href="#contact"
                            class="text-gray-600 hover:text-blue-600"
                            >Kontak</a
                        >

                        <!-- Authenticated User Mobile Menu -->
                        <div
                            v-if="auth?.user"
                            class="pt-2 border-t border-gray-200"
                        >
                            <div class="flex items-center space-x-2 mb-4">
                                <div
                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-white text-sm font-semibold"
                                        >{{
                                            auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}</span
                                    >
                                </div>
                                <span class="text-gray-700 font-medium">{{
                                    auth.user.name
                                }}</span>
                            </div>
                            <Link
                                :href="route('profile.edit')"
                                class="block text-gray-600 hover:text-blue-600 py-2"
                            >
                                Profil Saya
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-center mt-2"
                            >
                                Logout
                            </Link>
                        </div>

                        <!-- Guest User Mobile Menu -->
                        <div
                            v-else-if="canLogin || canRegister"
                            class="flex space-x-2 pt-2"
                        >
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex-1 text-center"
                            >
                                Login
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="border border-blue-600 text-blue-600 px-4 py-2 rounded-lg flex-1 text-center"
                            >
                                Daftar
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative z-10 py-12 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight"
                    >
                        GrowithBI Program
                    </h1>
                    <h2
                        class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 mb-6"
                    >
                        Lead, Innovate, Inspire
                    </h2>
                    <p
                        class="text-base md:text-lg text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed"
                    >
                        Bentuk masa depan teknologi dan komunitas di kampus
                        kamu, diberdayakan oleh Bank Indonesia KPW Lampung.
                    </p>

                    <!-- CTA Buttons -->
                    <div
                        class="flex flex-col sm:flex-row gap-3 justify-center items-center mb-12"
                    >
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        >
                            Daftar Sekarang
                        </Link>
                        <a
                            href="#divisions"
                            class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors px-6 py-3"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                ></path>
                            </svg>
                            Lihat Program
                        </a>
                    </div>

                    <!-- Hero Image Placeholder -->
                    <div class="relative">
                        <div
                            class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-xl border border-gray-200/50"
                        >
                            <div
                                class="aspect-video bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg flex items-center justify-center"
                            >
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3"
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
                                    <h3
                                        class="text-lg font-semibold text-gray-800 mb-2"
                                    >
                                        Business Intelligence Platform
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Teknologi canggih untuk analisis data
                                        perbankan
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating decorative elements -->
                        <div
                            class="absolute -top-2 -left-2 w-6 h-6 bg-blue-400 rounded-full opacity-60 animate-bounce"
                        ></div>
                        <div
                            class="absolute -top-1 -right-3 w-4 h-4 bg-indigo-400 rounded-full opacity-50 animate-pulse"
                        ></div>
                        <div
                            class="absolute -bottom-2 left-6 w-3 h-3 bg-purple-400 rounded-full opacity-40 animate-bounce"
                            style="animation-delay: 1s"
                        ></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative z-10 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-gray-900 mb-3"
                    >
                        Mengapa Memilih GrowithBI?
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Kami menyediakan pengalaman magang yang komprehensif
                        dengan teknologi terkini
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="group p-6 bg-white/70 backdrop-blur-sm rounded-xl border border-gray-200/50 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Bimbingan Expert
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Didampingi oleh praktisi berpengalaman dari Bank
                            Indonesia dan ahli Business Intelligence terkemuka
                        </p>
                    </div>

                    <div
                        class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Project Real
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Mengerjakan project nyata dengan data perbankan dan
                            kasus bisnis sesungguhnya dari Bank Indonesia
                        </p>
                    </div>

                    <div
                        class="group p-6 bg-white/70 backdrop-blur-sm rounded-xl border border-gray-200/50 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Teknologi Modern
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Menggunakan tools terkini seperti Power BI, Tableau,
                            Python, dan SQL
                        </p>
                    </div>

                    <div
                        class="group p-6 bg-white/70 backdrop-blur-sm rounded-xl border border-gray-200/50 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Sertifikat Resmi
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Dapatkan sertifikat resmi dari Bank Indonesia yang
                            diakui industri perbankan dan keuangan
                        </p>
                    </div>

                    <div
                        class="group p-6 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20a3 3 0 01-3-3v-2a3 3 0 013-3m3-3a3 3 0 110-6 3 3 0 010 6m0 3a3 3 0 110-6 3 3 0 010 6m3 3h1m-4 0h1m-4 0v-2a3 3 0 013-3m-3 3H7m3-3v3"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Networking
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Bangun jaringan profesional dengan sesama peserta
                            dan pegawai Bank Indonesia
                        </p>
                    </div>

                    <div
                        class="group p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            Flexible Schedule
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Program magang yang fleksibel dan dapat disesuaikan
                            dengan jadwal kuliah mahasiswa
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="relative z-10 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2
                            class="text-2xl md:text-3xl font-bold text-gray-900 mb-4"
                        >
                            Tentang GrowithBI
                        </h2>
                        <p class="text-base text-gray-600 mb-4 leading-relaxed">
                            GrowithBI adalah program magang eksklusif yang
                            diselenggarakan oleh Bank Indonesia Kantor
                            Perwakilan Wilayah (KPW) Lampung. Program ini
                            dirancang khusus untuk mahasiswa yang ingin
                            mengembangkan keahlian di bidang Business
                            Intelligence dan Data Analytics.
                        </p>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed">
                            Sebagai bagian dari Bank Indonesia, kami memberikan
                            kesempatan kepada mahasiswa untuk belajar langsung
                            dari praktisi berpengalaman dan menggunakan
                            teknologi terdepan dalam industri perbankan dan
                            keuangan.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="text-center p-3 bg-white rounded-lg shadow-sm"
                            >
                                <div
                                    class="text-2xl font-bold text-blue-600 mb-1"
                                >
                                    150+
                                </div>
                                <div class="text-sm text-gray-600">Alumni</div>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg shadow-sm"
                            >
                                <div
                                    class="text-2xl font-bold text-green-600 mb-1"
                                >
                                    92%
                                </div>
                                <div class="text-sm text-gray-600">
                                    Job Placement
                                </div>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg shadow-sm"
                            >
                                <div
                                    class="text-2xl font-bold text-purple-600 mb-1"
                                >
                                    15+
                                </div>
                                <div class="text-sm text-gray-600">
                                    Partner Universitas
                                </div>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg shadow-sm"
                            >
                                <div
                                    class="text-2xl font-bold text-orange-600 mb-1"
                                >
                                    4.8
                                </div>
                                <div class="text-sm text-gray-600">
                                    Rating Program
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div
                            class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl p-6 text-white"
                        >
                            <h3 class="text-xl font-bold mb-3">Visi Kami</h3>
                            <p class="text-blue-100 mb-4 text-sm">
                                Menjadi program magang unggulan Bank Indonesia
                                yang menghasilkan talenta-talenta terbaik di
                                bidang Business Intelligence dan Data Analytics
                                untuk mendukung transformasi digital sektor
                                perbankan.
                            </p>
                            <h3 class="text-xl font-bold mb-3">Misi Kami</h3>
                            <ul class="text-blue-100 space-y-1 text-sm">
                                <li class="flex items-start">
                                    <span class="text-yellow-400 mr-2 text-xs"
                                        >•</span
                                    >
                                    Memberikan pelatihan berkualitas tinggi
                                    sesuai standar Bank Indonesia
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-400 mr-2 text-xs"
                                        >•</span
                                    >
                                    Mengembangkan SDM yang kompeten di bidang
                                    data analytics
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-400 mr-2">•</span>
                                    Mendukung inovasi teknologi di sektor
                                    perbankan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Divisions Section -->
        <section id="divisions" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-gray-900 mb-3"
                    >
                        Program Magang Kami
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Pilih program yang sesuai dengan minat dan tujuan karir
                        Anda
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                    v-if="divisions.length > 0"
                >
                    <div
                        v-for="division in divisions"
                        :key="division.id"
                        class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden"
                    >
                        <!-- Card Header -->
                        <div class="p-6">
                            <div class="flex items-center justify-center mb-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                                >
                                    <i
                                        v-if="division.icon"
                                        :class="
                                            division.icon +
                                            ' text-white text-lg'
                                        "
                                    ></i>
                                    <svg
                                        v-else
                                        class="w-6 h-6 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM21 17a2 2 0 11-4 0 2 2 0 014 0z M8 12h8l-1-9H9l-1 9z"
                                        />
                                    </svg>
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
                                >
                                    {{
                                        division.is_active
                                            ? "Tersedia"
                                            : "Tidak Tersedia"
                                    }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div
                            class="px-6 pb-6 border-t border-gray-50 bg-gray-50/30"
                        >
                            <div
                                class="flex items-center justify-center gap-4 mb-4 pt-4"
                            >
                                <div
                                    class="text-xs text-gray-600 flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                    <span class="font-medium">{{
                                        division.quota
                                    }}</span>
                                    posisi
                                </div>
                                <div
                                    class="text-xs text-gray-600 flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <span class="font-medium">6</span> bulan
                                </div>
                            </div>

                            <Link
                                :href="
                                    route('public.division.detail', division.id)
                                "
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg font-medium text-center transition-all duration-200 block text-sm hover:shadow-md"
                            >
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Loading state -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
                >
                    <div
                        v-for="n in 8"
                        :key="n"
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 animate-pulse"
                    >
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-gray-300 rounded-2xl mb-6"
                            ></div>
                            <div class="h-6 bg-gray-300 rounded mb-3"></div>
                            <div class="h-4 bg-gray-300 rounded mb-2"></div>
                            <div class="h-4 bg-gray-300 rounded mb-6"></div>
                            <div class="h-6 bg-gray-300 rounded mb-6"></div>
                        </div>
                        <div
                            class="px-8 pb-8 pt-4 border-t border-gray-100 bg-gray-50/50"
                        >
                            <div class="flex justify-between mb-4">
                                <div class="h-4 bg-gray-300 rounded w-20"></div>
                                <div class="h-4 bg-gray-300 rounded w-16"></div>
                            </div>
                            <div class="h-10 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Bergabunglah dengan mahasiswa-mahasiswa terbaik Lampung
                    dalam program magang eksklusif Bank Indonesia
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="bg-white hover:bg-gray-100 text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg"
                    >
                        🚀 Daftar Magang
                    </Link>
                    <a
                        href="#divisions"
                        class="border-2 border-white hover:bg-white hover:text-blue-600 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all"
                    >
                        📋 Lihat Program
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-20 bg-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div
                    class="absolute inset-0"
                    style="
                        background-image: linear-gradient(
                                rgba(59, 130, 246, 0.1) 1px,
                                transparent 1px
                            ),
                            linear-gradient(
                                90deg,
                                rgba(59, 130, 246, 0.1) 1px,
                                transparent 1px
                            );
                        background-size: 60px 60px;
                    "
                ></div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-full mb-6"
                    >
                        <i
                            class="fas fa-question-circle text-blue-600 mr-2"
                        ></i>
                        <span class="text-blue-600 font-semibold text-sm"
                            >FAQ</span
                        >
                    </div>
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Pertanyaan yang Sering Diajukan
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Temukan jawaban untuk pertanyaan-pertanyaan umum seputar
                        program magang Bank Indonesia
                    </p>
                </div>

                <!-- FAQ Accordion -->
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow"
                    >
                        <button
                            @click="toggleFaq(1)"
                            class="w-full px-6 py-5 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-lg font-semibold text-gray-900 pr-4"
                                >
                                    Bagaimana cara mendaftar program magang di
                                    Bank Indonesia?
                                </h3>
                                <div class="flex-shrink-0">
                                    <i
                                        :class="[
                                            'fas transition-transform duration-300',
                                            openFaq === 1
                                                ? 'fa-minus text-blue-600 transform rotate-0'
                                                : 'fa-plus text-gray-400',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </button>
                        <div
                            :class="[
                                'overflow-hidden transition-all duration-300 ease-in-out',
                                openFaq === 1
                                    ? 'max-h-96 opacity-100'
                                    : 'max-h-0 opacity-0',
                            ]"
                        >
                            <div
                                class="px-6 pb-5 text-gray-600 leading-relaxed"
                            >
                                <p class="mb-3">
                                    Untuk mendaftar program magang di Bank
                                    Indonesia, Anda dapat mengikuti
                                    langkah-langkah berikut:
                                </p>
                                <ol
                                    class="list-decimal list-inside space-y-2 ml-4"
                                >
                                    <li>Kunjungi website resmi GrowithBI</li>
                                    <li>
                                        Pilih divisi yang sesuai dengan minat
                                        dan bidang studi Anda
                                    </li>
                                    <li>
                                        Klik tombol "Daftar Sekarang" pada
                                        program yang diminati
                                    </li>
                                    <li>
                                        Lengkapi formulir pendaftaran dengan
                                        data yang akurat
                                    </li>
                                    <li>
                                        Upload dokumen persyaratan yang
                                        diperlukan
                                    </li>
                                    <li>
                                        Submit aplikasi dan tunggu konfirmasi
                                        dari tim kami
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow"
                    >
                        <button
                            @click="toggleFaq(2)"
                            class="w-full px-6 py-5 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-lg font-semibold text-gray-900 pr-4"
                                >
                                    Apa saja dokumen yang perlu dipersiapkan?
                                </h3>
                                <div class="flex-shrink-0">
                                    <i
                                        :class="[
                                            'fas transition-transform duration-300',
                                            openFaq === 2
                                                ? 'fa-minus text-blue-600 transform rotate-0'
                                                : 'fa-plus text-gray-400',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </button>
                        <div
                            :class="[
                                'overflow-hidden transition-all duration-300 ease-in-out',
                                openFaq === 2
                                    ? 'max-h-96 opacity-100'
                                    : 'max-h-0 opacity-0',
                            ]"
                        >
                            <div
                                class="px-6 pb-5 text-gray-600 leading-relaxed"
                            >
                                <p class="mb-3">
                                    Dokumen yang wajib disiapkan untuk mendaftar
                                    program magang:
                                </p>
                                <ul
                                    class="list-disc list-inside space-y-2 ml-4"
                                >
                                    <li>
                                        <strong>Surat Pengantar</strong> dari
                                        universitas/institut
                                    </li>
                                    <li>
                                        <strong>Curriculum Vitae (CV)</strong>
                                        terbaru dan lengkap
                                    </li>
                                    <li>
                                        <strong>Motivation Letter</strong> yang
                                        menjelaskan alasan dan tujuan magang
                                    </li>
                                    <li>
                                        <strong>Transkrip Nilai</strong> resmi
                                        dari universitas
                                    </li>
                                    <li>
                                        <strong
                                            >Kartu Tanda Penduduk (KTP)</strong
                                        >
                                    </li>
                                </ul>
                                <p class="mt-3 text-sm text-blue-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Semua dokumen harus dalam format PDF, JPG,
                                    atau PNG dengan ukuran maksimal 5MB per
                                    file.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow"
                    >
                        <button
                            @click="toggleFaq(3)"
                            class="w-full px-6 py-5 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-lg font-semibold text-gray-900 pr-4"
                                >
                                    Berapa lama durasi program magang?
                                </h3>
                                <div class="flex-shrink-0">
                                    <i
                                        :class="[
                                            'fas transition-transform duration-300',
                                            openFaq === 3
                                                ? 'fa-minus text-blue-600 transform rotate-0'
                                                : 'fa-plus text-gray-400',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </button>
                        <div
                            :class="[
                                'overflow-hidden transition-all duration-300 ease-in-out',
                                openFaq === 3
                                    ? 'max-h-96 opacity-100'
                                    : 'max-h-0 opacity-0',
                            ]"
                        >
                            <div
                                class="px-6 pb-5 text-gray-600 leading-relaxed"
                            >
                                <p class="mb-3">
                                    Program magang Bank Indonesia memiliki
                                    beberapa pilihan durasi:
                                </p>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div
                                            class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"
                                        ></div>
                                        <div>
                                            <strong>Program Reguler:</strong>
                                            3-6 bulan (sesuai dengan kebutuhan
                                            akademik)
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div
                                            class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"
                                        ></div>
                                        <div>
                                            <strong>Program Khusus:</strong> 1-2
                                            bulan (untuk project tertentu)
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div
                                            class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"
                                        ></div>
                                        <div>
                                            <strong>Program Penelitian:</strong>
                                            Dapat diperpanjang hingga 12 bulan
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-gray-500">
                                    Durasi dapat disesuaikan dengan kebutuhan
                                    akademik dan kesepakatan dengan supervisor.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow"
                    >
                        <button
                            @click="toggleFaq(4)"
                            class="w-full px-6 py-5 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-lg font-semibold text-gray-900 pr-4"
                                >
                                    Apakah peserta magang mendapatkan
                                    benefit/insentif?
                                </h3>
                                <div class="flex-shrink-0">
                                    <i
                                        :class="[
                                            'fas transition-transform duration-300',
                                            openFaq === 4
                                                ? 'fa-minus text-blue-600 transform rotate-0'
                                                : 'fa-plus text-gray-400',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </button>
                        <div
                            :class="[
                                'overflow-hidden transition-all duration-300 ease-in-out',
                                openFaq === 4
                                    ? 'max-h-96 opacity-100'
                                    : 'max-h-0 opacity-0',
                            ]"
                        >
                            <div
                                class="px-6 pb-5 text-gray-600 leading-relaxed"
                            >
                                <p class="mb-4">
                                    Ya, peserta magang akan mendapatkan berbagai
                                    benefit menarik:
                                </p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-900">
                                            Benefit Finansial:
                                        </h4>
                                        <ul class="space-y-1 text-sm">
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Uang saku bulanan
                                            </li>
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Transportasi
                                            </li>
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Makan siang
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-900">
                                            Benefit Non-Finansial:
                                        </h4>
                                        <ul class="space-y-1 text-sm">
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Sertifikat magang resmi
                                            </li>
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Mentoring dari expert
                                            </li>
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Networking profesional
                                            </li>
                                            <li class="flex items-center">
                                                <i
                                                    class="fas fa-check text-green-500 mr-2"
                                                ></i
                                                >Pengalaman industri
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow"
                    >
                        <button
                            @click="toggleFaq(5)"
                            class="w-full px-6 py-5 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-xl"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-lg font-semibold text-gray-900 pr-4"
                                >
                                    Bagaimana cara melihat status lamaran saya?
                                </h3>
                                <div class="flex-shrink-0">
                                    <i
                                        :class="[
                                            'fas transition-transform duration-300',
                                            openFaq === 5
                                                ? 'fa-minus text-blue-600 transform rotate-0'
                                                : 'fa-plus text-gray-400',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </button>
                        <div
                            :class="[
                                'overflow-hidden transition-all duration-300 ease-in-out',
                                openFaq === 5
                                    ? 'max-h-96 opacity-100'
                                    : 'max-h-0 opacity-0',
                            ]"
                        >
                            <div
                                class="px-6 pb-5 text-gray-600 leading-relaxed"
                            >
                                <p class="mb-4">
                                    Anda dapat memantau status lamaran dengan
                                    beberapa cara:
                                </p>
                                <div class="space-y-4">
                                    <div
                                        class="bg-blue-50 border border-blue-200 rounded-lg p-4"
                                    >
                                        <h4
                                            class="font-semibold text-blue-900 mb-2"
                                        >
                                            <i
                                                class="fas fa-user-circle mr-2"
                                            ></i
                                            >Dashboard Pribadi
                                        </h4>
                                        <p class="text-sm text-blue-700">
                                            Login ke akun Anda dan akses menu
                                            "Status Lamaran" untuk melihat
                                            progress terkini.
                                        </p>
                                    </div>
                                    <div
                                        class="bg-green-50 border border-green-200 rounded-lg p-4"
                                    >
                                        <h4
                                            class="font-semibold text-green-900 mb-2"
                                        >
                                            <i class="fas fa-search mr-2"></i
                                            >Cek Status Online
                                        </h4>
                                        <p class="text-sm text-green-700">
                                            Gunakan fitur "Cek Status" dengan
                                            memasukkan NIK dan email yang
                                            terdaftar.
                                        </p>
                                    </div>
                                    <div
                                        class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"
                                    >
                                        <h4
                                            class="font-semibold text-yellow-900 mb-2"
                                        >
                                            <i class="fas fa-envelope mr-2"></i
                                            >Notifikasi Email
                                        </h4>
                                        <p class="text-sm text-yellow-700">
                                            Kami akan mengirimkan update status
                                            melalui email yang Anda daftarkan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="mt-16 text-center">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-8"
                    >
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">
                            Masih ada pertanyaan lain?
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Tim kami siap membantu Anda. Jangan ragu untuk
                            menghubungi kami.
                        </p>
                        <div
                            class="flex flex-col sm:flex-row gap-4 justify-center"
                        >
                            <a
                                href="mailto:growithbi@bi.go.id"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <i class="fas fa-envelope mr-2"></i>
                                Email Kami
                            </a>
                            <a
                                href="tel:(0721)1234567"
                                class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition-colors"
                            >
                                <i class="fas fa-phone mr-2"></i>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="bg-gray-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2">
                        <div class="flex items-center mb-6">
                            <div
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg"
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
                            <h3 class="ml-3 text-2xl font-bold">GrowithBI</h3>
                        </div>
                        <p class="text-gray-300 mb-6 max-w-md">
                            Program magang eksklusif Bank Indonesia KPW Lampung
                            untuk mengembangkan talenta di bidang Business
                            Intelligence dan Data Analytics.
                        </p>
                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.84-.282 1.073-1.045 2.414-1.565 3.235C9.329 23.651 10.646 24.013 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-6">Program</h4>
                        <ul class="space-y-3 text-gray-300">
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
                        <h4 class="text-lg font-semibold mb-6">Kontak</h4>
                        <ul class="space-y-3 text-gray-300">
                            <li class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                growithbi@bi.go.id
                            </li>
                            <li class="flex items-center">
                                <svg
                                    class="w-5 w-5 mr-3"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                                (0721) 123 4567
                            </li>
                            <li class="flex items-start">
                                <svg
                                    class="w-5 h-5 mr-3 mt-1"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                Bank Indonesia KPW Lampung<br />
                                Jl. Wolter Monginsidi No. 2<br />
                                Bandar Lampung 35115
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400"
                >
                    <p>
                        &copy; 2025 GrowithBI - Bank Indonesia KPW Lampung. All
                        rights reserved. Made with ❤️ for future data
                        professionals.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Custom animations */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
