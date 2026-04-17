import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

export function useAdminProfilePage(auth) {
    const editMode = ref(false);
    const photoPreview = ref(null);
    const selectedPhoto = ref(null);

    const profileForm = useForm({
        name: auth.user.name,
        email: auth.user.email,
        phone: auth.user.phone || "",
        position: auth.user.position || "",
        bio: auth.user.bio || "",
        profile_photo: null,
    });

    const passwordForm = useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
    });

    const preferences = reactive({
        email_notifications: true,
        auto_logout: false,
    });

    const getInitials = (name) => {
        return name
            .split(" ")
            .map((word) => word.charAt(0))
            .join("")
            .toUpperCase()
            .slice(0, 2);
    };

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
        });
    };

    const updateProfile = () => {
        if (selectedPhoto.value) {
            profileForm.profile_photo = selectedPhoto.value;
        }

        profileForm.post("/admin/profile", {
            forceFormData: true,
            onSuccess: (page) => {
                editMode.value = false;
                photoPreview.value = null;
                selectedPhoto.value = null;

                if (page.props.auth && page.props.auth.user) {
                    Object.assign(auth.user, page.props.auth.user);
                }
            },
            onError: (errors) => {
                console.error("Profile update errors:", errors);
            },
        });
    };

    const handlePhotoSelect = (event) => {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        if (!file.type.startsWith("image/")) {
            alert("Silakan pilih file gambar");
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert("Ukuran file maksimal 2MB");
            return;
        }

        selectedPhoto.value = file;

        const reader = new FileReader();
        reader.onload = (readerEvent) => {
            photoPreview.value = readerEvent.target.result;
        };
        reader.readAsDataURL(file);
    };

    const removePhoto = () => {
        photoPreview.value = null;
        selectedPhoto.value = null;
        profileForm.profile_photo = null;

        if (auth.user.profile_photo_path) {
            const deleteForm = useForm({});
            deleteForm.delete("/admin/profile/photo", {
                preserveScroll: true,
                onSuccess: () => {
                    auth.user.profile_photo_path = null;
                },
            });
        }
    };

    const updatePassword = () => {
        passwordForm.put("/admin/profile/password", {
            onSuccess: () => {
                passwordForm.reset();
            },
        });
    };

    const savePreferences = () => {
        const form = useForm(preferences);
        form.put("/admin/profile/preferences", {
            onSuccess: () => {
                console.log("Preferences saved successfully");
            },
        });
    };

    return {
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
    };
}
