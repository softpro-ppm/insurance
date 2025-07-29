// View Policy Modal Enhancement - Complete Data Display Fix

document.addEventListener('DOMContentLoaded', function() {
    // Enhanced viewpolicy function with loading states and error handling
    window.viewpolicy = function(identifier) {
        console.log("Enhanced viewpolicy function called");
        var id = $(identifier).data("id");
        console.log("Policy ID:", id);
        
        // Show modal immediately
        $('#renewalpolicyview').modal("show");
        console.log("Modal show called");
        
        // Add loading state to modal body
        const modalBody = document.getElementById('viewpolicydata');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bx bx-loader-alt bx-spin me-2"></i>
                        Loading Policy Details...
                    </h5>
                </div>
                <div class="modal-body text-center loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Fetching complete policy information...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Close
                    </button>
                </div>
            `;
        }
        
        // Make AJAX request to fetch policy data
        $.post("include/view-policy.php", { id: id })
            .done(function(data) {
                console.log("AJAX response received, length:", data.length);
                
                if (data && data.trim() !== '') {
                    $('#viewpolicydata').html(data);
                    console.log("Policy data loaded successfully");
                    
                    // Ensure modal is properly sized and scrollable
                    setTimeout(function() {
                        const modal = document.getElementById('renewalpolicyview');
                        const modalBody = modal.querySelector('.modal-body');
                        
                        if (modalBody) {
                            // Ensure proper scrolling
                            modalBody.style.overflowY = 'auto';
                            modalBody.style.maxHeight = 'calc(100vh - 150px)';
                            
                            // Scroll to top
                            modalBody.scrollTop = 0;
                            
                            console.log("Modal body configured for proper scrolling");
                        }
                    }, 100);
                } else {
                    throw new Error("Empty response from server");
                }
            })
            .fail(function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response:", xhr.responseText);
                
                // Show error message in modal
                $('#viewpolicydata').html(`
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bx bx-error me-2"></i>
                            Error Loading Policy
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="alert alert-danger">
                            <i class="bx bx-error-circle me-2"></i>
                            <strong>Error:</strong> Unable to load policy data.
                            <br><small class="text-muted">Error: ${error}</small>
                        </div>
                        <p class="text-muted">Please try refreshing the page and try again.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-primary" onclick="viewpolicy(this)" data-id="${id}">
                            <i class="bx bx-refresh me-1"></i>Retry
                        </button>
                    </div>
                `);
            });
    };
    
    // Enhanced modal event handlers
    $('#renewalpolicyview').on('shown.bs.modal', function() {
        console.log("View policy modal shown");
        
        // Focus management for accessibility
        const modal = this;
        const firstFocusable = modal.querySelector('button, a, input, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    });
    
    $('#renewalpolicyview').on('hidden.bs.modal', function() {
        console.log("View policy modal hidden");
        
        // Clean up modal content
        const modalBody = document.getElementById('viewpolicydata');
        if (modalBody) {
            modalBody.innerHTML = '';
        }
    });
    
    // Keyboard navigation enhancement
    $(document).on('keydown', function(e) {
        if ($('#renewalpolicyview').hasClass('show')) {
            // ESC key to close modal
            if (e.key === 'Escape') {
                $('#renewalpolicyview').modal('hide');
            }
        }
    });
    
    // Auto-resize modal content when window is resized
    $(window).on('resize', function() {
        if ($('#renewalpolicyview').hasClass('show')) {
            const modalBody = document.querySelector('#renewalpolicyview .modal-body');
            if (modalBody) {
                modalBody.style.maxHeight = 'calc(100vh - 150px)';
            }
        }
    });
    
    console.log("View Policy Modal Enhancement loaded successfully");
});

// Global function for opening edit modal from view modal
function openEditFromView(policyId) {
    console.log("Opening edit modal for policy:", policyId);
    
    // Close the view modal first
    $("#renewalpolicyview").modal("hide");
    
    // Wait for the view modal to close completely, then open edit modal
    setTimeout(function() {
        if (typeof loadPolicyForEdit === 'function') {
            loadPolicyForEdit(policyId);
        } else {
            console.error("loadPolicyForEdit function not found");
            alert("Edit function not available. Please refresh the page and try again.");
        }
    }, 300);
}

// Utility function to refresh policy data
function refreshPolicyView(policyId) {
    const policyElement = document.querySelector(`[data-id="${policyId}"]`);
    if (policyElement) {
        viewpolicy(policyElement);
    }
}
