<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-[28rem] shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Update Status Pendaftaran
                </h3>
                <form @submit.prevent="$emit('submit')">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Status Baru</label
                        >
                        <select
                            v-model="statusForm.status"
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

                    <div
                        v-if="statusForm.status === 'interview_scheduled'"
                        class="mb-4 space-y-4"
                    >
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal & Waktu Wawancara
                            </label>
                            <input
                                type="datetime-local"
                                v-model="statusForm.interview_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Lokasi Wawancara
                            </label>
                            <input
                                type="text"
                                v-model="statusForm.interview_location"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Contoh: Kantor BI Lampung, Ruang Meeting Lt. 3"
                            />
                        </div>
                    </div>

                    <div v-if="statusForm.status === 'rejected'" class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Alasan Penolakan
                        </label>
                        <textarea
                            v-model="statusForm.rejection_reason"
                            rows="2"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Jelaskan alasan penolakan (akan dikirim ke pelamar)"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Catatan Admin (Internal)</label
                        >
                        <textarea
                            v-model="statusForm.admin_notes"
                            rows="2"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Catatan internal (tidak dikirim ke pelamar)"
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
                            Update
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
    statusOptions: {
        type: Array,
        default: () => [],
    },
    statusForm: {
        type: Object,
        required: true,
    },
});

defineEmits(["close", "submit"]);
</script>
