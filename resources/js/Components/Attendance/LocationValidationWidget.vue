<template>
    <div 
        v-if="sampler.samplingStatus.value !== 'idle'" 
        class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 max-w-xl mx-auto mb-6 overflow-hidden relative transition-all duration-300"
    >
        <!-- Horizontal status bar at the top -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                </span>
                Proses Presensi
            </h3>
            <span 
                class="text-xs px-3 py-1 rounded-full font-semibold uppercase tracking-wider text-center"
                :class="statusBadgeClass"
            >
                {{ statusLabel }}
            </span>
        </div>

        <!-- Vertical Stepper -->
        <div class="relative pl-8 space-y-6">
            <!-- Connecting Line -->
            <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gray-200 z-0"></div>

            <!-- Step 1: Face Verification -->
            <div class="relative flex items-start">
                <!-- Dot Indicator -->
                <div class="absolute left-[-28px] top-0.5 z-10">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 border-2 border-emerald-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Verifikasi Wajah</h4>
                    <p class="text-xs text-emerald-600 font-medium">✓ Wajah terverifikasi berhasil</p>
                </div>
            </div>

            <!-- Step 2: GPS Location -->
            <div class="relative flex items-start">
                <!-- Dot Indicator -->
                <div class="absolute left-[-28px] top-0.5 z-10">
                    <!-- Current -->
                    <span v-if="gpsStepStatus === 'current'" class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600 border-2 border-blue-500 animate-pulse">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                    </span>
                    <!-- Completed -->
                    <span v-else-if="gpsStepStatus === 'completed'" class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 border-2 border-emerald-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <!-- Failed -->
                    <span v-else-if="gpsStepStatus === 'failed'" class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600 border-2 border-red-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                    <!-- Pending -->
                    <span v-else class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-gray-400 border-2 border-gray-200">
                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-bold" :class="gpsStepStatus !== 'pending' ? 'text-gray-800' : 'text-gray-400'">Membaca Lokasi GPS</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ gpsStepDescription }}</p>
                </div>
            </div>

            <!-- Step 3: Radius Verification -->
            <div class="relative flex items-start">
                <!-- Dot Indicator -->
                <div class="absolute left-[-28px] top-0.5 z-10">
                    <!-- Current -->
                    <span v-if="radiusStepStatus === 'current'" class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600 border-2 border-blue-500 animate-pulse">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                    </span>
                    <!-- Completed -->
                    <span v-else-if="radiusStepStatus === 'completed'" class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 border-2 border-emerald-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <!-- Failed -->
                    <span v-else-if="radiusStepStatus === 'failed'" class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600 border-2 border-red-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                    <!-- Pending -->
                    <span v-else class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-gray-400 border-2 border-gray-200">
                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-bold" :class="radiusStepStatus !== 'pending' ? 'text-gray-800' : 'text-gray-400'">Validasi Radius Presensi</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ radiusStepDescription }}</p>
                </div>
            </div>

            <!-- Step 4: Submission -->
            <div class="relative flex items-start">
                <!-- Dot Indicator -->
                <div class="absolute left-[-28px] top-0.5 z-10">
                    <!-- Current -->
                    <span v-if="submitStepStatus === 'current'" class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600 border-2 border-blue-500 animate-pulse">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                    </span>
                    <!-- Completed -->
                    <span v-else-if="submitStepStatus === 'completed'" class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 border-2 border-emerald-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <!-- Pending -->
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-gray-400 border-2 border-gray-200" v-else>
                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-bold" :class="submitStepStatus !== 'pending' ? 'text-gray-800' : 'text-gray-400'">Menyimpan Presensi</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ submitStepDescription }}</p>
                </div>
            </div>
        </div>

        <!-- Error display -->
        <div v-if="sampler.errorMessage.value" class="mt-6 p-4 bg-red-50 border border-red-100 rounded-xl flex gap-3 text-xs text-red-700">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-bold">Presensi Gagal</p>
                <p class="mt-0.5 text-red-600/90 leading-relaxed font-medium">{{ sampler.errorMessage.value }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    sampler: { type: Object, required: true },
    isProcessing: { type: Boolean, default: false },
    allowedRadius: { type: Number, default: 500 },
});

