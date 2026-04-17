<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Update Bulk Status
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    {{ selectedCount }} pendaftaran akan diupdate
                </p>
                <form @submit.prevent="$emit('submit')">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Status</label
                        >
                        <select
                            v-model="bulkForm.status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                        >
                            <option
                                v-for="option in statusOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Catatan Admin</label
                        >
                        <textarea
                            v-model="bulkForm.admin_notes"
                            rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Tambahkan catatan (opsional)"
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Update Semua
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    selectedCount: {
        type: Number,
        default: 0,
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    bulkForm: {
        type: Object,
        required: true,
    },
});

defineEmits(["close", "submit"]);
</script>
