<template>
  <Head :title="division.name" />
  <GuestLayout variant="public">
    <!-- Modal Notification Overlay -->
    <div v-if="notification.show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                   :class="notification.type === 'success' ? 'bg-green-100' : 'bg-red-100'">
                <svg v-if="notification.type === 'success'" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ notification.title }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    {{ notification.message }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="closeNotification" 
                    type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    :class="notification.type === 'success' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'">
              OK
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container-responsive">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <!-- Back Navigation -->
        <div class="mb-6 lg:mb-8">
          <Link href="/divisions" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200">
            <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="text-sm lg:text-base font-medium">Kembali ke Daftar Divisi</span>
          </Link>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8 mb-8 shadow-sm">
          <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
              <h1 class="text-responsive-xl font-bold text-gray-900 mb-4">{{ division.name }}</h1>
              <p class="text-gray-700 text-base lg:text-lg leading-relaxed mb-6">{{ division.description }}</p>
              
              <!-- Key Information Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 lg:p-5 rounded-lg border border-blue-100">
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-sm lg:text-base text-gray-600 font-medium">Kuota</span>
                  </div>
                  <p class="text-lg lg:text-xl font-bold text-blue-600">{{ division.quota }} posisi</p>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 lg:p-5 rounded-lg border border-green-100">
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm lg:text-base text-gray-600 font-medium">Tersedia</span>
                  </div>
                  <p class="text-lg lg:text-xl font-bold text-green-600">{{ division.available_slots }} slot</p>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 lg:p-5 rounded-lg border border-purple-100 sm:col-span-2 lg:col-span-1">
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <span class="text-sm lg:text-base text-gray-600 font-medium">Gaji</span>
                  </div>
                  <p class="text-lg lg:text-xl font-bold text-purple-600">{{ division.salary_range }}</p>
                </div>
              </div>
            </div>

            <!-- Apply Button -->
            <div class="lg:flex-shrink-0">
              <button v-if="!auth.user" 
                      @click="handleApply" 
                      :disabled="division.available_slots <= 0 || isLoading"
                      class="btn-responsive w-full lg:w-auto bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold px-8 py-3 lg:py-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl disabled:cursor-not-allowed disabled:shadow-none">
                <span v-if="!isLoading" class="flex items-center justify-center">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  <span class="text-sm lg:text-base">
                    {{ division.available_slots <= 0 ? 'Kuota Penuh' : 'Lamar Sekarang' }}
                  </span>
                </span>
                <span v-else class="flex items-center justify-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Processing...
                </span>
              </button>
              <div v-else class="text-center">
                <p class="text-gray-600 mb-4">Anda sudah login sebagai {{ auth.user.name }}</p>
                <Link href="/dashboard" class="btn-responsive bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                  Dashboard
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 lg:gap-12">
          <!-- Main Content -->
          <div class="xl:col-span-2">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6 lg:mb-8 shadow-sm">
              <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6 lg:px-8" aria-label="Tabs">
                  <button @click="activeTab = 'requirements'" 
                          :class="activeTab === 'requirements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm lg:text-base transition-colors duration-200">
                    Persyaratan
                  </button>
                  <button @click="activeTab = 'details'" 
                          :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm lg:text-base transition-colors duration-200">
                    Rincian Lowongan
                  </button>
                  <button @click="activeTab = 'company'" 
                          :class="activeTab === 'company' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm lg:text-base transition-colors duration-200">
                    Tentang Perusahaan
                  </button>
                </nav>
              </div>
            </div>

            <!-- Tab Content -->
            <div class="content-spacing">
              <!-- Requirements Tab -->
              <div v-if="activeTab === 'requirements'" class="content-spacing">
                <div class="card-enhanced spacing-responsive">
                  <h3 class="text-responsive-lg font-semibold text-gray-900 mb-4">Persyaratan</h3>
                  <div class="prose prose-gray max-w-none">
                    <ul class="space-y-2 text-gray-700">
                      <li v-for="requirement in division.requirements" :key="requirement" class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ requirement }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Job Details Tab -->
              <div v-else-if="activeTab === 'details'" class="content-spacing">
                <div class="card-enhanced spacing-responsive">
                  <h3 class="text-responsive-lg font-semibold text-gray-900 mb-4">Rincian Lowongan</h3>
                  <div class="prose prose-gray max-w-none">
                    <p class="text-gray-700 mb-4"><strong>Deskripsi Kerja :</strong></p>
                    <ol class="list-decimal list-inside space-y-1 text-gray-700">
                      <li v-for="desc in division.job_description" :key="desc">{{ desc.replace(/^\d+\.\s*/, '') }}</li>
                    </ol>
                  </div>
                </div>
              </div>

              <!-- Company Tab -->
              <div v-else-if="activeTab === 'company'" class="content-spacing">
                <div class="card-enhanced spacing-responsive">
                  <h3 class="text-responsive-lg font-semibold text-gray-900 mb-4">Tentang PT GrowithBI (Persero)</h3>
                  <p class="text-gray-700 leading-relaxed">
                    PT GrowithBI (Persero) adalah perusahaan BUMN yang bergerak di bidang teknologi dan business intelligence. 
                    Kami berkomitmen untuk mengembangkan solusi teknologi terdepan yang mendukung transformasi digital Indonesia.
                    Dengan tim yang berpengalaman dan teknologi terkini, kami membantu berbagai organisasi dalam pengambilan keputusan berbasis data.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar - Enhanced Responsive Design -->
          <div class="xl:col-span-1 space-y-6 lg:space-y-8">
            <!-- Quick Info Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8 shadow-sm hover:shadow-md transition-shadow duration-200 sticky top-6">
              <div class="flex items-center mb-4 lg:mb-6">
                <div class="flex items-center justify-center w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg mr-4">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <h3 class="text-lg lg:text-xl font-semibold text-gray-900">Info Lowongan</h3>
              </div>
              
              <div class="space-y-4 lg:space-y-5">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center xl:flex-col xl:items-start p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                  <span class="text-gray-600 lg:text-lg font-medium">Posisi Tersedia</span>
                  <div class="flex items-center mt-1 sm:mt-0 xl:mt-2">
                    <span class="text-2xl lg:text-3xl font-bold text-blue-600 mr-2">{{ division.quota }}</span>
                    <span class="text-gray-500 lg:text-lg">posisi</span>
                  </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center xl:flex-col xl:items-start p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                  <span class="text-gray-600 lg:text-lg font-medium">Total Pelamar</span>
                  <div class="flex items-center mt-1 sm:mt-0 xl:mt-2">
                    <span class="text-2xl lg:text-3xl font-bold text-green-600 mr-2">{{ division.quota - division.available_slots }}</span>
                    <span class="text-gray-500 lg:text-lg">orang</span>
                  </div>
                </div>
                
                <div class="space-y-3 lg:space-y-4 pt-2 border-t border-gray-100">
                  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 lg:text-lg font-medium">Penutupan lamaran</span>
                    <span class="font-semibold text-gray-900 lg:text-lg mt-1 sm:mt-0">{{ division.application_deadline }}</span>
                  </div>
                  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 lg:text-lg font-medium">Pengumuman lolos seleksi</span>
                    <span class="font-semibold text-gray-900 lg:text-lg mt-1 sm:mt-0">{{ division.selection_announcement }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Share Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 lg:p-8 shadow-sm">
              <h4 class="text-lg lg:text-xl font-semibold text-gray-900 mb-4 lg:mb-6">Bagikan Lowongan</h4>
              <div class="grid grid-cols-2 gap-3 lg:gap-4">
                <button class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm lg:text-base font-medium">
                  <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                  </svg>
                  Twitter
                </button>
                <button class="flex items-center justify-center px-4 py-3 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition-colors duration-200 text-sm lg:text-base font-medium">
                  <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                  </svg>
                  LinkedIn
                </button>
                <button class="flex items-center justify-center px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-sm lg:text-base font-medium">
                  <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                  </svg>
                  WhatsApp
                </button>
                <button class="flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 text-sm lg:text-base font-medium">
                  <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                  </svg>
                  Salin Link
                </button>
              </div>
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
    default: () => ({ user: null })
  }
})

// Reactive data
const activeTab = ref('requirements')
const isLoading = ref(false)
const notification = ref({
  show: false,
  type: 'success', // 'success' or 'error'
  title: '',
  message: ''
})

// Methods
const handleApply = async () => {
  if (props.division.available_slots <= 0) {
    showNotification('error', 'Maaf', 'Kuota untuk divisi ini sudah penuh.')
    return
  }

  isLoading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // For now, just redirect to application form
    window.location.href = `/divisions/${props.division.id}/apply`
  } catch (error) {
    showNotification('error', 'Error', 'Terjadi kesalahan saat memproses lamaran Anda.')
  } finally {
    isLoading.value = false
  }
}

const showNotification = (type, title, message) => {
  notification.value = {
    show: true,
    type,
    title,
    message
  }
}

const closeNotification = () => {
  notification.value.show = false
}
</script>
