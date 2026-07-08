<template>
    <div class="group relative">
        <div
            class="bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 hover:border-gray-200"
        >
            <ApplicationCardHeader
                :application="application"
                :get-accent-gradient="getAccentGradient"
                :get-status-badge-class="getStatusBadgeClass"
                :get-status-dot-class="getStatusDotClass"
                :get-status-text="getStatusText"
                :format-date="formatDate"
            />

            <ApplicationCardTimeline
                :application="application"
                :get-progress-bar-color="getProgressBarColor"
                :get-progress-width-adjusted="getProgressWidthAdjusted"
                :is-step-complete="isStepComplete"
                :is-step-active="isStepActive"
                :is-accepted="isAccepted"
                :show-letter-step="showLetterStep"
                :format-short-date="formatShortDate"
            />

            <ApplicationCardActions
                :application="application"
                :downloading="downloading"
                @view-details="viewDetails"
                @download-offer="downloadOffer"
                @withdraw-application="confirmWithdraw"
            />
        </div>

        <ApplicationCardDetailModal
            :show="showModal"
            :application="application"
            :downloading="downloading"
            :get-accent-gradient="getAccentGradient"
            :get-status-badge-class="getStatusBadgeClass"
            :get-status-dot-class="getStatusDotClass"
            :get-status-text="getStatusText"
            :format-date="formatDate"
            @close="closeModal"
            @download-offer="downloadOffer"
        />

        <Modal :show="showConfirmModal" @close="showConfirmModal = false" maxWidth="md">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">
                    Batalkan Lamaran?
                </h3>
                <p class="text-sm text-gray-500 text-center mb-6">
                    Anda akan membatalkan lamaran magang yang telah dikirim. Setelah pembatalan berhasil, Anda dapat mengajukan lamaran baru.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button
                        @click="showConfirmModal = false"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200"
                    >
                        Kembali
                    </button>
                    <button
                        @click="handleWithdraw"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-all duration-200"
                    >
                        Ya, Batalkan Lamaran
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import ApplicationCardActions from "@/Components/ApplicationCard/ApplicationCardActions.vue";
import ApplicationCardDetailModal from "@/Components/ApplicationCard/ApplicationCardDetailModal.vue";
import ApplicationCardHeader from "@/Components/ApplicationCard/ApplicationCardHeader.vue";
import ApplicationCardTimeline from "@/Components/ApplicationCard/ApplicationCardTimeline.vue";
import { useApplicationCard } from "@/Composables/useApplicationCard";

const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["status-updated"]);

const showConfirmModal = ref(false);

const confirmWithdraw = () => {
    showConfirmModal.value = true;
};

const handleWithdraw = () => {
    router.post(route("applications.cancel", props.application.id), {
        _method: 'delete',
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showConfirmModal.value = false;
        },
        onError: (errors) => {
            console.error("Failed to cancel application:", errors);
            showConfirmModal.value = false;
        },
        onFinish: () => {
            showConfirmModal.value = false;
        },
    });
};

const {
    showModal,
    downloading,
    isAccepted,
    isStepComplete,
    isStepActive,
    showLetterStep,
    getProgressWidthAdjusted,
    getProgressBarColor,
    getAccentGradient,
    getStatusBadgeClass,
    getStatusDotClass,
    getStatusText,
    formatDate,
    formatShortDate,
    viewDetails,
    closeModal,
    downloadOffer,
} = useApplicationCard(props.application, emit);
</script>
