<template>
    <section id="features" class="py-20 bg-slate-50 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="text-center mb-16"
                data-aos="fade-up"
                data-aos-duration="800"
            >
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-blue-800">
                    Benefit Magang di Bank Indonesia
                </h2>
                <p class="text-blue-700 text-lg max-w-2xl mx-auto">
                    Belajar, berkembang, dan berkontribusi bersama bank
                    indonesia provinsi lampung
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="benefit in benefits"
                    :key="benefit.title"
                    class="group bg-white rounded-2xl p-8 border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 cursor-pointer"
                    data-aos="fade-up"
                    data-aos-duration="600"
                    :data-aos-delay="benefit.delay"
                >
                    <div class="flex items-start justify-between mb-6">
                        <div
                            class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center transition-colors"
                        >
                            <svg
                                class="w-6 h-6 text-blue-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    :d="benefit.icon"
                                />
                            </svg>
                        </div>
                        <svg
                            class="w-5 h-5 text-blue-500 opacity-60 transform translate-x-0 group-hover:translate-x-1 transition-all duration-300"
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
                    <h3 class="text-xl font-bold text-slate-900 mb-3">
                        {{ benefit.title }}
                    </h3>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        {{ benefit.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const benefits = [
    {
        title: "Pengalaman Kerja Nyata",
        description:
            "Terlibat langsung dalam proyek-proyek riil Bank Indonesia dan rasakan atmosfer kerja profesional yang sesungguhnya.",
        delay: 100,
        icon: "M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z",
    },
    {
        title: "Pengembangan Skill & Kompetensi",
        description:
            "Kembangkan keahlian teknis dan soft skill melalui pelatihan berkualitas dari para ekspert industri perbankan.",
        delay: 200,
        icon: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        title: "Bimbingan dari Mentor Berpengalaman",
        description:
            "Dapatkan mentoring personal dari senior banker berpengalaman untuk mengoptimalkan potensi karier Anda.",
        delay: 300,
        icon: "M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z",
    },
    {
        title: "Jaringan & Relasi Profesional",
        description:
            "Bangun networking yang kuat dengan profesional perbankan dan buka peluang karier di masa depan.",
        delay: 400,
        icon: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
    },
    {
        title: "Sertifikat Magang Resmi",
        description:
            "Raih sertifikat resmi sebagai bukti pengalaman magang di institusi keuangan terpercaya di Indonesia.",
        delay: 500,
        icon: "M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4",
    },
    {
        title: "Uang Saku Magang",
        description:
            "Dapatkan dukungan finansial berupa uang saku selama program magang untuk membantu kebutuhan harian Anda.",
        delay: 600,
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    },
];
</script>
