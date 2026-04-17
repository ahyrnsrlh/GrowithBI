<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <svg
                class="w-5 h-5 inline mr-2 text-purple-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
            </svg>
            Pembelajaran & Insight
        </h2>

        <div>
            <label
                for="learning_points"
                class="block text-sm font-medium text-gray-700 mb-2"
                >Apa yang Dipelajari</label
            >
            <textarea
                id="learning_points"
                v-model="form.learning_points"
                rows="4"
                placeholder="Jelaskan hal-hal baru yang dipelajari, skill yang didapat, atau insight yang diperoleh dari aktivitas hari ini..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                :class="{ 'border-red-500': errors.learning_points }"
            ></textarea>
            <p v-if="errors.learning_points" class="mt-1 text-sm text-red-600">
                {{ errors.learning_points }}
            </p>
            <p class="mt-2 text-sm text-gray-500">
                {{ form.learning_points?.length || 0 }} karakter
            </p>
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
