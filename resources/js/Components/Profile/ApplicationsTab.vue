<template>
    <div class="space-y-6">
        <div
            v-if="applications.length === 0"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center"
        >
            <div
                class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <i class="fas fa-briefcase text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Belum Ada Lamaran
            </h3>
            <p class="text-gray-500 mb-6">
                Anda belum memiliki lamaran magang. Mulai lamar sekarang!
            </p>
            <a
                :href="route('home') + '#divisions'"
                class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                Daftar Magang Sekarang
            </a>
        </div>

        <div v-else class="space-y-4">
            <ApplicationCard
                v-for="application in applications"
                :key="application.id"
                :application="application"
            />
        </div>
    </div>
</template>

<script setup>
import ApplicationCard from "@/Components/ApplicationCard.vue";

defineProps({
    applications: { type: Array, default: () => [] },
});
</script>
