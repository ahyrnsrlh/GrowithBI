import { useForm } from "@inertiajs/vue3";

export function usePesertaReportsCreatePage() {
    const form = useForm({
        report_type: "weekly",
        title: "",
        period_start: "",
        period_end: "",
        summary: "",
        achievements: "",
        challenges: "",
        next_plans: "",
        selected_logbooks: [],
        report_file: null,
    });

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            form.report_file = file;
        }
    };

    const submit = () => {
        form.post(route("peserta.reports.store"), {
            forceFormData: true,
        });
    };

    const formatDate = (date) => {
        if (!date) {
            return "-";
        }

        return new Date(date).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    return {
        form,
        handleFileChange,
        submit,
        formatDate,
    };
}
