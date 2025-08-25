import axios from 'axios';
window.axios = axios;

// Configure axios defaults
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// CSRF protection setup
const setupCSRF = async () => {
    try {
        // Get CSRF cookie first
        await window.axios.get('/sanctum/csrf-cookie');
        
        // Get CSRF token from meta tag
        const token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            console.log('CSRF token configured successfully');
        } else {
            console.error('CSRF token not found in meta tag');
        }
    } catch (error) {
        console.error('Error setting up CSRF:', error);
    }
};

// Setup CSRF when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupCSRF);
} else {
    setupCSRF();
}
