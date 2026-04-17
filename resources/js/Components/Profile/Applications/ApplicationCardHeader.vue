<template>
    <div class="relative p-6 pb-4">
        <div
            :class="[
                'absolute top-0 left-0 right-0 h-1',
                getAccentGradient(application.status),
            ]"
        ></div>

        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <h3
                    class="text-xl font-bold text-gray-900 tracking-tight truncate"
                >
                    {{ application.division?.name || "Divisi" }}
                </h3>
                <p
                    class="mt-1 text-sm text-gray-500 line-clamp-2 leading-relaxed"
                >
                    {{
                        application.division?.description ||
                        "Deskripsi tidak tersedia"
                    }}
                </p>
            </div>

            <div class="flex-shrink-0">
                <span
                    :class="[
                        'inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full transition-all',
                        getStatusBadgeClass(application.status),
                    ]"
                >
                    <span
                        :class="[
                            'w-1.5 h-1.5 rounded-full',
                            getStatusDotClass(application.status),
                        ]"
                    ></span>
                    {{ getStatusText(application.status) }}
                </span>
            </div>
        </div>

        <div
            class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500"
        >
            <div class="flex items-center gap-2">
                <svg
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                </svg>
                <span>{{ formatDate(application.created_at) }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                    />
                </svg>
                <span class="font-mono"
                    >#{{ String(application.id).padStart(4, "0") }}</span
                >
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    application: { type: Object, required: true },
    getAccentGradient: { type: Function, required: true },
    getStatusBadgeClass: { type: Function, required: true },
    getStatusDotClass: { type: Function, required: true },
    getStatusText: { type: Function, required: true },
    formatDate: { type: Function, required: true },
});
</script>
