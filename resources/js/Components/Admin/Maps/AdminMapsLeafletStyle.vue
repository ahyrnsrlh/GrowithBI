<template>
    <div class="hidden"></div>
</template>

<style scoped>
:deep(.leaflet-container) {
    font-family: "Inter", system-ui, sans-serif;
}

:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-tip) {
    box-shadow: none;
}

:deep(.custom-div-icon) {
    background: transparent;
    border: none;
}
</style>
