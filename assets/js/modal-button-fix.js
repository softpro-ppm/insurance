/* Enhanced Modal Button JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    
    // Enhanced button click feedback
    function addButtonClickFeedback() {
        const modalButtons = document.querySelectorAll('.modal-footer .btn');
        
        modalButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Add ripple effect
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
                
                // Add success feedback for primary buttons
                if (this.classList.contains('btn-primary') && this.type === 'submit') {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                }
            });
        });
    }
    
    // Enhance form submission buttons
    function enhanceSubmissionButtons() {
        const forms = document.querySelectorAll('.modal form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]') || 
                                document.querySelector(`button[form="${form.id}"]`);
                
                if (submitBtn) {
                    // Store original content
                    const originalContent = submitBtn.innerHTML;
                    const originalText = submitBtn.textContent;
                    
                    // Show loading state
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
                    submitBtn.disabled = true;
                    submitBtn.style.pointerEvents = 'none';
                    
                    // Reset after timeout (fallback)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalContent;
                        submitBtn.disabled = false;
                        submitBtn.style.pointerEvents = 'all';
                    }, 10000);
                    
                    // Store for potential reset
                    submitBtn.setAttribute('data-original-content', originalContent);
                }
            });
        });
    }
    
    // Add keyboard navigation for modal buttons
    function addKeyboardNavigation() {
        document.addEventListener('keydown', function(e) {
            const activeModal = document.querySelector('.modal.show');
            if (!activeModal) return;
            
            const buttons = activeModal.querySelectorAll('.modal-footer .btn');
            const focusedButton = document.activeElement;
            
            if (e.key === 'Tab' && buttons.length > 0) {
                e.preventDefault();
                
                let currentIndex = Array.from(buttons).indexOf(focusedButton);
                if (currentIndex === -1) currentIndex = 0;
                
                if (e.shiftKey) {
                    currentIndex = currentIndex > 0 ? currentIndex - 1 : buttons.length - 1;
                } else {
                    currentIndex = currentIndex < buttons.length - 1 ? currentIndex + 1 : 0;
                }
                
                buttons[currentIndex].focus();
            }
            
            // Enter key on focused button
            if (e.key === 'Enter' && focusedButton && focusedButton.classList.contains('btn')) {
                e.preventDefault();
                focusedButton.click();
            }
            
            // Escape key to close modal
            if (e.key === 'Escape') {
                const closeBtn = activeModal.querySelector('.modal-footer .btn[data-bs-dismiss="modal"]');
                if (closeBtn) closeBtn.click();
            }
        });
    }
    
    // Add hover effects and animations
    function addHoverEffects() {
        const style = document.createElement('style');
        style.textContent = `
            .ripple-effect {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .modal-footer .btn {
                position: relative;
                overflow: hidden;
            }
            
            .modal-footer .btn:focus {
                outline: 2px solid rgba(102, 126, 234, 0.5) !important;
                outline-offset: 2px;
            }
            
            .modal-footer .btn:disabled {
                pointer-events: none !important;
                opacity: 0.6 !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Modal button state management
    function manageButtonStates() {
        // Reset button states when modal opens
        document.addEventListener('show.bs.modal', function(e) {
            const modal = e.target;
            const buttons = modal.querySelectorAll('.modal-footer .btn');
            
            buttons.forEach(button => {
                if (button.hasAttribute('data-original-content')) {
                    button.innerHTML = button.getAttribute('data-original-content');
                    button.disabled = false;
                    button.style.pointerEvents = 'all';
                }
            });
        });
        
        // Focus management when modal is shown
        document.addEventListener('shown.bs.modal', function(e) {
            const modal = e.target;
            const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
            const primaryButton = modal.querySelector('.modal-footer .btn-primary');
            
            // Focus first input if available, otherwise focus primary button
            if (firstInput && !firstInput.disabled) {
                setTimeout(() => firstInput.focus(), 100);
            } else if (primaryButton) {
                setTimeout(() => primaryButton.focus(), 100);
            }
        });
    }
    
    // Enhanced error handling for form submissions
    function enhanceErrorHandling() {
        window.addEventListener('unhandledrejection', function(e) {
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) {
                const submitBtn = activeModal.querySelector('.modal-footer button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    // Reset button if there was an error
                    const originalContent = submitBtn.getAttribute('data-original-content');
                    if (originalContent) {
                        submitBtn.innerHTML = originalContent;
                        submitBtn.disabled = false;
                        submitBtn.style.pointerEvents = 'all';
                    }
                }
            }
        });
    }
    
    // Force center alignment for all modal footers
    function forceCenterAlignment() {
        const modalFooters = document.querySelectorAll('.modal-footer');
        
        modalFooters.forEach(footer => {
            // Add center alignment classes
            footer.classList.add('d-flex', 'justify-content-center', 'align-items-center');
            footer.style.justifyContent = 'center';
            footer.style.alignItems = 'center';
            footer.style.textAlign = 'center';
            footer.style.display = 'flex';
            footer.style.gap = '1rem';
            
            // Remove any conflicting classes
            footer.classList.remove('justify-content-end', 'justify-content-start', 'text-end', 'text-start');
        });
    }
    
    // Initialize all enhancements
    function initializeModalButtonEnhancements() {
        forceCenterAlignment();
        addButtonClickFeedback();
        enhanceSubmissionButtons();
        addKeyboardNavigation();
        addHoverEffects();
        manageButtonStates();
        enhanceErrorHandling();
        
        console.log('Modal button enhancements initialized');
    }
    
    // Run initialization
    initializeModalButtonEnhancements();
    
    // Re-initialize when new modals are dynamically added
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && (node.classList.contains('modal') || node.querySelector('.modal'))) {
                        setTimeout(() => {
                            initializeModalButtonEnhancements();
                            forceCenterAlignment();
                        }, 100);
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Also fix alignment when modals are shown
    document.addEventListener('shown.bs.modal', function(e) {
        setTimeout(forceCenterAlignment, 50);
    });
});

// Global function to reset modal button state (can be called from outside)
window.resetModalButtonState = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const buttons = modal.querySelectorAll('.modal-footer .btn');
        buttons.forEach(button => {
            if (button.hasAttribute('data-original-content')) {
                button.innerHTML = button.getAttribute('data-original-content');
                button.disabled = false;
                button.style.pointerEvents = 'all';
            }
        });
    }
};

// Global function to set button loading state
window.setModalButtonLoading = function(button, loadingText = 'Processing...') {
    if (button) {
        button.setAttribute('data-original-content', button.innerHTML);
        button.innerHTML = `<i class="bx bx-loader-alt bx-spin me-2"></i>${loadingText}`;
        button.disabled = true;
        button.style.pointerEvents = 'none';
    }
};
