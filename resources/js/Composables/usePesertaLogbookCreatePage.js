import { computed, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";

export function usePesertaLogbookCreatePage() {
    const selectedFiles = ref([]);

    const form = useForm({
        date: new Date().toISOString().split("T")[0],
        title: "",
        activities: "",
        learning_points: "",
        challenges: "",
        duration: "",
        attachments: [],
        status: "submitted",
    });

    const today = new Date().toISOString().split("T")[0];

    const isFormValid = computed(() => {
        return (
            form.date &&
            form.title.trim() !== "" &&
            form.activities.trim() !== "" &&
            form.duration !== ""
        );
    });

    const handleFileUpload = (event) => {
        const files = Array.from(event.target.files || []);
        const maxSize = 5 * 1024 * 1024;

        files.forEach((file) => {
            if (file.size > maxSize) {
                alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
                return;
            }

            const exists = selectedFiles.value.find(
                (item) => item.name === file.name,
            );
            if (!exists) {
                selectedFiles.value.push(file);
            }
        });

        event.target.value = "";
    };

    const removeFile = (index) => {
        selectedFiles.value.splice(index, 1);
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) {
            return "0 Bytes";
        }

        const k = 1024;
        const sizes = ["Bytes", "KB", "MB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
    };

    const handleCancel = () => {
        const hasData =
            form.title.trim() !== "" ||
            form.activities.trim() !== "" ||
            form.learning_points.trim() !== "" ||
            form.challenges.trim() !== "" ||
            form.duration !== "" ||
            form.date !== new Date().toISOString().split("T")[0] ||
            selectedFiles.value.length > 0;

        if (hasData) {
            const shouldCancel = confirm(
                "Anda memiliki data yang belum disimpan. Yakin ingin membatalkan?",
            );
            if (!shouldCancel) {
                return;
            }
        }

        router.visit(route("peserta.logbooks.index"));
    };

    const submitWithStatus = (status) => {
        form.attachments = selectedFiles.value;
        form.status = status;

        form.post(route("peserta.logbooks.store"), {
            forceFormData: true,
            onSuccess: () => {
                selectedFiles.value = [];
            },
        });
    };

    const submit = () => {
        submitWithStatus("submitted");
    };

    const saveAsDraft = () => {
        submitWithStatus("draft");
    };

    return {
        form,
        today,
        isFormValid,
        selectedFiles,
        handleFileUpload,
        removeFile,
        formatFileSize,
        handleCancel,
        submit,
        saveAsDraft,
    };
}
