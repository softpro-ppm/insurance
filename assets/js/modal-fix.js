/* Bootstrap 5 Modal Fix and Debugging Script */
/* This script ensures proper modal functionality with Bootstrap 5 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Bootstrap 5 Modal Fix Script Loaded');
    
    // Ensure Bootstrap 5 is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap 5 JavaScript is not loaded!');
        return;
    }
    
    // Fix for modal event handling
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetModalId = this.getAttribute('data-bs-target');
            const targetModal = document.querySelector(targetModalId);
            
            if (targetModal) {
                const modal = new bootstrap.Modal(targetModal, {
                    backdrop: true,
                    keyboard: true
                });
                modal.show();
                console.log('Modal opened:', targetModalId);
            } else {
                console.error('Modal not found:', targetModalId);
            }
        });
    });
    
    // Debug modal events
    document.addEventListener('show.bs.modal', function(e) {
        console.log('Modal showing:', e.target.id);
        
        // Ensure modal is properly positioned
        setTimeout(function() {
            const modal = e.target;
            modal.style.display = 'block';
            modal.style.zIndex = '1050';
            
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = '1040';
            }
        }, 10);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('Modal shown:', e.target.id);
        
        // Focus management
        const modal = e.target;
        const firstInput = modal.querySelector('input, select, textarea, button');
        if (firstInput) {
            firstInput.focus();
        }
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('Modal hiding:', e.target.id);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('Modal hidden:', e.target.id);
        
        // Clean up any remaining backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(function(backdrop) {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });
        
        // Remove modal-open class if no modals are active
        const openModals = document.querySelectorAll('.modal.show');
        if (openModals.length === 0) {
            document.body.classList.remove('modal-open');
        }
    });
    
    // Fix for nested modals or dropdown conflicts
    document.addEventListener('click', function(e) {
        // Prevent modal from closing when clicking inside modal content
        if (e.target.closest('.modal-content')) {
            e.stopPropagation();
        }
    });
    
    // Initialize any existing modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modalElement) {
        // Ensure proper Bootstrap 5 structure
        if (!modalElement.querySelector('.modal-dialog')) {
            console.warn('Modal missing modal-dialog wrapper:', modalElement.id);
        }
        
        if (!modalElement.querySelector('.modal-content')) {
            console.warn('Modal missing modal-content wrapper:', modalElement.id);
        }
    });
    
    console.log('Found', modalTriggers.length, 'modal triggers');
    console.log('Found', modals.length, 'modals');
});

// Global function for programmatic modal opening (for backward compatibility)
function openModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        console.error('Modal not found:', modalId);
    }
}

// Global function for programmatic modal closing
function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
}
