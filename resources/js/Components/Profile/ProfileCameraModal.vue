<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="handleClose">
            <!-- Backdrop -->
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-950 bg-opacity-80 backdrop-blur-sm transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-[#1e3a7e] to-[#2F4C9E] px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <DialogTitle as="h3" class="text-lg font-bold text-white leading-tight">
                                                Ambil Foto Profil
                                            </DialogTitle>
                                            <p class="text-blue-100 text-xs mt-0.5">Sekaligus Mendaftarkan Data Biometrik Anda</p>
                                        </div>
                                    </div>
                                    <button @click="handleClose" class="text-white hover:text-gray-200 transition">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="px-6 py-5">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 text-center">
                                        Pastikan wajah Anda berada di dalam lingkaran, tidak tertutup masker atau kacamata, dan pencahayaan cukup terang.
                                    </p>
                                </div>

                                <!-- Camera / Canvas Container -->
                                <div class="relative bg-black rounded-xl overflow-hidden mx-auto shadow-inner" style="aspect-ratio: 4/3; max-width: 400px;">
                                    <video
                                        ref="videoRef"
                                        autoplay
                                        playsinline
                                        muted
                                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 scale-x-[-1]"
                                        :class="{ 'opacity-0': !cameraReady }"
                                    ></video>
                                    
                                    <canvas
                                        ref="canvasRef"
                                        class="absolute inset-0 w-full h-full pointer-events-none transition-opacity duration-300 scale-x-[-1]"
                                        :class="{ 'opacity-0': !cameraReady || capturedImage }"
                                    ></canvas>

                                    <!-- Captured Image Preview -->
                                    <img v-if="capturedImage" :src="capturedImage" class="absolute inset-0 w-full h-full object-cover" />

                                    <!-- Loading State -->
                                    <div v-if="!cameraReady && !error" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80">
                                        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-3"></div>
                                        <p class="text-white text-sm font-medium">{{ loadingMessage }}</p>
                                    </div>

                                    <!-- Error State -->
                                    <div v-if="error" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/90 text-white p-4 text-center">
                                        <svg class="w-12 h-12 text-red-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="text-sm">{{ error }}</p>
                                        <button @click="startCamera" class="mt-4 px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium hover:bg-gray-100">
                                            Coba Lagi
                                        </button>
                                    </div>

                                    <!-- Face Detection Status Indicator -->
                                    <div
                                        v-if="cameraReady && !capturedImage && !error"
                                        class="absolute top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full text-white text-xs font-bold shadow-lg transition-colors duration-300 z-10"
                                        :class="faceStatusClass"
                                    >
                                        {{ faceStatusMessage }}
                                    </div>

                                    <!-- Guide Overlay (Oval) -->
                                    <div v-if="cameraReady && !capturedImage && !error" class="absolute inset-0 pointer-events-none flex items-center justify-center z-0">
                                        <div
                                            class="w-48 h-64 border-4 border-dashed rounded-[50%] transition-colors duration-300"
                                            :class="isFacePerfect ? 'border-emerald-400' : 'border-white/50'"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex gap-3">
                                    <button
                                        v-if="!capturedImage"
                                        @click="capturePhoto"
                                        :disabled="!isFacePerfect || isCapturing"
                                        class="w-full py-3.5 px-4 font-bold rounded-xl text-white transition-all duration-300 flex justify-center items-center gap-2"
                                        :class="isFacePerfect ? 'bg-[#2F4C9E] hover:bg-[#274089] shadow-lg shadow-blue-500/30' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    >
                                        <svg v-if="isCapturing" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ isCapturing ? 'Memproses...' : 'Ambil Foto' }}
                                    </button>

                                    <template v-else>
                                        <button
                                            @click="retakePhoto"
                                            :disabled="isSubmitting"
                                            class="flex-1 py-3 px-4 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition"
                                        >
                                            Ulangi
                                        </button>
                                        <button
                                            @click="confirmPhoto"
                                            :disabled="isSubmitting"
                                            class="flex-[2] py-3 px-4 bg-[#2F4C9E] hover:bg-[#274089] text-white font-bold rounded-xl transition shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2"
                                        >
                                            <svg v-if="isSubmitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ isSubmitting ? 'Menyimpan...' : 'Gunakan Foto Ini' }}
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, onBeforeUnmount, watch, computed } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import * as faceapi from 'face-api.js';

