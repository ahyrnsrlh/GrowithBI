import { useForm } from "@inertiajs/vue3";

export function usePesertaReportsEditPage(report) {
    const form = useForm({
        title: report.title || "",
        description: report.description || "",
        report_file: null,
    });

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            form.report_file = file;
        }
    };

    const submit = () => {
        form.put(route("peserta.reports.update", report.id), {
            forceFormData: true,
        });
    };

    return {
        form,
        handleFileChange,
        submit,
    };
}
