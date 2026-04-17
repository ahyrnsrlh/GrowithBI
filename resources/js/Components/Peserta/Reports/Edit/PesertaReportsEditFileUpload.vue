<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    currentFileName: {
        type: String,
        default: "Unknown",
    },
    handleFileChange: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div>
        <label
            for="report_file"
            class="block text-sm font-medium text-gray-700 mb-2"
        >
            Ganti File Laporan <span class="text-gray-500">(Opsional)</span>
        </label>

        <div class="space-y-3">
            <div
                class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg"
            >
                <svg
                    class="w-5 h-5 text-gray-400 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">
                        File saat ini: {{ currentFileName }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Upload file baru jika ingin mengganti
                    </p>
                </div>
            </div>

            <input
                id="report_file"
                type="file"
                @change="handleFileChange"
                accept=".pdf,.doc,.docx,.xls,.xlsx"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.report_file }"
            />
        </div>

        <p class="mt-1 text-xs text-gray-500">
            Format: PDF, Word (.doc, .docx), Excel (.xls, .xlsx). Maksimal 10MB.
        </p>
        <p v-if="form.errors.report_file" class="mt-1 text-sm text-red-600">
            {{ form.errors.report_file }}
        </p>
    </div>
</template>
