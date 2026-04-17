<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="mb-8">
        <label class="flex items-center">
            <input
                v-model="form.is_active"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
            />
            <span class="ml-2 text-sm text-gray-700"
                >Aktif (dapat menerima pendaftaran)</span
            >
        </label>
    </div>
</template>
