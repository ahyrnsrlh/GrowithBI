<script setup>
import DivisionBasicFields from "@/Components/Admin/Divisions/DivisionBasicFields.vue";
import DivisionDateFields from "@/Components/Admin/Divisions/DivisionDateFields.vue";
import DivisionFormActions from "@/Components/Admin/Divisions/DivisionFormActions.vue";
import DivisionFormHeader from "@/Components/Admin/Divisions/DivisionFormHeader.vue";
import DivisionListFields from "@/Components/Admin/Divisions/DivisionListFields.vue";
import DivisionStatusField from "@/Components/Admin/Divisions/DivisionStatusField.vue";
import DivisionTipsCard from "@/Components/Admin/Divisions/DivisionTipsCard.vue";
import { useAdminDivisionEditPage } from "@/Composables/useAdminDivisionEditPage";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    division: {
        type: Object,
        required: true,
    },
});

const {
    form,
    addJobDescription,
    removeJobDescription,
    addRequirement,
    removeRequirement,
    submit,
} = useAdminDivisionEditPage(props.division);

const tips = [
    "Pastikan nama divisi unik dan deskriptif",
    "Kuota disesuaikan dengan kapasitas pembimbing",
    "Persyaratan yang jelas membantu calon peserta",
    "Divisi tidak aktif tidak akan menerima pendaftar baru",
];
</script>

<template>
    <AdminLayout title="Edit Divisi" :subtitle="`Edit divisi ${form.name}`">
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <DivisionFormHeader
                    title="Edit Divisi"
                    :back-href="route('admin.divisions.index')"
                    back-label="Kembali ke Daftar Divisi"
                />

                <form @submit.prevent="submit" class="space-y-6">
                    <DivisionBasicFields :form="form" />

                    <DivisionListFields
                        :form="form"
                        @add-job-description="addJobDescription"
                        @remove-job-description="removeJobDescription"
                        @add-requirement="addRequirement"
                        @remove-requirement="removeRequirement"
                    />

                    <DivisionDateFields :form="form" />

                    <DivisionStatusField :form="form" />

                    <DivisionFormActions
                        :processing="form.processing"
                        :cancel-href="route('admin.divisions.index')"
                        submit-label="Simpan Perubahan"
                        processing-label="Menyimpan..."
                    />
                </form>
            </div>

            <DivisionTipsCard title="Tips Edit Divisi" :tips="tips" />
        </div>
    </AdminLayout>
</template>
