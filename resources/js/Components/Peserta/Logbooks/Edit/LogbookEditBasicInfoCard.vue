<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <svg
                class="w-5 h-5 inline mr-2 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            Informasi Dasar
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="lg:col-span-2">
                <label
                    for="title"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Judul Logbook <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    placeholder="Masukkan judul aktivitas yang dilakukan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.title }"
                />
                <p v-if="errors.title" class="mt-1 text-sm text-red-600">
                    {{ errors.title }}
                </p>
            </div>

            <div>
                <label
                    for="date"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input
                    id="date"
                    v-model="form.date"
                    type="date"
                    :max="today"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.date }"
                />
                <p v-if="errors.date" class="mt-1 text-sm text-red-600">
                    {{ errors.date }}
                </p>
            </div>

            <div>
                <label
                    for="duration"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Durasi (jam) <span class="text-red-500">*</span>
                </label>
                <input
                    id="duration"
                    v-model.number="form.duration"
                    type="number"
                    min="0.5"
                    max="12"
                    step="0.5"
                    placeholder="8"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.duration }"
                />
                <p v-if="errors.duration" class="mt-1 text-sm text-red-600">
                    {{ errors.duration }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Maksimal 12 jam per hari
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    today: {
        type: String,
        required: true,
    },
});
</script>
