<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <h3 class="text-lg font-medium text-gray-900">Detail Laporan</h3>

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
                Judul Laporan
            </label>
            <input
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Masukkan judul laporan"
            />
            <div v-if="form.errors.title" class="mt-2 text-sm text-red-600">
                {{ form.errors.title }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label
                    for="period_start"
                    class="block text-sm font-medium text-gray-700"
                >
                    Periode Mulai
                </label>
                <input
                    id="period_start"
                    v-model="form.period_start"
                    type="date"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <div
                    v-if="form.errors.period_start"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.period_start }}
                </div>
            </div>

            <div>
                <label
                    for="period_end"
                    class="block text-sm font-medium text-gray-700"
                >
                    Periode Selesai
                </label>
                <input
                    id="period_end"
                    v-model="form.period_end"
                    type="date"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <div
                    v-if="form.errors.period_end"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.period_end }}
                </div>
            </div>
        </div>

        <div>
            <label
                for="summary"
                class="block text-sm font-medium text-gray-700"
            >
                Ringkasan Aktivitas
            </label>
            <textarea
                id="summary"
                v-model="form.summary"
                rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Jelaskan ringkasan aktivitas selama periode laporan"
            ></textarea>
            <div v-if="form.errors.summary" class="mt-2 text-sm text-red-600">
                {{ form.errors.summary }}
            </div>
        </div>

        <div>
            <label
                for="achievements"
                class="block text-sm font-medium text-gray-700"
            >
                Pencapaian
            </label>
            <textarea
                id="achievements"
                v-model="form.achievements"
                rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Jelaskan pencapaian dan hasil yang diperoleh"
            ></textarea>
            <div
                v-if="form.errors.achievements"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.achievements }}
            </div>
        </div>

        <div>
            <label
                for="challenges"
                class="block text-sm font-medium text-gray-700"
            >
                Tantangan & Pembelajaran
            </label>
            <textarea
                id="challenges"
                v-model="form.challenges"
                rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Jelaskan tantangan yang dihadapi dan pembelajaran yang didapat"
            ></textarea>
            <div
                v-if="form.errors.challenges"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.challenges }}
            </div>
        </div>

        <div>
            <label
                for="next_plans"
                class="block text-sm font-medium text-gray-700"
            >
                Rencana Selanjutnya
            </label>
            <textarea
                id="next_plans"
                v-model="form.next_plans"
                rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Jelaskan rencana aktivitas untuk periode selanjutnya"
            ></textarea>
            <div
                v-if="form.errors.next_plans"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.next_plans }}
            </div>
        </div>
    </div>
</template>
