<template>
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
                    >
                        ✨ Bidang Magang di Bank Indonesia Provinsi Lampung
                    </span>
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
                    class="group bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-sm hover:shadow-md transition transform hover:-translate-y-1 border border-blue-500/20 overflow-hidden flex flex-col"
                    data-aos="zoom-in"
                    data-aos-duration="600"
                    :data-aos-delay="index * 100"
                >
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
                            >
                                {{
                                    division.is_active
                                        ? "Tersedia"
                                        : "Tidak Tersedia"
                                }}
                            </span>
                        </div>
                    </div>

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
                            class="w-full bg-white hover:bg-blue-50 text-blue-700 py-2.5 px-4 rounded-lg font-medium text-center transition block text-sm hover:shadow-md border border-white/20"
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
