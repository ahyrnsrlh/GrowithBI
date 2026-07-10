import { ref, computed } from "vue";
import SwalPlugin from "@/Plugins/sweetalert";
import { router } from "@inertiajs/vue3";

export function useAttendanceCapture(options = {}) {
    const showCamera = ref(false);
    const actionType = ref("");
    const isProcessing = ref(false);
    const photoBase64 = ref(null);
    const faceDescriptor = ref(null);
    const userLocation = ref(null);

    const notify = (type, message) => {
        if (typeof options.onNotify === "function") {
            options.onNotify(type, message);
            return;
        }

        SwalPlugin.toastError(message);
    };

    const cameraTitle = computed(() =>
        actionType.value === "check-in" ? "Foto Check-in" : "Foto Check-out",
    );

    const handleCheckIn = () => {
        actionType.value = "check-in";
        if (options.locationSampler) {
            options.locationSampler.reset();
        }
        showCamera.value = true;
    };

    const handleCheckOut = () => {
        actionType.value = "check-out";
        if (options.locationSampler) {
            options.locationSampler.reset();
        }
        showCamera.value = true;
    };

    const getLocation = async () => {
        if (!navigator.geolocation) {
            notify("error", "Geolocation tidak didukung");
            return;
        }

        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                });
            });

            userLocation.value = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy,
            };
        } catch {
            notify(
                "error",
                "Gagal mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi.",
            );
        }
    };

    const onFaceVerified = async (data) => {
        if (typeof data === "string") {
            photoBase64.value = data;
        } else {
            photoBase64.value = data.photo;
            faceDescriptor.value = data.faceDescriptor;
        }

        showCamera.value = false;

        if (options.locationSampler) {
            options.locationSampler.reset();
            await options.locationSampler.startSampling();
            if (options.locationSampler.samplingStatus.value === "done") {
                userLocation.value = options.locationSampler.locationResult.value;
                submit();
            } else if (options.locationSampler.samplingStatus.value === "error") {
                notify("error", options.locationSampler.errorMessage.value);
            }
        } else {
            await getLocation();
            submit();
        }
    };

    const onPhotoCaptured = (data) => {
        if (typeof data === "string") {
            photoBase64.value = data;
        } else {
            photoBase64.value = data.photo;
            faceDescriptor.value = data.faceDescriptor;
        }
        submit();
    };

    const resetCaptureState = () => {
        photoBase64.value = null;
        faceDescriptor.value = null;
        userLocation.value = null;
        actionType.value = "";
        showCamera.value = false;
        if (options.locationSampler) {
            options.locationSampler.reset();
        }
    };

    const submit = () => {
        if (!photoBase64.value || !userLocation.value) {
            notify("error", "Data tidak lengkap");
            return;
        }

        if (!faceDescriptor.value || faceDescriptor.value.length !== 128) {
            notify(
                "error",
                "Face descriptor tidak valid. Silakan ambil foto ulang.",
            );
            return;
        }

        const currentAction = actionType.value;

        const routeName =
            actionType.value === "check-in"
                ? "profile.attendance.check-in"
                : "profile.attendance.check-out";

        isProcessing.value = true;

        router.post(
            route(routeName),
            {
                photo: photoBase64.value,
                face_descriptor: faceDescriptor.value,
                latitude: userLocation.value.latitude,
                longitude: userLocation.value.longitude,
                gps_accuracy: userLocation.value.accuracy ?? null,
            },
            {
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    isProcessing.value = false;
                    resetCaptureState();

                    if (typeof options.onSuccess === "function") {
                        options.onSuccess(currentAction);
                    }
                },
                onError: (errors) => {
                    isProcessing.value = false;
                    const errorMessage =
                        errors.error ||
                        errors.photo ||
                        errors.face_descriptor ||
                        "Gagal menyimpan absensi";

                    notify("error", errorMessage);
                },
                onFinish: () => {
                    isProcessing.value = false;
                },
            },
        );
    };

    return {
        showCamera,
        actionType,
        isProcessing,
        cameraTitle,
        handleCheckIn,
        handleCheckOut,
        onPhotoCaptured,
        onFaceVerified,
    };
}
