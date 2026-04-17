<template>
    <div class="mb-8">
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
        >
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol
                        class="inline-flex items-center space-x-1 text-sm text-gray-500"
                    >
                        <li class="inline-flex items-center">
                            <a
                                href="/admin/dashboard"
                                class="hover:text-blue-600 transition-colors"
                            >
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span class="text-gray-700 font-medium"
                                    >Maps Real-Time</span
                                >
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Maps Real-Time
                </h1>
                <p class="mt-1 text-gray-500">
                    Monitoring lokasi absensi peserta magang secara real-time
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700"
                >
                    <svg
                        class="w-4 h-4 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    {{ formattedCurrentDate }}
                </div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl"
                >
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                        ></span>
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"
                        ></span>
                    </span>
                    <span class="text-sm font-medium text-emerald-700"
                        >Live</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    formattedCurrentDate: {
        type: String,
        required: true,
    },
});
</script>
