<template>
    <div
        v-if="isLoading"
        class="fixed inset-0 z-[9999] flex items-center justify-center loading-screen"
        :class="{ 'fade-out': isFadingOut }"
    >
        <!-- Background dengan gradient yang sama seperti navbar -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900"
        ></div>

        <!-- Animated pattern background -->
        <div
            class="absolute inset-0 opacity-10"
            style="
                background-image: radial-gradient(
                    circle at 1px 1px,
                    rgba(255, 255, 255, 0.3) 1px,
                    transparent 0
                );
                background-size: 30px 30px;
                animation: movePattern 20s linear infinite;
            "
        ></div>

        <!-- Loading content -->
        <div
            class="relative z-10 flex items-center justify-center min-h-screen"
        >
            <!-- Logo container dengan animasi - Centered -->
            <div class="logo-section relative flex items-center justify-center">
                <!-- Pulse rings around logo - positioned relative to logo -->
                <div
                    class="pulse-rings absolute inset-0 flex items-center justify-center"
                >
                    <div class="pulse-ring ring-1"></div>
                    <div class="pulse-ring ring-2"></div>
                    <div class="pulse-ring ring-3"></div>
                </div>

                <!-- Logo wrapper -->
                <div class="logo-container relative z-10">
                    <div class="logo-wrapper flex items-center justify-center">
                        <img
                            src="/BI WHITE.png"
                            alt="Bank Indonesia Logo"
                            class="logo-image block mx-auto"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const isLoading = ref(true);
const isFadingOut = ref(false);

const props = defineProps({
    duration: {
        type: Number,
        default: 3000, // 3 seconds
    },
});

const emit = defineEmits(["finished"]);

onMounted(() => {
    // Pastikan semua asset ter-load dengan baik
    const logoImg = document.querySelector(".logo-image");

    if (logoImg && !logoImg.complete) {
        logoImg.onload = () => {
            startFinishSequence();
        };
    } else {
        startFinishSequence();
    }
});

const startFinishSequence = () => {
    // Start fade out animation before completely hiding
    setTimeout(() => {
        isFadingOut.value = true;

        // Hide loading screen after fade out animation
        setTimeout(() => {
            isLoading.value = false;
            emit("finished");
        }, 800); // Fade out duration
    }, props.duration);
};
</script>

<style scoped>
/* Loading screen fade out animation */
.loading-screen {
    transition: opacity 0.8s ease-out, visibility 0.8s ease-out;
}

.loading-screen.fade-out {
    opacity: 0;
    visibility: hidden;
}

/* Moving pattern background */
@keyframes movePattern {
    0% {
        transform: translate(0, 0);
    }
    100% {
        transform: translate(30px, 30px);
    }
}

/* Logo container animations */
.logo-section {
    width: 250px;
    height: 250px;
}

.logo-container {
    perspective: 1000px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.logo-wrapper {
    animation: logoEntrance 2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    transform-style: preserve-3d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-image {
    width: 180px;
    height: 180px;
    object-fit: contain;
    filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
    animation: logoFloat 3s ease-in-out infinite alternate;
    display: block;
}

/* Logo entrance animation */
@keyframes logoEntrance {
    0% {
        transform: scale(0) rotateY(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotateY(-90deg);
        opacity: 0.8;
    }
    100% {
        transform: scale(1) rotateY(0deg);
        opacity: 1;
    }
}

/* Logo floating animation */
@keyframes logoFloat {
    0% {
        transform: translateY(0px) scale(1);
    }
    100% {
        transform: translateY(-10px) scale(1.05);
    }
}

/* Text animations */
.text-content {
    animation: textSlideUp 1.5s ease-out 0.5s both;
}

.company-name {
    animation: fadeInUp 1s ease-out 1s both;
}

.tagline {
    animation: fadeInUp 1s ease-out 1.3s both;
}

@keyframes textSlideUp {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    0% {
        transform: translateY(20px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Loading dots animation */
.loading-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    animation: fadeIn 1s ease-out 2s both;
}

.dot {
    width: 8px;
    height: 8px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    animation: dotPulse 1.5s ease-in-out infinite;
}

.dot:nth-child(2) {
    animation-delay: 0.3s;
}

.dot:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes dotPulse {
    0%,
    100% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.5);
        opacity: 1;
    }
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Pulse rings around logo */
.pulse-rings {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: pulseRing 2s ease-out infinite;
}

.ring-1 {
    animation-delay: 0s;
}

.ring-2 {
    animation-delay: 0.7s;
}

.ring-3 {
    animation-delay: 1.4s;
}

@keyframes pulseRing {
    0% {
        width: 150px;
        height: 150px;
        opacity: 1;
    }
    100% {
        width: 400px;
        height: 400px;
        opacity: 0;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .logo-section {
        width: 200px;
        height: 200px;
    }

    .logo-image {
        width: 140px;
        height: 140px;
    }

    .company-name {
        font-size: 2rem;
    }

    .tagline {
        font-size: 1.125rem;
    }

    @keyframes pulseRing {
        0% {
            width: 120px;
            height: 120px;
            opacity: 1;
        }
        100% {
            width: 320px;
            height: 320px;
            opacity: 0;
        }
    }
}
</style>
