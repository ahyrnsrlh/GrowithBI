<template>
    <div class="mb-8">
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Selamat Datang di Dashboard 👋
                </h1>
                <p class="mt-1 text-gray-500">
                    Pantau statistik dan kelola program internship GrowithBI
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <div
                    class="flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full"
                >
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
                        ></span>
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"
                        ></span>
                    </span>
                    <span class="text-sm font-medium text-green-700"
                        >Sistem Aktif</span
                    >
                </div>
                <div class="text-sm text-gray-500">{{ currentDate }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    currentDate: {
        type: String,
        required: true,
    },
});
</script>
