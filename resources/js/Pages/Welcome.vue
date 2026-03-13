<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed } from "vue";
import AOS from "aos";

defineProps({ canLogin: Boolean, canRegister: Boolean, auth: Object });

const mobileMenuOpen = ref(false);
const divisions = ref([]);
const loadingDivisions = ref(true);
const openFaq = ref(null);
const selectedFaqCategory = ref("umum");
const isScrolled = ref(false);

// Handle navbar scroll effect
function handleScroll() {
    isScrolled.value = window.scrollY > 50;
}

function toggleFaq(i) {
    openFaq.value = openFaq.value === i ? null : i;
}

const faqCategories = [
    { id: "umum", name: "Informasi Umum" },
    { id: "persyaratan", name: "Persyaratan & Dokumen" },
    { id: "program", name: "Program & Durasi" },
    { id: "kompensasi", name: "Kompensasi" },
    { id: "seleksi", name: "Proses Seleksi" },
    { id: "fasilitas", name: "Fasilitas & Benefit" },
];

const faqs = {
    umum: [
        {
            q: "Apa itu Program Magang GrowithBI?",
            a: "GrowithBI adalah program magang profesional yang dirancang untuk mahasiswa berkualitas dari berbagai universitas di Indonesia. Program ini memberikan kesempatan belajar langsung di Bank Indonesia dengan bimbingan mentor berpengalaman dan exposure ke berbagai aspek perbankan modern.",
        },
        {
            q: "Siapa saja yang boleh mendaftar?",
            a: "Program ini terbuka untuk mahasiswa aktif tingkat akhir (minimal semester 6) dari semua jurusan, dengan fokus pada bidang Teknologi Informasi, Keuangan, Manajemen, dan Ekonomi. Syarat utama adalah memiliki akademik yang baik (IPK minimal 2.75) dan motivasi yang tinggi.",
        },
        {
            q: "Apakah ada batasan usia untuk peserta?",
            a: "Tidak ada batasan usia yang ketat, namun peserta harus masih merupakan mahasiswa aktif yang terdaftar di universitas. Kami menerima peserta dari berbagai tingkat usia selama memenuhi kriteria akademik dan profesional yang ditentukan.",
        },
    ],
    persyaratan: [
        {
            q: "Dokumen apa saja yang harus saya siapkan?",
            a: "Dokumen yang diperlukan meliputi: Surat Pengantar dari Kampus, CV terbaru, Motivation Letter (maksimal 1 halaman), Transkrip Nilai semester terakhir, dan fotokopi KTP/identitas yang jelas. Semua dokumen harus dalam format PDF dan tidak boleh melebihi ukuran maksimal 5MB per file.",
        },
        {
            q: "Apakah harus ada SPK (Surat Pengantar Kampus)?",
            a: "Ya, SPK merupakan dokumen wajib yang harus disertai dengan tanda tangan dari jurusan atau fakultas Anda. Surat ini menunjukkan bahwa universitas mengizinkan Anda untuk melaksanakan magang selama periode yang ditentukan.",
        },
        {
            q: "Berapa GPA minimum yang dibutuhkan?",
            a: "IPK minimum yang diperlukan adalah 2.75 atau setara dengan nilai C+ di sistem grading universitas Anda. Meskipun demikian, kandidat dengan IPK lebih tinggi akan memiliki prioritas lebih besar dalam proses seleksi.",
        },
    ],
    program: [
        {
            q: "Berapa lama durasi program magang?",
            a: "Program magang GrowithBI tersedia dalam dua format: Magang Reguler selama 3-4 bulan untuk mahasiswa tingkat akhir, dan Magang Project selama 5-6 bulan untuk project khusus dan penelitian tertentu. Durasi dapat disesuaikan dengan kebutuhan akademik dan project.",
        },
        {
            q: "Kapan saja program magang dapat dimulai?",
            a: "Program magang dibuka dua kali dalam setahun, biasanya pada bulan Februari-Mei (tahun pertama) dan Agustus-November (tahun kedua). Pendaftaran dibuka 1-2 bulan sebelum periode dimulai, dengan informasi lebih detail tersedia di website resmi GrowithBI.",
        },
        {
            q: "Divisi apa saja yang tersedia untuk magang?",
            a: "Tersedia 6 divisi utama: IT Development, Financial Services, Risk Management, Digital Banking, Human Resources, dan Corporate Strategy. Anda dapat memilih hingga 2 divisi sesuai minat dan latar belakang akademik Anda.",
        },
    ],
    kompensasi: [
        {
            q: "Apakah ada gaji atau uang saku selama magang?",
            a: "Ya, peserta magang akan mendapatkan kompensasi yang terdiri dari uang saku bulanan, tunjangan transportasi harian, dan asuransi kesehatan. Besaran kompensasi disesuaikan dengan kebijakan Bank Indonesia dan status pegawai magang.",
        },
        {
            q: "Berapa besaran uang saku yang saya terima?",
            a: "Uang saku bulanan berkisar antara Rp. 3 - 5 juta tergantung performa dan divisi tempat magang. Tunjangan transportasi diberikan per hari kerja, sementara peserta berprestasi dapat menerima bonus performance tambahan.",
        },
        {
            q: "Kapan pembayaran uang saku dilakukan?",
            a: "Pembayaran dilakukan setiap akhir bulan melalui transfer langsung ke rekening bank peserta yang terdaftar. Semua dokumentasi dan persetujuan kerja sama harus sudah selesai sebelum bulan pertama magang dimulai.",
        },
    ],
    seleksi: [
        {
            q: "Bagaimana proses seleksi dan tahap-tahapannya?",
            a: "Proses seleksi terdiri dari 3 tahap: (1) Seleksi Administratif - verifikasi kelengkapan dokumen, (2) Tes Kemampuan - evaluasi knowledge dan skill sesuai divisi pilihan, (3) Wawancara - interview dengan manager divisi terkait. Hasil akan diumumkan melalui email dan website dalam 2-3 minggu.",
        },
        {
            q: "Apa saja yang diujikan dalam Tes Kemampuan?",
            a: "Tes kemampuan mencakup: Tes psikologi untuk menilai karakter dan potensi, Tes akademik sesuai bidang (IT, Keuangan, Manajemen), dan Tes bahasa Inggris (reading & listening). Soal-soal dirancang untuk melacak pemahaman Anda terkait bidang magang yang dipilih.",
        },
        {
            q: "Berapa lama waktu proses seleksi?",
            a: "Total waktu proses seleksi mulai dari pendaftaran hingga pengumuman hasil adalah 4-6 minggu. Peserta akan menerima update status melalui email di setiap tahap. Anda dapat melihat status pendaftaran real-time di dashboard GrowithBI.",
        },
    ],
    fasilitas: [
        {
            q: "Apa saja fasilitas yang disediakan oleh Bank Indonesia?",
            a: "Fasilitas yang disediakan meliputi: Ruang kerja profesional dengan AC dan fasilitas lengkap, Akses ke training dan workshop internal, Mentoring dari professionals berpengalaman, Akses ke sistem dan tools modern, Asuransi kesehatan selama periode magang, dan Kesempatan networking dengan profesional Bank Indonesia.",
        },
        {
            q: "Apakah ada kesempatan untuk menjadi pegawai tetap setelah magang?",
            a: "Peserta dengan performa luar biasa memiliki kesempatan untuk dipertimbangkan dalam rekrutmen pegawai tetap, khususnya untuk divisi yang sedang membuka lowongan. Evaluasi dilakukan secara komprehensif oleh manager dan HR selama periode magang berakhir.",
        },
        {
            q: "Apakah tersedia sertifikat setelah selesai magang?",
            a: "Ya, semua peserta yang menyelesaikan program magang akan menerima sertifikat resmi dari Bank Indonesia yang mencantumkan divisi, durasi, dan evaluasi kinerja. Sertifikat ini diakui secara luas dan dapat meningkatkan nilai CV Anda di job market.",
        },
    ],
};

