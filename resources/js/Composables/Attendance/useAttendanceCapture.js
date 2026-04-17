import { ref, computed } from "vue";
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

        alert(message);
    };

    const cameraTitle = computed(() =>
        actionType.value === "check-in" ? "Foto Check-in" : "Foto Check-out",
    );

    const handleCheckIn = async () => {
        actionType.value = "check-in";
        await getLocation();
    };

    const handleCheckOut = async () => {
        actionType.value = "check-out";
        await getLocation();
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
            };

            showCamera.value = true;
        } catch {
            notify(
                "error",
                "Gagal mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi.",
            );
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
    };
}
