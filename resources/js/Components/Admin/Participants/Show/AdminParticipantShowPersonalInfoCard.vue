<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            Informasi Personal
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Email</label
                >
                <p class="text-gray-900">{{ participant.email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Nomor Telepon</label
                >
                <p class="text-gray-900">{{ participant.phone || "-" }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Alamat</label
                >
                <p class="text-gray-900">{{ participant.address || "-" }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    participant: {
        type: Object,
        required: true,
    },
});
</script>
