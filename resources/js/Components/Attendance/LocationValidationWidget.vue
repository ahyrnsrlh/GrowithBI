<template>
    <div v-if="sampler.samplingStatus.value !== 'idle'" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl max-w-xl mx-auto mb-6 text-slate-100 overflow-hidden relative">
        <!-- Progress bar glow -->
        <div 
            class="absolute top-0 left-0 h-[3px] bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500 transition-all duration-300"
            :style="{ width: `${sampler.samplingProgress.value}%` }"
        />

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold tracking-wide uppercase text-slate-400 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Verifikasi Lokasi Multi-Layer
            </h3>
            <span 
                class="text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider text-center"
                :class="statusBadgeClass"
            >
                {{ statusLabel }}
            </span>
        </div>

        <!-- Sampling progress details -->
        <div v-if="sampler.samplingStatus.value === 'sampling'" class="mb-5 bg-slate-950/60 p-4 rounded-xl border border-slate-800/80">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs text-slate-400">Mengumpulkan sampel koordinat...</span>
                <span class="text-xs font-semibold text-blue-400">{{ sampler.samplesCollected.value }} / {{ sampler.totalSamples.value }}</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" :style="{ width: `${sampler.samplingProgress.value}%` }" />
            </div>
            <p class="text-[11px] text-slate-500 mt-2 italic">
                Tahan posisi perangkat Anda tetap diam selama beberapa detik...
            </p>
        </div>

        <!-- Layers checking layout -->
        <div class="space-y-3.5">
            <!-- Layer 1: Geolocation Permission -->
            <div class="flex items-start justify-between gap-3 text-xs p-3 rounded-lg bg-slate-950/20 border border-slate-800/30">
                <div class="flex gap-2.5">
                    <div class="mt-0.5" :class="layerStatusColor(permissionStatus)">
                        <i :class="layerStatusIcon(permissionStatus)"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-200">Izin Geolocation Browser</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ permissionMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 2: GPS Accuracy -->
            <div class="flex items-start justify-between gap-3 text-xs p-3 rounded-lg bg-slate-950/20 border border-slate-800/30">
                <div class="flex gap-2.5">
                    <div class="mt-0.5" :class="layerStatusColor(accuracyStatus)">
                        <i :class="layerStatusIcon(accuracyStatus)"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-200">Akurasi Sinyal GPS (Maks: {{ maxAccuracy }}m)</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ accuracyMessage }}</p>
                    </div>
                </div>
                <div v-if="sampler.gpsAccuracy.value !== null" class="flex-shrink-0">
                    <span 
                        class="text-[11px] px-2 py-0.5 rounded font-mono font-semibold"
                        :class="accuracyBadgeClass"
                    >
                        ±{{ Math.round(sampler.gpsAccuracy.value) }}m
                    </span>
                </div>
            </div>

            <!-- Layer 3: Coordinate Stability -->
            <div class="flex items-start justify-between gap-3 text-xs p-3 rounded-lg bg-slate-950/20 border border-slate-800/30">
                <div class="flex gap-2.5">
                    <div class="mt-0.5" :class="layerStatusColor(stabilityStatus)">
                        <i :class="layerStatusIcon(stabilityStatus)"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-200">Stabilitas Sinyal GPS</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ stabilityMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 4: Presence Radius -->
            <div class="flex items-start justify-between gap-3 text-xs p-3 rounded-lg bg-slate-950/20 border border-slate-800/30">
                <div class="flex gap-2.5">
                    <div class="mt-0.5" :class="layerStatusColor(radiusStatus)">
                        <i :class="layerStatusIcon(radiusStatus)"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-200">Validasi Radius Presensi (Maks: {{ allowedRadius }}m)</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ radiusMessage }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- General error notice -->
        <div v-if="sampler.errorMessage.value" class="mt-4 p-3 bg-red-950/40 border border-red-900/50 rounded-xl flex gap-2.5 text-xs text-red-300">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-semibold">Kesalahan Verifikasi</p>
                <p class="mt-0.5 text-red-400/90 leading-relaxed">{{ sampler.errorMessage.value }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    sampler: { type: Object, required: true },
    maxAccuracy: { type: Number, default: 50 },
    allowedRadius: { type: Number, default: 500 },
});

const statusLabel = computed(() => {
    switch (props.sampler.samplingStatus.value) {
        case "requesting-permission":
            return "Perizinan";
        case "sampling":
            return "Mengambil Sampel";
        case "validating":
            return "Memvalidasi";
        case "done":
            return "Selesai";
        case "error":
            return "Gagal";
        default:
            return "Standby";
    }
});

