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
                    <!-- Current Status Info Box -->
                    <div class="mb-5 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-1">Panduan Alur Pendaftaran</span>
                            Status pendaftaran saat ini adalah <span class="font-extrabold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md inline-block mt-0.5">{{ getLabel(currentStatus) }}</span>.
                            Pilihan di bawah telah disaring otomatis untuk memandu proses tahap demi tahap.
                        </div>
                    </div>

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
                                v-for="option in filteredOptions"
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
                                :min="minDateTime"
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
import { computed } from "vue";

const props = defineProps({
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
    currentStatus: {
        type: String,
        default: "menunggu",
    },
});

defineEmits(["close", "submit"]);

const getLabel = (value) => {
    const option = props.statusOptions.find((o) => o.value === value);
    return option ? option.label : value;
};

const filteredOptions = computed(() => {
    const current = props.currentStatus || "menunggu";

    // Status transition map to enforce step-by-step update flow
    const transitions = {
        menunggu: ["menunggu", "in_review", "rejected", "expired"],
        in_review: ["in_review", "interview_scheduled", "rejected", "expired"],
        interview_scheduled: [
            "interview_scheduled",
            "accepted",
            "rejected",
            "expired",
        ],
        accepted: ["accepted", "letter_ready", "expired"],
        letter_ready: ["letter_ready", "expired"],
        rejected: ["rejected", "menunggu"],
        expired: ["expired", "menunggu"],
    };

    const allowed = transitions[current] || [current];
    return props.statusOptions.filter((option) => allowed.includes(option.value));
});

const minDateTime = computed(() => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const hours = String(now.getHours()).padStart(2, "0");
    const minutes = String(now.getMinutes()).padStart(2, "0");
    return `${year}-${month}-${day}T${hours}:${minutes}`;
});
</script>
