<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

defineProps({ canLogin: Boolean, canRegister: Boolean, auth: Object });

const mobileMenuOpen = ref(false);
const divisions = ref([]);
const loadingDivisions = ref(true);
const openFaq = ref(null);

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
]);

onMounted(async () => {
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
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50">
        <section
            class="relative overflow-hidden pt-24 pb-16 bg-gradient-to-br from-blue-50 via-white to-indigo-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <h1
                            class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-900 mb-6"
                        >
                            Program Magang
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"
                                >GrowithBI</span
                            >
                        </h1>
                        <p class="text-lg text-gray-600 mb-8 max-w-xl">
                            Bangun karier data dan business intelligence Anda
                            bersama Bank Indonesia KPW Lampung.
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
                    <div class="relative">
                        <div
                            class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-600 shadow-xl flex items-center justify-center text-white font-semibold text-xl"
                        >
                            Data • Insight • Impact
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-gray-900 mb-3"
                    >
                        Mengapa Memilih GrowithBI?
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Kami menyediakan pengalaman magang komprehensif dengan
                        teknologi terkini.
                    </p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="group p-6 bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4"
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
                            Didampingi praktisi berpengalaman Bank Indonesia &
                            BI specialist.
                        </p>
                    </div>
                    <div
                        class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center mb-4"
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
                            Project nyata dengan data perbankan & kasus bisnis
                            sesungguhnya.
                        </p>
                    </div>
                    <div
                        class="group p-6 bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-4"
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
                        <p class="text-gray-600 text-sm">
                            Power BI, Tableau, Python, SQL, dan praktik data
                            terbaru.
                        </p>
                    </div>
                    <div
                        class="group p-6 bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center mb-4"
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
                        <p class="text-gray-600 text-sm">
                            Sertifikat Bank Indonesia diakui industri.
                        </p>
                    </div>
                    <div
                        class="group p-6 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-lg flex items-center justify-center mb-4"
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
                            Relasi profesional lintas industri & institusi.
                        </p>
                    </div>
                    <div
                        class="group p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl hover:shadow-lg transition transform hover:-translate-y-1"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-4"
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
                            Penyesuaian jadwal akademik & project.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"
                    >
                        Ulasan Magang Bank Indonesia KPW Lampung
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Cerita peserta tentang pengalaman mereka mengikuti
                        program.
                    </p>
                </div>
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="t in testimonials"
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
                                <p class="text-xs text-gray-600 font-medium">
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
                                <p class="text-[11px] text-gray-400 mt-1">
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
                        <p class="text-gray-700 leading-relaxed text-sm flex-1">
                            "{{ t.quote }}"
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="divisions" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-gray-900 mb-3"
                    >
                        Program Magang Kami
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Pilih program sesuai minat dan tujuan karir Anda.
                    </p>
                </div>
                <div
                    v-if="divisions.length"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="division in divisions"
                        :key="division.id"
                        class="group bg-white rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100 overflow-hidden"
                    >
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
                            class="w-12 h-12 bg-gray-200 rounded-lg mb-4"
                        ></div>
                        <div class="h-5 bg-gray-200 rounded mb-3"></div>
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded mb-6"></div>
                        <div class="h-8 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Bergabunglah dalam program magang eksklusif Bank Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="bg-white hover:bg-gray-100 text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg transition transform hover:scale-105 shadow-lg"
                        >🚀 Daftar Magang</Link
                    >
                    <a
                        href="#divisions"
                        class="border-2 border-white hover:bg-white hover:text-blue-600 text-white px-8 py-4 rounded-xl font-semibold text-lg transition"
                        >📋 Lihat Program</a
                    >
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Pertanyaan Umum
                    </h2>
                    <p class="text-lg text-gray-600">
                        Beberapa hal yang sering ditanyakan peserta.
                    </p>
                </div>
                <div class="space-y-4">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm"
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
                </div>
            </div>
        </section>

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
                            Program magang Bank Indonesia KPW Lampung untuk
                            mengembangkan talenta Business Intelligence & Data
                            Analytics.
                        </p>
                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                                ><svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"
                                    /></svg
                            ></a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                                ><svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                                    /></svg
                            ></a>
                            <a
                                href="#"
                                class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors"
                                ><svg
                                    class="w-5 h-5"
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
                        <ul class="space-y-3 text-gray-300 text-sm">
                            <li class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3"
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
                                    class="w-5 h-5 mr-3"
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
                                    class="w-5 h-5 mr-3 mt-1"
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
                    class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400 text-sm"
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
