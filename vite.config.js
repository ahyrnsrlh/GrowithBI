import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"],
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
                    // Vue runtime — stable chunk for long-term caching
                    if (
                        id.includes("node_modules/vue") ||
                        id.includes("node_modules/@vue") ||
                        id.includes("node_modules/@inertiajs")
                    ) {
                        return "vendor-vue";
                    }
                    // Chart.js — loaded only when a chart component mounts (lazy)
                    if (id.includes("node_modules/chart.js")) {
                        return "vendor-chartjs";
                    }
                    // face-api — very large (6MB), loaded only on camera modal (lazy)
                    if (id.includes("node_modules/face-api")) {
                        return "vendor-faceapi";
                    }
                    // Real-time: pusher + echo — idle-loaded after page interactive
                    if (
                        id.includes("node_modules/pusher-js") ||
                        id.includes("node_modules/laravel-echo")
                    ) {
                        return "vendor-realtime";
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