const fallbackDivisions = [
    {
        id: 1,
        name: "Business Intelligence",
        description:
            "Program magang yang berfokus pada analisis data perbankan, dashboard BI, dan pengembangan insights untuk mendukung pengambilan keputusan strategis.",
        is_active: true,
        quota: 10,
        duration: 6,
    },
    {
        id: 2,
        name: "Data Analytics & Research",
        description:
            "Program magang untuk mengembangkan kemampuan analisis data ekonomi dan keuangan, riset pasar, serta pemodelan data untuk business intelligence.",
        is_active: true,
        quota: 10,
        duration: 6,
    },
    {
        id: 3,
        name: "Financial Technology",
        description:
            "Program magang yang mengelaborasi teknologi finansial terkini, payment systems, digital banking, dan inovasi teknologi perbankan.",
        is_active: true,
        quota: 10,
        duration: 6,
    },
    {
        id: 4,
        name: "General Affair",
        description:
            "Program magang yang bertujuan mendukung kegiatan administrasi, pengelolaan dokumen, serta operasional kantor untuk mendukung efisiensi organisasi.",
        is_active: true,
        quota: 10,
        duration: 6,
    },
];

const testimonials = ref([
    {
        id: 1,
        initials: "RD",
        name: "Reihan Denindra",
        company: "Universitas Lampung",
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
        company: "UIN Raden Intan Lampung",
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
        company: "Universitas Teknokrat Indonesia",
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
        company: "IIB Darmajaya",
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
        company: "Politeknik Negeri Lampung (Polinela)",
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
        company: "Universitas Lampung",
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
        company: "UIN Raden Intan Lampung",
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
        company: "Universitas Teknokrat Indonesia",
        tag: "Regulatory Compliance",
        tagColor: "green",
        variant: "B",
        date: "06 September 2025",
        quote: "Program yang sangat terstruktur dengan pembelajaran compliance dan governance yang sangat applicable di dunia kerja.",
    },
]);

