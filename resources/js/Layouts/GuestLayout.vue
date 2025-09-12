<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";

// Props untuk kontrol layout
defineProps({
    variant: {
        type: String,
        default: "auth", // 'auth' untuk login/register, 'public' untuk halaman publik
    },
    container: {
        type: Boolean,
        default: false, // true untuk membungkus dengan container max-width
    },
});
</script>

<template>
    <div>
        <!-- Layout untuk halaman auth (login/register) -->
        <template v-if="variant === 'auth'">
            <div
                class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
            >
                <div class="mb-6">
                    <Link href="/">
                        <ApplicationLogo
                            class="h-16 w-16 sm:h-20 sm:w-20 fill-current text-gray-500"
                        />
                    </Link>
                </div>

                <div
                    class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
                >
                    <slot />
                </div>
            </div>
        </template>

        <!-- Layout untuk halaman publik (full-width responsive) -->
        <template v-else>
            <div class="min-h-screen bg-white relative overflow-hidden">
                <!-- Grid Background Pattern -->
                <div class="absolute inset-0 opacity-30">
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
                            background-size: 50px 50px;
                        "
                    ></div>
                </div>

                <!-- Header Navigation -->
                <header
                    class="relative z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200"
                >
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center py-4">
                            <!-- Logo Section -->
                            <div class="flex items-center">
                                <Link href="/" class="flex items-center">
                                    <ApplicationLogo
                                        class="h-8 w-8 sm:h-10 sm:w-10 fill-current text-blue-600"
                                    />
                                    <span
                                        class="ml-3 text-xl sm:text-2xl font-bold text-gray-900"
                                    >
                                        GrowithBI
                                    </span>
                                </Link>
                            </div>

                            <!-- Navigation Menu -->
                            <nav class="hidden md:flex items-center space-x-8">
                                <Link
                                    href="/divisions"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Kriteria
                                </Link>
                                <Link
                                    href="/about"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Benefit
                                </Link>
                                <Link
                                    href="/requirements"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    Persyaratan
                                </Link>
                                <Link
                                    href="/faq"
                                    class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium"
                                >
                                    FAQ
                                </Link>
                            </nav>

                            <!-- CTA Button -->
                            <div class="flex items-center">
                                <Link
                                    href="/apply"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                >
                                    Daftar Sekarang
                                </Link>
                            </div>

                            <!-- Mobile Menu Button -->
                            <div class="md:hidden">
                                <button
                                    class="text-gray-700 hover:text-blue-600 focus:outline-none"
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
                    </div>
                </header>

                <!-- Main content area -->
                <main class="relative z-10">
                    <template v-if="container">
                        <!-- Dengan container max-width -->
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <slot />
                        </div>
                    </template>
                    <template v-else>
                        <!-- Full width tanpa container -->
                        <slot />
                    </template>
                </main>
            </div>
        </template>
    </div>
</template>
