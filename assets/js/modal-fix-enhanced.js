/* Enhanced Bootstrap 5 Modal Fix Script */
/* This script forces proper modal functionality and removes any blocking overlays */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced Bootstrap 5 Modal Fix Script Loaded');
    
    // Force remove any conflicting CSS that might block modals
    const style = document.createElement('style');
    style.textContent = `
        .modal * { pointer-events: all !important; }
        .modal { z-index: 1055 !important; pointer-events: all !important; }
        .modal-backdrop { pointer-events: none !important; }
        .modal-dialog { pointer-events: all !important; z-index: 1060 !important; }
        .modal-content { pointer-events: all !important; z-index: 1065 !important; }
    `;
    document.head.appendChild(style);
    
    // Enhanced modal trigger handling
    function initializeModalTriggers() {
        const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
        
        modalTriggers.forEach(function(trigger) {
            // Remove existing event listeners
            trigger.removeEventListener('click', handleModalTrigger);
            trigger.addEventListener('click', handleModalTrigger);
        });
        
        console.log('Initialized', modalTriggers.length, 'modal triggers');
    }
    
    function handleModalTrigger(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const targetModalId = this.getAttribute('data-bs-target');
        const targetModal = document.querySelector(targetModalId);
        
        console.log('Modal trigger clicked:', targetModalId);
        
        if (targetModal) {
            // Force proper modal structure
            ensureModalStructure(targetModal);
            
            // Initialize and show modal
            const modal = new bootstrap.Modal(targetModal, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            
            modal.show();
            console.log('Modal opened:', targetModalId);
            
            // Force proper z-index after showing
            setTimeout(() => {
                forceModalZIndex(targetModal);
            }, 100);
        } else {
            console.error('Modal not found:', targetModalId);
        }
    }
    
    function ensureModalStructure(modal) {
        // Ensure proper classes
        modal.classList.add('modal', 'fade');
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-hidden', 'true');
        
        // Ensure modal dialog has proper classes
        const modalDialog = modal.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.pointerEvents = 'all';
            modalDialog.style.zIndex = '1060';
        }
        
        // Ensure modal content has proper classes
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.pointerEvents = 'all';
            modalContent.style.zIndex = '1065';
        }
    }
    
    function forceModalZIndex(modal) {
        modal.style.zIndex = '1055';
        modal.style.display = 'block';
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.zIndex = '1040';
            backdrop.style.pointerEvents = 'none';
        }
        
        // Force all modal elements to be interactive
        const allModalElements = modal.querySelectorAll('*');
        allModalElements.forEach(el => {
            el.style.pointerEvents = 'all';
        });
    }
    
    // Enhanced modal event handling
    document.addEventListener('show.bs.modal', function(e) {
        console.log('Modal showing:', e.target.id);
        
        const modal = e.target;
        
        // Remove any existing backdrops
        const existingBackdrops = document.querySelectorAll('.modal-backdrop');
        existingBackdrops.forEach(backdrop => {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });
        
        // Force proper positioning
        setTimeout(() => {
            forceModalZIndex(modal);
            
            // Ensure body scroll lock
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            
        }, 10);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('Modal shown:', e.target.id);
        
        const modal = e.target;
        
        // Final force of proper styling
        forceModalZIndex(modal);
        
        // Focus management
        const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea, button');
        if (firstInput) {
            setTimeout(() => {
                firstInput.focus();
            }, 200);
        }
        
        // Remove any blocking overlays
        removeBlockingOverlays();
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('Modal hiding:', e.target.id);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('Modal hidden:', e.target.id);
        
        // Clean up backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });
        
        // Check if any modals are still open
        const openModals = document.querySelectorAll('.modal.show');
        if (openModals.length === 0) {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    });
    
    function removeBlockingOverlays() {
        // Remove any elements that might be blocking modal interaction
        const blockers = document.querySelectorAll('.overlay, .backdrop-overlay, .modal-overlay');
        blockers.forEach(blocker => {
            blocker.style.display = 'none';
            blocker.style.pointerEvents = 'none';
        });
        
        // Check for any elements with high z-index that might block modals
        const allElements = document.querySelectorAll('*');
        allElements.forEach(el => {
            const zIndex = window.getComputedStyle(el).zIndex;
            if (zIndex && parseInt(zIndex) > 1055 && !el.closest('.modal')) {
                console.log('Found potentially blocking element:', el, 'z-index:', zIndex);
                // Optionally lower the z-index of blocking elements
                // el.style.zIndex = '1000';
            }
        });
    }
    
    // Force click handler for modal elements
    function forceModalClickHandlers() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                // Allow clicks to pass through to modal content
                if (e.target === this) {
                    // Clicked on modal backdrop, close modal
                    const modalInstance = bootstrap.Modal.getInstance(this);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
            
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    }
    
    // Initialize everything
    initializeModalTriggers();
    forceModalClickHandlers();
    
    // Re-initialize when DOM changes
    const observer = new MutationObserver(function(mutations) {
        let shouldReinit = false;
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && (
                        node.matches('[data-bs-toggle="modal"]') || 
                        node.querySelector('[data-bs-toggle="modal"]')
                    )) {
                        shouldReinit = true;
                    }
                });
            }
        });
        
        if (shouldReinit) {
            setTimeout(initializeModalTriggers, 100);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Global functions for manual modal control
    window.openModal = function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            ensureModalStructure(modalElement);
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            setTimeout(() => forceModalZIndex(modalElement), 100);
        } else {
            console.error('Modal not found:', modalId);
        }
    };
    
    window.closeModal = function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    };
    
    // Emergency fix: Force all modals to be interactive every 2 seconds
    setInterval(() => {
        const visibleModals = document.querySelectorAll('.modal.show');
        visibleModals.forEach(modal => {
            forceModalZIndex(modal);
        });
    }, 2000);
    
    console.log('Enhanced Modal Fix Script fully initialized');
});
