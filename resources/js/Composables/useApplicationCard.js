import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";

export function useApplicationCard(application, emit) {
    const showModal = ref(false);
    const downloading = ref(false);

    let channelName = null;

    onMounted(() => {
        if (window.Echo && application.user_id) {
            channelName = `App.Models.User.${application.user_id}`;
            window.Echo.private(channelName).notification((notification) => {
                if (notification.application_id === application.id) {
                    router.reload({ only: ["applications"] });
                    emit("status-updated", {
                        applicationId: application.id,
                        newStatus: notification.status,
                    });
                }
            });
        }
    });

    onUnmounted(() => {
        if (channelName && window.Echo) {
            window.Echo.leave(channelName);
        }
    });

    const acceptedStatuses = ["accepted", "letter_ready", "diterima"];

    const isAccepted = (status) => acceptedStatuses.includes(status);

    const getStepNumber = (status) => {
        const steps = {
            menunggu: 1,
            in_review: 2,
            dalam_review: 2,
            interview_scheduled: 3,
            wawancara: 3,
            accepted: 4,
            rejected: 4,
            diterima: 4,
            ditolak: 4,
            letter_ready: 5,
        };
        return steps[status] || 1;
    };

    const isStepComplete = (step) => getStepNumber(application.status) > step;

    const isStepActive = (step) => getStepNumber(application.status) === step;

    const showLetterStep = (status) =>
        ["accepted", "letter_ready", "diterima"].includes(status);

    const getProgressWidthAdjusted = (status) => {
        const step = getStepNumber(status);
        const showLetter = showLetterStep(status);
        const totalSteps = showLetter ? 5 : 4;
        const percentage = ((step - 1) / (totalSteps - 1)) * 80;
        return `${percentage}%`;
    };

    const getProgressBarColor = (status) => {
        if (status === "rejected" || status === "ditolak") {
            return "bg-red-500";
        }
        if (status === "letter_ready") {
            return "bg-emerald-500";
        }
        if (isAccepted(status)) {
            return "bg-emerald-500";
        }
        return "bg-indigo-600";
    };

    const getAccentGradient = (status) => {
        const gradients = {
            menunggu: "bg-gradient-to-r from-amber-400 to-yellow-400",
            in_review: "bg-gradient-to-r from-blue-400 to-indigo-400",
            interview_scheduled:
                "bg-gradient-to-r from-purple-400 to-violet-400",
            accepted: "bg-gradient-to-r from-green-400 to-emerald-400",
            rejected: "bg-gradient-to-r from-red-400 to-rose-400",
            letter_ready: "bg-gradient-to-r from-emerald-400 to-teal-400",
            diterima: "bg-gradient-to-r from-green-400 to-emerald-400",
            ditolak: "bg-gradient-to-r from-red-400 to-rose-400",
        };
        return (
            gradients[status] || "bg-gradient-to-r from-gray-400 to-gray-300"
        );
    };

    const getStatusBadgeClass = (status) => {
        const classes = {
            menunggu: "bg-amber-50 text-amber-700 ring-1 ring-amber-600/20",
            in_review: "bg-blue-50 text-blue-700 ring-1 ring-blue-600/20",
            interview_scheduled:
                "bg-purple-50 text-purple-700 ring-1 ring-purple-600/20",
            accepted: "bg-green-50 text-green-700 ring-1 ring-green-600/20",
            rejected: "bg-red-50 text-red-700 ring-1 ring-red-600/20",
            letter_ready:
                "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20",
            diterima: "bg-green-50 text-green-700 ring-1 ring-green-600/20",
            ditolak: "bg-red-50 text-red-700 ring-1 ring-red-600/20",
        };
        return (
            classes[status] ||
            "bg-gray-50 text-gray-700 ring-1 ring-gray-600/20"
        );
    };

    const getStatusDotClass = (status) => {
        const classes = {
            menunggu: "bg-amber-500",
            in_review: "bg-blue-500",
            interview_scheduled: "bg-purple-500",
            accepted: "bg-green-500",
            rejected: "bg-red-500",
            letter_ready: "bg-emerald-500 animate-pulse",
            diterima: "bg-green-500",
            ditolak: "bg-red-500",
        };
        return classes[status] || "bg-gray-500";
    };

    const getStatusText = (status) => {
        const texts = {
            menunggu: "Menunggu Review",
            in_review: "Sedang Direview",
            interview_scheduled: "Wawancara",
            accepted: "Diterima",
            rejected: "Ditolak",
            letter_ready: "Surat Siap",
            expired: "Kedaluwarsa",
            diterima: "Diterima",
            ditolak: "Ditolak",
        };
        return texts[status] || status;
    };

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    };

    const formatShortDate = (dateString) => {
        if (!dateString) {
            return "-";
        }
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
        });
    };

    const viewDetails = () => {
        showModal.value = true;
    };

    const closeModal = () => {
        showModal.value = false;
    };

    const downloadOffer = async () => {
        downloading.value = true;
        try {
            const checkResponse = await fetch(
                route("acceptance-letter.check", application.id),
            );
            const checkResult = await checkResponse.json();

            if (!checkResult.success || !checkResult.has_letter) {
                alert(
                    "Surat penerimaan belum tersedia. Hubungi pembimbing Anda.",
                );
                return;
            }

            window.open(
                route("acceptance-letter.download", application.id),
                "_blank",
            );
        } catch (error) {
            console.error("Error downloading acceptance letter:", error);
            alert("Terjadi kesalahan saat mengunduh surat penerimaan.");
        } finally {
            downloading.value = false;
        }
    };

    return {
        showModal,
        downloading,
        isAccepted,
        getStepNumber,
        isStepComplete,
        isStepActive,
        showLetterStep,
        getProgressWidthAdjusted,
        getProgressBarColor,
        getAccentGradient,
        getStatusBadgeClass,
        getStatusDotClass,
        getStatusText,
        formatDate,
        formatShortDate,
        viewDetails,
        closeModal,
        downloadOffer,
    };
}
