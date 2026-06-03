<template>
    <section id="divisions" class="py-16 sm:py-20 bg-white/50 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="text-center mb-12"
                data-aos="fade-up"
                data-aos-duration="800"
            >
                <h2 class="text-2xl md:text-3xl font-bold mb-3">
                    <span
                        class="mt-3 text-2xl sm:text-2xl lg:text-4xl font-semibold tracking-tight text-slate-900"
                    >
                        Divisi Magang di Bank Indonesia Provinsi Lampung
                    </span>
                </h2>
                <p
                    class="text-base sm:text-lg-2xl text-gray-600 max-w-2xl mx-auto"
                >
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
                    class="group bg-blue-600 rounded-xl shadow-sm hover:shadow-md transition transform hover:-translate-y-1 border border-blue-700/30 overflow-hidden flex flex-col"
                    data-aos="zoom-in"
                    data-aos-duration="600"
                    :data-aos-delay="index * 100"
                >
                    <div class="p-2 flex flex-col">
                        <div
                            class="flex items-center justify-center -mt-6 mb-0"
                        >
                            <div
                                class="w-28 h-28 sm:w-32 sm:h-32 lg:w-36 lg:h-36 flex items-center justify-center group-hover:scale-105 transition-transform duration-300"
                            >
                                <img
                                    src="/BI WHITE.png"
                                    alt="Bank Indonesia Logo"
                                    class="w-full h-full object-contain filter drop-shadow-lg"
                                />
                            </div>
                        </div>

                        <div
                            class="flex-1 flex flex-col items-center justify-start text-center px-2 -mt-6"
                        >
                            <h3
                                class="text-base font-semibold text-white mb-0 text-center leading-tight"
                            >
                                {{ division.name }}
                            </h3>
                            <p
                                class="text-blue-100 mb-1 text-center leading-relaxed text-sm line-clamp-2"
                            >
                                {{ division.description }}
                            </p>

                            <div class="mt-1">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-xs font-medium',
                                        division.is_active
                                            ? 'bg-green-500/20 text-green-200 border border-green-400/30'
                                            : 'bg-gray-500/20 text-gray-200 border border-gray-400/30',
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
                    </div>

                    <div
                        class="px-4 py-3 border-t border-blue-700/30 bg-blue-700"
                    >
                        <div
                            class="flex items-center justify-between gap-4 mb-3 text-xs text-blue-200"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                <span class="font-medium text-white">
                                    {{ division.quota }}
                                </span>
                                &nbsp;posisi
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span class="font-medium text-white">
                                    {{ division.duration || 6 }}
                                </span>
                                &nbsp;bulan
                            </div>
                        </div>
                        <Link
                            :href="route('public.division.detail', division.id)"
                            class="w-full bg-white hover:bg-blue-50 text-blue-700 py-2 px-3 rounded-md font-medium text-center transition block text-sm hover:shadow-sm border border-white/20"
                        >
                            Lihat Detail
                        </Link>
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
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
});
</script>
