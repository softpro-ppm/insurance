// Enhanced Edit Policy Modal functionality - FIXED VERSION
// This file handles loading policy data and populating the edit modal

console.log('Edit policy modal script loaded successfully');

let currentEditPolicyId = null;

function openEditModal(policyId) {
    console.log('=== EDIT MODAL REQUESTED ===');
    console.log('Policy ID:', policyId);
    
    currentEditPolicyId = policyId;
    
    // Show modal with loading state immediately
    showEditModalLoading();
    
    // Load policy data
    loadPolicyData(policyId);
}

function showEditModalLoading() {
    console.log('Showing edit modal with loading state');
    
    // Ensure modal exists
    const modal = document.getElementById('editPolicyModal');
    if (!modal) {
        console.error('Edit modal not found in DOM!');
        alert('Edit modal is not available. Please refresh the page and try again.');
        return;
    }
    
    // Show the modal using Bootstrap 4 jQuery API
    $('#editPolicyModal').modal('show');
    
    // Set loading content
    const modalBody = modal.querySelector('.modal-body');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="mt-3">Loading Policy Data...</h5>
                <p class="text-muted">Please wait while we fetch the policy information.</p>
            </div>
        `;
    }
}

function loadPolicyData(policyId) {
    console.log('=== LOADING POLICY DATA ===');
    console.log('Policy ID:', policyId);
    
    // Use jQuery AJAX for better compatibility
    $.ajax({
        url: 'include/policy-operations.php',
        type: 'POST',
        data: {
            action: 'get_policy_data',
            policy_id: policyId
        },
        timeout: 15000, // 15 second timeout
        success: function(response) {
            console.log('Policy data loaded successfully');
            console.log('Response type:', typeof response);
            console.log('Response length:', response ? response.length : 'null');
            
            try {
                let data;
                if (typeof response === 'string') {
                    data = JSON.parse(response);
                } else {
                    data = response;
                }
                
                if (data.success) {
                    populateEditModal(data.policy);
                } else {
                    showEditModalError(data.message || 'Failed to load policy data');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                console.log('Raw response:', response);
                showEditModalError('Invalid response format from server');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            
            let errorMessage = 'Failed to load policy data.';
            if (status === 'timeout') {
                errorMessage = 'Request timed out. Please try again.';
            } else if (status === 'error' && xhr.status === 0) {
                errorMessage = 'Network error. Please check your connection.';
            } else if (xhr.status === 404) {
                errorMessage = 'Policy data service not found.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please contact support.';
            }
            
            showEditModalError(errorMessage);
        }
    });
}

function showEditModalError(message) {
    console.log('Showing edit modal error:', message);
    
    const modal = document.getElementById('editPolicyModal');
    if (!modal) {
        alert('Error: ' + message);
        return;
    }
    
    const modalBody = modal.querySelector('.modal-body');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center p-5">
                <div class="text-danger mb-3">
                    <i class="bx bx-error-circle" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-danger">Error Loading Policy</h5>
                <p class="text-muted">${message}</p>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary mr-2" onclick="loadPolicyData(${currentEditPolicyId})">
                        <i class="bx bx-refresh mr-1"></i>Try Again
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="bx bx-x mr-1"></i>Close
                    </button>
                </div>
            </div>
        `;
    }
}

function populateEditModal(policyData) {
    console.log('Populating edit modal with policy data:', policyData);
    
    const modal = document.getElementById('editPolicyModal');
    if (!modal) {
        console.error('Edit modal not found');
        return;
    }
    
    // For now, show success and redirect to edit page
    // This is a temporary solution until we implement proper form population
    const modalBody = modal.querySelector('.modal-body');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center p-4">
                <div class="text-success mb-3">
                    <i class="bx bx-check-circle" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-success">Policy Found Successfully</h5>
                <p class="text-muted">Redirecting to edit form...</p>
                <div class="mt-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        `;
        
        // Redirect to edit page after short delay
        setTimeout(() => {
            window.location.href = `edit.php?id=${currentEditPolicyId}`;
        }, 2000);
    }
}

// Ensure the function is globally accessible
window.openEditModal = openEditModal;

// Debug: Log that the function has been assigned to window
console.log('openEditModal assigned to window:', typeof window.openEditModal);

// Add a test function for debugging
window.testEditModal = function(policyId = 1) {
    console.log('Testing edit modal with policy ID:', policyId);
    openEditModal(policyId);
};

// Add connectivity test function
window.testConnectivity = function() {
    console.log('=== CONNECTIVITY TEST ===');
    
    $.ajax({
        url: 'include/policy-operations.php',
        type: 'POST',
        data: { action: 'test' },
        success: function(response) {
            console.log('Connectivity test SUCCESS:', response);
            alert('Connection successful!');
        },
        error: function(xhr, status, error) {
            console.error('Connectivity test FAILED:', error);
            alert('Connection failed: ' + error);
        }
    });
};

// Add direct API test function
window.testAPI = function(policyId = 1) {
    console.log('=== DIRECT API TEST ===');
    console.log('Testing API directly with policy ID:', policyId);
    
    $.ajax({
        url: 'include/policy-operations.php',
        type: 'POST',
        data: {
            action: 'get_policy_data',
            policy_id: policyId
        },
        success: function(response) {
            console.log('Direct API test SUCCESS:', response);
            try {
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                console.log('Parsed data:', data);
            } catch (e) {
                console.log('Response is not JSON:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Direct API test FAILED:', error);
            console.log('Status:', status);
            console.log('Response:', xhr.responseText);
        }
    });
};

// Initialize when document is ready
$(document).ready(function() {
    console.log('Edit modal script ready');
    console.log('Available functions:', {
        openEditModal: typeof window.openEditModal,
        testEditModal: typeof window.testEditModal,
        testConnectivity: typeof window.testConnectivity,
        testAPI: typeof window.testAPI
    });
});