const props = defineProps({
    show: { type: Boolean, default: false },
    isSubmitting: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'capture']);

const videoRef = ref(null);
const canvasRef = ref(null);

const cameraReady = ref(false);
const error = ref('');
const loadingMessage = ref('Memuat Kamera...');
const isCapturing = ref(false);

const capturedImage = ref(null);
const capturedDescriptor = ref(null);

// Face Detection State
const facesCount = ref(0);
const isFaceCentered = ref(false);
const isLightingGood = ref(false);

let detectionInterval = null;
let stream = null;

const isFacePerfect = computed(() => {
    return facesCount.value === 1 && isFaceCentered.value && isLightingGood.value;
});

const faceStatusMessage = computed(() => {
    if (facesCount.value === 0) return 'Wajah Tidak Terdeteksi';
    if (facesCount.value > 1) return 'Terdeteksi Lebih Dari 1 Wajah';
    if (!isFaceCentered.value) return 'Posisikan Wajah Di Tengah';
    if (!isLightingGood.value) return 'Pencahayaan Kurang';
    return 'Wajah Siap Difoto!';
});

const faceStatusClass = computed(() => {
    if (isFacePerfect.value) return 'bg-emerald-500';
    return 'bg-amber-500';
});

// Load Models
let modelsLoaded = false;
const loadModels = async () => {
    if (modelsLoaded) return true;
    loadingMessage.value = 'Memuat AI Model...';
    try {
        const MODEL_URL = '/models';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        modelsLoaded = true;
        return true;
    } catch (err) {
        console.error("Gagal memuat model face-api", err);
        error.value = "Gagal memuat model pendeteksi wajah. Pastikan koneksi internet stabil.";
        return false;
    }
};

const startCamera = async () => {
    error.value = '';
    cameraReady.value = false;
    loadingMessage.value = 'Memuat Kamera...';

    const ok = await loadModels();
    if (!ok) return;

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            },
            audio: false
        });

        if (videoRef.value) {
            videoRef.value.srcObject = stream;
            videoRef.value.onloadedmetadata = () => {
                videoRef.value.play();
                cameraReady.value = true;
                startDetection();
            };
        }
    } catch (err) {
        console.error("Akses kamera ditolak", err);
        error.value = "Izin akses kamera ditolak. Harap izinkan akses kamera di browser Anda.";
    }
};

const stopCamera = () => {
    stopDetection();
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    if (videoRef.value) {
        videoRef.value.srcObject = null;
    }
    cameraReady.value = false;
};

const startDetection = () => {
    if (detectionInterval) clearInterval(detectionInterval);
    
    detectionInterval = setInterval(async () => {
        if (!videoRef.value || !cameraReady.value || capturedImage.value) return;

        try {
            const detections = await faceapi.detectAllFaces(
                videoRef.value, 
                new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 })
            ).withFaceLandmarks();

            facesCount.value = detections.length;

            if (detections.length === 1) {
                const detection = detections[0];
                const box = detection.detection.box;
                const videoWidth = videoRef.value.videoWidth;
                const videoHeight = videoRef.value.videoHeight;
                
                // Check if face is centered
                const faceCenterX = box.x + (box.width / 2);
                const faceCenterY = box.y + (box.height / 2);
                const distanceX = Math.abs((videoWidth / 2) - faceCenterX);
                const distanceY = Math.abs((videoHeight / 2) - faceCenterY);
                
                // Allow some tolerance for center
                isFaceCentered.value = distanceX < (videoWidth * 0.25) && distanceY < (videoHeight * 0.25);

                // Estimate lighting (heuristic: box score and size)
                isLightingGood.value = detection.detection.score > 0.6;
                
                drawDetections(detections);
            } else {
                isFaceCentered.value = false;
                isLightingGood.value = false;
                if (canvasRef.value) {
                    const ctx = canvasRef.value.getContext('2d');
                    ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
                }
            }
        } catch (err) {
            console.error("Deteksi wajah error", err);
        }
    }, 200);
};

