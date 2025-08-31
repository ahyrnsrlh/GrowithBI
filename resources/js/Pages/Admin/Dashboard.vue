<template>
  <AdminLayout title="Dashboard">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-bi-blue-600 to-bi-blue-800 rounded-lg p-6 mb-8 text-white">
      <h1 class="text-3xl font-bold mb-2">Dashboard Analytics</h1>
      <p class="text-bi-blue-100">Selamat datang di dashboard analitik. Lihat ringkasan data dan statistik terbaru.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-full">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Aplikasi Disetujui</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.accepted_applications || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-full">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Menunggu Review</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.pending_applications || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-full">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Ditolak</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.rejected_applications || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-bi-blue-100 rounded-full">
            <svg class="w-6 h-6 text-bi-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Peserta</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_participants || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-full">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Aplikasi</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_applications || 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua status</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-indigo-100 rounded-full">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Divisi Aktif</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.active_divisions || 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Menerima pendaftar</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-full">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Tingkat Penerimaan</p>
            <p class="text-2xl font-semibold text-gray-900">{{ acceptanceRate }}%</p>
            <p class="text-xs text-gray-500 mt-1">Aplikasi yang diterima</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <!-- Status Distribution Pie Chart -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Aplikasi</h3>
        <PieChart 
          :data="statusChartData" 
          :options="{ responsive: true, maintainAspectRatio: false }"
          class="h-64"
        />
      </div>

      <!-- Applications Trend Line Chart -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Aplikasi (6 Bulan Terakhir)</h3>
        <LineChart 
          :data="trendsChartData" 
          :options="{ responsive: true, maintainAspectRatio: false }"
          class="h-64"
        />
      </div>
    </div>

    <!-- Division Capacity Bar Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Kapasitas Divisi</h3>
      <BarChart 
        :data="divisionChartData" 
        :options="{ responsive: true, maintainAspectRatio: false }"
        class="h-80"
      />
    </div>

    <!-- Division Details Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div 
        v-for="division in divisionData" 
        :key="division.name"
        class="bg-white rounded-lg shadow-md p-6 border-l-4 border-bi-blue-500"
      >
        <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ division.name }}</h4>
        <div class="space-y-2">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Kuota Maksimal:</span>
            <span class="font-semibold text-gray-900">{{ division.quota }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Diterima:</span>
            <span class="font-semibold text-green-600">{{ division.accepted }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Menunggu:</span>
            <span class="font-semibold text-yellow-600">{{ division.pending }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Slot Tersisa:</span>
            <span class="font-semibold text-bi-blue-600">{{ division.available }}</span>
          </div>
          <div class="mt-3 pt-3 border-t border-gray-200">
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Penggunaan:</span>
              <span class="font-semibold">{{ Math.round((division.accepted / division.quota) * 100) || 0 }}%</span>
            </div>
            <div class="mt-2 bg-gray-200 rounded-full h-2">
              <div 
                class="bg-bi-blue-500 h-2 rounded-full transition-all duration-300"
                :style="{ width: `${Math.min((division.accepted / division.quota) * 100, 100) || 0}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Applications Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Aplikasi Terbaru</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama Aplikasi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Pemohon
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Divisi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="app in recentApplications" :key="app.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ app.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ app.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ app.division }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="{
                    'bg-green-100 text-green-800': app.status === 'approved',
                    'bg-yellow-100 text-yellow-800': app.status === 'pending',
                    'bg-red-100 text-red-800': app.status === 'rejected',
                    'bg-gray-100 text-gray-800': app.status === 'draft'
                  }"
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                >
                  {{ getStatusText(app.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ app.created_at }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PieChart from '@/Components/Charts/PieChart.vue'
import LineChart from '@/Components/Charts/LineChart.vue'
import BarChart from '@/Components/Charts/BarChart.vue'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
  statusDistribution: {
    type: Array,
    default: () => []
  },
  applicationTrends: {
    type: Array,
    default: () => []
  },
  divisionData: {
    type: Array,
    default: () => []
  },
  recentApplications: {
    type: Array,
    default: () => []
  }
})

// Chart data computed properties  
const statusChartData = computed(() => {
  // Fallback data if no applications exist
  if (!props.statusDistribution || props.statusDistribution.length === 0) {
    return {
      labels: ['Tidak ada data'],
      datasets: [{
        data: [1],
        backgroundColor: ['#E5E7EB'],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    }
  }
  
  console.log('Status Distribution Debug:', props.statusDistribution)
  const chartData = {
    labels: props.statusDistribution.map(item => item.name),
    datasets: [{
      data: props.statusDistribution.map(item => item.value),
      backgroundColor: props.statusDistribution.map(item => item.color),
      borderWidth: 2,
      borderColor: '#ffffff',
      hoverBorderWidth: 3,
      hoverBorderColor: '#ffffff'
    }]
  }
  console.log('Pie Chart Data:', chartData)
  return chartData
})

const trendsChartData = computed(() => {
  return {
    labels: props.applicationTrends.map(t => t.month),
    datasets: [{
      label: 'Aplikasi Baru',
      data: props.applicationTrends.map(t => t.applications),
      borderColor: '#1E40AF',
      backgroundColor: 'rgba(30, 64, 175, 0.1)',
      tension: 0.4,
      fill: true
    }]
  }
})

const divisionChartData = computed(() => {
  return {
    labels: props.divisionData.map(d => d.name),
    datasets: [{
      label: 'Diterima',
      data: props.divisionData.map(d => d.accepted),
      backgroundColor: '#10B981',
      borderColor: '#10B981',
      borderWidth: 1
    }, {
      label: 'Kuota Maksimal',
      data: props.divisionData.map(d => d.quota),
      backgroundColor: '#E5E7EB',
      borderColor: '#9CA3AF',
      borderWidth: 1
    }]
  }
})

// Additional computed properties
const acceptanceRate = computed(() => {
  const total = props.stats.total_applications || 0
  const accepted = props.stats.accepted_applications || 0
  return total > 0 ? Math.round((accepted / total) * 100) : 0
})

// Helper functions
const getStatusText = (status) => {
  const statusMap = {
    approved: 'Disetujui',
    pending: 'Menunggu',
    rejected: 'Ditolak',
    draft: 'Draft'
  }
  return statusMap[status] || status
}
</script>
