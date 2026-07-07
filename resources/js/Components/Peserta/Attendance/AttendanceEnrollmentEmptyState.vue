<template>
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        <!-- Header bar matching the attendance card style -->
        <div class="bg-[#2F4C9E] px-6 py-4 border-b border-[#274089]">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Status Hari Ini
            </h2>
            <div class="mt-2 flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse" />
                <span class="text-xs font-medium text-amber-300">Wajah belum terdaftar</span>
            </div>
        </div>

        <!-- Empty state body -->
        <div class="p-8 flex flex-col items-center text-center">
            <!-- Icon -->
            <div class="relative mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-full flex items-center justify-center shadow-inner">
                    <svg class="w-10 h-10 text-[#2F4C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <!-- Lock overlay -->
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">Presensi Belum Tersedia</h3>
            <p class="text-sm text-gray-500 max-w-sm leading-relaxed mb-6">
                Anda belum menyelesaikan registrasi wajah. Silakan lengkapi foto profil terlebih dahulu
                untuk mengaktifkan fitur presensi.
            </p>

            <!-- Steps indicator -->
            <div class="flex items-center gap-3 mb-6 text-xs text-gray-400">
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-emerald-700 font-medium">Login</span>
                </div>
                <div class="w-6 h-px bg-gray-300" />
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-amber-100 border border-amber-400 flex items-center justify-center">
                        <span class="text-amber-600 font-bold text-xs">2</span>
                    </div>
                    <span class="text-amber-700 font-medium">Daftarkan Wajah</span>
                </div>
                <div class="w-6 h-px bg-gray-300" />
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center">
                        <span class="text-gray-400 font-bold text-xs">3</span>
                    </div>
                    <span>Presensi</span>
                </div>
            </div>

            <!-- CTA Button -->
            <Link
                href="/profile"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#2F4C9E] hover:bg-[#274089] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 active:translate-y-0"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Lengkapi Profil
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>