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
                                'in_review',
                                'interview_scheduled',
                                'accepted',
                                'rejected',
                                'letter_ready',
                                'dalam_review',
                                'wawancara',
                                'diterima',
                                'ditolak',
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
                        [
                            'interview_scheduled',
                            'accepted',
                            'rejected',
                            'letter_ready',
                            'wawancara',
                            'diterima',
                            'ditolak',
                        ].includes(application.status)
                    "
                    class="flex items-center"
                >
                    <div
                        :class="[
                            'w-3 h-3 rounded-full mr-3',
                            [
                                'interview_scheduled',
                                'accepted',
                                'rejected',
                                'letter_ready',
                                'wawancara',
                                'diterima',
                                'ditolak',
                            ].includes(application.status)
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
                        <span
                            v-if="application.interview_location"
                            class="ml-2 text-xs text-gray-400"
                        >
                            - {{ application.interview_location }}
                        </span>
                    </div>
                </div>

                <!-- Final Decision -->
                <div
                    v-if="
                        [
                            'accepted',
                            'rejected',
                            'letter_ready',
                            'diterima',
                            'ditolak',
                        ].includes(application.status)
                    "
                    class="flex items-center"
                >
                    <div
                        :class="[
                            'w-3 h-3 rounded-full mr-3',
                            ['accepted', 'letter_ready', 'diterima'].includes(
                                application.status
                            )
                                ? 'bg-green-500'
                                : 'bg-red-500',
                        ]"
                    ></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900">
                            {{
                                [
                                    "accepted",
                                    "letter_ready",
                                    "diterima",
                                ].includes(application.status)
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

                <!-- Letter Ready (if applicable) -->
                <div
                    v-if="application.status === 'letter_ready'"
                    class="flex items-center"
                >
                    <div class="w-3 h-3 rounded-full mr-3 bg-emerald-500"></div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900"
                            >Surat Penerimaan Siap</span
                        >
                        <span class="ml-2 text-xs text-emerald-600 font-medium"
                            >Unduh sekarang!</span
                        >
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
                v-if="application.status === 'menunggu'"
                @click="cancelApplication"
                class="px-4 py-2 text-sm text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition-colors"
            >
                <i class="fas fa-times mr-2"></i>
                Batalkan
            </button>

            <button
                v-if="
                    ['accepted', 'letter_ready', 'diterima'].includes(
                        application.status
                    )
                "
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

        <!-- Modal for Cancel Confirmation -->
        <div
            v-if="showCancelModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeCancelModal"
        >
            <div class="bg-white rounded-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4"
                        >
                            <i
                                class="fas fa-exclamation-triangle text-red-600 text-xl"
                            ></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Konfirmasi Pembatalan
                            </h3>
                            <p class="text-sm text-gray-600">
                                Tindakan ini tidak dapat dibatalkan
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-700">
                            Apakah Anda yakin ingin membatalkan pendaftaran
                            untuk divisi
                            <strong>{{ application.division.name }}</strong
                            >?
                        </p>
                        <div
                            class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg"
                        >
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Dengan membatalkan pendaftaran, semua data dan
                                dokumen yang telah diunggah akan dihapus secara
                                permanen.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            @click="closeCancelModal"
                            :disabled="cancelling"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
                        >
                            Batal
                        </button>
                        <button
                            @click="confirmCancel"
                            :disabled="cancelling"
                            class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        >
                            <i
                                v-if="cancelling"
                                class="fas fa-spinner fa-spin mr-2"
                            ></i>
                            <i v-else class="fas fa-trash mr-2"></i>
                            {{ cancelling ? "Membatalkan..." : "Ya, Batalkan" }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";

const props = defineProps({
    application: Object,
});

const emit = defineEmits(["status-updated"]);

const showModal = ref(false);
const showCancelModal = ref(false);
const refreshing = ref(false);
const cancelling = ref(false);
const currentStatus = ref(props.application.status);
const statusInfo = ref(props.application.status_info);

// Watch for prop changes
watch(
    () => props.application.status,
    (newStatus) => {
        currentStatus.value = newStatus;
    }
);

watch(
    () => props.application.status_info,
    (newInfo) => {
        statusInfo.value = newInfo;
    }
);

// Echo channel for real-time status updates
let echoChannel = null;

onMounted(() => {
    // Listen for real-time status updates
    if (window.Echo && props.application.user_id) {
        echoChannel = window.Echo.private(
            `App.Models.User.${props.application.user_id}`
        ).notification((notification) => {
            if (notification.application_id === props.application.id) {
                // Refresh the page to get updated application data
                router.reload({ only: ["applications"] });
                emit("status-updated", {
                    applicationId: props.application.id,
                    newStatus: notification.status,
                });
            }
        });
    }
});

onUnmounted(() => {
    // Cleanup Echo listener
    if (echoChannel && window.Echo) {
        window.Echo.leave(`App.Models.User.${props.application.user_id}`);
    }
});

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
        const response = await window.axios.get(
            `/applications/${props.application.id}/status`
        );
        if (response.data.status !== props.application.status) {
            // Status has changed, reload the page to update the UI
            window.location.reload();
        }
    } catch (error) {
        console.error("Error refreshing status:", error);
    } finally {
        refreshing.value = false;
    }
};

const getStatusClass = (status) => {
    const classes = {
        menunggu: "bg-yellow-100 text-yellow-800 border border-yellow-200",
        in_review: "bg-blue-100 text-blue-800 border border-blue-200",
        interview_scheduled:
            "bg-purple-100 text-purple-800 border border-purple-200",
        accepted: "bg-green-100 text-green-800 border border-green-200",
        rejected: "bg-red-100 text-red-800 border border-red-200",
        letter_ready:
            "bg-emerald-100 text-emerald-800 border border-emerald-200",
        expired: "bg-gray-100 text-gray-600 border border-gray-200",
        // Legacy status values for backward compatibility
        dalam_review: "bg-blue-100 text-blue-800 border border-blue-200",
        wawancara: "bg-purple-100 text-purple-800 border border-purple-200",
        diterima: "bg-green-100 text-green-800 border border-green-200",
        ditolak: "bg-red-100 text-red-800 border border-red-200",
    };
    return (
        classes[status] || "bg-gray-100 text-gray-800 border border-gray-200"
    );
};

const getStatusText = (status) => {
    const texts = {
        menunggu: "Menunggu Review",
        in_review: "Sedang Direview",
        interview_scheduled: "Wawancara Dijadwalkan",
        accepted: "Diterima",
        rejected: "Ditolak",
        letter_ready: "Surat Siap",
        expired: "Kadaluarsa",
        // Legacy status values for backward compatibility
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
    showCancelModal.value = true;
};

const confirmCancel = () => {
    cancelling.value = true;
    router.delete(route("applications.cancel", props.application.id), {
        onSuccess: () => {
            showCancelModal.value = false;
            cancelling.value = false;
        },
        onError: () => {
            cancelling.value = false;
        },
    });
};

const closeCancelModal = () => {
    showCancelModal.value = false;
};

const downloadOffer = async () => {
    try {
        // First check if acceptance letter exists
        const checkResponse = await fetch(
            route("acceptance-letter.check", props.application.id)
        );
        const checkResult = await checkResponse.json();

        if (!checkResult.success || !checkResult.has_letter) {
            alert("Surat penerimaan belum tersedia. Hubungi pembimbing Anda.");
            return;
        }

        // Download the letter
        window.open(
            route("acceptance-letter.download", props.application.id),
            "_blank"
        );
    } catch (error) {
        console.error("Error downloading acceptance letter:", error);
        alert("Terjadi kesalahan saat mengunduh surat penerimaan.");
    }
};
</script>
