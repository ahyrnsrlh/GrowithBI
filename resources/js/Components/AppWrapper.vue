<template>
    <div>
        <!-- Loading Screen - disabled on landing so the LCP hero can paint immediately -->
        <LoadingScreen
            v-if="showLoadingScreen"
            @finished="onLoadingFinished"
            :duration="2500"
        />

        <!-- Main App Content: min-h-screen via Tailwind — eliminates the scoped CSS
             file that Vite was injecting as a render-blocking <link> stylesheet. -->
        <div class="min-h-screen">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { defineAsyncComponent, ref, onMounted } from "vue";

const LoadingScreen = defineAsyncComponent(() => import("./LoadingScreen.vue"));

const showLoadingScreen = ref(false);

const shouldShowBlockingLoader = () => {
    const isSPANavigation = sessionStorage.getItem("spa-navigation");
    const isLandingPage = window.location.pathname === "/";

    if (isSPANavigation) {
        sessionStorage.removeItem("spa-navigation");
        return false;
    }

    return !isLandingPage;
};

onMounted(() => {
    showLoadingScreen.value = shouldShowBlockingLoader();
});

const onLoadingFinished = () => {
    showLoadingScreen.value = false;
};
</script>
