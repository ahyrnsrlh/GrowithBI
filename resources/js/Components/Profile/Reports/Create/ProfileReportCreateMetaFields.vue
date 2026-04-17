<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-6">
        <div>
            <label
                for="title"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Judul Laporan <span class="text-red-500">*</span>
            </label>
            <input
                id="title"
                type="text"
                v-model="form.title"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.title }"
                placeholder="Contoh: Laporan Akhir Magang Divisi IT"
                required
            />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                {{ form.errors.title }}
            </p>
        </div>

        <div>
            <label
                for="description"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Deskripsi <span class="text-gray-500">(Opsional)</span>
            </label>
            <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.description }"
                placeholder="Deskripsi singkat tentang isi laporan..."
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
            </p>
        </div>
    </div>
</template>