const drawDetections = (detections) => {
    if (!canvasRef.value || !videoRef.value) return;
    const displaySize = { width: videoRef.value.videoWidth, height: videoRef.value.videoHeight };
    faceapi.matchDimensions(canvasRef.value, displaySize);
    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    
    const ctx = canvasRef.value.getContext('2d');
    ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
    
    const color = isFacePerfect.value ? '#10b981' : '#f59e0b';
    ctx.strokeStyle = color;
    ctx.lineWidth = 3;
    
    resizedDetections.forEach(detection => {
        const box = detection.detection.box;
        // Draw corners instead of full box for better UI
        const len = 20;
        ctx.beginPath();
        // Top left
        ctx.moveTo(box.x + len, box.y);
        ctx.lineTo(box.x, box.y);
        ctx.lineTo(box.x, box.y + len);
        // Top right
        ctx.moveTo(box.x + box.width - len, box.y);
        ctx.lineTo(box.x + box.width, box.y);
        ctx.lineTo(box.x + box.width, box.y + len);
        // Bottom left
        ctx.moveTo(box.x + len, box.y + box.height);
        ctx.lineTo(box.x, box.y + box.height);
        ctx.lineTo(box.x, box.y + box.height - len);
        // Bottom right
        ctx.moveTo(box.x + box.width - len, box.y + box.height);
        ctx.lineTo(box.x + box.width, box.y + box.height);
        ctx.lineTo(box.x + box.width, box.y + box.height - len);
        ctx.stroke();
    });
};

const stopDetection = () => {
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
};

const capturePhoto = async () => {
    if (!isFacePerfect.value || !videoRef.value) return;
    
    isCapturing.value = true;
    stopDetection();

    try {
        // Get face descriptor first to ensure we have it before stopping camera
        const detection = await faceapi.detectSingleFace(
            videoRef.value,
            new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks().withFaceDescriptor();

        if (!detection) {
            error.value = "Gagal memproses data biometrik. Silakan coba lagi.";
            isCapturing.value = false;
            startDetection();
            return;
        }

        capturedDescriptor.value = Array.from(detection.descriptor);

        // Create canvas to capture image
        const canvas = document.createElement('canvas');
        canvas.width = videoRef.value.videoWidth;
        canvas.height = videoRef.value.videoHeight;
        const ctx = canvas.getContext('2d');
        
        // Flip the image horizontally so it acts like a mirror
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(videoRef.value, 0, 0, canvas.width, canvas.height);
        
        capturedImage.value = canvas.toDataURL('image/jpeg', 0.9);
        
    } catch (err) {
        console.error("Error capturing photo:", err);
        error.value = "Gagal mengambil foto. Silakan coba lagi.";
        startDetection();
    } finally {
        isCapturing.value = false;
    }
};

const retakePhoto = () => {
    capturedImage.value = null;
    capturedDescriptor.value = null;
    error.value = '';
    startDetection();
};

const confirmPhoto = () => {
    emit('capture', {
        photo: capturedImage.value,
        descriptor: capturedDescriptor.value
    });
};

const handleClose = () => {
    if (props.isSubmitting) return;
    stopCamera();
    capturedImage.value = null;
    capturedDescriptor.value = null;
    error.value = '';
    emit('close');
};

watch(() => props.show, (val) => {
    if (val) {
        startCamera();
    } else {
        stopCamera();
    }
});

onBeforeUnmount(() => {
    stopCamera();
});
</script>
