<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow relative"
    >
        <!-- Status ribbon for new applications -->
        <div
            v-if="isNewApplication"
            class="absolute -top-1 -right-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full animate-pulse"
        >
            Baru
        </div>

        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ application.division.name }}
                    </h3>
                    <button
                        @click="refreshStatus"
                        :disabled="refreshing"
                        class="text-gray-400 hover:text-blue-600 transition-colors p-1 rounded"
                        title="Refresh Status"
                    >
                        <i
                            :class="[
                                'fas fa-sync-alt text-sm',
                                { 'animate-spin': refreshing },
                            ]"
                        ></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-3">
                    {{ application.division.description }}
                </p>

                <!-- Application Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Tanggal Lamar:</span>
                        <span class="ml-2 font-medium">{{
                            formatDate(application.created_at)
                        }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Nomor Lamaran:</span>
                        <span class="ml-2 font-medium"
                            >#{{
                                application.id.toString().padStart(4, "0")
                            }}</span
                        >
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="ml-4">
                <span
                    :class="[
                        'px-3 py-1.5 text-xs font-medium rounded-full',
                        getStatusClass(application.status),
                    ]"
                >
                    {{ getStatusText(application.status) }}
                </span>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="border-t border-gray-200 pt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">
                Timeline Status
            </h4>

            <div class="space-y-3">
                <!-- Applied -->
                <div class="flex items-center">
                    <div
                        :class="['w-3 h-3 rounded-full mr-3', 'bg-blue-500']"
                    ></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900"
                            >Lamaran Dikirim</span
                        >
                        <span class="ml-2 text-xs text-gray-500">{{
                            formatDate(application.created_at)
                        }}</span>
                    </div>
                </div>

                <!-- Under Review -->
                <div class="flex items-center">
                    <div
                        :class="[
                            'w-3 h-3 rounded-full mr-3',
                            [
                                'under_review',
                                'interview',
                                'accepted',
                                'rejected',
                            ].includes(application.status)
                                ? 'bg-yellow-500'
                                : 'bg-gray-300',
                        ]"
                    ></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900"
                            >Sedang Diproses</span
                        >
                        <span
                            v-if="application.reviewed_at"
                            class="ml-2 text-xs text-gray-500"
                        >
                            {{ formatDate(application.reviewed_at) }}
                        </span>
                    </div>
                </div>

                <!-- Interview (if applicable) -->
                <div
                    v-if="
                        ['interview', 'accepted', 'rejected'].includes(
                            application.status
                        )
                    "
                    class="flex items-center"
                >
                    <div
                        :class="[
                            'w-3 h-3 rounded-full mr-3',
                            ['interview', 'accepted', 'rejected'].includes(
                                application.status
                            )
                                ? 'bg-purple-500'
                                : 'bg-gray-300',
                        ]"
                    ></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900"
                            >Interview</span
                        >
                        <span
                            v-if="application.interview_date"
                            class="ml-2 text-xs text-gray-500"
                        >
                            {{ formatDate(application.interview_date) }}
                        </span>
                    </div>
                </div>

                <!-- Final Decision -->
                <div
                    v-if="['accepted', 'rejected'].includes(application.status)"
                    class="flex items-center"
                >
                    <div
                        :class="[
                            'w-3 h-3 rounded-full mr-3',
                            application.status === 'accepted'
                                ? 'bg-green-500'
                                : 'bg-red-500',
                        ]"
                    ></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900">
                            {{
                                application.status === "accepted"
                                    ? "Diterima"
                                    : "Ditolak"
                            }}
                        </span>
                        <span
                            v-if="application.decision_date"
                            class="ml-2 text-xs text-gray-500"
                        >
                            {{ formatDate(application.decision_date) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div
            v-if="application.notes || application.feedback"
            class="border-t border-gray-200 pt-4 mt-4"
        >
            <h4 class="text-sm font-medium text-gray-900 mb-2">
                Informasi Tambahan
            </h4>

            <div v-if="application.notes" class="mb-3">
                <span class="text-xs text-gray-500 uppercase tracking-wide"
                    >Catatan:</span
                >
                <p class="text-sm text-gray-700 mt-1">
                    {{ application.notes }}
                </p>
            </div>

            <div v-if="application.feedback" class="mb-3">
                <span class="text-xs text-gray-500 uppercase tracking-wide"
                    >Feedback:</span
                >
                <p class="text-sm text-gray-700 mt-1">
                    {{ application.feedback }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div
            class="border-t border-gray-200 pt-4 mt-4 flex justify-end space-x-3"
        >
            <button
                @click="viewDetails"
                class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
            >
                <i class="fas fa-eye mr-2"></i>
                Lihat Detail
            </button>

            <button
                v-if="application.status === 'pending'"
                @click="cancelApplication"
                class="px-4 py-2 text-sm text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition-colors"
            >
                <i class="fas fa-times mr-2"></i>
                Batalkan
            </button>

            <button
                v-if="application.status === 'accepted'"
                @click="downloadOffer"
                class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
            >
                <i class="fas fa-download mr-2"></i>
                Unduh Surat
            </button>
        </div>

        <!-- Modal for Details -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeModal"
        >
            <div
                class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto"
            >
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Detail Lamaran</h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Divisi</label
                            >
                            <p class="text-gray-900">
                                {{ application.division.name }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Motivasi</label
                            >
                            <p class="text-gray-900 whitespace-pre-wrap">
                                {{ application.motivation }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Status</label
                                >
                                <span
                                    :class="[
                                        'inline-block px-2 py-1 text-xs font-medium rounded-full',
                                        getStatusClass(application.status),
                                    ]"
                                >
                                    {{ getStatusText(application.status) }}
                                </span>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Tanggal Lamar</label
                                >
                                <p class="text-gray-900">
                                    {{ formatDate(application.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    application: Object,
});

const showModal = ref(false);
const refreshing = ref(false);

// Check if application is new (created in last 30 minutes)
const isNewApplication = computed(() => {
    if (!props.application.created_at) return false;
    const createdAt = new Date(props.application.created_at);
    const now = new Date();
    const diffInMinutes = (now - createdAt) / (1000 * 60);
    return diffInMinutes < 30;
});

// Methods
const refreshStatus = async () => {
    refreshing.value = true;
    try {
        // Fetch updated application status using web route
        const response = await window.axios.get(`/applications/${props.application.id}/status`);
        if (response.data.status !== props.application.status) {
            // Status has changed, reload the page to update the UI
            window.location.reload();
        }
    } catch (error) {
        console.error('Error refreshing status:', error);
    } finally {
        refreshing.value = false;
    }
};

const getStatusClass = (status) => {
    const classes = {
        menunggu: "bg-yellow-100 text-yellow-800 border border-yellow-200",
        dalam_review: "bg-blue-100 text-blue-800 border border-blue-200", 
        wawancara: "bg-purple-100 text-purple-800 border border-purple-200",
        diterima: "bg-green-100 text-green-800 border border-green-200",
        ditolak: "bg-red-100 text-red-800 border border-red-200",
    };
    return classes[status] || "bg-gray-100 text-gray-800 border border-gray-200";
};

const getStatusText = (status) => {
    const texts = {
        menunggu: "Menunggu Review",
        dalam_review: "Sedang Direview",
        wawancara: "Tahap Wawancara", 
        diterima: "Diterima",
        ditolak: "Ditolak",
    };
    return texts[status] || "Tidak Diketahui";
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const viewDetails = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const cancelApplication = () => {
    if (confirm("Apakah Anda yakin ingin membatalkan lamaran ini?")) {
        router.delete(route("applications.destroy", props.application.id));
    }
};

const downloadOffer = () => {
    window.open(
        route("applications.download-offer", props.application.id),
        "_blank"
    );
};
</script>
