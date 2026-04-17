<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
});

const cards = [
    {
        key: "total_divisions",
        label: "Total Divisi",
        caption: "Divisi yang tersedia",
        bgClass: "bg-blue-600 shadow-blue-500/20 hover:shadow-blue-500/25",
        iconPath:
            "M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4",
    },
    {
        key: "active_divisions",
        label: "Divisi Aktif",
        caption: "Sedang berjalan",
        bgClass:
            "bg-emerald-600 shadow-emerald-500/20 hover:shadow-emerald-500/25",
        iconPath: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        key: "total_quota",
        label: "Total Kuota",
        caption: "Kapasitas tersedia",
        bgClass:
            "bg-violet-600 shadow-violet-500/20 hover:shadow-violet-500/25",
        iconPath:
            "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
    },
    {
        key: "total_applications",
        label: "Total Aplikasi",
        caption: "Pendaftaran masuk",
        bgClass: "bg-amber-500 shadow-amber-500/20 hover:shadow-amber-500/25",
        iconPath:
            "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
    },
];
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div
            v-for="card in cards"
            :key="card.key"
            :class="[
                card.bgClass,
                'rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300',
            ]"
        >
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center"
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
                            :d="card.iconPath"
                        />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/90">
                        {{ card.label }}
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ stats[card.key] }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-xs text-white/70">{{ card.caption }}</p>
            </div>
        </div>
    </div>
</template>
