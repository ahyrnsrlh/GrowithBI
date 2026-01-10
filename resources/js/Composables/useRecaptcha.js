/**
 * reCAPTCHA v3 Composable for Vue 3
 *
 * Provides reactive reCAPTCHA functionality for authentication forms.
 * Used only on peserta authentication pages (login, forgot password, OTP).
 *
 * Features:
 * - Lazy loading of reCAPTCHA script
 * - Token generation with action context
 * - Automatic cleanup on component unmount
 * - Error handling with fallback behavior
 */

import { ref, onMounted, onUnmounted } from "vue";

const RECAPTCHA_SCRIPT_ID = "recaptcha-v3-script";

/**
 * Use reCAPTCHA v3 in a Vue component
 *
 * @param {string} siteKey - The reCAPTCHA site key
 * @param {boolean} enabled - Whether reCAPTCHA is enabled
 */
export function useRecaptcha(siteKey, enabled = true) {
    const isReady = ref(false);
    const isLoading = ref(false);
    const error = ref(null);

    /**
     * Load the reCAPTCHA script dynamically
     */
    const loadScript = () => {
        return new Promise((resolve, reject) => {
            // Check if script already exists
            if (document.getElementById(RECAPTCHA_SCRIPT_ID)) {
                if (window.grecaptcha && window.grecaptcha.ready) {
                    window.grecaptcha.ready(() => {
                        isReady.value = true;
                        resolve();
                    });
                } else {
                    // Script exists but not ready, wait for it
                    const checkReady = setInterval(() => {
                        if (window.grecaptcha && window.grecaptcha.ready) {
                            clearInterval(checkReady);
                            window.grecaptcha.ready(() => {
                                isReady.value = true;
                                resolve();
                            });
                        }
                    }, 100);

                    // Timeout after 10 seconds
                    setTimeout(() => {
                        clearInterval(checkReady);
                        reject(new Error("reCAPTCHA failed to load"));
                    }, 10000);
                }
                return;
            }

            // Create and load script
            const script = document.createElement("script");
            script.id = RECAPTCHA_SCRIPT_ID;
            script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
            script.async = true;
            script.defer = true;

            script.onload = () => {
                if (window.grecaptcha && window.grecaptcha.ready) {
                    window.grecaptcha.ready(() => {
                        isReady.value = true;
                        resolve();
                    });
                } else {
                    reject(new Error("reCAPTCHA object not found"));
                }
            };

            script.onerror = () => {
                reject(new Error("Failed to load reCAPTCHA script"));
            };

            document.head.appendChild(script);
        });
    };

    /**
     * Execute reCAPTCHA and get a token for the specified action
     *
     * @param {string} action - The action name (login, forgot_password, otp_verification)
     * @returns {Promise<string|null>} The reCAPTCHA token or null if disabled/failed
     */
    const executeRecaptcha = async (action) => {
        if (!enabled || !siteKey) {
            return null;
        }

        if (!isReady.value) {
            console.warn("reCAPTCHA not ready, attempting to load...");
            try {
                await loadScript();
            } catch (err) {
                error.value = err.message;
                console.error("Failed to load reCAPTCHA:", err);
                return null;
            }
        }

        try {
            const token = await window.grecaptcha.execute(siteKey, { action });
            error.value = null;
            return token;
        } catch (err) {
            error.value = err.message;
            console.error("reCAPTCHA execution failed:", err);
            return null;
        }
    };

    /**
     * Initialize reCAPTCHA on component mount
     */
    onMounted(async () => {
        console.log(
            "🔧 useRecaptcha mounted - enabled:",
            enabled,
            "siteKey:",
            siteKey
        );

        if (!enabled || !siteKey) {
            console.warn("⚠️ reCAPTCHA disabled or no site key");
            return;
        }

        isLoading.value = true;
        try {
            await loadScript();
            console.log("✅ reCAPTCHA script loaded successfully");
        } catch (err) {
            error.value = err.message;
            console.error("❌ Failed to initialize reCAPTCHA:", err);
        } finally {
            isLoading.value = false;
        }
    });

    /**
     * Cleanup on component unmount
     * Note: We don't remove the script as it might be used by other components
     */
    onUnmounted(() => {
        // Clean up if needed in the future
    });

    return {
        isReady,
        isLoading,
        error,
        executeRecaptcha,
    };
}

export default useRecaptcha;
