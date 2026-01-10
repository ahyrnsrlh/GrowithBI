<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
});

const mobileMenuOpen = ref(false);
const isScrolled = ref(false);

function handleScroll() {
    isScrolled.value = window.scrollY > 50;
}

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    handleScroll(); // Check initial scroll position
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<template>
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
                    <Link href="/">
                        <img
                            src="/storage/logo_web.png"
                            alt="GrowithBI Bank Indonesia Lampung"
                            class="h-8 w-auto object-contain"
                        />
                    </Link>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <Link
                        href="/#features"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                    >
                        Program
                    </Link>
                    <Link
                        href="/#testimonials"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                    >
                        Testimoni
                    </Link>
                    <Link
                        href="/#divisions"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                    >
                        Divisi
                    </Link>
                    <Link
                        href="/#faq"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                    >
                        FAQ
                    </Link>
                    <Link
                        href="/#contact"
                        class="text-white hover:text-blue-200 font-medium transition-colors duration-200"
                    >
                        Kontak
                    </Link>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <Link
                        v-if="canLogin && !auth?.user"
                        href="/login"
                        class="text-white hover:text-blue-200 font-semibold transition-colors duration-200"
                    >
                        Masuk
                    </Link>
                    <Link
                        v-if="canRegister && !auth?.user"
                        href="/register"
                        class="px-6 py-2.5 bg-white hover:bg-gray-100 text-blue-800 font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105"
                    >
                        Daftar
                    </Link>
                    <div v-if="auth?.user" class="flex items-center space-x-3">
                        <span class="text-white font-medium">{{
                            auth.user.name
                        }}</span>
                        <Link
                            href="/dashboard"
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
                class="md:hidden mt-4 p-6 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg shadow-black/10 border border-white/20"
            >
                <div class="flex flex-col space-y-4">
                    <Link
                        href="/#features"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                    >
                        Program
                    </Link>
                    <Link
                        href="/#testimonials"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                    >
                        Testimoni
                    </Link>
                    <Link
                        href="/#divisions"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                    >
                        Divisi
                    </Link>
                    <Link
                        href="/#faq"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                    >
                        FAQ
                    </Link>
                    <Link
                        href="/#contact"
                        @click="mobileMenuOpen = false"
                        class="text-gray-700 hover:text-blue-600 font-medium py-2 transition-colors duration-200"
                    >
                        Kontak
                    </Link>

                    <div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
                        <Link
                            v-if="canLogin && !auth?.user"
                            href="/login"
                            @click="mobileMenuOpen = false"
                            class="block text-center py-3 text-blue-600 hover:text-blue-700 font-semibold transition-colors duration-200"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister && !auth?.user"
                            href="/register"
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
                                href="/dashboard"
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
</template>
