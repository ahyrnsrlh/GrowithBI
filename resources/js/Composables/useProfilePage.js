import { ref, reactive, computed, watch } from "vue";
import { useForm, router, usePage } from "@inertiajs/vue3";
import { useProfilePageBoot } from "@/Composables/useProfilePageBoot";

const normalizeDateValue = (value) => {
    if (!value) {
        return "";
    }
    if (typeof value === "string") {
        return value.split("T")[0];
    }
    if (value instanceof Date) {
        return value.toISOString().slice(0, 10);
    }
    return String(value);
};

export function useProfilePage(props) {
    const user = reactive({ ...props.user });
    const activeTab = ref("profile");
    const editMode = ref(false);
    const showNotification = ref(false);
    const notificationType = ref("success");
    const notificationMessage = ref("");
    const showCreateLogbookModal = ref(false);
    const showCreateReportModal = ref(false);

    // ── Face Enrollment ───────────────────────────────────────────────────────
    // Seeded from the dedicated Inertia prop (face_descriptor is hidden from user object).
    const page = usePage();
    const faceEnrolled = ref(props.face_enrolled ?? page.props.auth?.face_enrolled ?? false);
    watch(
        () => page.props.flash,
        (flash) => {
            if (flash?.success) {
                showToast("success", flash.success);
            }
            if (flash?.error) {
                showToast("error", flash.error);
            }
        },
        { deep: true, immediate: true }
    );

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
        birth_date: normalizeDateValue(user.birth_date),
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
        attachments: [],
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

    const showDetailLogbookModal = ref(false);
    const showEditLogbookModal = ref(false);
    const selectedLogbookId = ref(null);

    const viewLogbookDetail = (logbookId) => {
        selectedLogbookId.value = logbookId;
        showDetailLogbookModal.value = true;
    };

    const editLogbook = (logbookId) => {
        selectedLogbookId.value = logbookId;
        showEditLogbookModal.value = true;
    };

    const deleteLogbook = (logbookId) => {
        if (confirm("Apakah Anda yakin ingin menghapus entri logbook ini?")) {
            router.delete(route("peserta.logbooks.destroy", logbookId), {
                preserveScroll: true,
                onSuccess: () => {
                    showToast("success", "Logbook berhasil dihapus!");
                    setTimeout(() => {
                        router.reload({ only: ["logbooks", "logbookStats"] });
                    }, 1000);
                },
                onError: (errors) => {
                    console.error("Delete logbook error:", errors);
                    showToast("error", "Gagal menghapus logbook.");
                }
            });
        }
    };

    const handleEditSuccess = () => {
        showEditLogbookModal.value = false;
        showToast("success", "Logbook berhasil diperbarui!");
        setTimeout(() => {
            router.reload({ only: ["logbooks", "logbookStats"] });
        }, 1000);
    };

    const showDetailReportModal = ref(false);
    const showEditReportModal = ref(false);
    const selectedReportId = ref(null);

    const viewReportDetail = (reportId) => {
        selectedReportId.value = reportId;
        showDetailReportModal.value = true;
    };

    const editReport = (reportId) => {
        selectedReportId.value = reportId;
        showEditReportModal.value = true;
    };

    const deleteReport = (reportId) => {
        if (confirm("Apakah Anda yakin ingin menghapus laporan ini? File laporan juga akan dihapus dari server.")) {
            router.delete(route("peserta.reports.destroy", reportId), {
                preserveScroll: true,
                onSuccess: () => {
                    showToast("success", "Laporan berhasil dihapus!");
                    setTimeout(() => {
                        router.reload({ only: ["reports", "reportStats"] });
                    }, 1000);
                },
                onError: (errors) => {
                    console.error("Delete report errors:", errors);
                    showToast("error", "Gagal menghapus laporan!");
                }
            });
        }
    };

    const handleReportEditSuccess = () => {
        showEditReportModal.value = false;
        showToast("success", "Laporan berhasil diperbarui!");
        setTimeout(() => {
            router.reload({ only: ["reports", "reportStats"] });
        }, 1000);
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
                    birth_date: normalizeDateValue(user.birth_date),
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

    const uploadPhoto = async ({ photo, descriptor }, callback) => {
        try {
            const response = await window.axios.post(route("profile.upload-photo"), {
                photo: photo,
                face_descriptor: JSON.stringify(descriptor)
            }, {
                headers: {
                    Accept: "application/json",
                },
            });

            const updatedPhotoPath = response.data?.profile_photo_path;
            if (updatedPhotoPath) {
                user.profile_photo_path = updatedPhotoPath;
            }

            // Mark enrollment as complete — face_descriptor is hidden from user object,
            // so we track it reactively here instead.
            faceEnrolled.value = true;

            showToast(
                "success",
                response.data?.message || "Foto profil dan biometrik berhasil diperbarui!",
            );

            if (typeof callback === 'function') callback(true);
        } catch (error) {
            const errors = error.response?.data?.errors;
            const message =
                errors?.photo?.[0] ||
                errors?.face_descriptor?.[0] ||
                error.response?.data?.message ||
                "Gagal menyimpan data biometrik!";

            console.error("Profile photo upload error:", error);
            showToast("error", message);

            if (typeof callback === 'function') callback(false);
        }
    };

    const submitLogbook = () => {
        createLogbookForm.post(route("profile.logbooks.store"), {
            preserveScroll: true,
            forceFormData: true,
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
            if (file.type !== "application/pdf") {
                reportForm.setError("report_file", "File harus berformat PDF.");
                event.target.value = null;
                reportForm.report_file = null;
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                reportForm.setError("report_file", "Ukuran file tidak boleh melebihi 10MB.");
                event.target.value = null;
                reportForm.report_file = null;
                return;
            }
            reportForm.clearErrors("report_file");
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
        faceEnrolled,
        showNotification,
        notificationType,
        notificationMessage,
        showCreateLogbookModal,
        showCreateReportModal,
        showDetailLogbookModal,
        showEditLogbookModal,
        selectedLogbookId,
        handleEditSuccess,
        showDetailReportModal,
        showEditReportModal,
        selectedReportId,
        viewReportDetail,
        editReport,
        deleteReport,
        handleReportEditSuccess,
        hasAcceptedApplication,
        documentProgress,
        profileForm,
        createLogbookForm,
        reportForm,
        formatDate,
        showToast,
        viewLogbookDetail,
        editLogbook,
        deleteLogbook,
        updateProfile,
        uploadPhoto,
        submitLogbook,
        submitReport,
        handleFileChange,
        uploadDocument,
    };
}
