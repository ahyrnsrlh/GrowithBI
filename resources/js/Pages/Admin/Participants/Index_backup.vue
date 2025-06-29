<template>
  <AdminLayout 
    title="Manajemen Peserta" 
    subtitle="Kelola data peserta magang yang telah diterima"
  >
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Peserta</p>
            <p class="text-2xl font-bold text-gray-900">{{ safeStats.total_participants || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Peserta Aktif</p>
            <p class="text-2xl font-bold text-gray-900">{{ safeStats.active_participants || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Aplikasi</p>
            <p class="text-2xl font-bold text-gray-900">{{ safeStats.total_applications || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Selesai Magang</p>
            <p class="text-2xl font-bold text-gray-900">{{ safeStats.completed_participants || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
          <!-- Search -->
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <input
              type="text"
              v-model="searchQuery"
              @input="search"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              placeholder="Cari peserta..."
            />
          </div>

          <!-- Status Filter -->
          <select
            v-model="statusFilter"
            @change="filter"
            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
          </select>

          <!-- Division Filter -->
          <select
            v-model="divisionFilter"
            @change="filter"
            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Semua Divisi</option>
            <option v-for="division in safeDivisions" :key="division.id" :value="division.id">
              {{ division.name }}
            </option>
          </select>
        </div>

        <div class="flex space-x-3">
          <button @click="exportData" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export
          </button>
        </div>
      </div>
    </div>

    <!-- Participants List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Peserta</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Peserta
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Kontak
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Divisi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status Aplikasi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status Akun
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Bergabung
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="participant in safeParticipants" :key="participant.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                      <span class="text-sm font-medium text-blue-600">
                        {{ participant.name.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ participant.name }}</div>
                    <div class="text-sm text-gray-500">{{ participant.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ participant.phone || '-' }}</div>
                <div class="text-sm text-gray-500 max-w-32 truncate">{{ participant.address || '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="getAcceptedApplication(participant)" class="text-sm text-gray-900">
                  {{ getAcceptedApplication(participant).division.name }}
                </div>
                <div v-else class="text-sm text-gray-500">-</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="getAcceptedApplication(participant)">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Diterima
                  </span>
                </div>
                <div v-else-if="participant.applications && participant.applications.length > 0">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="getLastApplicationStatusClass(participant)">
                    {{ getLastApplicationStatusText(participant) }}
                  </span>
                </div>
                <div v-else>
                  <span class="text-sm text-gray-500">Belum mendaftar</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="participant.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ participant.is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(participant.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <Link :href="route('admin.participants.show', participant.id)" 
                  class="text-blue-600 hover:text-blue-900">
                  Detail
                </Link>
                <button @click="toggleStatus(participant)" 
                  :class="participant.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'">
                  {{ participant.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="safeParticipants.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada peserta</h3>
        <p class="mt-1 text-sm text-gray-500">Belum ada peserta yang terdaftar dalam sistem.</p>
      </div>

      <!-- Pagination -->
      <div v-if="safeParticipants.length > 0" class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Menampilkan {{ participants?.from || 0 }} sampai {{ participants?.to || 0 }} dari {{ participants?.total || 0 }} hasil
          </div>
          <div class="flex space-x-1">
            <Link v-for="link in (participants?.links || [])" :key="link.label" 
              :href="link.url" 
              :class="[
                'px-3 py-2 text-sm rounded-lg',
                link.active 
                  ? 'bg-blue-600 text-white' 
                  : link.url 
                    ? 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' 
                    : 'text-gray-300 cursor-not-allowed'
              ]"
              v-html="link.label">
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Import route helper
const route = window.route

const props = defineProps({
  participants: {
    type: Object,
    default: () => ({ data: [], links: [], from: 0, to: 0, total: 0 })
  },
  stats: {
    type: Object,
    default: () => ({ 
      total_participants: 0, 
      active_participants: 0, 
      total_applications: 0, 
      completed_participants: 0 
    })
  },
  divisions: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({ search: '', status: '', division: '' })
  }
})

// Computed safe data
const safeParticipants = computed(() => props.participants?.data || [])
const safeStats = computed(() => props.stats || {})
const safeDivisions = computed(() => props.divisions || [])
const safeFilters = computed(() => props.filters || {})

const searchQuery = ref(safeFilters.value.search || '')
const statusFilter = ref(safeFilters.value.status || '')
const divisionFilter = ref(safeFilters.value.division || '')

let searchTimeout = null

const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('admin.participants.index'), {
      search: searchQuery.value,
      status: statusFilter.value,
      division: divisionFilter.value
    }, {
      preserveState: true,
      replace: true
    })
  }, 300)
}

const filter = () => {
  router.get(route('admin.participants.index'), {
    search: searchQuery.value,
    status: statusFilter.value,
    division: divisionFilter.value
  }, {
    preserveState: true,
    replace: true
  })
}

const formatDate = (date) => {
  if (!date) return '-'
  try {
    return new Date(date).toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  } catch (error) {
    return '-'
  }
}

const getAcceptedApplication = (participant) => {
  if (!participant?.applications || !Array.isArray(participant.applications)) return null
  return participant.applications.find(app => app?.status === 'diterima')
}

const getLastApplicationStatusClass = (participant) => {
  if (!participant?.applications || !Array.isArray(participant.applications) || participant.applications.length === 0) {
    return 'bg-gray-100 text-gray-800'
  }
  
  const lastApp = participant.applications[0]
  if (!lastApp?.status) return 'bg-gray-100 text-gray-800'
  
  const classes = {
    menunggu: 'bg-yellow-100 text-yellow-800',
    diterima: 'bg-green-100 text-green-800',
    ditolak: 'bg-red-100 text-red-800'
  }
  return classes[lastApp.status] || 'bg-gray-100 text-gray-800'
}

const getLastApplicationStatusText = (participant) => {
  if (!participant?.applications || !Array.isArray(participant.applications) || participant.applications.length === 0) {
    return 'Belum mendaftar'
  }
  
  const lastApp = participant.applications[0]
  if (!lastApp?.status) return 'Belum mendaftar'
  
  const texts = {
    menunggu: 'Menunggu',
    diterima: 'Diterima',
    ditolak: 'Ditolak'
  }
  return texts[lastApp.status] || lastApp.status
}

const toggleStatus = (participant) => {
  if (!participant?.id) return
  
  router.put(route('admin.participants.update-status', participant.id), {
    is_active: !participant.is_active
  }, {
    preserveState: true
  })
}

const exportData = () => {
  // TODO: Implement export functionality
  alert('Fitur export akan segera ditambahkan!')
}
</script>
