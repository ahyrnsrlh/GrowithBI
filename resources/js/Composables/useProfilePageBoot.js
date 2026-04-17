import { onMounted } from "vue";

export function useProfilePageBoot({
    props,
    activeTab,
    editMode,
    showToast,
    getStatusText,
}) {
    onMounted(() => {
        console.log("Profile data received:", {
            reports: props.reports,
            reportCount: props.reports ? props.reports.length : 0,
            logbooks: props.logbooks,
            logbookCount: props.logbooks ? props.logbooks.length : 0,
            applications: props.applications,
            user: props.user,
        });

        if (props.status) {
            showToast("success", props.status);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get("tab");
        if (
            tab &&
            [
                "profile",
                "documents",
                "applications",
                "attendance",
                "logbook",
                "reports",
            ].includes(tab)
        ) {
            activeTab.value = tab;
        }

        const missingFields = urlParams.get("missing_fields");
        if (missingFields) {
            const fieldLabels = {
                phone: "Nomor Telepon",
                address: "Alamat",
                university: "Universitas",
                major: "Jurusan",
                semester: "Semester",
            };
            const missingLabels = missingFields
                .split(",")
                .map((field) => fieldLabels[field] || field)
                .join(", ");
            showToast("error", `Harap lengkapi: ${missingLabels}`);
            activeTab.value = "profile";
            editMode.value = true;
        }

        if (
            urlParams.get("from") === "application" ||
            urlParams.get("success") === "application"
        ) {
            activeTab.value = "applications";
            showToast(
                "success",
                "Selamat! Lamaran Anda telah berhasil dikirim. Anda dapat memantau statusnya di sini.",
            );
        }

        if (props.applications && props.applications.length > 0) {
            const latestApplication = props.applications[0];
            const applicationTime = new Date(latestApplication.created_at);
            const currentTime = new Date();
            const timeDiff = (currentTime - applicationTime) / (1000 * 60);
            if (timeDiff < 5) {
                setTimeout(() => {
                    activeTab.value = "applications";
                    showToast(
                        "info",
                        `Status lamaran terbaru: ${getStatusText(latestApplication.status)}`,
                    );
                }, 1000);
            }
        }
    });
}
