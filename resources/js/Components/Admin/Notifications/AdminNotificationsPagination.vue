<script setup>
defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["change-page"]);
</script>

<template>
    <div
        v-if="pagination.last_page > 1"
        class="mt-6 flex items-center justify-between"
    >
        <div class="text-sm text-gray-700">
            Menampilkan
            {{ (pagination.current_page - 1) * pagination.per_page + 1 }}
            sampai
            {{
                Math.min(
                    pagination.current_page * pagination.per_page,
                    pagination.total,
                )
            }}
            dari {{ pagination.total }} notifikasi
        </div>
        <div class="flex items-center space-x-2">
            <button
                :disabled="pagination.current_page === 1"
                @click="emit('change-page', pagination.current_page - 1)"
                class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
                Sebelumnya
            </button>
            <span class="text-sm text-gray-700">
                Halaman {{ pagination.current_page }} dari
                {{ pagination.last_page }}
            </span>
            <button
                :disabled="pagination.current_page === pagination.last_page"
                @click="emit('change-page', pagination.current_page + 1)"
                class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
                Selanjutnya
            </button>
        </div>
    </div>
</template>
