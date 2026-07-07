import { ref } from "vue";

export function useLocationSampler(options = {}) {
    const samplingStatus = ref("idle"); // 'idle' | 'requesting-permission' | 'locating' | 'done' | 'error'
    const samplingProgress = ref(0);
    const gpsAccuracy = ref(null);
    const samplesCollected = ref(0);
    const samples = ref([]);
    const totalSamples = ref(1);
    
    const allowedRadius = ref(options.allowedRadius || 500);
    const officeLat = ref(options.officeLocation?.latitude ?? -5.4194538);
    const officeLon = ref(options.officeLocation?.longitude ?? 105.2604370);

    const validationLayers = ref([]);
    const locationResult = ref(null);
    const errorMessage = ref(null);

    const getSinglePosition = () => {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error("Geolocation not supported"));
                return;
            }
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });
        });
    };

    const calculateDistance = (lat1, lon1, lat2, lon2) => {
        const R = 6371000; // meters
        const dLat = ((lat2 - lat1) * Math.PI) / 180;
        const dLon = ((lon2 - lon1) * Math.PI) / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    };

    const startSampling = async () => {
        samplingStatus.value = "requesting-permission";
        errorMessage.value = null;
        samples.value = [];
        samplesCollected.value = 0;
        samplingProgress.value = 25;
        validationLayers.value = [];
        locationResult.value = null;

        let position;
        try {
            position = await getSinglePosition();
            samplingProgress.value = 75;
            samplingStatus.value = "locating";
        } catch (err) {
            samplingStatus.value = "error";
            errorMessage.value = "Izinkan akses lokasi untuk melakukan presensi.";
            return;
        }

        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        gpsAccuracy.value = position.coords.accuracy;
        samplesCollected.value = 1;
        samples.value = [{ latitude: lat, longitude: lon, accuracy: gpsAccuracy.value }];

        const distance = calculateDistance(lat, lon, officeLat.value, officeLon.value);
        const radiusPassed = distance <= allowedRadius.value;

        validationLayers.value = [
            {
                name: "radius_validation",
                passed: radiusPassed,
                message: radiusPassed
                    ? `Berada dalam radius presensi (±${Math.round(distance)}m)`
                    : "Anda berada di luar area presensi yang diizinkan.",
            },
        ];

        // Add warning layer for poor accuracy but do NOT block
        if (gpsAccuracy.value > 100) {
            validationLayers.value.push({
                name: "gps_accuracy",
                passed: true,
                message: `Sinyal GPS kurang akurat (±${Math.round(gpsAccuracy.value)}m)`,
            });
        }

        if (!radiusPassed) {
            samplingStatus.value = "error";
            errorMessage.value = "Anda berada di luar area presensi yang diizinkan.";
            return;
        }

        samplingProgress.value = 100;
        samplingStatus.value = "done";
        locationResult.value = {
            latitude: lat,
            longitude: lon,
            accuracy: gpsAccuracy.value,
            coordinateStability: null,
            samples: samples.value,
        };
    };

    const reset = () => {
        samplingStatus.value = "idle";
        samplingProgress.value = 0;
        gpsAccuracy.value = null;
        samplesCollected.value = 0;
        samples.value = [];
        validationLayers.value = [];
        locationResult.value = null;
        errorMessage.value = null;
    };

    return {
        samplingStatus,
        samplingProgress,
        gpsAccuracy,
        samplesCollected,
        totalSamples,
        validationLayers,
        locationResult,
        errorMessage,
        startSampling,
        reset,
    };
}
