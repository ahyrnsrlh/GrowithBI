import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import AOS from 'aos';
import 'aos/dist/aos.css';
import AppWrapper from './Components/AppWrapper.vue';

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 50,
});

// Mark SPA navigation to skip loading screen
router.on('start', () => {
    sessionStorage.setItem('spa-navigation', 'true');
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ 
            render: () => h(AppWrapper, {}, {
                default: () => h(App, props)
            })
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
