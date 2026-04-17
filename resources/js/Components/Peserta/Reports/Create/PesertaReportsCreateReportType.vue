<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});

const reportTypes = [
    {
        value: "weekly",
        title: "Laporan Mingguan",
        description: "Ringkasan aktivitas per minggu",
    },
    {
        value: "monthly",
        title: "Laporan Bulanan",
        description: "Ringkasan aktivitas per bulan",
    },
    {
        value: "final",
        title: "Laporan Akhir",
        description: "Laporan keseluruhan magang",
    },
];
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Jenis Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <label
                v-for="reportType in reportTypes"
                :key="reportType.value"
                class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none"
            >
                <input
                    v-model="form.report_type"
                    type="radio"
                    :value="reportType.value"
                    class="sr-only"
                />
                <span class="flex flex-1">
                    <span class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-900">
                            {{ reportType.title }}
                        </span>
                        <span
                            class="mt-1 flex items-center text-sm text-gray-500"
                        >
                            {{ reportType.description }}
                        </span>
                    </span>
                </span>
                <svg
                    v-show="form.report_type === reportType.value"
                    class="h-5 w-5 text-blue-600"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
            </label>
        </div>
    </div>
</template>
