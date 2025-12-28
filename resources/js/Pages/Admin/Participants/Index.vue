<template>
    <AdminLayout
        title="Manajemen Peserta"
        subtitle="Kelola data peserta magang yang telah diterima"
    >
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Peserta -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Peserta
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.total_participants || 0 }}
                        </p>
                        <p class="text-blue-100 text-xs mt-1">
                            Terdaftar di sistem
                        </p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Peserta Aktif -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Peserta Aktif
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.active_participants || 0 }}
                        </p>
                        <p class="text-green-100 text-xs mt-1">Sedang magang</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Aplikasi -->
            <div
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            Total Aplikasi
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.total_applications || 0 }}
                        </p>
                        <p class="text-purple-100 text-xs mt-1">
                            Pendaftaran masuk
                        </p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Selesai Magang -->
            <div
                class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            Selesai Magang
                        </p>
                        <p class="text-3xl font-bold">
                            {{ safeStats.completed_participants || 0 }}
                        </p>
                        <p class="text-orange-100 text-xs mt-1">
                            Lulus program
                        </p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg
                            class="w-8 h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"
        >
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0"
            >
                <div class="flex-1 max-w-lg">
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            @input="search"
                            type="text"
                            placeholder="Cari peserta berdasarkan nama, email, atau telepon..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <!-- Status Filter -->
                    <select
                        v-model="statusFilter"
                        @change="filter"
                        class="block w-full sm:w-40 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>

                    <!-- Division Filter -->
                    <select
                        v-model="divisionFilter"
                        @change="filter"
                        class="block w-full sm:w-48 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Divisi</option>
                        <option
                            v-for="division in safeDivisions"
                            :key="division.id"
                            :value="division.id"
                        >
                            {{ division.name }}
                        </option>
                    </select>

                    <!-- Export Button -->
                    <button
                        @click="exportData"
                        class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <svg
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    Daftar Peserta Magang
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola dan pantau data peserta magang
                </p>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Peserta
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Kontak
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status Aplikasi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status Akun
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Tanggal Daftar
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="participant in safeParticipants"
                            :key="participant.id"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <!-- Participant Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div
                                            class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center"
                                        >
                                            <span
                                                class="text-white font-semibold text-lg"
                                            >
                                                {{
                                                    participant.name
                                                        ?.charAt(0)
                                                        ?.toUpperCase() || "U"
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ participant.name || "-" }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ participant.email || "-" }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ participant.phone || "-" }}
                                </div>
                                <div
                                    class="text-sm text-gray-500 max-w-32 truncate"
                                >
                                    {{ participant.address || "-" }}
                                </div>
                            </td>

                            <!-- Division -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    v-if="getAcceptedApplication(participant)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                                >
                                    {{
                                        getAcceptedApplication(participant)
                                            .division.name
                                    }}
                                </div>
                                <div v-else class="text-sm text-gray-500">
                                    Belum ditempatkan
                                </div>
                            </td>

                            <!-- Application Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div v-if="getAcceptedApplication(participant)">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-1"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Diterima
                                    </span>
                                </div>
                                <div
                                    v-else-if="
                                        participant.applications &&
                                        participant.applications.length > 0
                                    "
                                >
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="
                                            getLastApplicationStatusClass(
                                                participant
                                            )
                                        "
                                    >
                                        {{
                                            getLastApplicationStatusText(
                                                participant
                                            )
                                        }}
                                    </span>
                                </div>
                                <div v-else>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800"
                                    >
                                        Belum mendaftar
                                    </span>
                                </div>
                            </td>

                            <!-- Account Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="toggleStatus(participant)"
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors',
                                        participant.is_active
                                            ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                            : 'bg-red-100 text-red-800 hover:bg-red-200',
                                    ]"
                                >
                                    <div
                                        class="w-2 h-2 rounded-full mr-2"
                                        :class="
                                            participant.is_active
                                                ? 'bg-green-500'
                                                : 'bg-red-500'
                                        "
                                    ></div>
                                    {{
                                        participant.is_active
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </button>
                            </td>

                            <!-- Registration Date -->
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(participant.created_at) }}
                            </td>

                            <!-- Actions -->
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <Link
                                    v-if="participant.id && route"
                                    :href="
                                        safeRoute(
                                            'admin.participants.show',
                                            participant.id
                                        )
                                    "
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    Detail
                                </Link>
                                <button
                                    v-else
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="safeParticipants.length === 0" class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">
                    Tidak ada peserta ditemukan
                </h3>
                <p class="mt-2 text-gray-500">
                    Belum ada peserta yang terdaftar atau sesuai dengan filter
                    yang dipilih.
                </p>
            </div>

            <!-- Pagination -->
            <div
                v-if="safeParticipants.length > 0"
                class="px-6 py-4 border-t border-gray-200 bg-gray-50"
            >
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ participants?.from || 0 }} sampai
                        {{ participants?.to || 0 }} dari
                        {{ participants?.total || 0 }} hasil
                    </div>
                    <div class="flex space-x-1">
                        <Link
                            v-for="link in participants?.links || []"
                            :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                'px-4 py-2 text-sm rounded-lg border transition-colors',
                                link.active
                                    ? 'bg-blue-600 text-white border-blue-600'
                                    : link.url
                                    ? 'text-gray-700 border-gray-300 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50'
                                    : 'text-gray-400 border-gray-200 cursor-not-allowed',
                            ]"
                            v-html="link.label"
                        >
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

