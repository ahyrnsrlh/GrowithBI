import { ref, reactive, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { useProfilePageBoot } from "@/Composables/useProfilePageBoot";

export function useProfilePage(props) {
    const user = reactive({ ...props.user });
    const activeTab = ref("profile");
    const editMode = ref(false);
    const showNotification = ref(false);
    const notificationType = ref("success");
    const notificationMessage = ref("");
    const showCreateLogbookModal = ref(false);
    const showCreateReportModal = ref(false);

    const acceptedStatuses = ["accepted", "letter_ready", "diterima"];
    const hasAcceptedApplication = computed(() =>
        props.applications.some((app) => acceptedStatuses.includes(app.status)),
    );

    const documentProgress = computed(() => {
        const uploadedDocuments = [
            user.surat_pengantar_path,
            user.cv_path,
            user.motivation_letter_path,
            user.transkrip_path,
            user.ktp_path,
            user.npwp_path,
            user.buku_rekening_path,
            user.pas_foto_path,
        ].filter(Boolean).length;
        return Math.round((uploadedDocuments / 8) * 100);
    });

    const formatDate = (date) => {
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(date).toLocaleDateString("id-ID", options);
    };

    const profileForm = useForm({
        name: user.name,
        email: user.email,
        phone: user.phone,
        address: user.address,
        university: user.university,
        major: user.major,
        semester: user.semester,
        gpa: user.gpa,
        birth_date: user.birth_date,
        gender: user.gender,
    });

    const createLogbookForm = useForm({
        date: new Date().toISOString().split("T")[0],
        duration: 8,
        title: "",
        activities: "",
        learning_points: "",
        challenges: "",
        status: "submitted",
    });

    const reportForm = useForm({
        title: "",
        description: "",
        report_file: null,
    });

    const showToast = (type, message) => {
        notificationType.value = type;
        notificationMessage.value = message;
        showNotification.value = true;
        setTimeout(() => {
            showNotification.value = false;
        }, 5000);
    };

    const viewLogbookDetail = (logbookId) => {
        router.visit(route("profile.logbooks.show", logbookId));
    };

    const editLogbook = (logbookId) => {
        router.visit(route("profile.logbooks.show", logbookId), {
            data: { edit: true },
        });
    };

    const updateProfile = () => {
        profileForm.patch(route("profile.update"), {
            onSuccess: (page) => {
                editMode.value = false;
                Object.assign(user, page.props.user);
                profileForm.data = {
                    name: user.name,
                    email: user.email,
                    phone: user.phone,
                    address: user.address,
                    university: user.university,
                    major: user.major,
                    semester: user.semester,
                    gpa: user.gpa,
                    birth_date: user.birth_date,
                    gender: user.gender,
                };
                showToast("success", "Profil berhasil diperbarui!");
            },
            onError: (errors) => {
                console.error("Profile update errors:", errors);
                showToast("error", "Gagal memperbarui profil!");
            },
        });
    };

    const uploadPhoto = (event) => {
        const file = event.target.files[0];
        if (!file) {
            return;
        }
        const formData = new FormData();
        formData.append("photo", file);
        window.axios
            .post(route("profile.upload-photo"), formData, {
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then(() => {
                window.location.reload();
            })
            .catch(() => {
                showToast("error", "Gagal mengunggah foto!");
            });
    };

    const submitLogbook = () => {
        createLogbookForm.post(route("profile.logbooks.store"), {
            preserveScroll: true,
            onSuccess: () => {
                showCreateLogbookModal.value = false;
                createLogbookForm.reset();
                showToast("success", "Logbook berhasil ditambahkan!");
                setTimeout(() => {
                    router.reload({ only: ["logbooks", "logbookStats"] });
                }, 1000);
            },
            onError: (errors) => {
                console.error("Logbook submission errors:", errors);
                if (errors.error) {
                    showToast("error", errors.error);
                } else if (errors.date) {
                    showToast("error", errors.date);
                } else {
                    showToast(
                        "error",
                        "Gagal menambahkan logbook! Periksa semua field yang wajib diisi.",
                    );
                }
            },
        });
    };

    const submitReport = () => {
        reportForm.post(route("profile.reports.store"), {
            preserveScroll: true,
            onSuccess: () => {
                showCreateReportModal.value = false;
                reportForm.reset();
                showToast("success", "Laporan berhasil diupload!");
                setTimeout(() => {
                    router.reload({ only: ["reports", "reportStats"] });
                }, 1000);
            },
            onError: (errors) => {
                console.error("Report submission errors:", errors);
                if (errors.error) {
                    showToast("error", errors.error);
                } else if (errors.title) {
                    showToast("error", errors.title);
                } else if (errors.report_file) {
                    showToast("error", errors.report_file);
                } else {
                    showToast(
                        "error",
                        "Gagal upload laporan! Periksa semua field yang wajib diisi.",
                    );
                }
            },
        });
    };

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            reportForm.report_file = file;
        }
    };

    const uploadDocument = (type, file) => {
        const formData = new FormData();
        formData.append("document_type", type);
        formData.append("document", file);
        window.axios
            .post(route("profile.upload-document"), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                },
            })
            .then((response) => {
                if (response.data.user) {
                    Object.assign(user, response.data.user);
                }
                showToast(
                    "success",
                    response.data.message || "Dokumen berhasil diunggah!",
                );
            })
            .catch((error) => {
                const message =
                    error.response?.data?.message ||
                    "Gagal mengunggah dokumen!";
                showToast("error", message);
            });
    };

    const getStatusText = (status) => {
        const statusMap = {
            menunggu: "Pending",
            in_review: "Sedang Direview",
            interview_scheduled: "Tahap Wawancara",
            accepted: "Diterima",
            rejected: "Ditolak",
            expired: "Kedaluwarsa",
        };
        return statusMap[status] || status;
    };

    useProfilePageBoot({
        props,
        activeTab,
        editMode,
        showToast,
        getStatusText,
    });

    return {
        user,
        activeTab,
        editMode,
        showNotification,
        notificationType,
        notificationMessage,
        showCreateLogbookModal,
        showCreateReportModal,
        hasAcceptedApplication,
        documentProgress,
        profileForm,
        createLogbookForm,
        reportForm,
        formatDate,
        showToast,
        viewLogbookDetail,
        editLogbook,
        updateProfile,
        uploadPhoto,
        submitLogbook,
        submitReport,
        handleFileChange,
        uploadDocument,
    };
}
