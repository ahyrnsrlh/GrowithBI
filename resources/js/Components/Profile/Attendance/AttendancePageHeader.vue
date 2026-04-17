<template>
    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25"
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Absensi Online
                    </h1>
                    <p class="text-gray-500 mt-0.5">
                        Kelola kehadiran Anda dengan mudah
                    </p>
                </div>
            </div>

            <div
                class="hidden sm:flex items-center gap-2 bg-slate-100 px-4 py-2.5 rounded-xl"
            >
                <div
                    class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"
                ></div>
                <span class="text-sm font-medium text-slate-700">{{
                    currentTime
                }}</span>
            </div>
        </div>

        <div
            class="sm:hidden mt-4 flex items-center gap-2 bg-slate-100 px-4 py-2.5 rounded-xl w-fit"
        >
            <div
                class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"
            ></div>
            <span class="text-sm font-medium text-slate-700">{{
                currentTime
            }}</span>
        </div>
    </div>
</template>

<script setup>
defineProps({
    currentTime: { type: String, default: "" },
});
</script>
