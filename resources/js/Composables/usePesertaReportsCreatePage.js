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
        form.transform((data) => {
            const formattedType = {
                weekly: "Mingguan",
                monthly: "Bulanan",
                final: "Akhir"
            }[data.report_type] || data.report_type;

            const periodText = (data.period_start && data.period_end)
                ? `${formatDate(data.period_start)} - ${formatDate(data.period_end)}`
                : "-";

            const descriptionParts = [
                `Tipe Laporan: ${formattedType}`,
                `Periode: ${periodText}`,
                `Ringkasan Aktivitas:\n${data.summary || "-"}`,
                `Pencapaian:\n${data.achievements || "-"}`,
                `Tantangan & Pembelajaran:\n${data.challenges || "-"}`,
                `Rencana Selanjutnya:\n${data.next_plans || "-"}`
            ];

            return {
                ...data,
                description: descriptionParts.join("\n\n")
            };
        }).post(route("peserta.reports.store"), {
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
