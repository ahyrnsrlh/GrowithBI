<template>
    <div class="mb-8">
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-3xl font-bold text-blue-600">Logbook Harian</h1>
                <p class="text-gray-600 mt-2">
                    Catat perkembangan dan kegiatan harian selama masa magang
                </p>
            </div>
            <button
                @click="$emit('create')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
            >
                <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Tambah Logbook
            </button>
        </div>
    </div>
</template>

<script setup>
defineEmits(["create"]);
</script>
