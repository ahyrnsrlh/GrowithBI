import axios from "axios";
window.axios = axios;

// Configure axios defaults for Laravel session-based authentication
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;

// Get CSRF token from meta tag and configure it
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
    console.log("CSRF token configured successfully");
} else {
    console.error("CSRF token not found in meta tag");
}

// Add request interceptor to ensure fresh CSRF token
window.axios.interceptors.request.use(
    (config) => {
        // Get fresh CSRF token from meta tag
        const freshToken = document.head.querySelector(
            'meta[name="csrf-token"]'
        );
        if (freshToken) {
            config.headers["X-CSRF-TOKEN"] = freshToken.content;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor to handle 419 (CSRF token mismatch)
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 419) {
            // CSRF token expired, reload page to get new token
            console.warn("CSRF token expired, reloading page...");
            window.location.reload();
        }
        return Promise.reject(error);
    }
);
