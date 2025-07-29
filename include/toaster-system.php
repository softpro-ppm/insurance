<!-- Bootstrap 5 Toaster Notification System -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<style>
/* Toast Notification Styles */
.toast-container {
    max-width: 350px;
}

.toast {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.toast-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.toast-error {
    background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
    color: white;
}

.toast-warning {
    background: linear-gradient(135deg, #ffc107 0%, #f39c12 100%);
    color: #212529;
}

.toast-info {
    background: linear-gradient(135deg, #17a2b8 0%, #3498db 100%);
    color: white;
}

.toast-header {
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.toast-body {
    font-weight: 500;
}

.toast .btn-close {
    filter: brightness(0) invert(1);
}

.toast-warning .btn-close {
    filter: brightness(0);
}

/* Animation */
.toast.show {
    animation: slideInRight 0.3s ease-out;
}

.toast.hide {
    animation: slideOutRight 0.3s ease-in;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Progress bar for auto-dismiss */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 0 0 8px 8px;
    animation: progressBar 5s linear;
}

@keyframes progressBar {
    from { width: 100%; }
    to { width: 0%; }
}

/* Mobile responsiveness */
@media (max-width: 576px) {
    .toast-container {
        left: 1rem;
        right: 1rem;
        top: 1rem;
        max-width: none;
    }
}
</style>

<script>
/**
 * Universal Toast Notification System
 * Usage: showToast(message, type, title, duration)
 */

// Toast counter for unique IDs
let toastCounter = 0;

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - Type of toast (success, error, warning, info)
 * @param {string} title - Optional title for the toast
 * @param {number} duration - Duration in milliseconds (0 for persistent)
 * @param {boolean} showProgress - Show progress bar for auto-dismiss
 */
function showToast(message, type = 'info', title = '', duration = 5000, showProgress = true) {
    const toastId = `toast-${++toastCounter}`;
    const toastContainer = document.getElementById('toastContainer');
    
    // Set default titles based on type
    if (!title) {
        switch (type) {
            case 'success':
                title = 'Success';
                break;
            case 'error':
                title = 'Error';
                break;
            case 'warning':
                title = 'Warning';
                break;
            case 'info':
                title = 'Information';
                break;
            default:
                title = 'Notification';
        }
    }

    // Get icon based on type
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    // Create toast HTML
    const toastHTML = `
        <div id="${toastId}" class="toast toast-${type}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="${icons[type]} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <small class="text-muted">${getCurrentTime()}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
                ${showProgress && duration > 0 ? '<div class="toast-progress"></div>' : ''}
            </div>
        </div>
    `;

    // Add toast to container
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    // Initialize Bootstrap toast
    const toastElement = document.getElementById(toastId);
    const bsToast = new bootstrap.Toast(toastElement, {
        autohide: duration > 0,
        delay: duration
    });

    // Show toast
    bsToast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });

    return toastId;
}

/**
 * Show success toast
 */
function showSuccessToast(message, title = '', duration = 5000) {
    return showToast(message, 'success', title, duration);
}

/**
 * Show error toast
 */
function showErrorToast(message, title = '', duration = 8000) {
    return showToast(message, 'error', title, duration);
}

/**
 * Show warning toast
 */
function showWarningToast(message, title = '', duration = 6000) {
    return showToast(message, 'warning', title, duration);
}

/**
 * Show info toast
 */
function showInfoToast(message, title = '', duration = 5000) {
    return showToast(message, 'info', title, duration);
}

/**
 * Show persistent toast (doesn't auto-hide)
 */
function showPersistentToast(message, type = 'info', title = '') {
    return showToast(message, type, title, 0, false);
}

/**
 * Hide specific toast
 */
function hideToast(toastId) {
    const toastElement = document.getElementById(toastId);
    if (toastElement) {
        const bsToast = bootstrap.Toast.getInstance(toastElement);
        if (bsToast) {
            bsToast.hide();
        }
    }
}

/**
 * Hide all toasts
 */
function hideAllToasts() {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        const bsToast = bootstrap.Toast.getInstance(toast);
        if (bsToast) {
            bsToast.hide();
        }
    });
}

/**
 * Get current time for timestamp
 */
function getCurrentTime() {
    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

/**
 * Handle AJAX responses and show appropriate toasts
 */
function handleAjaxResponse(response, successMessage = 'Operation completed successfully') {
    try {
        if (typeof response === 'string') {
            response = JSON.parse(response);
        }
        
        if (response.success) {
            showSuccessToast(response.message || successMessage);
        } else {
            showErrorToast(response.message || 'An error occurred');
        }
    } catch (error) {
        showErrorToast('Invalid response format');
        console.error('Toast response error:', error);
    }
}

/**
 * Show form validation errors
 */
function showValidationErrors(errors) {
    if (Array.isArray(errors)) {
        errors.forEach(error => showErrorToast(error));
    } else if (typeof errors === 'object') {
        Object.values(errors).forEach(error => showErrorToast(error));
    } else {
        showErrorToast(errors);
    }
}

/**
 * Show bulk operation progress
 */
function showBulkProgress(completed, total, operation = 'Processing') {
    const percentage = Math.round((completed / total) * 100);
    const message = `${operation}: ${completed}/${total} (${percentage}%)`;
    
    // Remove previous progress toast if exists
    const existingProgress = document.querySelector('[id^="bulk-progress-"]');
    if (existingProgress) {
        existingProgress.remove();
    }
    
    if (completed < total) {
        return showToast(message, 'info', operation, 0, false);
    } else {
        showSuccessToast(`${operation} completed successfully!`);
    }
}

// Global error handler for AJAX requests
document.addEventListener('DOMContentLoaded', function() {
    // Handle global AJAX errors
    window.addEventListener('unhandledrejection', function(event) {
        showErrorToast('An unexpected error occurred');
        console.error('Unhandled promise rejection:', event.reason);
    });
    
    // Show initial load success (optional)
    if (document.readyState === 'complete') {
        // Page fully loaded
    }
});

// Export functions for use in other scripts
window.toastSystem = {
    show: showToast,
    success: showSuccessToast,
    error: showErrorToast,
    warning: showWarningToast,
    info: showInfoToast,
    persistent: showPersistentToast,
    hide: hideToast,
    hideAll: hideAllToasts,
    handleResponse: handleAjaxResponse,
    showValidation: showValidationErrors,
    showProgress: showBulkProgress
};
</script>
