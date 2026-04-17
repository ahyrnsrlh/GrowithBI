<script setup>
import ProfileLogbookCreateActions from "@/Components/Profile/Logbooks/Create/ProfileLogbookCreateActions.vue";
import ProfileLogbookCreateAttachments from "@/Components/Profile/Logbooks/Create/ProfileLogbookCreateAttachments.vue";
import ProfileLogbookCreateFormIntro from "@/Components/Profile/Logbooks/Create/ProfileLogbookCreateFormIntro.vue";
import ProfileLogbookCreateHeader from "@/Components/Profile/Logbooks/Create/ProfileLogbookCreateHeader.vue";
import ProfileLogbookCreateMainFields from "@/Components/Profile/Logbooks/Create/ProfileLogbookCreateMainFields.vue";
import { useProfileLogbookCreatePage } from "@/Composables/useProfileLogbookCreatePage";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    acceptedApplication: {
        type: Object,
        default: null,
    },
});

const {
    form,
    maxDate,
    handleFileSelect,
    handleDrop,
    removeFile,
    formatFileSize,
    submit,
} = useProfileLogbookCreatePage();
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Logbook" />

        <div class="max-w-4xl mx-auto">
            <ProfileLogbookCreateHeader
                :back-href="route('profile.logbooks.index')"
            />

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <ProfileLogbookCreateFormIntro />

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <ProfileLogbookCreateMainFields
                        :form="form"
                        :max-date="maxDate"
                    />

                    <ProfileLogbookCreateAttachments
                        :form="form"
                        :handle-drop="handleDrop"
                        :handle-file-select="handleFileSelect"
                        :remove-file="removeFile"
                        :format-file-size="formatFileSize"
                    />

                    <ProfileLogbookCreateActions
                        :processing="form.processing"
                        :cancel-href="route('profile.logbooks.index')"
                    />
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
