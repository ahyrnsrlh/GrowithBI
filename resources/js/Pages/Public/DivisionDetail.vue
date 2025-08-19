<template>
  <Head :title="division.name" />
  <GuestLayout>
    <!-- Modal Notification Overlay -->
    <div v-if="notification.show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
          <div class="text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4" :class="notification.type === 'success' ? 'bg-green-100' : 'bg-red-100'">
              <svg v-if="notification.type === 'success'" class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              <svg v-else class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-medium text-gray-900 mb-3">
              {{ notification.type === 'success' ? 'Berhasil' : 'Gagal' }}
            </h3>
            
            <!-- Message -->
            <div class="text-sm text-gray-600 mb-6">
              {{ notification.message }}
            </div>
            
            <!-- Button -->
            <button
              @click="notification.show = false"
              class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm"
              :class="notification.type === 'success' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'"
            >
              OK
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="min-h-screen bg-gray-50">
      <!-- Header Section -->
      <div class="bg-white border-b">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
              <!-- Company Logo -->
              <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-teal-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                  {{ division.name.charAt(0) }}
                </div>
              </div>
              
              <!-- Job Info -->
              <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ division.name }}</h1>
                <p class="text-lg text-gray-600 mb-2">PT GrowithBI (Persero)</p>
                
                <!-- Location and Type -->
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                  <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ division.location }}</span>
                  </div>
                  <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>{{ division.employment_type }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3">
              <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                Bagikan Lowongan
              </button>
              <button 
                @click="quickRegister"
                :disabled="isLoading"
                :class="[
                  'inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200',
                  isLoading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'
                ]">
                <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isLoading ? 'Memproses...' : 'Daftar Sekarang' }}
              </button>
            </div>
          </div>
          
          <!-- Status Badge -->
          <div class="mt-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Umum
            </span>
          </div>
        </div>
      </div>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Navigation Tabs -->
            <div class="border-b border-gray-200">
              <nav class="-mb-px flex space-x-8">
                <button 
                  @click="activeTab = 'description'"
                  :class="[
                    'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
                    activeTab === 'description' 
                      ? 'border-blue-500 text-blue-600' 
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                  ]"
                >
                  Deskripsi Lowongan
                </button>
                <button 
                  @click="activeTab = 'company'"
                  :class="[
                    'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
                    activeTab === 'company' 
                      ? 'border-blue-500 text-blue-600' 
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                  ]"
                >
                  Perusahaan
                </button>
              </nav>
            </div>

            <!-- Tab Content -->
            <div v-if="activeTab === 'description'" class="space-y-6">
              <!-- Education Requirements -->
              <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                  <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900">Pendidikan</h3>
                </div>
                <ul class="space-y-2">
                  <li v-for="req in division.required_education" :key="req" class="text-gray-700">{{ req }}</li>
                </ul>
              </div>

              <!-- Required Documents -->
              <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                  <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900">Persyaratan Dokumen</h3>
                </div>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <li v-for="doc in division.required_documents" :key="doc" class="flex items-center text-gray-700">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ doc }}
                  </li>
                </ul>
              </div>

              <!-- Important Dates -->
              <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                  <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900">Tanggal penting</h3>
                </div>
                <div class="space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Durasi</span>
                    <span class="font-medium text-gray-900">: {{ division.duration }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Penutupan lamaran</span>
                    <span class="font-medium text-gray-900">: {{ division.application_deadline }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pengumuman lolos seleksi</span>
                    <span class="font-medium text-gray-900">: {{ division.selection_announcement }}</span>
                  </div>
                </div>
              </div>

              <!-- Job Description -->
              <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Lowongan</h3>
                <div class="prose prose-gray max-w-none">
                  <p class="text-gray-700 mb-4"><strong>Deskripsi Kerja :</strong></p>
                  <ol class="list-decimal list-inside space-y-1 text-gray-700">
                    <li v-for="desc in division.job_description" :key="desc">{{ desc.replace(/^\d+\.\s*/, '') }}</li>
                  </ol>
                </div>
              </div>
            </div>

            <!-- Company Tab -->
            <div v-if="activeTab === 'company'" class="space-y-6">
              <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tentang PT GrowithBI (Persero)</h3>
                <p class="text-gray-700 leading-relaxed">
                  PT GrowithBI (Persero) adalah perusahaan BUMN yang bergerak di bidang teknologi dan business intelligence. 
                  Kami berkomitmen untuk mengembangkan solusi teknologi terdepan yang mendukung transformasi digital Indonesia.
                  Dengan tim yang berpengalaman dan teknologi terkini, kami membantu berbagai organisasi dalam pengambilan keputusan berbasis data.
                </p>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Lowongan</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Posisi</span>
                  <span class="font-medium">{{ division.quota }} posisi</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Pelamar</span>
                  <span class="font-medium">{{ division.quota - division.available_slots }} pelamar</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Supervisor</span>
                  <span class="font-medium text-right">{{ division.supervisor }}</span>
                </div>
              </div>
            </div>

            <!-- Apply Button -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
              <button 
                @click="quickRegister"
                :disabled="isLoading"
                :class="[
                  'w-full inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200',
                  isLoading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'
                ]">
                <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isLoading ? 'Memproses...' : 'Daftar Sekarang' }}
              </button>
              <p class="mt-3 text-xs text-gray-500 text-center">
                Dengan mendaftar, Anda menyetujui syarat dan ketentuan yang berlaku
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const props = defineProps({
  division: {
    type: Object,
    required: true
  },
  auth: {
    type: Object,
    default: null
  }
})

const activeTab = ref('description')
const isLoading = ref(false)
const notification = ref({
  show: false,
  type: 'success', // success, error
  message: ''
})

const showNotification = (type, message) => {
  notification.value = {
    show: true,
    type,
    message
  }
}

const quickRegister = async () => {
  if (!props.auth?.user) {
    showNotification('error', 'Anda harus login terlebih dahulu untuk mendaftar')
    return
  }

  isLoading.value = true
  
  try {
    const response = await fetch('/quick-register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({
        division_id: props.division.id
      })
    })

    const data = await response.json()
    
    if (data.success) {
      showNotification('success', data.message)
    } else {
      showNotification('error', data.message)
    }
  } catch (error) {
    showNotification('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
  } finally {
    isLoading.value = false
  }
}
</script>
