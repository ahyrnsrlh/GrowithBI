import { useForm } from "@inertiajs/vue3";

export function useAdminDivisionCreatePage() {
    const form = useForm({
        name: "",
        description: "",
        job_description: [""],
        requirements: [""],
        quota: 10,
        start_date: "",
        end_date: "",
        application_deadline: "",
        selection_announcement: "",
        is_active: true,
    });

    const addJobDescription = () => {
        form.job_description.push("");
    };

    const removeJobDescription = (index) => {
        form.job_description.splice(index, 1);
    };

    const addRequirement = () => {
        form.requirements.push("");
    };

    const removeRequirement = (index) => {
        form.requirements.splice(index, 1);
    };

    const submit = () => {
        form.job_description = form.job_description.filter(
            (job) => job.trim() !== "",
        );
        form.requirements = form.requirements.filter(
            (req) => req.trim() !== "",
        );

        form.post("/admin/divisions");
    };

    return {
        form,
        addJobDescription,
        removeJobDescription,
        addRequirement,
        removeRequirement,
        submit,
    };
}
