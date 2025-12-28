<template>
    <AdminLayout
        title="Manajemen Pendaftaran"
        subtitle="Kelola dan review semua pendaftaran magang"
        :pendingCount="stats.pending"
        :notificationCount="stats.pending"
    >
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Aplikasi -->
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            Total Aplikasi
                        </p>
                        <p class="text-3xl font-bold">{{ stats.total }}</p>
                        <p class="text-blue-100 text-xs mt-1">
                            Semua pendaftaran
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu Review -->
            <div
                class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">
                            Menunggu Review
                        </p>
                        <p class="text-3xl font-bold">{{ stats.pending }}</p>
                        <p class="text-yellow-100 text-xs mt-1">
                            Perlu ditinjau
                        </p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
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

            <!-- Diterima -->
            <div
                class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            Diterima
                        </p>
                        <p class="text-3xl font-bold">{{ stats.accepted }}</p>
                        <p class="text-green-100 text-xs mt-1">Lolos seleksi</p>
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

            <!-- Ditolak -->
            <div
                class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Ditolak</p>
                        <p class="text-3xl font-bold">{{ stats.rejected }}</p>
                        <p class="text-red-100 text-xs mt-1">
                            Tidak memenuhi syarat
                        </p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"
        >
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
            >
                <!-- Search and Filters -->
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Search -->
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            @input="search"
                            type="text"
                            placeholder="Cari nama atau email..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <svg
                            class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
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

                    <!-- Status Filter -->
                    <select
                        v-model="selectedStatus"
                        @change="filterByStatus"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Status</option>
                        <option
                            v-for="option in statusOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>

                    <!-- Division Filter -->
                    <select
                        v-model="selectedDivision"
                        @change="filterByDivision"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Divisi</option>
                        <option
                            v-for="division in divisions"
                            :key="division.id"
                            :value="division.id"
                        >
                            {{ division.name }}
                        </option>
                    </select>
                </div>

                <!-- Bulk Actions -->
                <div
                    v-if="selectedApplications.length > 0"
                    class="flex items-center gap-2"
                >
                    <span class="text-sm text-gray-600"
                        >{{ selectedApplications.length }} terpilih</span
                    >
                    <button
                        @click="showBulkModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                    >
                        Aksi Bulk
                    </button>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input
                                    type="checkbox"
                                    @change="toggleAllApplications"
                                    :checked="
                                        selectedApplications.length ===
                                            applications.data.length &&
                                        applications.data.length > 0
                                    "
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                />
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Pendaftar
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Divisi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Tanggal Apply
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
                            v-for="application in applications.data"
                            :key="application.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                <input
                                    type="checkbox"
                                    :value="application.id"
                                    v-model="selectedApplications"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-white font-medium text-sm"
                                            >{{
                                                application.user?.name?.charAt(
                                                    0
                                                ) || "N"
                                            }}</span
                                        >
                                    </div>
                                    <div class="ml-4">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                application.user?.name || "N/A"
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                application.user?.email || "N/A"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ application.division.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <StatusBadge
                                    :status="application.status"
                                    :statusInfo="application.status_info"
                                />
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ formatDate(application.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <Link
                                        :href="`/admin/applications/${application.id}`"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Detail
                                    </Link>
                                    <button
                                        @click="openStatusModal(application)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Update Status
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="applications.links.length > 3"
                class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
            >
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <Link
                            v-if="applications.prev_page_url"
                            :href="applications.prev_page_url"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Previous
                        </Link>
                        <Link
                            v-if="applications.next_page_url"
                            :href="applications.next_page_url"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Next
                        </Link>
                    </div>
                    <div
                        class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing {{ applications.from }} to
                                {{ applications.to }} of
                                {{ applications.total }} results
                            </p>
                        </div>
                        <div>
                            <nav
                                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            >
                                <template
                                    v-for="(link, index) in applications.links"
                                    :key="index"
                                >
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                        ]"
                                    />
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500"
                                    />
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div
            v-if="showStatusModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-[28rem] shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Update Status Pendaftaran
                    </h3>
                    <form @submit.prevent="updateStatus">
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Status Baru</label
                            >
                            <select
                                v-model="statusForm.status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                            >
                                <option
                                    v-for="option in statusOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Interview fields (shown when interview_scheduled) -->
                        <div
                            v-if="statusForm.status === 'interview_scheduled'"
                            class="mb-4 space-y-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Tanggal & Waktu Wawancara
                                </label>
                                <input
                                    type="datetime-local"
                                    v-model="statusForm.interview_date"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Lokasi Wawancara
                                </label>
                                <input
                                    type="text"
                                    v-model="statusForm.interview_location"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: Kantor BI Lampung, Ruang Meeting Lt. 3"
                                />
                            </div>
                        </div>

                        <!-- Rejection reason (shown when rejected) -->
                        <div
                            v-if="statusForm.status === 'rejected'"
                            class="mb-4"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Alasan Penolakan
                            </label>
                            <textarea
                                v-model="statusForm.rejection_reason"
                                rows="2"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Jelaskan alasan penolakan (akan dikirim ke pelamar)"
                            ></textarea>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Catatan Admin (Internal)</label
                            >
                            <textarea
                                v-model="statusForm.admin_notes"
                                rows="2"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Catatan internal (tidak dikirim ke pelamar)"
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="showStatusModal = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Update Modal -->
        <div
            v-if="showBulkModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Update Bulk Status
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ selectedApplications.length }} pendaftaran akan
                        diupdate
                    </p>
                    <form @submit.prevent="bulkUpdateStatus">
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Status</label
                            >
                            <select
                                v-model="bulkForm.status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                            >
                                <option
                                    v-for="option in statusOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Catatan Admin</label
                            >
                            <textarea
                                v-model="bulkForm.admin_notes"
                                rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Tambahkan catatan (opsional)"
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="showBulkModal = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Update Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, reactive, onMounted, onUnmounted } from "vue";

