<template>
    <section
        class="relative z-10 overflow-hidden pt-28 pb-14 sm:pt-32 sm:pb-16 bg-white/30"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-10 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h1
                        class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-slate-900 mb-5 sm:mb-6"
                    >
                        <span class="block">Be The Next</span>
                        <span class="block">Future Economic</span>
                        <span class="block">& Digital Talent</span>
                    </h1>
                    <p
                        class="text-base sm:text-lg text-gray-600 mb-7 sm:mb-8 max-w-xl"
                    >
                        Bank Indonesia Provinsi Lampung membuka peluang bagi
                        mahasiswa dan fresh graduate untuk terlibat dalam proyek
                        nyata di bidang ekonomi, keuangan, dan transformasi
                        digital.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="w-full sm:w-auto text-center px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-600/30 transition"
                        >
                            Daftar Sekarang
                        </Link>
                        <a
                            href="#divisions"
                            class="w-full sm:w-auto text-center px-7 py-3.5 rounded-xl border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold transition"
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
                        class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg"
                    >
                        <img
                            src="/hero.webp"
                            alt="Hero Banner"
                            width="999"
                            height="660"
                            class="w-full h-full object-cover"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
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
@keyframes slide-up {
    0% {
        transform: translateY(18px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slide-up 700ms cubic-bezier(0.2, 0.9, 0.3, 1) both;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-8px);
    }
}
</style>
