<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <svg
                class="w-5 h-5 inline mr-2 text-green-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                />
            </svg>
            Aktivitas yang Dilakukan
        </h2>

        <div>
            <label
                for="activities"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Deskripsi Aktivitas <span class="text-red-500">*</span>
            </label>
            <textarea
                id="activities"
                v-model="form.activities"
                rows="6"
                placeholder="Jelaskan secara detail aktivitas yang dilakukan, tugas yang diselesaikan, dan hasil yang dicapai..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                :class="{ 'border-red-500': errors.activities }"
            ></textarea>
            <p v-if="errors.activities" class="mt-1 text-sm text-red-600">
                {{ errors.activities }}
            </p>
            <div class="flex justify-between mt-2">
                <p class="text-sm text-gray-500">Minimal 50 karakter</p>
                <p class="text-sm text-gray-500">
                    {{ form.activities?.length || 0 }} karakter
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
});
</script>