// Carousel functionality for testimonials
const currentSlide = ref(0);
const totalSlides = computed(() => testimonials.value.length);

const activeTestimonial = computed(
    () => testimonials.value[currentSlide.value] || testimonials.value[0],
);

const previousTestimonial = computed(() => {
    const length = testimonials.value.length;
    if (!length) return null;
    const prevIndex = (currentSlide.value - 1 + length) % length;
    return testimonials.value[prevIndex];
});

const nextTestimonialData = computed(() => {
    const length = testimonials.value.length;
    if (!length) return null;
    const nextIndex = (currentSlide.value + 1) % length;
    return testimonials.value[nextIndex];
});

const nextSlide = () => {
    const length = testimonials.value.length;
    if (!length) return;
    currentSlide.value = (currentSlide.value + 1) % length;
};

const prevSlide = () => {
    const length = testimonials.value.length;
    if (!length) return;
    currentSlide.value = (currentSlide.value - 1 + length) % length;
};

const goToSlide = (index) => {
    const length = testimonials.value.length;
    if (!length) return;
    currentSlide.value = ((index % length) + length) % length;
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
                    'flex items-center justify-between rounded-2xl transition-all duration-300 ease-in-out',
                    isScrolled
                        ? 'bg-gradient-to-r from-blue-800 via-blue-700 to-indigo-800 shadow-lg shadow-black/10 border border-blue-600/20 px-6 py-3'
                        : 'bg-gradient-to-r from-blue-800 via-blue-700 to-indigo-800 shadow-md shadow-black/5 border border-blue-600/30 px-8 py-4',
                ]"
            >
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img
                        src="/storage/logo_web.png"
                        alt="GrowithBI Bank Indonesia Lampung"
                        class="h-8 w-auto object-contain"
                    />
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
                class="md:hidden mt-4 p-6 bg-white/95 rounded-2xl shadow-lg shadow-black/10 border border-white/20"
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

    <div class="bg-white relative overflow-hidden">
        <!-- Enhanced Background Pattern -->
        <div class="absolute inset-0">
            <!-- Main Grid Pattern -->
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
            <!-- Secondary smaller grid for more detail -->
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
            <!-- Soft Background overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-slate-50/80 via-blue-50/75 to-indigo-100/80"
            ></div>
        </div>
        <section class="relative z-10 overflow-hidden pt-32 pb-16 bg-white/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h1
                            class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-900 mb-6"
                        >
                            <span class="block overflow-hidden">
                                <span
                                    class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-300% animate-gradient-x animate-slide-up delay-100"
                                    >Be The Next</span
                                >
                            </span>
                            <span class="block overflow-hidden">
                                <span
                                    class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 bg-300% animate-gradient-x animate-slide-up delay-300"
                                    >Future Economic</span
                                >
                            </span>
                            <span class="block overflow-hidden">
                                <span
                                    class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 bg-300% animate-gradient-x animate-slide-up delay-500"
                                    >& Digital Talent</span
                                >
                            </span>
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
                    <div
                        class="relative"
                        data-aos="fade-left"
                        data-aos-duration="1000"
                        data-aos-delay="200"
                    >
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

        <section
            id="features"
            class="py-20 bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900 relative z-10"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="text-center mb-16"
                    data-aos="fade-up"
                    data-aos-duration="800"
                >
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                        Benefit Magang di Bank Indonesia
                    </h2>
                    <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                        Belajar, berkembang, dan berkontribusi bersama bank
                        indonesia provinsi lampung
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Benefit 1 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="100"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Pengalaman Kerja Nyata
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Terlibat langsung dalam proyek-proyek riil Bank
                            Indonesia dan rasakan atmosfer kerja profesional
                            yang sesungguhnya.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="200"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Pengembangan Skill & Kompetensi
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Kembangkan keahlian teknis dan soft skill melalui
                            pelatihan berkualitas dari para ekspert industri
                            perbankan.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="300"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Bimbingan dari Mentor Berpengalaman
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Dapatkan mentoring personal dari senior banker
                            berpengalaman untuk mengoptimalkan potensi karier
                            Anda.
                        </p>
                    </div>

                    <!-- Benefit 4 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="400"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Jaringan & Relasi Profesional
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Bangun networking yang kuat dengan profesional
                            perbankan dan buka peluang karier di masa depan.
                        </p>
                    </div>

                    <!-- Benefit 5 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="500"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Sertifikat Magang Resmi
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Raih sertifikat resmi sebagai bukti pengalaman
                            magang di institusi keuangan terpercaya di
                            Indonesia.
                        </p>
                    </div>

                    <!-- Benefit 6 -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer"
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="600"
                    >
                        <div class="flex items-start justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-400/30 flex items-center justify-center group-hover:bg-blue-300/40 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <svg
                                class="w-5 h-5 text-blue-100 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 translate-y-0 group-hover:-translate-y-1 transition-all duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 17L17 7M17 7H7M17 7V17"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">
                            Uang Saku Magang
                        </h3>
                        <p class="text-blue-100/90 leading-relaxed text-sm">
                            Dapatkan dukungan finansial berupa uang saku selama
                            program magang untuk membantu kebutuhan harian Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="divisions" class="py-16 bg-white/50 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="text-center mb-12"
                    data-aos="fade-up"
                    data-aos-duration="800"
                >
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
                        class="group bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 border border-blue-500/20 overflow-hidden flex flex-col"
                        data-aos="zoom-in"
                        data-aos-duration="600"
                        :data-aos-delay="index * 100"
                    >
                        <!-- Content Area - Flex Grow -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center justify-center mb-4">
                                <div
                                    class="w-32 h-32 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                                >
                                    <img
                                        src="/BI WHITE.png"
                                        alt="Bank Indonesia Logo"
                                        class="w-full h-full object-contain filter drop-shadow-lg"
                                    />
                                </div>
                            </div>
                            <h3
                                class="text-lg font-bold text-white mb-2 text-center leading-tight"
                            >
                                {{ division.name }}
                            </h3>
                            <p
                                class="text-blue-100 mb-4 text-center leading-relaxed text-sm line-clamp-3 flex-1"
                            >
                                {{ division.description }}
                            </p>
                            <div class="flex justify-center">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-xs font-medium',
                                        division.is_active
                                            ? 'bg-green-500/20 text-green-200 border border-green-400/30'
                                            : 'bg-gray-500/20 text-gray-200 border border-gray-400/30',
                                    ]"
                                    >{{
                                        division.is_active
                                            ? "Tersedia"
                                            : "Tidak Tersedia"
                                    }}</span
                                >
                            </div>
                        </div>

                        <!-- Footer Area - Always at Bottom -->
                        <div
                            class="px-6 pb-6 pt-4 border-t border-blue-500/20 bg-blue-800/30"
                        >
                            <div
                                class="flex items-center justify-center gap-4 mb-4 text-xs text-blue-200"
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
                                    ><span class="font-medium text-white">{{
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
                                    ><span class="font-medium text-white">{{
                                        division.duration || 6
                                    }}</span
                                    >&nbsp;bulan
                                </div>
                            </div>
                            <Link
                                :href="
                                    route('public.division.detail', division.id)
                                "
                                class="w-full bg-white hover:bg-blue-50 text-blue-700 py-2.5 px-4 rounded-lg font-medium text-center transition block text-sm hover:shadow-md border border-white/20"
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
                                @click="goToSlide(index)"
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
                                            activeTestimonial.tagColor ===
                                            'blue'
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
                                    >5.0</span
                                >
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
                            @click="prevSlide"
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
                            @click="nextSlide"
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
                            @click="goToSlide(index)"
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

        <section
            id="faq"
            class="py-24 bg-gradient-to-b from-white to-blue-50/30 relative z-10"
        >
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
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
                    <h2
                        class="text-4xl md:text-5xl font-bold mb-6 leading-tight"
                    >
                        <span class="text-gray-900">Pertanyaan yang </span>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"
                            >Sering Ditanyakan</span
                        >
                    </h2>
                    <p
                        class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-light"
                    >
                        Temukan jawaban untuk pertanyaan-pertanyaan umum tentang
                        GrowithBI dan bagaimana program ini dapat menguntungkan
                        karir Anda.
                    </p>
                </div>

                <!-- FAQ Container with Sidebar -->
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Sidebar Categories -->
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
                                    @click="
                                        selectedFaqCategory = cat.id;
                                        openFaq = null;
                                    "
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

                    <!-- FAQ Items -->
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
                                @click="toggleFaq(index)"
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

                        <!-- Still have questions section -->
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

        <footer
            id="contact"
            class="bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900 text-white py-16 relative z-10"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        class="lg:col-span-2"
                        data-aos="fade-right"
                        data-aos-duration="800"
                    >
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

@keyframes gradient-x {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes slide-up {
    0% {
        transform: translateY(100%);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-gradient-x {
    background-size: 300% 300%;
    animation: gradient-x 4s ease infinite;
}

.animate-slide-up {
    animation: slide-up 1s ease-out forwards;
}

.animate-typing {
    animation:
        typing 2s ease-out,
        fadeInUp 1.5s ease-out;
}

.bg-300\% {
    background-size: 300% 300%;
}

.delay-100 {
    animation-delay: 0.1s;
}

.delay-300 {
    animation-delay: 0.3s;
}

.delay-500 {
    animation-delay: 0.5s;
}

/* Initial state for slide-up animation */
.animate-slide-up {
    transform: translateY(100%);
    opacity: 0;
}
</style>
