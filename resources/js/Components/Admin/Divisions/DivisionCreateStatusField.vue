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
        <label class="flex items-center gap-3">
            <input
                v-model="form.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            />
            <span class="text-sm text-gray-700">
                Aktif (dapat menerima pendaftaran)
            </span>
        </label>
    </div>
</template>
