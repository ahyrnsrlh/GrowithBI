<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    maxDate: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label
                    for="date"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input
                    id="date"
                    type="date"
                    v-model="form.date"
                    :max="maxDate"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': form.errors.date }"
                />
                <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                    {{ form.errors.date }}
                </p>
            </div>

            <div>
                <label
                    for="hours"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Jam Kerja <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        id="hours"
                        type="number"
                        v-model="form.hours"
                        min="1"
                        max="12"
                        step="0.5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-12"
                        :class="{ 'border-red-500': form.errors.hours }"
                        placeholder="8"
                    />
                    <div
                        class="absolute inset-y-0 right-0 pr-4 flex items-center"
                    >
                        <span class="text-gray-500 text-sm">jam</span>
                    </div>
                </div>
                <p v-if="form.errors.hours" class="mt-1 text-sm text-red-600">
                    {{ form.errors.hours }}
                </p>
            </div>
        </div>

        <div>
            <label
                for="activities"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Aktivitas <span class="text-red-500">*</span>
            </label>
            <textarea
                id="activities"
                v-model="form.activities"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.activities }"
                placeholder="Jelaskan aktivitas yang dilakukan hari ini..."
            ></textarea>
            <p v-if="form.errors.activities" class="mt-1 text-sm text-red-600">
                {{ form.errors.activities }}
            </p>
        </div>

        <div>
            <label
                for="description"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Deskripsi/Catatan <span class="text-red-500">*</span>
            </label>
            <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.description }"
                placeholder="Tuliskan deskripsi detail, pembelajaran, atau catatan penting..."
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
            </p>
        </div>

        <div>
            <label
                for="learning_points"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Poin Pembelajaran
            </label>
            <textarea
                id="learning_points"
                v-model="form.learning_points"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Apa yang dipelajari hari ini? (opsional)"
            ></textarea>
        </div>
    </div>
</template>
