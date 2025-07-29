// Critical Modal Fix JavaScript - Ultra Aggressive

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚨 Critical Modal Fix Loading...');
    
    // Emergency modal fix function
    function emergencyModalFix() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            // Force z-index
            modal.style.zIndex = '1055';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.pointerEvents = 'auto';
            
            // Fix modal dialog
            const modalDialog = modal.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.style.zIndex = '1060';
                modalDialog.style.pointerEvents = 'all';
                modalDialog.style.position = 'relative';
            }
            
            // Fix modal content
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.zIndex = '1061';
                modalContent.style.pointerEvents = 'all';
                modalContent.style.position = 'relative';
                modalContent.style.background = '#ffffff';
            }
            
            // Fix all interactive elements
            const interactiveElements = modal.querySelectorAll('input, select, textarea, button, a, [role="button"], [onclick], [data-bs-toggle]');
            interactiveElements.forEach(element => {
                element.style.pointerEvents = 'all';
                element.style.zIndex = '1062';
                element.style.position = 'relative';
                element.style.cursor = 'pointer';
            });
            
            // Special fix for close button
            const closeBtn = modal.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.style.zIndex = '1070';
                closeBtn.style.pointerEvents = 'all';
                closeBtn.style.cursor = 'pointer';
                closeBtn.style.opacity = '1';
            }
        });
        
        // Fix backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.style.zIndex = '1040';
            backdrop.style.pointerEvents = 'none';
        });
    }
    
    // Run emergency fix immediately
    emergencyModalFix();
    
    // Re-run fix when modal is shown
    document.addEventListener('show.bs.modal', function(e) {
        console.log('🔧 Modal showing, applying emergency fix');
        setTimeout(emergencyModalFix, 100);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('✅ Modal shown, applying final fix');
        emergencyModalFix();
        
        // Force focus on modal
        const modal = e.target;
        if (modal) {
            modal.focus();
            const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 200);
            }
        }
    });
    
    // Remove any blocking overlays
    function removeBlockingElements() {
        const blockingElements = document.querySelectorAll('.overlay, .loading-overlay, .block-overlay, .modal-loading-overlay');
        blockingElements.forEach(element => {
            if (element.style.zIndex > 1055) {
                element.remove();
            }
        });
    }
    
    // Monitor for dynamically added elements that might block the modal
    const observer = new MutationObserver(function(mutations) {
        let shouldRunFix = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && (node.classList.contains('modal') || node.classList.contains('overlay'))) {
                            shouldRunFix = true;
                        }
                    }
                });
            }
        });
        
        if (shouldRunFix) {
            setTimeout(() => {
                emergencyModalFix();
                removeBlockingElements();
            }, 50);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Emergency button click handler
    document.addEventListener('click', function(e) {
        // If click is on modal backdrop, ensure it doesn't interfere
        if (e.target.classList.contains('modal')) {
            e.stopPropagation();
            return;
        }
        
        // Force click through for modal elements
        if (e.target.closest('.modal')) {
            const modal = e.target.closest('.modal');
            if (modal && modal.classList.contains('show')) {
                // Ensure the click can proceed
                e.target.style.pointerEvents = 'all';
                e.target.style.zIndex = '1065';
            }
        }
    });
    
    // Emergency keyboard handler
    document.addEventListener('keydown', function(e) {
        const visibleModal = document.querySelector('.modal.show');
        if (visibleModal) {
            if (e.key === 'Escape') {
                const closeBtn = visibleModal.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
            if (e.key === 'Tab') {
                // Ensure tab navigation works
                const focusableElements = visibleModal.querySelectorAll('input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])');
                if (focusableElements.length > 0) {
                    focusableElements.forEach(el => {
                        el.style.pointerEvents = 'all';
                        el.style.zIndex = '1062';
                    });
                }
            }
        }
    });
    
    // Run fix every 2 seconds as a safety net
    setInterval(() => {
        const visibleModal = document.querySelector('.modal.show');
        if (visibleModal) {
            emergencyModalFix();
        }
    }, 2000);
    
    // Global function to force modal to work
    window.forceModalClickable = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            emergencyModalFix();
            console.log('🔧 Force fix applied to modal:', modalId);
        }
    };
    
    console.log('🚨 Critical Modal Fix Loaded Successfully');
});

// Additional global override
window.addEventListener('load', function() {
    // Final safety check after everything loads
    setTimeout(() => {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.pointerEvents = 'auto';
            const content = modal.querySelector('.modal-content');
            if (content) {
                content.style.pointerEvents = 'all';
                content.style.zIndex = '1061';
            }
        });
    }, 1000);
});
