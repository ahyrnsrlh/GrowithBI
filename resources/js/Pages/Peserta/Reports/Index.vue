<script setup>
import PesertaReportsIndexActionBar from "@/Components/Peserta/Reports/Index/PesertaReportsIndexActionBar.vue";
import PesertaReportsIndexInternshipInfo from "@/Components/Peserta/Reports/Index/PesertaReportsIndexInternshipInfo.vue";
import PesertaReportsIndexProgressSummary from "@/Components/Peserta/Reports/Index/PesertaReportsIndexProgressSummary.vue";
import PesertaReportsIndexRecentLogbooks from "@/Components/Peserta/Reports/Index/PesertaReportsIndexRecentLogbooks.vue";
import ReportGrid from "@/Components/Profile/Reports/ReportGrid.vue";
import FinalReportDetailModal from "@/Components/Profile/Reports/FinalReportDetailModal.vue";
import FinalReportEditModal from "@/Components/Profile/Reports/FinalReportEditModal.vue";
import ToastNotification from "@/Components/Profile/ToastNotification.vue";
import { usePesertaReportsIndexPage } from "@/Composables/usePesertaReportsIndexPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Head, router } from "@inertiajs/vue3";

defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    logbooks: {
        type: Array,
        default: () => [],
    },
    reports: {
        type: Array,
        default: () => [],
    },
    application: {
        type: Object,
        default: null,
    },
});

const {
    showDetailReportModal,
    showEditReportModal,
    selectedReportId,
    viewReportDetail,
    editReport,
    deleteReport,
    handleReportEditSuccess,
    showNotification,
    notificationType,
    notificationMessage,
    formatDate,
    getStatusClass,
    getStatusText,
    getCompletionRate,
    generateReport,
} = usePesertaReportsIndexPage();
</script>

<template>
    <Head title="Laporan Magang" />

    <PesertaLayout
        title="Laporan Magang"
        subtitle="Kelola dan buat laporan progress magang Anda"
    >
        <div class="space-y-6">
            <PesertaReportsIndexInternshipInfo
                :stats="stats"
                :format-date="formatDate"
            />

            <PesertaReportsIndexActionBar
                :on-generate-report="generateReport"
                :create-href="route('peserta.reports.create')"
            />

            <PesertaReportsIndexProgressSummary
                :stats="stats"
                :completion-rate="getCompletionRate(stats)"
            />

            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Laporan Akhir Magang</h3>
                        <p class="text-xs text-gray-500 mt-1">Daftar laporan akhir yang telah Anda unggah</p>
                    </div>
                </div>
                <ReportGrid
                    :reports="reports"
                    :formatDate="formatDate"
                    @create="router.visit(route('peserta.reports.create'))"
                    @view="viewReportDetail"
                    @edit="editReport"
                    @delete="deleteReport"
                />
            </div>

            <PesertaReportsIndexRecentLogbooks
                :logbooks="logbooks"
                :format-date="formatDate"
                :get-status-class="getStatusClass"
                :get-status-text="getStatusText"
                :create-logbook-href="route('peserta.logbooks.create')"
                :logbooks-index-href="route('peserta.logbooks.index')"
            />

            <FinalReportDetailModal
                :show="showDetailReportModal"
                :report-id="selectedReportId"
                @close="showDetailReportModal = false"
            />

            <FinalReportEditModal
                :show="showEditReportModal"
                :report-id="selectedReportId"
                @close="showEditReportModal = false"
                @success="handleReportEditSuccess"
            />

            <ToastNotification
                v-if="showNotification"
                :type="notificationType"
                :message="notificationMessage"
                @close="showNotification = false"
            />
        </div>
    </PesertaLayout>
</template>
