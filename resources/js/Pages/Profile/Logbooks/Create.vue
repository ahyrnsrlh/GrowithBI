<template>
    <AuthenticatedLayout>
        <Head title="Tambah Logbook" />
        
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Logbook Harian</h1>
                    <p class="text-gray-600 mt-2">Catat aktivitas dan pencapaian hari ini</p>
                </div>
                <Link
                    :href="route('profile.logbooks.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informasi Logbook</h2>
                    <p class="text-sm text-gray-600 mt-1">Isi informasi lengkap tentang aktivitas harian Anda</p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Date and Hours Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="date"
                                v-model="form.date"
                                :max="new Date().toISOString().split('T')[0]"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.date }"
                            />
                            <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">{{ form.errors.date }}</p>
                        </div>

                        <div>
                            <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Kerja <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="hours"
                                    v-model="form.hours"
                                    min="1"
                                    max="12"
                                    step="0.5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-12"
                                    :class="{ 'border-red-500': form.errors.hours }"
                                    placeholder="8"
                                />
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <span class="text-gray-500 text-sm">jam</span>
                                </div>
                            </div>
                            <p v-if="form.errors.hours" class="mt-1 text-sm text-red-600">{{ form.errors.hours }}</p>
                        </div>
                    </div>

                    <!-- Activities -->
                    <div>
                        <label for="activities" class="block text-sm font-medium text-gray-700 mb-2">
                            Aktivitas <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="activities"
                            v-model="form.activities"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.activities }"
                            placeholder="Jelaskan aktivitas yang dilakukan hari ini..."
                        ></textarea>
                        <p v-if="form.errors.activities" class="mt-1 text-sm text-red-600">{{ form.errors.activities }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi/Catatan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.description }"
                            placeholder="Tuliskan deskripsi detail, pembelajaran, atau catatan penting..."
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <!-- Learning Points -->
                    <div>
                        <label for="learning_points" class="block text-sm font-medium text-gray-700 mb-2">
                            Poin Pembelajaran
                        </label>
                        <textarea
                            id="learning_points"
                            v-model="form.learning_points"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Apa yang dipelajari hari ini? (opsional)"
                        ></textarea>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lampiran (opsional)
                        </label>
                        <div
                            @drop="handleDrop"
                            @dragover.prevent
                            @dragenter.prevent
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                        >
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="attachments" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Seret file ke sini atau klik untuk pilih
                                    </span>
                                    <span class="mt-1 block text-xs text-gray-500">
                                        PNG, JPG, PDF hingga 10MB
                                    </span>
                                </label>
                                <input
                                    id="attachments"
                                    type="file"
                                    multiple
                                    @change="handleFileSelect"
                                    class="hidden"
                                    accept="image/*,.pdf"
                                />
                            </div>
                        </div>
                        
                        <!-- File Preview -->
                        <div v-if="form.attachments.length > 0" class="mt-4 space-y-2">
                            <h4 class="text-sm font-medium text-gray-700">File yang dipilih:</h4>
                            <div class="space-y-2">
                                <div
                                    v-for="(file, index) in form.attachments"
                                    :key="index"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                >
                                    <div class="flex items-center space-x-3">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ file.name }}</p>
                                            <p class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeFile(index)"
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link
                            :href="route('profile.logbooks.index')"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Logbook</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    acceptedApplication: Object
})

const form = useForm({
    date: new Date().toISOString().split('T')[0],
    hours: 8,
    activities: '',
    description: '',
    learning_points: '',
    attachments: []
})

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files)
    form.attachments = [...form.attachments, ...files]
}

const handleDrop = (event) => {
    event.preventDefault()
    const files = Array.from(event.dataTransfer.files)
    form.attachments = [...form.attachments, ...files]
}

const removeFile = (index) => {
    form.attachments.splice(index, 1)
}

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const submit = () => {
    form.post(route('profile.logbooks.store'), {
        onSuccess: () => {
            // Redirect handled by controller
        }
    })
}
</script>
