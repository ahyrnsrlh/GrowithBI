<script setup>
import PesertaReportsEditActions from "@/Components/Peserta/Reports/Edit/PesertaReportsEditActions.vue";
import PesertaReportsEditFileUpload from "@/Components/Peserta/Reports/Edit/PesertaReportsEditFileUpload.vue";
import PesertaReportsEditHeader from "@/Components/Peserta/Reports/Edit/PesertaReportsEditHeader.vue";
import PesertaReportsEditMetaFields from "@/Components/Peserta/Reports/Edit/PesertaReportsEditMetaFields.vue";
import { usePesertaReportsEditPage } from "@/Composables/usePesertaReportsEditPage";
import PesertaLayout from "@/Layouts/PesertaLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    report: {
        type: Object,
        required: true,
    },
});

const { form, handleFileChange, submit } = usePesertaReportsEditPage(
    props.report,
);
</script>

<template>
    <Head title="Edit Laporan" />

    <PesertaLayout
        title="Edit Laporan"
        subtitle="Edit laporan akhir magang Anda"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            <PesertaReportsEditHeader
                :back-href="route('peserta.reports.show', report.id)"
            />

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Form Edit Laporan
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Ubah informasi laporan Anda
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <PesertaReportsEditMetaFields :form="form" />

                    <PesertaReportsEditFileUpload
                        :form="form"
                        :current-file-name="report.file_name || 'Unknown'"
                        :handle-file-change="handleFileChange"
                    />

                    <PesertaReportsEditActions
                        :processing="form.processing"
                        :cancel-href="route('peserta.reports.show', report.id)"
                    />
                </form>
            </div>
        </div>
    </PesertaLayout>
</template>
