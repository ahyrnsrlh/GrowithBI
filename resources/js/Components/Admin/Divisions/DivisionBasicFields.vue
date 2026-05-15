<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    descriptionRequired: {
        type: Boolean,
        default: false,
    },
    quotaMax: {
        type: Number,
        default: 50,
    },
});
</script>

<template>
    <div class="space-y-6">
        <div>
            <label
                for="name"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Nama Divisi *
            </label>
            <input
                id="name"
                type="text"
                v-model="form.name"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Masukkan nama divisi"
                required
            />
            <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                {{ form.errors.name }}
            </div>
        </div>

        <div>
            <label
                for="description"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Deskripsi
                <span v-if="descriptionRequired" class="text-red-500">*</span>
            </label>
            <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Deskripsi divisi dan tugas-tugas yang akan dikerjakan"
                :required="descriptionRequired"
            ></textarea>
            <div
                v-if="form.errors.description"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.description }}
            </div>
        </div>

        <div>
            <label
                for="quota"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Kuota Peserta *
            </label>
            <input
                id="quota"
                type="number"
                v-model="form.quota"
                min="1"
                :max="quotaMax"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Maksimal peserta yang dapat diterima"
                required
            />
            <div v-if="form.errors.quota" class="mt-1 text-sm text-red-600">
                {{ form.errors.quota }}
            </div>
        </div>
    </div>
</template>
