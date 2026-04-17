<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    handleFileChange: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            Upload File Laporan
        </h3>

        <div>
            <label
                for="report_file"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                File Laporan <span class="text-red-500">*</span>
            </label>
            <input
                id="report_file"
                type="file"
                @change="handleFileChange"
                accept=".pdf,.doc,.docx,.xls,.xlsx"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.report_file }"
                required
            />
            <p class="mt-1 text-xs text-gray-500">
                Format: PDF, Word (.doc, .docx), Excel (.xls, .xlsx). Maksimal
                10MB.
            </p>
            <div
                v-if="form.errors.report_file"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.report_file }}
            </div>
        </div>
    </div>
</template>
