<script setup>
import { PlusIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["create-user"]);
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">
                        Daftar Pengguna
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola semua pengguna dalam sistem
                    </p>
                </div>
                <button
                    @click="emit('create-user')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                >
                    <PlusIcon class="h-5 w-5 mr-2" />
                    Tambah Pengguna
                </button>
            </div>
        </div>
    </div>
</template>
