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
                    Update Status Pendaftaran
                </h3>
                <form @submit.prevent="$emit('submit')">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Status
                        </label>
                        <select
                            v-model="statusForm.status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                        >
                            <option value="menunggu">Menunggu</option>
                            <option value="in_review">Dalam Review</option>
                            <option value="interview_scheduled">
                                Wawancara Dijadwalkan
                            </option>
                            <option value="accepted">Diterima</option>
                            <option value="rejected">Ditolak</option>
                            <option value="expired">Kedaluwarsa</option>
                        </select>
                    </div>

                    <div
                        v-if="statusForm.status === 'interview_scheduled'"
                        class="space-y-4 mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200"
                    >
                        <h4 class="text-sm font-semibold text-blue-900">
                            Detail Jadwal Wawancara
                        </h4>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tanggal Wawancara
                            </label>
                            <input
                                type="date"
                                v-model="statusForm.interview_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Jam Wawancara
                            </label>
                            <input
                                type="time"
                                v-model="statusForm.interview_time"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Lokasi
                            </label>
                            <input
                                type="text"
                                v-model="statusForm.interview_location"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Contoh: Kantor Pusat, Ruang Meeting A"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Detail Lokasi / Instruksi
                            </label>
                            <textarea
                                v-model="statusForm.interview_location_detail"
                                rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Contoh: Jl. Contoh No. 123, Jakarta. Silakan lapor ke resepsionis lantai 3"
                            ></textarea>
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
                            rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                            placeholder="Jelaskan alasan penolakan (opsional)"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Catatan Admin
                        </label>
                        <textarea
                            v-model="statusForm.admin_notes"
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
    show: { type: Boolean, default: false },
    statusForm: { type: Object, required: true },
});

defineEmits(["close", "submit"]);
</script>
