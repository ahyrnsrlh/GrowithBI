<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <svg
                class="w-5 h-5 inline mr-2 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                />
            </svg>
            Tantangan & Kendala
        </h2>

        <div>
            <label
                for="challenges"
                class="block text-sm font-medium text-gray-700 mb-2"
                >Tantangan yang Dihadapi</label
            >
            <textarea
                id="challenges"
                v-model="form.challenges"
                rows="4"
                placeholder="Jelaskan kendala, kesulitan, atau tantangan yang dihadapi dan bagaimana cara mengatasinya..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                :class="{ 'border-red-500': errors.challenges }"
            ></textarea>
            <p v-if="errors.challenges" class="mt-1 text-sm text-red-600">
                {{ errors.challenges }}
            </p>
            <p class="mt-2 text-sm text-gray-500">
                {{ form.challenges?.length || 0 }} karakter
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
