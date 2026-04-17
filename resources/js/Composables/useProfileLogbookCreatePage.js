import { useForm } from "@inertiajs/vue3";

export function useProfileLogbookCreatePage() {
    const form = useForm({
        date: new Date().toISOString().split("T")[0],
        hours: 8,
        activities: "",
        description: "",
        learning_points: "",
        attachments: [],
    });

    const maxDate = new Date().toISOString().split("T")[0];

    const handleFileSelect = (event) => {
        const files = Array.from(event.target.files);
        form.attachments = [...form.attachments, ...files];
    };

    const handleDrop = (event) => {
        event.preventDefault();
        const files = Array.from(event.dataTransfer.files);
        form.attachments = [...form.attachments, ...files];
    };

    const removeFile = (index) => {
        form.attachments.splice(index, 1);
    };

    const formatFileSize = (bytes) => {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    };

    const submit = () => {
        form.post(route("profile.logbooks.store"), {
            onSuccess: () => {
                // Redirect is handled by controller response.
            },
        });
    };

    return {
        form,
        maxDate,
        handleFileSelect,
        handleDrop,
        removeFile,
        formatFileSize,
        submit,
    };
}
