import Swal from 'sweetalert2';

// Set default configuration for all SweetAlert2 popups
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

/**
 * Show a success toast notification
 * @param {string} title - The success message
 */
export const toastSuccess = (title) => {
    Toast.fire({
        icon: 'success',
        title: title,
        background: '#ecfdf5', // emerald-50
        color: '#065f46',      // emerald-800
        iconColor: '#10b981',  // emerald-500
        customClass: {
            timerProgressBar: 'bg-emerald-500'
        }
    });
};

/**
 * Show an error toast notification
 * @param {string} title - The error message
 */
export const toastError = (title) => {
    Toast.fire({
        icon: 'error',
        title: title,
        background: '#fef2f2', // red-50
        color: '#991b1b',      // red-800
        iconColor: '#ef4444',  // red-500
        customClass: {
            timerProgressBar: 'bg-red-500'
        }
    });
};

/**
 * Show a warning toast notification
 * @param {string} title - The warning message
 */
export const toastWarning = (title) => {
    Toast.fire({
        icon: 'warning',
        title: title,
        background: '#fffbeb', // amber-50
        color: '#92400e',      // amber-800
        iconColor: '#f59e0b',  // amber-500
        customClass: {
            timerProgressBar: 'bg-amber-500'
        }
    });
};

/**
 * Show a confirmation dialog (standard)
 * @param {string} title - Dialog title
 * @param {string} text - Dialog description
 * @param {string} confirmButtonText - Confirm button text
 * @param {string} icon - Dialog icon (warning, info, question)
 * @returns {Promise} - Resolves to the SweetAlert2 result object
 */
export const confirmAction = (title, text, confirmButtonText = 'Ya, Lanjutkan', icon = 'warning') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#2563eb', // blue-600
        cancelButtonColor: '#6b7280',  // gray-500
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30',
            cancelButton: 'px-5 py-2.5 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 ml-3',
            popup: 'rounded-2xl'
        },
        buttonsStyling: false
    });
};

/**
 * Show a destructive confirmation dialog (e.g., Delete)
 * @param {string} title - Dialog title
 * @param {string} text - Dialog description
 * @param {string} confirmButtonText - Confirm button text
 * @returns {Promise} - Resolves to the SweetAlert2 result object
 */
export const confirmDestructive = (title, text, confirmButtonText = 'Ya, Hapus') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626', // red-600
        cancelButtonColor: '#6b7280',  // gray-500
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30',
            cancelButton: 'px-5 py-2.5 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 ml-3',
            popup: 'rounded-2xl'
        },
        buttonsStyling: false
    });
};

/**
 * Show a loading overlay
 * @param {string} title - Loading message
 * @param {string} text - Optional additional text
 */
export const showLoading = (title = 'Memproses...', text = 'Harap tunggu sebentar') => {
    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        customClass: {
            popup: 'rounded-2xl pb-8 pt-6'
        },
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

/**
 * Close the current loading overlay or active Swal popup
 */
export const closeLoading = () => {
    Swal.close();
};

export default {
    toastSuccess,
    toastError,
    toastWarning,
    confirmAction,
    confirmDestructive,
    showLoading,
    closeLoading
};