const props = defineProps({
    applications: Object,
    divisions: Array,
    filters: Object,
    stats: Object,
    statusOptions: {
        type: Array,
        default: () => [
            { value: "menunggu", label: "Menunggu Review" },
            { value: "in_review", label: "Dalam Proses Seleksi" },
            { value: "interview_scheduled", label: "Wawancara Dijadwalkan" },
            { value: "accepted", label: "Diterima" },
            { value: "rejected", label: "Ditolak" },
            { value: "letter_ready", label: "Surat Penerimaan Tersedia" },
            { value: "expired", label: "Kedaluwarsa" },
        ],
    },
});

// Reactive data
const searchQuery = ref(props.filters.search || "");
const selectedStatus = ref(props.filters.status || "");
const selectedDivision = ref(props.filters.division || "");
const selectedApplications = ref([]);
const showStatusModal = ref(false);
const showBulkModal = ref(false);
const currentApplication = ref(null);

const statusForm = reactive({
    status: "",
    admin_notes: "",
    rejection_reason: "",
    interview_date: "",
    interview_location: "",
});

const bulkForm = reactive({
    status: "",
    admin_notes: "",
});

// Real-time updates via Echo (if available)
let echoChannel = null;

onMounted(() => {
    // Listen for real-time application status updates
    if (window.Echo) {
        echoChannel = window.Echo.private("admin.applications")
            .listen(".application.status.changed", (data) => {
                // Refresh the page to get updated data
                router.reload({ only: ["applications", "stats"] });
            })
            .listen(".application.submitted", (data) => {
                // Refresh when new application submitted
                router.reload({ only: ["applications", "stats"] });
            });
    }
});

onUnmounted(() => {
    if (echoChannel) {
        echoChannel.stopListening(".application.status.changed");
        echoChannel.stopListening(".application.submitted");
    }
});

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const search = () => {
    router.get(
        "/admin/applications",
        {
            search: searchQuery.value,
            status: selectedStatus.value,
            division: selectedDivision.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const filterByStatus = () => {
    router.get(
        "/admin/applications",
        {
            search: searchQuery.value,
            status: selectedStatus.value,
            division: selectedDivision.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const filterByDivision = () => {
    router.get(
        "/admin/applications",
        {
            search: searchQuery.value,
            status: selectedStatus.value,
            division: selectedDivision.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const toggleAllApplications = () => {
    if (selectedApplications.value.length === props.applications.data.length) {
        selectedApplications.value = [];
    } else {
        selectedApplications.value = props.applications.data.map(
            (app) => app.id
        );
    }
};

const openStatusModal = (application) => {
    currentApplication.value = application;
    statusForm.status = application.status;
    statusForm.admin_notes = application.admin_notes || "";
    statusForm.interview_date = application.interview_date || "";
    statusForm.interview_location = application.interview_location || "";
    statusForm.rejection_reason = application.rejection_reason || "";
    showStatusModal.value = true;
};

const closeStatusModal = () => {
    showStatusModal.value = false;
    statusForm.status = "";
    statusForm.admin_notes = "";
    statusForm.interview_date = "";
    statusForm.interview_location = "";
    statusForm.rejection_reason = "";
    currentApplication.value = null;
};

const updateStatus = () => {
    // Prepare form data based on status
    const formData = {
        status: statusForm.status,
        admin_notes: statusForm.admin_notes,
    };

    // Include interview fields only for interview_scheduled status
    if (statusForm.status === "interview_scheduled") {
        formData.interview_date = statusForm.interview_date;
        formData.interview_location = statusForm.interview_location;
    }

    // Include rejection reason only for rejected status
    if (statusForm.status === "rejected") {
        formData.rejection_reason = statusForm.rejection_reason;
    }

    router.put(`/admin/applications/${currentApplication.value.id}`, formData, {
        onSuccess: () => {
            closeStatusModal();
        },
        onError: (errors) => {
            console.error("Status update failed:", errors);
        },
    });
};

const bulkUpdateStatus = () => {
    router.post(
        "/admin/applications/bulk-update",
        {
            application_ids: selectedApplications.value,
            ...bulkForm,
        },
        {
            onSuccess: () => {
                showBulkModal.value = false;
                selectedApplications.value = [];
                bulkForm.status = "";
                bulkForm.admin_notes = "";
            },
        }
    );
};
</script>
