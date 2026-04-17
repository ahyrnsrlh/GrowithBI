<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Informasi Peserta
        </h3>

        <div class="space-y-3">
            <div class="flex items-center space-x-3">
                <div
                    class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center"
                >
                    <span class="text-white text-lg font-medium">{{
                        logbook.user.name.charAt(0).toUpperCase()
                    }}</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">
                        {{ logbook.user.name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ logbook.user.email }}
                    </p>
                </div>
            </div>

            <div
                v-if="logbook.user.division"
                class="pt-3 border-t border-gray-200"
            >
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Divisi:</span>
                    {{ logbook.user.division.name }}
                </p>
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
