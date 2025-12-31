import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Global response interceptor for error handling
window.axios.interceptors.response.use(
    response => response,
    error => {
        // Handle session expiration - but only for axios requests, not navigation
        if (error.response?.status === 419 && error.config) {
            console.warn('CSRF token mismatch detected');
            // Only reload if this was an actual AJAX request that failed
            if (error.config.headers['X-Requested-With'] === 'XMLHttpRequest') {
                alert('Your session has expired. Please refresh the page.');
                window.location.reload();
            }
            return Promise.reject(error);
        }

        // Handle validation errors
        if (error.response?.status === 422) {
            console.error('Validation failed:', error.response.data.errors);

            // If there's a custom error handler in the catch block, let it handle it
            // Otherwise show the first validation error
            if (!error.config?.skipDefaultErrorHandler) {
                const errors = error.response.data.errors;
                const firstError = Object.values(errors)[0][0];
                if (window.showToast) {
                    window.showToast(firstError, 'error');
                } else {
                    alert(firstError);
                }
            }
        }

        // Handle authorization errors
        if (error.response?.status === 403) {
            if (window.showToast) {
                window.showToast('You do not have permission to perform this action.', 'error');
            } else {
                alert('You do not have permission to perform this action.');
            }
        }

        // Handle server errors
        if (error.response?.status >= 500) {
            if (window.showToast) {
                window.showToast('A server error occurred. Please try again later.', 'error');
            } else {
                alert('A server error occurred. Please try again later.');
            }
        }

        return Promise.reject(error);
    }
);

// Global request interceptor for logging (development only)
if (import.meta.env.DEV) {
    window.axios.interceptors.request.use(
        config => {
            console.log('Request:', config.method.toUpperCase(), config.url);
            return config;
        },
        error => Promise.reject(error)
    );
}
