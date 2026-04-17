<template>
    <AdminLayout
        title="Profile Admin"
        subtitle="Kelola informasi profile dan pengaturan akun"
    >
        <div class="max-w-7xl mx-auto space-y-6">
            <AdminProfileHeader
                :auth-user="auth.user"
                :photo-preview="photoPreview"
                :get-initials="getInitials"
                :format-date="formatDate"
                @photo-selected="handlePhotoSelect"
                @remove-photo="removePhoto"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <AdminProfileInformationCard
                        :edit-mode="editMode"
                        :auth-user="auth.user"
                        :profile-form="profileForm"
                        :photo-preview="photoPreview"
                        :get-initials="getInitials"
                        @toggle-edit="editMode = !editMode"
                        @select-photo-trigger="selectPhotoFromCard"
                        @remove-photo="removePhoto"
                        @submit-profile="updateProfile"
                        @cancel-edit="editMode = false"
                    />

                    <AdminPasswordCard
                        :password-form="passwordForm"
                        @submit-password="updatePassword"
                    />
                </div>

                <AdminProfileSidebar
                    :stats="stats"
                    :preferences="preferences"
                    @save-preferences="savePreferences"
                />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminPasswordCard from "@/Components/Admin/Profile/AdminPasswordCard.vue";
import AdminProfileHeader from "@/Components/Admin/Profile/AdminProfileHeader.vue";
import AdminProfileInformationCard from "@/Components/Admin/Profile/AdminProfileInformationCard.vue";
import AdminProfileSidebar from "@/Components/Admin/Profile/AdminProfileSidebar.vue";
import { useAdminProfilePage } from "@/Composables/useAdminProfilePage";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
});

const {
    editMode,
    photoPreview,
    profileForm,
    passwordForm,
    preferences,
    getInitials,
    formatDate,
    updateProfile,
    handlePhotoSelect,
    removePhoto,
    updatePassword,
    savePreferences,
} = useAdminProfilePage(props.auth);

const selectPhotoFromCard = () => {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";
    input.addEventListener("change", handlePhotoSelect);
    input.click();
};
</script>
