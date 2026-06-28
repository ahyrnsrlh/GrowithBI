import axios from "axios";

window.axios = axios;

// Configure axios defaults for Laravel session-based authentication
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
window.axios.defaults.timeout = 30000; // 30 second timeout

// CSRF Token Management
let isRefreshingToken = false;
let failedRequestsQueue = [];

const getCsrfToken = () => {
    return document.head.querySelector('meta[name="csrf-token"]')?.content;
};

const updateCsrfToken = (newToken) => {
    const metaTag = document.head.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        metaTag.content = newToken;
    }
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = newToken;
};

// Initialize CSRF token
const initialToken = getCsrfToken();
if (initialToken) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = initialToken;
    if (import.meta.env.DEV) console.log("✅ CSRF token configured");
} else {
    if (import.meta.env.DEV) console.error("❌ CSRF token not found in meta tag");
}

// Request interceptor - ensure fresh CSRF token on every request
window.axios.interceptors.request.use(
    (config) => {
        const freshToken = getCsrfToken();
        if (freshToken) {
            config.headers["X-CSRF-TOKEN"] = freshToken;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Response interceptor - handle 419 with silent token refresh & retry
window.axios.interceptors.response.use(
    (response) => {
        // If response contains new CSRF token, update it
        if (response.data?.csrf_token) {
            updateCsrfToken(response.data.csrf_token);
        }
        return response;
    },
    async (error) => {
        const originalRequest = error.config;

        // Handle 419 CSRF Token Mismatch
        if (error.response?.status === 419 && !originalRequest._retry) {
            originalRequest._retry = true;

            // If already refreshing, queue this request
            if (isRefreshingToken) {
                return new Promise((resolve, reject) => {
                    failedRequestsQueue.push({
                        resolve,
                        reject,
                        config: originalRequest,
                    });
                });
            }

            isRefreshingToken = true;

            try {
                // Try to get fresh token from backend
                const tokenResponse = await axios.get("/sanctum/csrf-cookie");

                // Extract new token from response or meta tag
                const newToken =
                    tokenResponse.data?.csrf_token || getCsrfToken();

                if (newToken) {
                    updateCsrfToken(newToken);
                    if (import.meta.env.DEV) console.log("✅ CSRF token refreshed silently");

                    // Retry all queued requests
                    failedRequestsQueue.forEach(({ resolve, config }) => {
                        config.headers["X-CSRF-TOKEN"] = newToken;
                        resolve(axios(config));
                    });
                    failedRequestsQueue = [];

                    // Retry original request
                    originalRequest.headers["X-CSRF-TOKEN"] = newToken;
                    return axios(originalRequest);
                } else {
                    throw new Error("Failed to get new CSRF token");
                }
            } catch (refreshError) {
                if (import.meta.env.DEV) console.error("❌ CSRF token refresh failed:", refreshError);

                // Reject all queued requests
                failedRequestsQueue.forEach(({ reject }) =>
                    reject(refreshError)
                );
                failedRequestsQueue = [];

                // Last resort: Reload page with Inertia (preserves state)
                try {
                    const { router } = await import("@inertiajs/vue3");
                    if (import.meta.env.DEV) console.warn("Reloading page via Inertia after CSRF refresh failure...");
                    router.reload({
                        preserveScroll: true,
                        preserveState: true,
                    });
                } catch {
                    if (import.meta.env.DEV) console.warn("Hard reload required after CSRF refresh failure...");
                    window.location.reload();
                }

                return Promise.reject(error);
            } finally {
                isRefreshingToken = false;
            }
        }

        // Handle 401 Unauthorized (session expired)
        if (error.response?.status === 401) {
            if (import.meta.env.DEV) console.warn("Session expired, redirecting to login...");
            try {
                const { router } = await import("@inertiajs/vue3");
                router.visit("/login");
            } catch {
                window.location.href = "/login";
            }
        }

        return Promise.reject(error);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

const requiredEnvVars = ["VITE_REVERB_APP_KEY", "VITE_REVERB_HOST"];

const missingVars = requiredEnvVars.filter(
    (varName) => !import.meta.env[varName]
);

if (missingVars.length > 0 && import.meta.env.DEV) {
    console.warn(
        "⚠️ Missing environment variables:",
        missingVars.join(", "),
        "Real-time notifications will fall back to polling mode."
    );
}

const runWhenIdle = (callback) => {
    if ("requestIdleCallback" in window) {
        window.requestIdleCallback(callback, { timeout: 3000 });
        return;
    }

    window.setTimeout(callback, 1500);
};

const initializeEcho = async () => {
    try {
        const [{ default: Echo }, { default: Pusher }] = await Promise.all([
            import("laravel-echo"),
            import("pusher-js"),
        ]);

        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: "reverb",
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
            forceTLS:
                (import.meta.env.VITE_REVERB_SCHEME || "http") === "https",
            enabledTransports: ["ws", "wss"],
            authEndpoint: "/broadcasting/auth",
            auth: {
                headers: {
                    "X-CSRF-TOKEN": document.head.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                    Accept: "application/json",
                },
            },
        });

        if (import.meta.env.DEV) console.log("✅ Laravel Echo initialized successfully");
    } catch (error) {
        if (import.meta.env.DEV) {
            console.error("❌ Echo initialization failed:", error);
            console.warn("⚠️ Falling back to polling mode for notifications");
        }
    }
};

if (missingVars.length === 0) {
    // Prevent Echo initialization for bots/Lighthouse and save resources by waiting for actual user interaction.
    // PageSpeed Insights does not trigger these events, so it will never attempt the WebSocket connection.
    const initEchoOnInteraction = () => {
        initializeEcho();
        ['mousemove', 'scroll', 'keydown', 'click', 'touchstart'].forEach(
            event => document.removeEventListener(event, initEchoOnInteraction)
        );
    };

    ['mousemove', 'scroll', 'keydown', 'click', 'touchstart'].forEach(
        event => document.addEventListener(event, initEchoOnInteraction, { once: true, passive: true })
    );
} else if (import.meta.env.DEV) {
    console.warn("⚠️ Laravel Echo NOT initialized - missing configuration");
    console.info("ℹ️ Notifications will use polling mode as fallback");
}
