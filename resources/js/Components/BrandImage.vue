<template>
    <img
        :src="src"
        :alt="alt"
        :width="width"
        :height="height"
        :loading="loading"
        decoding="async"
    />
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    variant: {
        type: String,
        default: "logoWeb",
        validator: (value) => ["logo", "logoWeb", "biWhite"].includes(value),
    },
    alt: {
        type: String,
        default: "GrowithBI",
    },
    loading: {
        type: String,
        default: "lazy",
    },
});

const assets = {
    logo: {
        src: "/logo.webp",
        width: 200,
        height: 200,
    },
    logoWeb: {
        src: "/logo_web.webp",
        width: 540,
        height: 131,
    },
    biWhite: {
        src: "/BI WHITE.webp",
        width: 945,
        height: 428,
    },
};

const selectedAsset = computed(() => assets[props.variant]);
const src = computed(() => selectedAsset.value.src);
const width = computed(() => selectedAsset.value.width);
const height = computed(() => selectedAsset.value.height);
</script>
