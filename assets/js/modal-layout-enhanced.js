// Enhanced Modal Layout JavaScript - Improved UX & Functionality

document.addEventListener('DOMContentLoaded', function() {
    
    /* ====================
       MODAL INITIALIZATION
       ==================== */
    
    // Initialize all modals with enhanced options
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const bsModal = new bootstrap.Modal(modal, {
            backdrop: 'static',
            keyboard: true,
            focus: true
        });
        
        // Store modal instance for later use
        modal.bsModal = bsModal;
    });
    
    /* ====================
       FORM ENHANCEMENTS
       ==================== */
    
    // Auto-format vehicle numbers (uppercase, add spaces)
    const vehicleInputs = document.querySelectorAll('input[name="vehicle_number"], #modal_vehicle_number, #edit_vehicle_number');
    vehicleInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^A-Z0-9]/gi, '').toUpperCase();
            
            // Format as XX##XX#### (Indian vehicle number format)
            if (value.length > 2) {
                value = value.substring(0, 2) + ' ' + value.substring(2);
            }
            if (value.length > 5) {
                value = value.substring(0, 5) + ' ' + value.substring(5);
            }
            if (value.length > 8) {
                value = value.substring(0, 8) + ' ' + value.substring(8, 12);
            }
            
            this.value = value;
            validateVehicleNumber(this);
        });
        
        input.addEventListener('blur', function(e) {
            validateVehicleNumber(this);
        });
    });
    
    // Phone number formatting and validation
    const phoneInputs = document.querySelectorAll('input[name="phone"], input[type="tel"], #edit_phone');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/\D/g, '');
            
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
            
            validatePhoneNumber(this);
        });
        
        input.addEventListener('blur', function(e) {
            validatePhoneNumber(this);
        });
    });
    
    // Name formatting (proper case)
    const nameInputs = document.querySelectorAll('input[name="name"], #edit_name');
    nameInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Convert to proper case
            this.value = this.value.replace(/\w\S*/g, (txt) => 
                txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
            );
        });
    });
    
    // Financial input formatting
    const financialInputs = document.querySelectorAll('input[name="premium"], input[name="payout"], input[name="customer_paid"], #modal_premium, #modal_payout, #modal_customer_paid, #edit_premium, #edit_payout, #edit_customer_paid');
    financialInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Format currency input
            formatCurrencyInput(this);
        });
        
        input.addEventListener('focus', function(e) {
            // Remove formatting for editing
            this.value = this.value.replace(/[^\d.]/g, '');
        });
        
        input.addEventListener('blur', function(e) {
            // Add formatting back
            formatCurrencyInput(this);
        });
    });
    
    /* ====================
       MODAL BEHAVIOR ENHANCEMENTS
       ==================== */
    
    // Enhanced modal show behavior
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function(e) {
            // Add loading overlay
            showModalLoading(this);
            
            // Reset form if it exists
            const form = this.querySelector('form');
            if (form && !this.dataset.preserveData) {
                form.reset();
                clearFormValidation(form);
            }
            
            // Disable background scrolling
            document.body.style.overflow = 'hidden';
        });
        
        modal.addEventListener('shown.bs.modal', function(e) {
            // Remove loading overlay
            hideModalLoading(this);
            
            // Focus on first input
            const firstInput = this.querySelector('input:not([type="hidden"]):not([readonly]), select, textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
            
            // Initialize tooltips in modal
            initializeTooltips(this);
            
            // Auto-calculate financial fields if present
            if (this.id === 'addPolicyModal' || this.id === 'editPolicyModal') {
                setTimeout(triggerFinancialCalculation, 200);
            }
        });
        
        modal.addEventListener('hide.bs.modal', function(e) {
            // Re-enable background scrolling
            document.body.style.overflow = '';
            
            // Clean up tooltips
            cleanupTooltips(this);
        });
        
        modal.addEventListener('hidden.bs.modal', function(e) {
            // Reset modal state
            resetModalState(this);
        });
    });
    
    /* ====================
       FORM VALIDATION
       ==================== */
    
    // Real-time form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('input', function(e) {
            validateField(e.target);
        });
        
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showValidationErrors(this);
                return false;
            }
            
            // Show loading state
            showFormLoading(this);
        });
    });
    
    /* ====================
       UTILITY FUNCTIONS
       ==================== */
    
    function validateVehicleNumber(input) {
        const value = input.value.replace(/\s/g, '');
        const isValid = /^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}$/.test(value);
        
        updateFieldValidation(input, isValid, isValid ? 'Valid vehicle number' : 'Enter valid vehicle number (e.g., MH12AB1234)');
        return isValid;
    }
    
    function validatePhoneNumber(input) {
        const isValid = /^[0-9]{10}$/.test(input.value);
        updateFieldValidation(input, isValid, isValid ? 'Valid phone number' : 'Enter 10-digit phone number');
        return isValid;
    }
    
    function updateFieldValidation(field, isValid, message) {
        // Remove existing validation classes
        field.classList.remove('field-success', 'field-error', 'field-warning');
        
        // Remove existing feedback
        const existingFeedback = field.parentNode.querySelector('.field-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        if (field.value.trim() === '') {
            return; // Don't validate empty fields
        }
        
        // Add appropriate class
        field.classList.add(isValid ? 'field-success' : 'field-error');
        
        // Add feedback message
        const feedback = document.createElement('div');
        feedback.className = `field-feedback text-${isValid ? 'success' : 'danger'} small mt-1`;
        feedback.innerHTML = `<i class="bx bx-${isValid ? 'check' : 'x'}-circle me-1"></i>${message}`;
        field.parentNode.appendChild(feedback);
    }
    
    function formatCurrencyInput(input) {
        let value = parseFloat(input.value.replace(/[^\d.]/g, '')) || 0;
        if (value > 0) {
            input.value = '₹' + value.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }
    }
    
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                updateFieldValidation(field, false, 'This field is required');
                isValid = false;
            } else {
                // Run specific validation based on field type
                if (field.name === 'vehicle_number') {
                    isValid = validateVehicleNumber(field) && isValid;
                } else if (field.name === 'phone') {
                    isValid = validatePhoneNumber(field) && isValid;
                }
            }
        });
        
        return isValid;
    }
    
    function clearFormValidation(form) {
        const fields = form.querySelectorAll('.field-success, .field-error, .field-warning');
        fields.forEach(field => {
            field.classList.remove('field-success', 'field-error', 'field-warning');
        });
        
        const feedbacks = form.querySelectorAll('.field-feedback');
        feedbacks.forEach(feedback => feedback.remove());
    }
    
    function showValidationErrors(form) {
        const firstErrorField = form.querySelector('.field-error');
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Show toast notification
        showToast('Please correct the highlighted errors', 'error');
    }
    
    function showModalLoading(modal) {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'modal-loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Loading...</p>
            </div>
        `;
        loadingOverlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 1060;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        
        modal.querySelector('.modal-content').appendChild(loadingOverlay);
    }
    
    function hideModalLoading(modal) {
        const loadingOverlay = modal.querySelector('.modal-loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }
    
    function resetModalState(modal) {
        // Clear any loading states
        hideModalLoading(modal);
        
        // Reset form if present
        const form = modal.querySelector('form');
        if (form) {
            clearFormValidation(form);
            
            // Reset calculated fields
            const calculatedFields = form.querySelectorAll('[readonly]');
            calculatedFields.forEach(field => {
                field.value = '';
                field.style.backgroundColor = '';
                field.style.borderColor = '';
                field.style.color = '';
            });
            
            // Reset submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText || submitBtn.innerHTML;
            }
        }
    }
    
    function showFormLoading(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
            submitBtn.disabled = true;
        }
    }
    
    function initializeTooltips(container) {
        const tooltipElements = container.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipElements.forEach(element => {
            new bootstrap.Tooltip(element);
        });
    }
    
    function cleanupTooltips(container) {
        const tooltipElements = container.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipElements.forEach(element => {
            const tooltip = bootstrap.Tooltip.getInstance(element);
            if (tooltip) {
                tooltip.dispose();
            }
        });
    }
    
    function triggerFinancialCalculation() {
        // Trigger financial calculation if functions exist
        if (typeof calculateFinancials === 'function') {
            calculateFinancials();
        }
        if (typeof calculateEditFinancials === 'function') {
            calculateEditFinancials();
        }
    }
    
    function showToast(message, type = 'info') {
        // Create toast notification
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
        toastElement.setAttribute('role', 'alert');
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx bx-${type === 'error' ? 'error' : 'info'}-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastElement);
        
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove element after hide
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1070';
        document.body.appendChild(container);
        return container;
    }
    
    /* ====================
       KEYBOARD SHORTCUTS
       ==================== */
    
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to submit form
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const visibleModal = document.querySelector('.modal.show');
            if (visibleModal) {
                const form = visibleModal.querySelector('form');
                if (form) {
                    form.requestSubmit();
                }
            }
        }
        
        // Escape to close modal (if not prevented)
        if (e.key === 'Escape') {
            const visibleModal = document.querySelector('.modal.show');
            if (visibleModal && !visibleModal.dataset.preventEscape) {
                visibleModal.bsModal.hide();
            }
        }
    });
    
    /* ====================
       ACCESSIBILITY ENHANCEMENTS
       ==================== */
    
    // Announce modal open/close to screen readers
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'visually-hidden';
            announcement.textContent = `${this.querySelector('.modal-title').textContent} dialog opened`;
            document.body.appendChild(announcement);
            
            setTimeout(() => announcement.remove(), 1000);
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'visually-hidden';
            announcement.textContent = 'Dialog closed';
            document.body.appendChild(announcement);
            
            setTimeout(() => announcement.remove(), 1000);
        });
    });
    
    console.log('🎨 Enhanced Modal Layout System Initialized');
    console.log('✨ Features: Auto-validation, formatting, accessibility, keyboard shortcuts');
});

/* ====================
   GLOBAL HELPER FUNCTIONS
   ==================== */

// Function to open modal programmatically with enhanced features
window.openEnhancedModal = function(modalId, options = {}) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error(`Modal with ID ${modalId} not found`);
        return;
    }
    
    // Set options
    if (options.preserveData) {
        modal.dataset.preserveData = 'true';
    }
    if (options.preventEscape) {
        modal.dataset.preventEscape = 'true';
    }
    
    // Show modal
    modal.bsModal.show();
    
    return modal.bsModal;
};

// Function to validate entire form programmatically
window.validateModalForm = function(modalId) {
    const modal = document.getElementById(modalId);
    const form = modal?.querySelector('form');
    
    if (!form) {
        console.error(`No form found in modal ${modalId}`);
        return false;
    }
    
    return validateForm(form);
};

// Function to show custom modal loading
window.showModalLoading = function(modalId, message = 'Loading...') {
    const modal = document.getElementById(modalId);
    if (modal) {
        showModalLoading(modal);
        const loadingText = modal.querySelector('.modal-loading-overlay p');
        if (loadingText) {
            loadingText.textContent = message;
        }
    }
};

// Function to hide modal loading
window.hideModalLoading = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        hideModalLoading(modal);
    }
};
