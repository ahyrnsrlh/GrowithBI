import { computed, onMounted, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";

export function usePesertaLogbookEditPage(logbook) {
    const newFiles = ref([]);
    const existingFiles = ref([]);
    const processing = ref(false);
    const submitType = ref("");

    const form = useForm({
        title: logbook.title || "",
        date: logbook.date || "",
        duration: logbook.duration || "",
        activities: logbook.activities || "",
        learning_points: logbook.learning_points || "",
        challenges: logbook.challenges || "",
        attachments: [],
        removed_files: [],
    });

    const today = computed(() => new Date().toISOString().split("T")[0]);

    const lastRevisionComment = computed(() => {
        if (!logbook.comments) {
            return null;
        }

        return logbook.comments
            .filter((comment) => comment.comment_type === "revision")
            .sort(
                (first, second) =>
                    new Date(second.created_at) - new Date(first.created_at),
            )[0];
    });

    onMounted(() => {
        if (logbook.attachments) {
            existingFiles.value = [...logbook.attachments];
        }
    });

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }

        try {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        } catch {
            return "-";
        }
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) {
            return "0 Bytes";
        }
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
    };

    const addFiles = (files) => {
        const validFiles = files.filter((file) => {
            const maxSize = 10 * 1024 * 1024;
            const allowedTypes = [
                "application/pdf",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "image/jpeg",
                "image/jpg",
                "image/png",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ];

            return file.size <= maxSize && allowedTypes.includes(file.type);
        });

        newFiles.value = [...newFiles.value, ...validFiles];
    };

    const removeNewFile = (index) => {
        newFiles.value.splice(index, 1);
    };

    const removeExistingFile = (index) => {
        const removedFile = existingFiles.value[index];
        form.removed_files.push(removedFile.id);
        existingFiles.value.splice(index, 1);
    };

    const validateForm = () => {
        const errors = {};

        if (!form.title?.trim()) {
            errors.title = "Judul logbook harus diisi";
        }

        if (!form.date) {
            errors.date = "Tanggal harus diisi";
        }

        if (!form.duration || form.duration < 0.5 || form.duration > 12) {
            errors.duration = "Durasi harus antara 0.5 - 12 jam";
        }

        if (!form.activities?.trim() || form.activities.length < 50) {
            errors.activities = "Deskripsi aktivitas minimal 50 karakter";
        }

        return errors;
    };

    const handleCancel = () => {
        const hasChanges =
            form.title !== (logbook.title || "") ||
            form.date !== (logbook.date || "") ||
            form.duration !== (logbook.duration || "") ||
            form.activities !== (logbook.activities || "") ||
            form.learning_points !== (logbook.learning_points || "") ||
            form.challenges !== (logbook.challenges || "") ||
            newFiles.value.length > 0 ||
            form.removed_files.length > 0;

        if (hasChanges) {
            const shouldLeave = confirm(
                "Anda memiliki perubahan yang belum disimpan. Yakin ingin membatalkan?",
            );
            if (!shouldLeave) {
                return;
            }
        }

        router.visit(route("peserta.logbooks.index"));
    };

    const submitForm = () => {
        submitType.value = "submit";
        updateLogbook("submitted");
    };

    const saveDraft = () => {
        submitType.value = "draft";
        updateLogbook("draft");
    };

    const updateLogbook = (status) => {
        const formErrors = validateForm();
        if (Object.keys(formErrors).length > 0 && status === "submitted") {
            return;
        }

        processing.value = true;

        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("title", form.title);
        formData.append("date", form.date);
        formData.append("duration", form.duration);
        formData.append("activities", form.activities);
        formData.append("learning_points", form.learning_points || "");
        formData.append("challenges", form.challenges || "");
        formData.append("status", status);

        newFiles.value.forEach((file, index) => {
            formData.append(`attachments[${index}]`, file);
        });

        form.removed_files.forEach((fileId, index) => {
            formData.append(`removed_files[${index}]`, fileId);
        });

        router.post(route("peserta.logbooks.update", logbook.id), formData, {
            forceFormData: true,
            onSuccess: () => {
                router.visit(route("peserta.logbooks.show", logbook.id));
            },
            onError: (errors) => {
                console.error("Update errors:", errors);
            },
            onFinish: () => {
                processing.value = false;
            },
        });
    };

    return {
        form,
        newFiles,
        existingFiles,
        processing,
        submitType,
        today,
        lastRevisionComment,
        formatDate,
        formatFileSize,
        addFiles,
        removeNewFile,
        removeExistingFile,
        handleCancel,
        submitForm,
        saveDraft,
    };
}
