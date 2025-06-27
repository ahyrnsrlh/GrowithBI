<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-16">
            <!-- About Content -->
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h1 class="text-5xl font-bold text-gray-900 mb-6">
                        Tentang <span class="text-blue-600">GrowithBI</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Platform Business Intelligence modern yang memungkinkan
                        organisasi untuk menganalisis data dengan cepat dan
                        membuat keputusan yang tepat berdasarkan insight yang
                        akurat.
                    </p>
                </div>

                <!-- Mission & Vision -->
                <div class="grid md:grid-cols-2 gap-8 mb-16">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-bold text-gray-900 mb-4 text-center"
                        >
                            Misi Kami
                        </h3>
                        <p class="text-gray-600 text-center">
                            Memberdayakan setiap organisasi dengan tools
                            business intelligence yang mudah digunakan, membantu
                            mereka mengubah data menjadi insight yang actionable
                            untuk pertumbuhan bisnis yang berkelanjutan.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-bold text-gray-900 mb-4 text-center"
                        >
                            Visi Kami
                        </h3>
                        <p class="text-gray-600 text-center">
                            Menjadi platform BI terdepan yang memungkinkan
                            setiap perusahaan, dari startup hingga enterprise,
                            untuk membuat keputusan berbasis data dengan
                            confidence dan speed.
                        </p>
                    </div>
                </div>

                <!-- Key Features -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-16">
                    <h2
                        class="text-3xl font-bold text-gray-900 mb-8 text-center"
                    >
                        Mengapa Memilih GrowithBI?
                    </h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2"
                            >
                                Mudah Digunakan
                            </h3>
                            <p class="text-gray-600">
                                Interface yang intuitif dan user-friendly untuk
                                semua level pengguna
                            </p>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    ></path>
                                </svg>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2"
                            >
                                Performa Tinggi
                            </h3>
                            <p class="text-gray-600">
                                Processing data yang cepat dengan teknologi
                                modern
                            </p>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    ></path>
                                </svg>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2"
                            >
                                Analisis Mendalam
                            </h3>
                            <p class="text-gray-600">
                                Visualisasi data yang komprehensif dan
                                customizable
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tech Stack -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2
                        class="text-3xl font-bold text-gray-900 mb-8 text-center"
                    >
                        Teknologi yang Kami Gunakan
                    </h2>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <span class="text-red-600 font-bold text-lg"
                                    >L</span
                                >
                            </div>
                            <h4 class="font-semibold text-gray-900">Laravel</h4>
                            <p class="text-sm text-gray-600">PHP Framework</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <span class="text-green-600 font-bold text-lg"
                                    >V</span
                                >
                            </div>
                            <h4 class="font-semibold text-gray-900">Vue.js</h4>
                            <p class="text-sm text-gray-600">
                                Frontend Framework
                            </p>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <span class="text-purple-600 font-bold text-lg"
                                    >I</span
                                >
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                Inertia.js
                            </h4>
                            <p class="text-sm text-gray-600">Modern Monolith</p>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <span class="text-blue-600 font-bold text-lg"
                                    >T</span
                                >
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                TailwindCSS
                            </h4>
                            <p class="text-sm text-gray-600">CSS Framework</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    name: "About",
    components: {
        AppLayout,
    },
};
</script>
