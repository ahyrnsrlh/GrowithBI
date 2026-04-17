import { useForm } from "@inertiajs/vue3";

export function useAdminDivisionEditPage(division) {
    const form = useForm({
        name: division.name,
        description: division.description,
        job_description: division.job_description || [""],
        requirements: division.requirements || [""],
        quota: division.quota,
        start_date: division.start_date || "",
        end_date: division.end_date || "",
        application_deadline: division.application_deadline || "",
        selection_announcement: division.selection_announcement || "",
        is_active: division.is_active,
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

        form.put(route("admin.divisions.update", division.id));
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
