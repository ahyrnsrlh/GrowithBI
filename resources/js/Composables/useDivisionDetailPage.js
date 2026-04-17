import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

export function useDivisionDetailPage(props) {
    const isLoading = ref(false);
    const notification = ref({
        show: false,
        type: "success",
        title: "",
        message: "",
    });
    const shouldRedirectToProfile = ref(false);
    const redirectCountdown = ref(5);

    const hasJobDescription = computed(() => {
        const jd = props.division.job_description;
        return jd && (Array.isArray(jd) ? jd.length > 0 : !!jd);
    });

    const normalizedJobDescription = computed(() => {
        const jd = props.division.job_description;
        if (!jd) return [];
        if (Array.isArray(jd)) return jd.filter((item) => item && item.trim());
        if (typeof jd === "string") {
            return jd.split("\n").filter((item) => item.trim());
        }
        return [];
    });

    const hasRequirements = computed(() => {
        const req = props.division.requirements;
        return req && (Array.isArray(req) ? req.length > 0 : !!req);
    });

    const normalizedRequirements = computed(() => {
        const req = props.division.requirements;
        if (!req) return [];
        if (Array.isArray(req)) {
            return req.filter((item) => item && item.trim());
        }
        if (typeof req === "string") {
            return req.split("\n").filter((item) => item.trim());
        }
        return [];
    });

    const hasCriteria = computed(() => {
        const criteria = props.division.criteria;
        return (
            criteria &&
            (Array.isArray(criteria) ? criteria.length > 0 : !!criteria)
        );
    });

    const normalizedCriteria = computed(() => {
        const criteria = props.division.criteria;
        if (!criteria) return [];
        if (Array.isArray(criteria)) {
            return criteria.filter((item) => item && item.trim());
        }
        if (typeof criteria === "string") {
            return criteria.split("\n").filter((item) => item.trim());
        }
        return [];
    });

    const hasBenefits = computed(() => {
        const benefits = props.division.benefit || props.division.benefits;
        return (
            benefits &&
            (Array.isArray(benefits) ? benefits.length > 0 : !!benefits)
        );
    });

    const normalizedBenefits = computed(() => {
        const benefits = props.division.benefit || props.division.benefits;
        if (!benefits) return [];
        if (Array.isArray(benefits)) {
            return benefits.filter((item) => item && item.trim());
        }
        if (typeof benefits === "string") {
            return benefits.split("\n").filter((item) => item.trim());
        }
        return [];
    });

    const availableSlots = computed(() => {
        return (
            props.division.available_slots ??
            props.division.available_quota ??
            props.division.quota ??
            0
        );
    });

    const formattedDuration = computed(() => {
        const start = props.division.start_date;
        const end = props.division.end_date;

        if (!start || !end) return "-";

        const startDate = new Date(start);
        const endDate = new Date(end);
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const diffMonths = Math.round(diffDays / 30);

        if (diffMonths >= 1) {
            return `${diffMonths} bulan`;
        }
        return `${diffDays} hari`;
    });

    const formatQuota = computed(() => {
        const quota = props.division.quota ?? props.division.max_interns;
        return quota ? `${quota} orang` : "-";
    });

    const formatAvailableSlots = computed(() => {
        if (availableSlots.value === 0) return "Penuh";
        return availableSlots.value > 0
            ? `${availableSlots.value} tersisa`
            : "-";
    });

    const formatDate = (date) => {
        if (!date) return "-";
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const cleanTaskText = (text) => {
        if (!text) return "";
        return text.replace(/^\d+\.\s*/, "").trim();
    };

    const showNotification = (type, title, message) => {
        notification.value = { show: true, type, title, message };

        if (type === "success" && shouldRedirectToProfile.value) {
            redirectCountdown.value = 5;

            const countdownInterval = setInterval(() => {
                redirectCountdown.value--;
                if (redirectCountdown.value <= 0) {
                    clearInterval(countdownInterval);
                    if (notification.value.show) {
                        closeNotification();
                    }
                }
            }, 1000);
        }
    };

    const closeNotification = () => {
        notification.value.show = false;

        if (shouldRedirectToProfile.value) {
            shouldRedirectToProfile.value = false;
            setTimeout(() => {
                router.visit("/profile", {
                    preserveScroll: false,
                    preserveState: false,
                });
            }, 300);
        }
    };

    const handleApply = async () => {
        if (availableSlots.value <= 0) {
            showNotification(
                "error",
                "Maaf",
                "Kuota untuk divisi ini sudah penuh.",
            );
            return;
        }

        if (!props.auth?.user) {
            showNotification(
                "error",
                "Login Diperlukan",
                "Silakan login terlebih dahulu untuk mendaftar magang.",
            );
            setTimeout(() => {
                router.visit("/login", {
                    method: "get",
                    preserveState: false,
                    data: { redirect: window.location.pathname },
                });
            }, 2000);
            return;
        }

        isLoading.value = true;

        try {
            const response = await fetch(
                `/applications/check/${props.division.id}`,
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                },
            );

            const result = await response.json();

            if (response.ok && result.canApply) {
                router.post(
                    "/profile/create-application",
                    { division_id: props.division.id },
                    {
                        preserveScroll: true,
                        preserveState: true,
                        onSuccess: () => {
                            isLoading.value = false;
                            shouldRedirectToProfile.value = true;
                            showNotification(
                                "success",
                                "Pendaftaran Berhasil!",
                                `Anda telah berhasil mendaftar untuk divisi ${props.division.name}. Silakan tunggu konfirmasi dari admin.`,
                            );
                        },
                        onError: (errors) => {
                            isLoading.value = false;
                            console.error("Application errors:", errors);
                            const errorMessage =
                                errors.division_id ||
                                errors.message ||
                                "Terjadi kesalahan saat mendaftar.";
                            showNotification(
                                "error",
                                "Gagal Mendaftar",
                                errorMessage,
                            );
                        },
                    },
                );
                return;
            }

            const missingItems = [];
            if (result.missingPersonalData?.length > 0) {
                missingItems.push(
                    `Data Pribadi: ${result.missingPersonalData.join(", ")}`,
                );
            }
            if (result.missingDocuments?.length > 0) {
                missingItems.push(
                    `Dokumen: ${result.missingDocuments.join(", ")}`,
                );
            }

            const missingMessage =
                missingItems.length > 0
                    ? `Harap lengkapi terlebih dahulu:\n\n${missingItems.join("\n\n")}`
                    : "Harap lengkapi profil Anda terlebih dahulu.";

            showNotification("error", "Data Belum Lengkap", missingMessage);
            setTimeout(() => {
                router.visit("/profile", {
                    method: "get",
                    preserveState: false,
                });
            }, 3000);
        } catch (error) {
            console.error("Application error:", error);
            showNotification(
                "error",
                "Error",
                "Terjadi kesalahan saat memproses lamaran Anda. Silakan coba lagi.",
            );
        } finally {
            isLoading.value = false;
        }
    };

    return {
        availableSlots,
        cleanTaskText,
        closeNotification,
        formatAvailableSlots,
        formatDate,
        formatQuota,
        formattedDuration,
        handleApply,
        hasBenefits,
        hasCriteria,
        hasJobDescription,
        hasRequirements,
        isLoading,
        normalizedBenefits,
        normalizedCriteria,
        normalizedJobDescription,
        normalizedRequirements,
        notification,
        redirectCountdown,
        shouldRedirectToProfile,
    };
}
