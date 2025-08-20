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

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Navigation -->
        <nav
            class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
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
                        <h1
                            class="ml-3 text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            GrowithBI
                        </h1>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a
                            href="#features"
                            class="text-gray-600 hover:text-blue-600 transition-colors font-medium"
                            >Features</a
                        >
                        <a
                            href="#about"
                            class="text-gray-600 hover:text-blue-600 transition-colors font-medium"
                            >About</a
                        >
                        <a
                            href="#divisions"
                            class="text-gray-600 hover:text-blue-600 transition-colors font-medium"
                            >Divisions</a
                        >
                        <a
                            href="#contact"
                            class="text-gray-600 hover:text-blue-600 transition-colors font-medium"
                            >Contact</a
                        >
                        
                        <!-- Authenticated User Menu -->
                        <div v-if="auth?.user" class="flex items-center space-x-4">
                            <Link
                                :href="route('profile.edit')"
                                class="text-gray-600 hover:text-blue-600 transition-colors font-medium"
                            >
                                Profil
                            </Link>
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ auth.user.name.charAt(0).toUpperCase() }}</span>
                                </div>
                                <span class="text-gray-700 font-medium">{{ auth.user.name }}</span>
                            </div>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-all"
                            >
                                Logout
                            </Link>
                        </div>
                        
                        <!-- Guest User Menu -->
                        <div v-else class="flex items-center space-x-4">
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:scale-105"
                            >
                                Login
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg font-medium transition-all"
                            >
                                Daftar
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
                            >Features</a
                        >
                        <a
                            href="#about"
                            class="text-gray-600 hover:text-blue-600"
                            >About</a
                        >
                        <a
                            href="#divisions"
                            class="text-gray-600 hover:text-blue-600"
                            >Divisions</a
                        >
                        <a
                            href="#contact"
                            class="text-gray-600 hover:text-blue-600"
                            >Contact</a
                        >
                        
                        <!-- Authenticated User Mobile Menu -->
                        <div v-if="auth?.user" class="pt-2 border-t border-gray-200">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ auth.user.name.charAt(0).toUpperCase() }}</span>
                                </div>
                                <span class="text-gray-700 font-medium">{{ auth.user.name }}</span>
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
        <section class="relative overflow-hidden py-20 lg:py-32">
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-indigo-600/10"
            ></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1
                        class="text-5xl lg:text-7xl font-bold text-gray-900 mb-6"
                    >
                        Grow Your Career with
                        <span
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            Business Intelligence
                        </span>
                    </h1>
                    <p
                        class="text-xl lg:text-2xl text-gray-600 mb-8 max-w-4xl mx-auto leading-relaxed"
                    >
                        Program magang eksklusif Bank Indonesia KPW Lampung
                        untuk mahasiswa yang ingin mengembangkan keahlian di
                        bidang Business Intelligence dan Data Analytics
                    </p>
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center"
                    >
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            🚀 Mulai Magang Sekarang
                        </Link>
                        <a
                            href="#divisions"
                            class="border-2 border-gray-300 hover:border-blue-600 text-gray-700 hover:text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg transition-all"
                        >
                            📊 Lihat Program Kami
                        </a>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div
                class="absolute top-20 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 animate-bounce"
            ></div>
            <div
                class="absolute top-40 right-20 w-16 h-16 bg-indigo-200 rounded-full opacity-30 animate-pulse"
            ></div>
            <div
                class="absolute bottom-20 left-20 w-12 h-12 bg-purple-200 rounded-full opacity-25 animate-bounce"
                style="animation-delay: 1s"
            ></div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Mengapa Memilih GrowithBI?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Kami menyediakan pengalaman magang yang komprehensif
                        dengan teknologi terkini
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        class="group p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
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
                            Bimbingan Expert
                        </h3>
                        <p class="text-gray-600">
                            Didampingi oleh praktisi berpengalaman dari Bank
                            Indonesia dan ahli Business Intelligence terkemuka
                        </p>
                    </div>

                    <div
                        class="group p-8 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Project Real
                        </h3>
                        <p class="text-gray-600">
                            Mengerjakan project nyata dengan data perbankan dan
                            kasus bisnis sesungguhnya dari Bank Indonesia
                        </p>
                    </div>

                    <div
                        class="group p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
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
                            Teknologi Modern
                        </h3>
                        <p class="text-gray-600">
                            Menggunakan tools terkini seperti Power BI, Tableau,
                            Python, dan SQL
                        </p>
                    </div>

                    <div
                        class="group p-8 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-orange-600 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Sertifikat
                        </h3>
                        <p class="text-gray-600">
                            Dapatkan sertifikat resmi dari Bank Indonesia yang
                            diakui industri perbankan dan keuangan
                        </p>
                    </div>

                    <div
                        class="group p-8 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
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
                            Networking
                        </h3>
                        <p class="text-gray-600">
                            Bangun jaringan profesional dengan sesama peserta
                            dan pegawai Bank Indonesia
                        </p>
                    </div>

                    <div
                        class="group p-8 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
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
                            Flexible Schedule
                        </h3>
                        <p class="text-gray-600">
                            Program magang yang fleksibel dan dapat disesuaikan
                            dengan jadwal kuliah mahasiswa
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section
            id="about"
            class="py-20 bg-gradient-to-br from-gray-50 to-blue-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">
                            Tentang GrowithBI
                        </h2>
                        <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                            GrowithBI adalah program magang eksklusif yang
                            diselenggarakan oleh Bank Indonesia Kantor
                            Perwakilan Wilayah (KPW) Lampung. Program ini
                            dirancang khusus untuk mahasiswa yang ingin
                            mengembangkan keahlian di bidang Business
                            Intelligence dan Data Analytics.
                        </p>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Sebagai bagian dari Bank Indonesia, kami memberikan
                            kesempatan kepada mahasiswa untuk belajar langsung
                            dari praktisi berpengalaman dan menggunakan
                            teknologi terdepan dalam industri perbankan dan
                            keuangan.
                        </p>
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                class="text-center p-4 bg-white rounded-xl shadow-sm"
                            >
                                <div
                                    class="text-3xl font-bold text-blue-600 mb-2"
                                >
                                    150+
                                </div>
                                <div class="text-gray-600">Alumni</div>
                            </div>
                            <div
                                class="text-center p-4 bg-white rounded-xl shadow-sm"
                            >
                                <div
                                    class="text-3xl font-bold text-green-600 mb-2"
                                >
                                    92%
                                </div>
                                <div class="text-gray-600">Job Placement</div>
                            </div>
                            <div
                                class="text-center p-4 bg-white rounded-xl shadow-sm"
                            >
                                <div
                                    class="text-3xl font-bold text-purple-600 mb-2"
                                >
                                    15+
                                </div>
                                <div class="text-gray-600">
                                    Partner Universitas
                                </div>
                            </div>
                            <div
                                class="text-center p-4 bg-white rounded-xl shadow-sm"
                            >
                                <div
                                    class="text-3xl font-bold text-orange-600 mb-2"
                                >
                                    4.8
                                </div>
                                <div class="text-gray-600">Rating Program</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div
                            class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-8 text-white"
                        >
                            <h3 class="text-2xl font-bold mb-4">Visi Kami</h3>
                            <p class="text-blue-100 mb-6">
                                Menjadi program magang unggulan Bank Indonesia
                                yang menghasilkan talenta-talenta terbaik di
                                bidang Business Intelligence dan Data Analytics
                                untuk mendukung transformasi digital sektor
                                perbankan.
                            </p>
                            <h3 class="text-2xl font-bold mb-4">Misi Kami</h3>
                            <ul class="text-blue-100 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-yellow-400 mr-2">•</span>
                                    Memberikan pelatihan berkualitas tinggi
                                    sesuai standar Bank Indonesia
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-400 mr-2">•</span>
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
        <section id="divisions" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Program Magang Kami
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Pilih program yang sesuai dengan minat dan tujuan karir
                        Anda
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
                    v-if="divisions.length > 0"
                >
                    <div
                        v-for="division in divisions"
                        :key="division.id"
                        class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100"
                    >
                        <!-- Card Header -->
                        <div class="p-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center"
                                >
                                    <i
                                        v-if="division.icon"
                                        :class="division.icon + ' text-white text-2xl'"
                                    ></i>
                                    <svg
                                        v-else
                                        class="w-8 h-8 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM21 17a2 2 0 11-4 0 2 2 0 014 0z M8 12h8l-1-9H9l-1 9z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                                {{ division.name }}
                            </h3>
                            
                            <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                                {{ division.description }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-6">
                                <span
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-medium',
                                        division.is_active
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-600'
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
                        <div class="px-8 pb-8 pt-4 border-t border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-users mr-2"></i>
                                    <span class="font-medium">{{ division.quota }}</span> posisi
                                </div>
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-medium">6</span> bulan
                                </div>
                            </div>
                            
                            <Link
                                :href="route('public.division.detail', division.id)"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-2.5 rounded-xl font-semibold text-center transition-all transform hover:scale-105 block text-sm"
                            >
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Loading state -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
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
                        <div class="px-8 pb-8 pt-4 border-t border-gray-100 bg-gray-50/50">
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
