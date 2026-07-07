import { ref } from "vue";

export function useLocationSampler(options = {}) {
    const samplingStatus = ref("idle"); // 'idle' | 'requesting-permission' | 'sampling' | 'validating' | 'done' | 'error'
    const samplingProgress = ref(0);
    const gpsAccuracy = ref(null);
    const samplesCollected = ref(0);
    const samples = ref([]);
    const totalSamples = ref(options.sampleCount || 3);
    
    const maxAccuracy = ref(options.gpsAccuracyMax || 50);
    const maxStability = ref(options.stabilityMax || 0.0003);
    const allowedRadius = ref(options.allowedRadius || 500);
    const officeLat = ref(options.officeLocation?.latitude ?? -5.397140);
    const officeLon = ref(options.officeLocation?.longitude ?? 105.266792);

    const validationLayers = ref([]);
    const locationResult = ref(null);
    const errorMessage = ref(null);

    const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

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

    const calculateStdDev = (values) => {
        if (values.length <= 1) return 0;
        const mean = values.reduce((a, b) => a + b, 0) / values.length;
        const variance = values.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / (values.length - 1);
        return Math.sqrt(variance);
    };

    const calculateStability = (sampleList) => {
        if (sampleList.length <= 1) return 0;
        const lats = sampleList.map((s) => s.latitude);
        const lons = sampleList.map((s) => s.longitude);
        const stdDevLat = calculateStdDev(lats);
        const stdDevLon = calculateStdDev(lons);
        return Math.max(stdDevLat, stdDevLon);
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
        samplingProgress.value = 0;
        validationLayers.value = [];
        locationResult.value = null;

        // Layer 1: Request permission (via first sample)
        try {
            const firstPosition = await getSinglePosition();
            samples.value.push({
                latitude: firstPosition.coords.latitude,
                longitude: firstPosition.coords.longitude,
                accuracy: firstPosition.coords.accuracy,
            });
            samplesCollected.value = 1;
            samplingProgress.value = Math.round((1 / totalSamples.value) * 100);
            samplingStatus.value = "sampling";
        } catch (err) {
            samplingStatus.value = "error";
            errorMessage.value = "Izinkan akses lokasi untuk melakukan presensi.";
            return;
        }

        // Collect remaining samples
        for (let i = 1; i < totalSamples.value; i++) {
            await sleep(2000); // 2 seconds interval between samples
            try {
                const position = await getSinglePosition();
                samples.value.push({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                });
                samplesCollected.value = samples.value.length;
                samplingProgress.value = Math.round((samples.value.length / totalSamples.value) * 100);
            } catch (err) {
                console.warn("Failed to collect GPS sample:", err);
            }
        }

        if (samples.value.length === 0) {
            samplingStatus.value = "error";
            errorMessage.value = "Gagal memperoleh lokasi. Pastikan GPS aktif dan izinkan akses lokasi.";
            return;
        }

        samplingStatus.value = "validating";

        // Select best sample for accuracy reference
        const bestSample = samples.value.reduce((best, current) => {
            return current.accuracy < best.accuracy ? current : best;
        }, samples.value[0]);

        gpsAccuracy.value = bestSample.accuracy;

        const accuracyPassed = gpsAccuracy.value <= maxAccuracy.value;
        
        // Calculate stability std dev of collected samples
        const stabilityVal = calculateStability(samples.value);
        const stabilityPassed = samples.value.length < 2 || stabilityVal <= maxStability.value;

        // Average coordinates for finalized location submission
        const averageLat = samples.value.reduce((sum, s) => sum + s.latitude, 0) / samples.value.length;
        const averageLon = samples.value.reduce((sum, s) => sum + s.longitude, 0) / samples.value.length;
        const distance = calculateDistance(averageLat, averageLon, officeLat.value, officeLon.value);
        const radiusPassed = distance <= allowedRadius.value;

        validationLayers.value = [
            {
                name: "gps_accuracy",
                passed: accuracyPassed,
                message: accuracyPassed
                    ? `Akurasi GPS baik (±${Math.round(gpsAccuracy.value)}m)`
                    : "Akurasi GPS Anda terlalu rendah. Silakan aktifkan mode Lokasi Akurasi Tinggi dan berpindah ke area terbuka.",
            },
            {
                name: "coordinate_stability",
                passed: stabilityPassed,
                message: stabilityPassed
                    ? "Koordinat lokasi stabil"
                    : "Lokasi Anda belum stabil. Mohon tunggu beberapa saat hingga sistem memperoleh lokasi yang lebih akurat.",
            },
            {
                name: "radius_validation",
                passed: radiusPassed,
                message: radiusPassed
                    ? `Berada dalam radius presensi (±${Math.round(distance)}m)`
                    : "Anda berada di luar area presensi yang diizinkan.",
            },
        ];

        if (!accuracyPassed) {
            samplingStatus.value = "error";
            errorMessage.value = "Akurasi GPS Anda terlalu rendah. Silakan aktifkan mode Lokasi Akurasi Tinggi dan berpindah ke area terbuka.";
            return;
        }

        if (!stabilityPassed) {
            samplingStatus.value = "error";
            errorMessage.value = "Lokasi Anda belum stabil. Mohon tunggu beberapa saat hingga sistem memperoleh lokasi yang lebih akurat.";
            return;
        }

        if (!radiusPassed) {
            samplingStatus.value = "error";
            errorMessage.value = "Anda berada di luar area presensi yang diizinkan.";
            return;
        }

        samplingStatus.value = "done";
        locationResult.value = {
            latitude: averageLat,
            longitude: averageLon,
            accuracy: gpsAccuracy.value,
            coordinateStability: stabilityVal,
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
