<script setup>
import DivisionBasicFields from "@/Components/Admin/Divisions/DivisionBasicFields.vue";
import DivisionCreateHeader from "@/Components/Admin/Divisions/DivisionCreateHeader.vue";
import DivisionCreateStatusField from "@/Components/Admin/Divisions/DivisionCreateStatusField.vue";
import DivisionDateFields from "@/Components/Admin/Divisions/DivisionDateFields.vue";
import DivisionFormActions from "@/Components/Admin/Divisions/DivisionFormActions.vue";
import DivisionListFields from "@/Components/Admin/Divisions/DivisionListFields.vue";
import DivisionTipsCard from "@/Components/Admin/Divisions/DivisionTipsCard.vue";
import { useAdminDivisionCreatePage } from "@/Composables/useAdminDivisionCreatePage";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const {
    form,
    addJobDescription,
    removeJobDescription,
    addRequirement,
    removeRequirement,
    submit,
} = useAdminDivisionCreatePage();

const tips = [
    "Pastikan deskripsi divisi jelas dan menarik untuk calon peserta",
    "Kuota dapat disesuaikan berdasarkan kapasitas pembimbing",
    "Periode magang sebaiknya minimal 2-3 bulan untuk hasil optimal",
    "Pilih pembimbing yang sesuai dengan expertise divisi",
];
</script>

<template>
    <AdminLayout title="Tambah Divisi" subtitle="Buat divisi magang baru">
        <div class="max-w-2xl mx-auto">
            <DivisionCreateHeader :back-href="route('admin.divisions.index')" />

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <DivisionBasicFields
                        :form="form"
                        :description-required="true"
                        :quota-max="100"
                    />

                    <DivisionListFields
                        :form="form"
                        @add-job-description="addJobDescription"
                        @remove-job-description="removeJobDescription"
                        @add-requirement="addRequirement"
                        @remove-requirement="removeRequirement"
                    />

                    <DivisionDateFields :form="form" :required-fields="true" />

                    <DivisionCreateStatusField :form="form" />

                    <DivisionFormActions
                        :processing="form.processing"
                        :cancel-href="route('admin.divisions.index')"
                        submit-label="Simpan Divisi"
                        processing-label="Menyimpan..."
                    />
                </form>
            </div>

            <DivisionTipsCard title="Tips" :tips="tips" />
        </div>
    </AdminLayout>
</template>
