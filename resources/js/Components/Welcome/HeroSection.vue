<template>
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
                            >
                                Be The Next
                            </span>
                        </span>
                        <span class="block overflow-hidden">
                            <span
                                class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 bg-300% animate-gradient-x animate-slide-up delay-300"
                            >
                                Future Economic
                            </span>
                        </span>
                        <span class="block overflow-hidden">
                            <span
                                class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 bg-300% animate-gradient-x animate-slide-up delay-500"
                            >
                                & Digital Talent
                            </span>
                        </span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl">
                        Bank Indonesia Provinsi Lampung membuka peluang bagi
                        mahasiswa dan fresh graduate untuk terlibat dalam proyek
                        nyata di bidang ekonomi, keuangan, dan transformasi
                        digital.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-600/30 transition"
                        >
                            Daftar Sekarang
                        </Link>
                        <a
                            href="#divisions"
                            class="px-8 py-4 rounded-xl border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold transition"
                        >
                            Lihat Program
                        </a>
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
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    canRegister: Boolean,
});
</script>

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

.animate-slide-up {
    transform: translateY(100%);
    opacity: 0;
}
</style>
