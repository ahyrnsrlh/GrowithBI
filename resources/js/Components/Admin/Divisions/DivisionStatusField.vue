<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2"
            >Status Divisi</label
        >
        <div class="flex items-center space-x-4">
            <label class="flex items-center">
                <input
                    type="radio"
                    v-model="form.is_active"
                    :value="true"
                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                />
                <span class="ml-2 text-sm text-gray-700">Aktif</span>
            </label>
            <label class="flex items-center">
                <input
                    type="radio"
                    v-model="form.is_active"
                    :value="false"
                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                />
                <span class="ml-2 text-sm text-gray-700">Tidak Aktif</span>
            </label>
        </div>
        <div v-if="form.errors.is_active" class="mt-1 text-sm text-red-600">
            {{ form.errors.is_active }}
        </div>
    </div>
</template>
