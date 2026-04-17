<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([
    "add-job-description",
    "remove-job-description",
    "add-requirement",
    "remove-requirement",
]);
</script>

<template>
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi Pekerjaan
            </label>
            <div class="space-y-3">
                <div
                    v-for="(job, index) in form.job_description"
                    :key="`job-${index}`"
                    class="flex items-center space-x-2"
                >
                    <input
                        type="text"
                        v-model="form.job_description[index]"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        :placeholder="`Deskripsi pekerjaan ${index + 1}`"
                    />
                    <button
                        v-if="form.job_description.length > 1"
                        @click="emit('remove-job-description', index)"
                        type="button"
                        class="text-red-600 hover:text-red-800 p-2"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <button
                @click="emit('add-job-description')"
                type="button"
                class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center"
            >
                <svg
                    class="w-4 h-4 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Tambah Deskripsi Pekerjaan
            </button>
            <div
                v-if="form.errors.job_description"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.job_description }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Persyaratan
            </label>
            <div class="space-y-3">
                <div
                    v-for="(req, index) in form.requirements"
                    :key="`req-${index}`"
                    class="flex items-center space-x-2"
                >
                    <input
                        type="text"
                        v-model="form.requirements[index]"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        :placeholder="`Persyaratan ${index + 1}`"
                    />
                    <button
                        v-if="form.requirements.length > 1"
                        @click="emit('remove-requirement', index)"
                        type="button"
                        class="text-red-600 hover:text-red-800 p-2"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <button
                @click="emit('add-requirement')"
                type="button"
                class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center"
            >
                <svg
                    class="w-4 h-4 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                Tambah Persyaratan
            </button>
            <div
                v-if="form.errors.requirements"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.requirements }}
            </div>
        </div>
    </div>
</template>
