import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        target: "esnext",
        sourcemap: false,
        manifest: true,
        cssCodeSplit: true,
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                entryFileNames: "js/[name]-[hash].js",
                chunkFileNames: "js/[name]-[hash].js",
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith(".css")) {
                        return "css/[name]-[hash][extname]";
                    }
                    return "assets/[name]-[hash][extname]";
                },
                manualChunks(id) {
                    // Chart.js — loaded only when a chart component mounts
                    if (id.includes("node_modules/chart.js")) {
                        return "vendor-chartjs";
                    }
                    // Leaflet maps — loaded only when maps page is visited
                    if (id.includes("node_modules/leaflet")) {
                        return "vendor-leaflet";
                    }
                    // face-api — very large, loaded only on camera modal
                    if (id.includes("node_modules/face-api")) {
                        return "vendor-faceapi";
                    }
                    // Real-time: pusher + echo
                    if (
                        id.includes("node_modules/pusher-js") ||
                        id.includes("node_modules/laravel-echo")
                    ) {
                        return "vendor-realtime";
                    }
                    // Headless UI / Heroicons — UI components loaded on demand
                    if (
                        id.includes("node_modules/@headlessui") ||
                        id.includes("node_modules/@heroicons")
                    ) {
                        return "vendor-ui";
                    }
                },
            },
        },
    },
    define: {
        __VUE_PROD_DEVTOOLS__: false,
    },
    server: {
        port: 5173,
        hmr: {
            host: "localhost",
        },
    },
});

