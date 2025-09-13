<template>
    <div>
        <!-- Loading Screen - muncul saat refresh/reload -->
        <LoadingScreen 
            v-if="showLoadingScreen" 
            @finished="onLoadingFinished" 
            :duration="2500"
        />
        
        <!-- Main App Content -->
        <div v-show="!isLoading" class="app-content">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import LoadingScreen from './LoadingScreen.vue';

const isLoading = ref(true);
const showLoadingScreen = ref(true);

onMounted(() => {
    // Simple approach: Always show loading screen unless it's marked as SPA navigation
    const isSPANavigation = sessionStorage.getItem('spa-navigation');
    
    if (isSPANavigation) {
        // This was SPA navigation, skip loading
        isLoading.value = false;
        showLoadingScreen.value = false;
        // Clear the flag
        sessionStorage.removeItem('spa-navigation');
    } else {
        // This is a refresh/reload/new tab, show loading
        showLoadingScreen.value = true;
    }
});

const onLoadingFinished = () => {
    isLoading.value = false;
    showLoadingScreen.value = false;
};
</script>

<style scoped>
.app-content {
    min-height: 100vh;
}
</style>