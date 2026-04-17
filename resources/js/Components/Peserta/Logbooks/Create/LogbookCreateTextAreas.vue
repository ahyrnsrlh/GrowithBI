<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Deskripsi Aktivitas <span class="text-red-500">*</span>
        </label>
        <textarea
            v-model="form.activities"
            rows="6"
            placeholder="Jelaskan secara detail aktivitas yang telah dilakukan hari ini, termasuk tugas yang diselesaikan, kendala yang dihadapi, dan hasil yang dicapai..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            :class="{ 'border-red-300': form.errors.activities }"
            required
        ></textarea>
        <div class="flex justify-between items-center mt-1">
            <p v-if="form.errors.activities" class="text-sm text-red-600">
                {{ form.errors.activities }}
            </p>
            <p class="text-sm text-gray-500">
                {{ form.activities.length }} karakter
            </p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2"
            >Pembelajaran & Refleksi</label
        >
        <textarea
            v-model="form.learning_points"
            rows="4"
            placeholder="Apa yang dipelajari hari ini? Skill baru, pengetahuan, atau insight yang didapat..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            :class="{ 'border-red-300': form.errors.learning_points }"
        ></textarea>
        <p v-if="form.errors.learning_points" class="mt-1 text-sm text-red-600">
            {{ form.errors.learning_points }}
        </p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2"
            >Kendala & Solusi</label
        >
        <textarea
            v-model="form.challenges"
            rows="4"
            placeholder="Kendala yang dihadapi dan bagaimana cara mengatasinya..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            :class="{ 'border-red-300': form.errors.challenges }"
        ></textarea>
        <p v-if="form.errors.challenges" class="mt-1 text-sm text-red-600">
            {{ form.errors.challenges }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>
