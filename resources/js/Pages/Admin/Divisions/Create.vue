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
        <div class="max-w-5xl mx-auto">
            <DivisionCreateHeader :back-href="route('admin.divisions.index')" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            Informasi Dasar
                                        </h2>
                                        <p class="text-xs text-gray-500">
                                            Nama, deskripsi, dan kuota peserta.
                                        </p>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-gray-100">
                                    <DivisionBasicFields
                                        :form="form"
                                        :description-required="true"
                                        :quota-max="100"
                                    />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h2
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        Deskripsi & Persyaratan
                                    </h2>
                                    <p class="text-xs text-gray-500">
                                        Detail tugas dan kualifikasi peserta.
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-gray-100">
                                    <DivisionListFields
                                        :form="form"
                                        @add-job-description="addJobDescription"
                                        @remove-job-description="
                                            removeJobDescription
                                        "
                                        @add-requirement="addRequirement"
                                        @remove-requirement="removeRequirement"
                                    />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h2
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        Timeline
                                    </h2>
                                    <p class="text-xs text-gray-500">
                                        Atur tanggal mulai, akhir, dan seleksi.
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-gray-100">
                                    <DivisionDateFields
                                        :form="form"
                                        :required-fields="true"
                                    />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h2
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        Status
                                    </h2>
                                    <p class="text-xs text-gray-500">
                                        Tentukan divisi bisa menerima
                                        pendaftaran.
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-gray-100">
                                    <DivisionCreateStatusField :form="form" />
                                </div>
                            </div>

                            <DivisionFormActions
                                :processing="form.processing"
                                :cancel-href="route('admin.divisions.index')"
                                submit-label="Simpan Divisi"
                                processing-label="Menyimpan..."
                            />
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <DivisionTipsCard title="Tips" :tips="tips" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
