<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal <span class="text-red-500">*</span>
            </label>
            <input
                v-model="form.date"
                type="date"
                :max="today"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-300': form.errors.date }"
                required
            />
            <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                {{ form.errors.date }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Durasi (jam) <span class="text-red-500">*</span>
            </label>
            <input
                v-model="form.duration"
                type="number"
                min="0"
                max="24"
                step="0.5"
                placeholder="8"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-300': form.errors.duration }"
                required
            />
            <p v-if="form.errors.duration" class="mt-1 text-sm text-red-600">
                {{ form.errors.duration }}
            </p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Judul Aktivitas <span class="text-red-500">*</span>
        </label>
        <input
            v-model="form.title"
            type="text"
            placeholder="Contoh: Pengembangan Fitur Login System"
            maxlength="255"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            :class="{ 'border-red-300': form.errors.title }"
            required
        />
        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
            {{ form.errors.title }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    today: {
        type: String,
        required: true,
    },
});
</script>