const statusBadgeClass = computed(() => {
    switch (props.sampler.samplingStatus.value) {
        case "requesting-permission":
        case "sampling":
            return "bg-blue-900/40 text-blue-400 border border-blue-800/40";
        case "validating":
            return "bg-amber-900/40 text-amber-400 border border-amber-800/40 animate-pulse";
        case "done":
            return "bg-emerald-900/40 text-emerald-400 border border-emerald-800/40";
        case "error":
            return "bg-red-900/40 text-red-400 border border-red-800/40";
        default:
            return "bg-slate-800 text-slate-400 border border-slate-700";
    }
});

// Layer state resolvers
const permissionStatus = computed(() => {
    if (props.sampler.samplingStatus.value === "requesting-permission") return "checking";
    if (props.sampler.samplingStatus.value === "error" && props.sampler.errorMessage.value?.includes("akses lokasi")) return "failed";
    if (props.sampler.samplingStatus.value === "idle") return "pending";
    return "passed";
});

const permissionMessage = computed(() => {
    if (permissionStatus.value === "checking") return "Menunggu izin akses GPS...";
    if (permissionStatus.value === "failed") return "Akses GPS ditolak oleh browser/pengguna.";
    if (permissionStatus.value === "passed") return "Izin GPS diberikan.";
    return "Menunggu memulai verifikasi...";
});

const accuracyStatus = computed(() => {
    if (permissionStatus.value !== "passed") return "pending";
    if (props.sampler.samplingStatus.value === "sampling") return "checking";
    if (props.sampler.gpsAccuracy.value === null) return "pending";
    return props.sampler.gpsAccuracy.value <= props.maxAccuracy ? "passed" : "failed";
});

const accuracyMessage = computed(() => {
    if (accuracyStatus.value === "pending") return "Menunggu sinyal GPS...";
    if (accuracyStatus.value === "checking") return "Membaca akurasi sinyal GPS...";
    if (accuracyStatus.value === "passed") return `Akurasi sinyal stabil (di bawah threshold ${props.maxAccuracy}m).`;
    return `Sinyal terlalu lemah (±${Math.round(props.sampler.gpsAccuracy.value)}m). Silakan pindah ke area terbuka.`;
});

const accuracyBadgeClass = computed(() => {
    if (accuracyStatus.value === "passed") return "bg-emerald-950 text-emerald-400 border border-emerald-900/40";
    if (accuracyStatus.value === "failed") return "bg-red-950 text-red-400 border border-red-900/40 animate-pulse";
    return "bg-slate-800 text-slate-400 border border-slate-700";
});

const stabilityStatus = computed(() => {
    if (accuracyStatus.value !== "passed") return "pending";
    if (props.sampler.samplingStatus.value === "validating") return "checking";
    if (props.sampler.samplingStatus.value === "done") {
        const check = props.sampler.validationLayers.value.find(l => l.name === 'coordinate_stability');
        return check?.passed ? "passed" : "failed";
    }
    if (props.sampler.samplingStatus.value === "error" && props.sampler.errorMessage.value?.includes("stabil")) return "failed";
    return "pending";
});

const stabilityMessage = computed(() => {
    if (stabilityStatus.value === "pending") return "Menunggu pengumpulan sampel selesai...";
    if (stabilityStatus.value === "checking") return "Memvalidasi fluktuasi koordinat GPS...";
    if (stabilityStatus.value === "passed") return "Koordinat lokasi konsisten dan bebas dari manipulasi drift.";
    return "Pergeseran koordinat terlalu tinggi. Pastikan perangkat Anda diam.";
});

const radiusStatus = computed(() => {
    if (stabilityStatus.value !== "passed") return "pending";
    if (props.sampler.samplingStatus.value === "validating") return "checking";
    if (props.sampler.samplingStatus.value === "done") {
        const check = props.sampler.validationLayers.value.find(l => l.name === 'radius_validation');
        return check?.passed ? "passed" : "failed";
    }
    if (props.sampler.samplingStatus.value === "error" && props.sampler.errorMessage.value?.includes("luar area")) return "failed";
    return "pending";
});

const radiusMessage = computed(() => {
    if (radiusStatus.value === "pending") return "Menunggu koordinat lokasi terverifikasi...";
    if (radiusStatus.value === "checking") return "Memverifikasi koordinat terhadap radius kantor...";
    if (radiusStatus.value === "passed") {
        const check = props.sampler.validationLayers.value.find(l => l.name === 'radius_validation');
        return check ? check.message : "Berada di dalam area presensi magang.";
    }
    return "Koordinat Anda berada di luar batas area presensi magang Bank Indonesia.";
});

// Helper for layer state classes
const layerStatusColor = (status) => {
    switch (status) {
        case "passed":
            return "text-emerald-400";
        case "failed":
            return "text-red-400";
        case "checking":
            return "text-blue-400";
        default:
            return "text-slate-600";
    }
};

const layerStatusIcon = (status) => {
    switch (status) {
        case "passed":
            return "fas fa-check-circle";
        case "failed":
            return "fas fa-times-circle";
        case "checking":
            return "fas fa-spinner animate-spin";
        default:
            return "fas fa-dot-circle";
    }
};
</script>
