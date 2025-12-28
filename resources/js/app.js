import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import AOS from "aos";
import "aos/dist/aos.css";
import AppWrapper from "./Components/AppWrapper.vue";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Initialize AOS
AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    offset: 50,
});

// Sync CSRF token from Inertia props to meta tag and axios
const syncCsrfToken = (token) => {
    if (!token) return;

    const metaTag = document.head.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        metaTag.content = token;
    }

    if (window.axios) {
        window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
    }
};

// Monitor Inertia page visits to sync CSRF token
router.on("navigate", (event) => {
    sessionStorage.setItem("spa-navigation", "true");

    // Sync CSRF token from page props if available
    if (event.detail?.page?.props?.csrf_token) {
        syncCsrfToken(event.detail.page.props.csrf_token);
    }
});

// Warn about session expiry (480min - 10min warning)
let sessionWarningShown = false;
const SESSION_LIFETIME_MS = 480 * 60 * 1000; // 480 minutes
const WARNING_BEFORE_MS = 10 * 60 * 1000; // 10 minutes warning

setTimeout(() => {
    if (!sessionWarningShown) {
        sessionWarningShown = true;
        console.warn(
            "⏰ Your session will expire in 10 minutes. Please save your work."
        );

        // Optional: Show toast notification to user
        // You can implement a toast component here
    }
}, SESSION_LIFETIME_MS - WARNING_BEFORE_MS);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () =>
                h(
                    AppWrapper,
                    {},
                    {
                        default: () => h(App, props),
                    }
                ),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