// Import route helper dengan error handling
const route = (() => {
    try {
        return window.route || (() => "#");
    } catch (error) {
        console.warn("Route helper not available:", error);
        return () => "#";
    }
})();

const props = defineProps({
    participants: {
        type: Object,
        default: () => ({ data: [], links: [], from: 0, to: 0, total: 0 }),
    },
    stats: {
        type: Object,
        default: () => ({
            total_participants: 0,
            active_participants: 0,
            total_applications: 0,
            completed_participants: 0,
        }),
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: "", status: "", division: "" }),
    },
});

// Computed safe data dengan error handling yang lebih baik
const safeParticipants = computed(() => {
    try {
        return props.participants?.data || [];
    } catch (error) {
        console.warn("Error accessing participants data:", error);
        return [];
    }
});

const safeStats = computed(() => {
    try {
        return {
            total_participants: props.stats?.total_participants || 0,
            active_participants: props.stats?.active_participants || 0,
            total_applications: props.stats?.total_applications || 0,
            completed_participants: props.stats?.completed_participants || 0,
        };
    } catch (error) {
        console.warn("Error accessing stats data:", error);
        return {
            total_participants: 0,
            active_participants: 0,
            total_applications: 0,
            completed_participants: 0,
        };
    }
});

const safeDivisions = computed(() => {
    try {
        return Array.isArray(props.divisions) ? props.divisions : [];
    } catch (error) {
        console.warn("Error accessing divisions data:", error);
        return [];
    }
});

const safeFilters = computed(() => {
    try {
        return {
            search: props.filters?.search || "",
            status: props.filters?.status || "",
            division: props.filters?.division || "",
        };
    } catch (error) {
        console.warn("Error accessing filters data:", error);
        return { search: "", status: "", division: "" };
    }
});

const searchQuery = ref(safeFilters.value.search || "");
const statusFilter = ref(safeFilters.value.status || "");
const divisionFilter = ref(safeFilters.value.division || "");

let searchTimeout = null;

const search = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            safeRoute("admin.participants.index"),
            {
                search: searchQuery.value,
                status: statusFilter.value,
                division: divisionFilter.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 300);
};

const filter = () => {
    router.get(
        safeRoute("admin.participants.index"),
        {
            search: searchQuery.value,
            status: statusFilter.value,
            division: divisionFilter.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const formatDate = (date) => {
    if (!date) return "-";
    try {
        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    } catch (error) {
        return "-";
    }
};

const getAcceptedApplication = (participant) => {
    if (!participant?.applications || !Array.isArray(participant.applications))
        return null;
    return participant.applications.find((app) => app?.status === "accepted");
};

const getLastApplicationStatusClass = (participant) => {
    if (
        !participant?.applications ||
        !Array.isArray(participant.applications) ||
        participant.applications.length === 0
    ) {
        return "bg-gray-100 text-gray-800";
    }

    const lastApp = participant.applications[0];
    if (!lastApp?.status) return "bg-gray-100 text-gray-800";

    const classes = {
        menunggu: "bg-yellow-100 text-yellow-800",
        in_review: "bg-blue-100 text-blue-800",
        interview_scheduled: "bg-purple-100 text-purple-800",
        accepted: "bg-green-100 text-green-800",
        rejected: "bg-red-100 text-red-800",
        expired: "bg-gray-100 text-gray-800",
    };
    return classes[lastApp.status] || "bg-gray-100 text-gray-800";
};

const getLastApplicationStatusText = (participant) => {
    if (
        !participant?.applications ||
        !Array.isArray(participant.applications) ||
        participant.applications.length === 0
    ) {
        return "Belum mendaftar";
    }

    const lastApp = participant.applications[0];
    if (!lastApp?.status) return "Belum mendaftar";

    const texts = {
        menunggu: "Menunggu",
        in_review: "Dalam Review",
        interview_scheduled: "Wawancara",
        accepted: "Diterima",
        rejected: "Ditolak",
        expired: "Kedaluwarsa",
    };
    return texts[lastApp.status] || lastApp.status;
};

const toggleStatus = (participant) => {
    if (!participant?.id) return;

    router.put(
        safeRoute("admin.participants.update-status", participant.id),
        {
            is_active: !participant.is_active,
        },
        {
            preserveState: true,
        }
    );
};

const exportData = () => {
    // TODO: Implement export functionality
    alert("Fitur export akan segera ditambahkan!");
};

// Helper function for safe routing
const safeRoute = (name, params = null) => {
    if (!route || typeof route !== "function") {
        console.warn("Route helper not available");
        return "#";
    }

    try {
        return route(name, params);
    } catch (error) {
        console.warn("Route generation failed:", error);
        return "#";
    }
};
</script>
