<script setup>
import PesertaReportsShowActions from "@/Components/Peserta/Reports/Show/PesertaReportsShowActions.vue";
import PesertaReportsShowHeader from "@/Components/Peserta/Reports/Show/PesertaReportsShowHeader.vue";
import PesertaReportsShowInfoCard from "@/Components/Peserta/Reports/Show/PesertaReportsShowInfoCard.vue";
import { usePesertaReportsShowPage } from "@/Composables/usePesertaReportsShowPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    report: {
        type: Object,
        required: true,
    },
});

const { formatDate, getStatusClass, getStatusText } =
    usePesertaReportsShowPage();
</script>

<template>
    <Head title="Detail Laporan" />

    <PesertaLayout
        title="Detail Laporan"
        subtitle="Lihat detail laporan akhir magang Anda"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            <PesertaReportsShowHeader
                :title="report.title || 'Detail Laporan'"
                :division-name="report.application?.division?.name || 'Unknown'"
                :back-href="route('peserta.reports.index')"
            />

            <PesertaReportsShowInfoCard
                :report="report"
                :format-date="formatDate"
                :get-status-class="getStatusClass"
                :get-status-text="getStatusText"
            />

            <PesertaReportsShowActions
                :report="report"
                :edit-href="route('peserta.reports.edit', report.id)"
            />
        </div>
    </PesertaLayout>
</template>