const statusLabel = computed(() => {
    if (props.sampler.errorMessage.value) return "Gagal";
    if (props.isProcessing) return "Menyimpan";
    
    switch (props.sampler.samplingStatus.value) {
        case "requesting-permission":
            return "Perizinan GPS";
        case "locating":
            return "Mencari Lokasi";
        case "done":
            return "Validasi Berhasil";
        default:
            return "Memproses";
    }
});

const statusBadgeClass = computed(() => {
    if (props.sampler.errorMessage.value) {
        return "bg-red-50 text-red-600 border border-red-100";
    }
    if (props.isProcessing) {
        return "bg-blue-50 text-blue-600 border border-blue-100 animate-pulse";
    }
    
    switch (props.sampler.samplingStatus.value) {
        case "requesting-permission":
        case "locating":
            return "bg-blue-50 text-blue-600 border border-blue-100";
        case "done":
            return "bg-emerald-50 text-emerald-600 border border-emerald-100";
        default:
            return "bg-gray-50 text-gray-500 border border-gray-100";
    }
});

// Step statuses: 'pending' | 'current' | 'completed' | 'failed'
const gpsStepStatus = computed(() => {
    const status = props.sampler.samplingStatus.value;
    const errorMsg = props.sampler.errorMessage.value;

    if (errorMsg && errorMsg.includes("akses lokasi")) return "failed";
    if (status === "requesting-permission" || status === "locating") return "current";
    if (status === "done") return "completed";
    return "pending";
});

const gpsStepDescription = computed(() => {
    const status = props.sampler.samplingStatus.value;
    const errorMsg = props.sampler.errorMessage.value;

    if (errorMsg && errorMsg.includes("akses lokasi")) return "Izin GPS ditolak. Silakan berikan izin lokasi pada browser Anda.";
    if (status === "requesting-permission") return "Meminta izin akses GPS perangkat...";
    if (status === "locating") return "Membaca posisi koordinat...";
    if (status === "done") {
        const accuracyVal = props.sampler.gpsAccuracy.value;
        const accuracyStr = accuracyVal !== null ? ` (Akurasi: ±${Math.round(accuracyVal)}m)` : "";
        return "Lokasi berhasil didapatkan" + accuracyStr;
    }
    return "Menunggu memulai pencarian lokasi...";
});

const radiusStepStatus = computed(() => {
    const status = props.sampler.samplingStatus.value;
    const errorMsg = props.sampler.errorMessage.value;

    if (errorMsg && (errorMsg.includes("radius") || errorMsg.includes("luar area"))) return "failed";
    if (status === "done") return "completed";
    if (gpsStepStatus.value === "completed" && status !== "done" && !errorMsg) return "current";
    return "pending";
});

const radiusStepDescription = computed(() => {
    const status = props.sampler.samplingStatus.value;
    const errorMsg = props.sampler.errorMessage.value;

    if (errorMsg && (errorMsg.includes("radius") || errorMsg.includes("luar area"))) {
        return "Gagal: Anda berada di luar radius kantor yang diizinkan.";
    }
    if (status === "done") {
        const distanceVal = props.sampler.validationLayers.value.find(l => l.name === 'radius_validation')?.message;
        return distanceVal || `Berada di dalam radius presensi (maks ${props.allowedRadius}m).`;
    }
    if (radiusStepStatus.value === "current") return "Memverifikasi koordinat terhadap area kantor...";
    return "Menunggu koordinat lokasi terverifikasi...";
});

const submitStepStatus = computed(() => {
    if (props.isProcessing) return "current";
    if (props.sampler.samplingStatus.value === "done" && !props.isProcessing) {
        // Since the page updates or reloads, if it is done and not processing, it is completed or ready
        return "completed";
    }
    return "pending";
});

const submitStepDescription = computed(() => {
    if (props.isProcessing) return "Mengirimkan data presensi aman ke server...";
    if (submitStepStatus.value === "completed") return "Presensi berhasil tercatat di sistem.";
    return "Menunggu verifikasi lokasi selesai...";
});
</script>
