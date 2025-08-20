<template>
  <div class="border border-gray-200 rounded-lg p-4">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-sm font-medium text-gray-900">
        {{ title }}
        <span v-if="required" class="text-red-500 ml-1">*</span>
      </h3>
      <div v-if="currentFile" class="flex items-center text-green-600">
        <i class="fas fa-check-circle mr-1"></i>
        <span class="text-xs">Terupload</span>
      </div>
    </div>

    <!-- Current File Display -->
    <div v-if="currentFile" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <i class="fas fa-file-alt text-green-600 mr-2"></i>
          <span class="text-sm text-green-800">{{ getFileName(currentFile) }}</span>
        </div>
        <div class="flex items-center space-x-2">
          <a 
            :href="`/storage/${currentFile}`" 
            target="_blank" 
            class="text-xs text-blue-600 hover:text-blue-800"
          >
            <i class="fas fa-eye mr-1"></i>
            Lihat
          </a>
          <a 
            :href="`/storage/${currentFile}`" 
            download 
            class="text-xs text-green-600 hover:text-green-800"
          >
            <i class="fas fa-download mr-1"></i>
            Unduh
          </a>
        </div>
      </div>
    </div>

    <!-- Upload Area -->
    <div 
      @drop="handleDrop"
      @dragover.prevent
      @dragenter.prevent
      :class="[
        'border-2 border-dashed rounded-lg p-6 text-center transition-colors',
        isDragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
      ]"
    >
      <div class="flex flex-col items-center">
        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
        <p class="text-sm text-gray-600 mb-2">
          {{ currentFile ? 'Upload file baru untuk mengganti' : 'Drag & drop file atau klik untuk browse' }}
        </p>
        <p class="text-xs text-gray-500 mb-4">
          Format: PDF, JPG, PNG (Max: 5MB)
        </p>
        
        <label class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg cursor-pointer hover:bg-blue-700 transition-colors">
          <i class="fas fa-folder-open mr-2"></i>
          Pilih File
          <input 
            type="file" 
            @change="handleFileSelect"
            accept=".pdf,.jpg,.jpeg,.png"
            class="hidden"
          >
        </label>
      </div>
    </div>

    <!-- Upload Progress -->
    <div v-if="uploading" class="mt-4">
      <div class="flex items-center justify-between mb-2">
        <span class="text-sm text-gray-600">Uploading...</span>
        <span class="text-sm text-gray-600">{{ uploadProgress }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div 
          class="bg-blue-600 h-2 rounded-full transition-all duration-300"
          :style="`width: ${uploadProgress}%`"
        ></div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
      <div class="flex items-center text-red-700">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span class="text-sm">{{ errorMessage }}</span>
      </div>
    </div>

    <!-- File Requirements -->
    <div class="mt-3 text-xs text-gray-500">
      <div class="flex items-center space-x-4">
        <span><i class="fas fa-info-circle mr-1"></i>Max 5MB</span>
        <span><i class="fas fa-file-alt mr-1"></i>PDF, JPG, PNG</span>
        <span v-if="required"><i class="fas fa-asterisk mr-1 text-red-500"></i>Wajib</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  title: String,
  type: String,
  currentFile: String,
  required: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['upload'])

// State
const isDragging = ref(false)
const uploading = ref(false)
const uploadProgress = ref(0)
const errorMessage = ref('')

// Methods
const getFileName = (filePath) => {
  if (!filePath) return ''
  return filePath.split('/').pop()
}

const validateFile = (file) => {
  const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png']
  const maxSize = 5 * 1024 * 1024 // 5MB

  if (!allowedTypes.includes(file.type)) {
    return 'Format file tidak didukung. Gunakan PDF, JPG, atau PNG.'
  }

  if (file.size > maxSize) {
    return 'Ukuran file terlalu besar. Maksimal 5MB.'
  }

  return null
}

const processFile = (file) => {
  errorMessage.value = ''
  
  const error = validateFile(file)
  if (error) {
    errorMessage.value = error
    return
  }

  uploading.value = true
  uploadProgress.value = 0

  // Simulate upload progress
  const interval = setInterval(() => {
    uploadProgress.value += 10
    if (uploadProgress.value >= 90) {
      clearInterval(interval)
    }
  }, 100)

  // Emit the upload event
  emit('upload', props.type, file)

  // Reset states after a delay
  setTimeout(() => {
    uploading.value = false
    uploadProgress.value = 0
    clearInterval(interval)
  }, 2000)
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    processFile(file)
  }
}

const handleDrop = (event) => {
  event.preventDefault()
  isDragging.value = false
  
  const files = event.dataTransfer.files
  if (files.length > 0) {
    processFile(files[0])
  }
}

const handleDragEnter = () => {
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

// Lifecycle
onMounted(() => {
  document.addEventListener('dragenter', handleDragEnter)
  document.addEventListener('dragleave', handleDragLeave)
})

onUnmounted(() => {
  document.removeEventListener('dragenter', handleDragEnter)
  document.removeEventListener('dragleave', handleDragLeave)
})
</script>
