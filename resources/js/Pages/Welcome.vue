<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-16">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-6xl font-bold text-gray-900 mb-4">
                    Selamat Datang di
                    <span class="text-blue-600">GrowithBI</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Platform Business Intelligence yang powerful dengan Laravel,
                    Inertia.js, dan Vue.js
                </p>
            </div>

            <!-- Tech Stack Cards -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div
                    class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow"
                >
                    <div
                        class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-red-600"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M23.642 5.43a.364.364 0 01.014.1v5.149c0 .135-.073.26-.189.326l-4.323 2.49v4.934a.378.378 0 01-.188.327L9.93 23.949a.316.316 0 01-.066.02c-.01.003-.02.007-.032.007a.306.306 0 01-.094-.016l-9.026-5.193a.378.378 0 01-.189-.327V5.527c0-.042.01-.084.027-.124.004-.01.008-.018.013-.027.006-.01.014-.02.021-.028.011-.013.024-.025.038-.035.006-.005.012-.01.019-.014L9.647.039a.378.378 0 01.378 0l9.006 5.183a.357.357 0 01.062.054c.01.009.018.02.024.031.01.017.016.036.018.056.001.014 0 .027-.004.04zM12.323 5.731L9.646 4.318 6.969 5.731l2.677 1.413 2.677-1.413zm-3.044 2.278L6.602 6.595v2.826l2.677 1.414V8.009zm.378-4.092L6.979 2.504 4.302 3.917l2.677 1.413 2.678-1.413zm11.697 4.092l-2.677 1.414v2.826l2.677-1.414V6.009zm-3.055-1.414L15.621 4.318 12.944 5.731l2.677 1.413 2.678-1.413zM12.944 2.504L10.267 3.917l2.677 1.413 2.677-1.413-2.677-1.413z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Laravel
                    </h3>
                    <p class="text-gray-600">
                        PHP framework yang elegant untuk pengembangan web modern
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow"
                >
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-purple-600"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 0L1.75 6v12L12 24l10.25-6V6L12 0zm0 2.236L20.25 7.5v9L12 21.764 3.75 16.5v-9L12 2.236z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Inertia.js
                    </h3>
                    <p class="text-gray-600">
                        Modern monolith tanpa kompleksitas SPA tradisional
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow"
                >
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-green-600"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M24,12.073c0,6.627-5.373,12-12,12s-12-5.373-12-12s5.373-12,12-12S24,5.446,24,12.073z M13.426,11.072 l-2.552-4.583l-2.552,4.583H13.426z M11.238,13.235l-2.552,4.583l2.552-4.583H11.238z M12.762,13.235h4.997l-2.552,4.583 L12.762,13.235z M12.762,11.072l2.445-4.583H8.318L12.762,11.072z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Vue.js
                    </h3>
                    <p class="text-gray-600">
                        Progressive JavaScript framework untuk UI yang
                        interaktif
                    </p>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    Fitur Utama
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Server-side Routing
                            </h3>
                            <p class="text-gray-600">
                                Menggunakan routing Laravel yang familiar
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                No API Required
                            </h3>
                            <p class="text-gray-600">
                                Tidak perlu membangun API terpisah
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Modern UI
                            </h3>
                            <p class="text-gray-600">
                                Menggunakan Vue.js untuk komponen interaktif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                TailwindCSS
                            </h3>
                            <p class="text-gray-600">
                                Styling dengan utility-first CSS framework
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-16">
                <p class="text-lg text-gray-600 mb-8">
                    Siap memulai pengembangan aplikasi BI Anda?
                </p>
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors"
                >
                    Mulai Sekarang
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    name: "Welcome",
    components: {
        AppLayout,
    },
};
</script>
