<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    requiredFields: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label
                for="start_date"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Tanggal Mulai
                <span v-if="requiredFields" class="text-red-500">*</span>
            </label>
            <input
                id="start_date"
                type="date"
                v-model="form.start_date"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :required="requiredFields"
            />
            <div
                v-if="form.errors.start_date"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.start_date }}
            </div>
        </div>

        <div>
            <label
                for="end_date"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Tanggal Selesai
                <span v-if="requiredFields" class="text-red-500">*</span>
            </label>
            <input
                id="end_date"
                type="date"
                v-model="form.end_date"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :required="requiredFields"
            />
            <div v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">
                {{ form.errors.end_date }}
            </div>
        </div>

        <div>
            <label
                for="application_deadline"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Batas Waktu Pendaftaran
                <span v-if="requiredFields" class="text-red-500">*</span>
            </label>
            <input
                id="application_deadline"
                type="date"
                v-model="form.application_deadline"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :required="requiredFields"
            />
            <div
                v-if="form.errors.application_deadline"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.application_deadline }}
            </div>
        </div>

        <div>
            <label
                for="selection_announcement"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Pengumuman Hasil Seleksi
                <span v-if="requiredFields" class="text-red-500">*</span>
            </label>
            <input
                id="selection_announcement"
                type="date"
                v-model="form.selection_announcement"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :required="requiredFields"
            />
            <div
                v-if="form.errors.selection_announcement"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.selection_announcement }}
            </div>
        </div>
    </div>
</template>
