<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Detail Logbook</h2>

        <div class="space-y-6">
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">
                    Aktivitas yang Dilakukan
                </h3>
                <p class="text-gray-900 leading-relaxed">
                    {{ logbook.activities }}
                </p>
            </div>

            <div v-if="logbook.learning_outcome">
                <h3 class="text-sm font-medium text-gray-700 mb-2">
                    Hasil Pembelajaran
                </h3>
                <p class="text-gray-900 leading-relaxed">
                    {{ logbook.learning_outcome }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        Durasi
                    </h3>
                    <p class="text-gray-900">{{ logbook.duration }} jam</p>
                </div>
                <div v-if="logbook.user.division">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        Divisi
                    </h3>
                    <p class="text-gray-900">
                        {{ logbook.user.division.name }}
                    </p>
                </div>
            </div>

            <div v-if="logbook.attachments && logbook.attachments.length > 0">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Lampiran</h3>
                <div class="space-y-2">
                    <div
                        v-for="attachment in logbook.attachments"
                        :key="attachment.name"
                        class="flex items-center p-3 bg-gray-50 rounded-lg"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                            />
                        </svg>
                        <span class="text-gray-900">{{ attachment.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});
</script>
