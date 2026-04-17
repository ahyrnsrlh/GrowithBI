<script setup>
import PesertaReportsIndexActionBar from "@/Components/Peserta/Reports/Index/PesertaReportsIndexActionBar.vue";
import PesertaReportsIndexInternshipInfo from "@/Components/Peserta/Reports/Index/PesertaReportsIndexInternshipInfo.vue";
import PesertaReportsIndexProgressSummary from "@/Components/Peserta/Reports/Index/PesertaReportsIndexProgressSummary.vue";
import PesertaReportsIndexRecentLogbooks from "@/Components/Peserta/Reports/Index/PesertaReportsIndexRecentLogbooks.vue";
import PesertaReportsIndexStatsCards from "@/Components/Peserta/Reports/Index/PesertaReportsIndexStatsCards.vue";
import { usePesertaReportsIndexPage } from "@/Composables/usePesertaReportsIndexPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    logbooks: {
        type: Array,
        default: () => [],
    },
    application: {
        type: Object,
        default: null,
    },
});

const {
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
            <PesertaReportsIndexStatsCards :stats="stats" />

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

            <PesertaReportsIndexRecentLogbooks
                :logbooks="logbooks"
                :format-date="formatDate"
                :get-status-class="getStatusClass"
                :get-status-text="getStatusText"
                :create-logbook-href="route('peserta.logbooks.create')"
                :logbooks-index-href="route('peserta.logbooks.index')"
            />
        </div>
    </PesertaLayout>
</template>
