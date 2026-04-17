<script setup>
import PesertaReportsCreateActions from "@/Components/Peserta/Reports/Create/PesertaReportsCreateActions.vue";
import PesertaReportsCreateDetails from "@/Components/Peserta/Reports/Create/PesertaReportsCreateDetails.vue";
import PesertaReportsCreateFileUpload from "@/Components/Peserta/Reports/Create/PesertaReportsCreateFileUpload.vue";
import PesertaReportsCreateLogbookSelection from "@/Components/Peserta/Reports/Create/PesertaReportsCreateLogbookSelection.vue";
import PesertaReportsCreateReportType from "@/Components/Peserta/Reports/Create/PesertaReportsCreateReportType.vue";
import { usePesertaReportsCreatePage } from "@/Composables/usePesertaReportsCreatePage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    division: {
        type: Object,
        default: null,
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

const { form, handleFileChange, submit, formatDate } =
    usePesertaReportsCreatePage();
</script>

<template>
    <Head title="Buat Laporan Magang" />

    <PesertaLayout
        title="Buat Laporan Magang"
        subtitle="Buat laporan progress dan pencapaian selama magang"
    >
        <div class="max-w-4xl mx-auto">
            <form @submit.prevent="submit" class="space-y-6">
                <PesertaReportsCreateReportType :form="form" />

                <PesertaReportsCreateDetails :form="form" />

                <PesertaReportsCreateLogbookSelection
                    :form="form"
                    :logbooks="logbooks"
                    :format-date="formatDate"
                />

                <PesertaReportsCreateFileUpload
                    :form="form"
                    :handle-file-change="handleFileChange"
                />

                <PesertaReportsCreateActions
                    :processing="form.processing"
                    :cancel-href="route('peserta.reports.index')"
                />
            </form>
        </div>
    </PesertaLayout>
</template>
