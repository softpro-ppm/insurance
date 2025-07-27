// Enhanced Edit Policy Modal functionality with insurance company fix
// This file handles loading policy data and populating the edit modal

console.log('Edit policy modal script loaded successfully');

let currentEditPolicyId = null;

function openEditModal(policyId) {
    console.log('=== EDIT MODAL REQUESTED ===');
    console.log('Policy ID:', policyId);
    
    currentEditPolicyId = policyId;
    
    // First test basic connectivity
    testBasicConnectivity(() => {
        showEditModalLoading();
        loadPolicyData(policyId);
    });
}

function testBasicConnectivity(callback) {
    console.log('=== TESTING BASIC CONNECTIVITY ===');
    
    fetch('include/test-endpoint.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'test=1',
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Basic connectivity test response:', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok
        });
        
        if (response.ok) {
            return response.json();
        } else {
            throw new Error(`HTTP ${response.status}`);
        }
    })
    .then(data => {
        console.log('Basic connectivity SUCCESS:', data);
        callback(); // Proceed with actual request
    })
    .catch(error => {
        console.error('Basic connectivity FAILED:', error);
        showEditModalError('Cannot connect to server. Please check your internet connection and try again.');
    });
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

// Add direct API test function
window.testAPI = function(policyId = 1) {
    console.log('=== DIRECT API TEST ===');
    console.log('Testing API directly with policy ID:', policyId);
    
    const testUrl = 'include/policy-operations.php';
    const formData = new FormData();
    formData.append('action', 'get_policy_data');
    formData.append('policy_id', policyId);
    
    fetch(testUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Direct API response:', response);
        return response.text();
    })
    .then(text => {
        console.log('Direct API raw response:', text);
        try {
            const json = JSON.parse(text);
            console.log('Direct API JSON:', json);
        } catch (e) {
            console.error('Direct API not JSON:', e);
        }
    })
    .catch(error => {
        console.error('Direct API error:', error);
    });
};

// Add basic connectivity test function
window.testConnectivity = function() {
    console.log('=== TESTING BASIC CONNECTIVITY ===');
    
    fetch('include/test-endpoint.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'test=1'
    })
    .then(response => response.text())
    .then(text => {
        console.log('Connectivity test response:', text);
        try {
            const json = JSON.parse(text);
            console.log('Connectivity test JSON:', json);
        } catch (e) {
            console.error('Connectivity test not JSON:', e);
        }
    })
    .catch(error => {
        console.error('Connectivity test error:', error);
    });
};

console.log('Test functions available:');
console.log('- window.testEditModal(policyId)');
console.log('- window.testAPI(policyId)');
console.log('- window.testConnectivity()');

function showEditModalLoading() {
    console.log('Showing edit modal with loading state');
    
    // Just show the modal without changing content
    const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
    modal.show();
    
    // Add loading overlay to the modal body
    const modalBody = document.querySelector('#editPolicyModal .modal-body');
    
    // Create loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loading-overlay';
    loadingOverlay.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        border-radius: 0.375rem;
    `;
    
    loadingOverlay.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="text-muted">Loading policy data...</h5>
            <p class="text-muted">Please wait while we fetch the policy details.</p>
        </div>
    `;
    
    // Remove any existing loading overlay
    const existingOverlay = document.getElementById('loading-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Make modal body relative positioned
    modalBody.style.position = 'relative';
    modalBody.appendChild(loadingOverlay);
}

function hideLoadingInModal() {
    console.log('Hiding loading overlay');
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

function showEditModalError(message) {
    console.log('Showing error in modal:', message);
    
    // Remove loading overlay
    hideLoadingInModal();
    
    // Show the modal if not already shown
    const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
    modal.show();
    
    // Add error message to modal body
    const modalBody = document.querySelector('#editPolicyModal .modal-body');
    
    // Create error overlay
    const errorOverlay = document.createElement('div');
    errorOverlay.id = 'error-overlay';
    errorOverlay.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        border-radius: 0.375rem;
    `;
    
    errorOverlay.innerHTML = `
        <div class="text-center p-4">
            <div class="text-danger mb-3">
                <i class="bx bx-error-circle" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-danger">Error Loading Policy</h5>
            <p class="text-muted mb-3">${message}</p>
            <button type="button" class="btn btn-primary me-2" onclick="retryLoadPolicy()">
                <i class="bx bx-refresh me-1"></i>Try Again
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    `;
    
    // Remove any existing overlays
    const existingOverlay = document.getElementById('error-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Make modal body relative positioned
    modalBody.style.position = 'relative';
    modalBody.appendChild(errorOverlay);
}

function retryLoadPolicy() {
    console.log('Retrying to load policy data for ID:', currentEditPolicyId);
    if (currentEditPolicyId) {
        // Remove error overlay
        const errorOverlay = document.getElementById('error-overlay');
        if (errorOverlay) {
            errorOverlay.remove();
        }
        // Retry loading
        showEditModalLoading();
        loadPolicyData(currentEditPolicyId);
    }
}

function loadPolicyData(policyId) {
    console.log('=== STARTING POLICY DATA LOAD ===');
    console.log('Policy ID:', policyId);
    console.log('Current URL:', window.location.href);
    
    // Test multiple URL patterns to find the working one
    const urlsToTry = [
        'include/policy-operations.php',
        './include/policy-operations.php',
        '/insurance/include/policy-operations.php',
        window.location.pathname.replace(/\/[^\/]*$/, '') + '/include/policy-operations.php'
    ];
    
    console.log('Testing URLs:', urlsToTry);
    
    let urlIndex = 0;
    let requestTimeout;
    
    function tryNextUrl() {
        if (urlIndex >= urlsToTry.length) {
            console.error('=== ALL URLS FAILED ===');
            showEditModalError('Unable to connect to server. All connection attempts failed.');
            return;
        }
        
        const apiUrl = urlsToTry[urlIndex];
        console.log(`=== TRYING URL ${urlIndex + 1}/${urlsToTry.length} ===`);
        console.log('URL:', apiUrl);
        
        const formData = new FormData();
        formData.append('action', 'get_policy_data');
        formData.append('policy_id', policyId);
        
        console.log('FormData contents:', {
            action: 'get_policy_data',
            policy_id: policyId
        });
        
        // Clear any existing timeout
        if (requestTimeout) {
            clearTimeout(requestTimeout);
        }
        
        // Set a manual timeout
        requestTimeout = setTimeout(() => {
            console.error(`URL ${urlIndex + 1} TIMEOUT after 15 seconds`);
            urlIndex++;
            tryNextUrl();
        }, 15000);
        
        fetch(apiUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            clearTimeout(requestTimeout);
            console.log(`URL ${urlIndex + 1} Response:`, {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok,
                headers: Object.fromEntries(response.headers.entries())
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            console.log(`URL ${urlIndex + 1} Content-Type:`, contentType);
            
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.log(`URL ${urlIndex + 1} Raw Response (first 500 chars):`, text.substring(0, 500));
                    throw new Error('Server returned non-JSON response');
                });
            }
            
            return response.json();
        })
        .then(data => {
            clearTimeout(requestTimeout);
            console.log(`URL ${urlIndex + 1} SUCCESS - JSON Data:`, data);
            
            if (data.success) {
                // Backend returns 'policy' but we expect 'data', so handle both
                const policyData = data.policy || data.data;
                console.log('Policy data extracted:', policyData);
                
                if (policyData) {
                    populateEditModal(policyData);
                } else {
                    console.error('No policy data found in response');
                    showEditModalError('No policy data received from server');
                }
            } else {
                console.error('API returned error:', data.message);
                showEditModalError(data.message || 'Failed to load policy data');
            }
        })
        .catch(error => {
            clearTimeout(requestTimeout);
            console.error(`URL ${urlIndex + 1} FAILED:`, {
                error: error.message,
                stack: error.stack,
                name: error.name
            });
            
            urlIndex++;
            
            if (urlIndex < urlsToTry.length) {
                console.log('Trying next URL in 1 second...');
                setTimeout(tryNextUrl, 1000); // 1 second delay before trying next URL
            } else {
                console.error('=== ALL URLS EXHAUSTED ===');
                showEditModalError(`Connection failed: ${error.message}`);
            }
        });
    }
    
    // Start trying URLs
    console.log('=== STARTING URL TESTS ===');
    tryNextUrl();
}

function populateEditModal(policy) {
    console.log('Populating modal with policy data:', policy);
    
    // Show the modal first
    const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
    modal.show();
    
    try {
        // Populate form fields
        document.getElementById('edit_policy_id').value = policy.id;
        document.getElementById('edit_vehicle_number').value = policy.vehicle_number || '';
        document.getElementById('edit_phone').value = policy.phone || '';
        document.getElementById('edit_name').value = policy.name || '';
        document.getElementById('edit_vehicle_type').value = policy.vehicle_type || '';
        
        // Handle insurance company selection with fallback
        const insuranceSelect = document.getElementById('edit_insurance_company');
        const insuranceCompany = policy.insurance_company || '';
        
        // Try to find exact match first
        let optionFound = false;
        for (let i = 0; i < insuranceSelect.options.length; i++) {
            if (insuranceSelect.options[i].value === insuranceCompany) {
                insuranceSelect.selectedIndex = i;
                optionFound = true;
                break;
            }
        }
        
        // If exact match not found, try partial match
        if (!optionFound && insuranceCompany) {
            for (let i = 0; i < insuranceSelect.options.length; i++) {
                const optionText = insuranceSelect.options[i].text.toLowerCase();
                const companyName = insuranceCompany.toLowerCase();
                
                // Check for partial matches (e.g., "HDFC ERGO" matches "HDFC ERGO General Insurance Company Ltd")
                if (optionText.includes(companyName.split(' ')[0]) || companyName.includes(optionText.split(' ')[0])) {
                    insuranceSelect.selectedIndex = i;
                    optionFound = true;
                    break;
                }
            }
        }
        
        // If still no match found, add the company as a new option
        if (!optionFound && insuranceCompany) {
            const newOption = document.createElement('option');
            newOption.value = insuranceCompany;
            newOption.text = insuranceCompany + ' (Legacy)';
            newOption.selected = true;
            newOption.style.backgroundColor = '#fff3cd'; // Warning background
            insuranceSelect.appendChild(newOption);
            
            // Show warning message
            showInsuranceCompanyWarning(insuranceCompany);
        }
        
        document.getElementById('edit_policy_type').value = policy.policy_type || '';
        document.getElementById('edit_policy_start_date').value = policy.policy_start_date || '';
        document.getElementById('edit_policy_end_date').value = policy.policy_end_date || '';
        document.getElementById('edit_premium').value = policy.premium || '';
        
        // Financial fields (if they exist)
        const payoutField = document.getElementById('edit_payout');
        if (payoutField) {
            payoutField.value = policy.payout || '';
        }
        const customerPaidField = document.getElementById('edit_customer_paid');
        if (customerPaidField) {
            customerPaidField.value = policy.customer_paid || '';
        }
        const discountField = document.getElementById('edit_discount');
        if (discountField) {
            discountField.value = policy.discount || '';
        }
        const calculatedRevenueField = document.getElementById('edit_calculated_revenue');
        if (calculatedRevenueField) {
            calculatedRevenueField.value = policy.calculated_revenue || '';
        }
        
        const commentsField = document.getElementById('edit_comments');
        if (commentsField) {
            commentsField.value = policy.comments || '';
        }
        
        // Update modal title with vehicle number
        document.getElementById('editPolicyModalLabel').innerHTML = 
            `<i class="bx bx-edit me-2"></i>Edit Policy - ${policy.vehicle_number}`;
        
        // Initialize financial calculations if available
        if (typeof initializeFinancialCalculations === 'function') {
            initializeFinancialCalculations('edit');
        }
        
        console.log('Modal populated successfully');
        
    } catch (error) {
        console.error('Error populating modal:', error);
        showEditModalError('Error displaying policy data: ' + error.message);
    }
}

function showInsuranceCompanyWarning(companyName) {
    const warningHtml = `
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <i class="bx bx-info-circle me-2"></i>
            <strong>Notice:</strong> The insurance company "${companyName}" from this policy is not in our current list. 
            You can update it to a current company or leave it as is.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const formContainer = document.querySelector('#editPolicyForm');
    const existingAlert = formContainer.querySelector('.alert-warning');
    
    if (existingAlert) {
        existingAlert.remove();
    }
    
    formContainer.insertAdjacentHTML('afterbegin', warningHtml);
}

function showEditModalError(message) {
    const modalBody = document.querySelector('#editPolicyModal .modal-body');
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="text-danger mb-3">
                <i class="bx bx-error-circle" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-danger">Error Loading Policy</h5>
            <p class="text-muted">${message}</p>
            <button type="button" class="btn btn-primary" onclick="loadPolicyData(${currentEditPolicyId})">
                <i class="bx bx-refresh me-2"></i>Try Again
            </button>
            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    `;
}

// Form submission handler for edit modal
function submitEditForm() {
    const form = document.getElementById('editPolicyForm');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<div class="loading-spinner me-2"></div>Updating...';
    submitBtn.disabled = true;
    
    fetch('include/edit-policies.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Success', data.message, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editPolicyModal'));
            modal.hide();
            
            // Refresh the page or update the table
            if (typeof refreshPolicyTable === 'function') {
                refreshPolicyTable();
            } else {
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            showToast('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating policy:', error);
        showToast('Error', 'Network error occurred while updating policy', 'error');
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
    
    return false; // Prevent form submission
}

// Toast notification function
function showToast(title, message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toastId = 'toast_' + Date.now();
    const iconClass = {
        'success': 'bx-check-circle text-success',
        'error': 'bx-error-circle text-danger',
        'warning': 'bx-info-circle text-warning',
        'info': 'bx-info-circle text-info'
    }[type] || 'bx-info-circle text-info';
    
    const toastHtml = `
        <div class="toast align-items-center border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <div class="d-flex align-items-center">
                        <i class="bx ${iconClass} me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>${title}</strong>
                            <div class="text-muted">${message}</div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: type === 'error' ? 8000 : 5000
    });
    
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const editForm = document.getElementById('editPolicyForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEditForm();
        });
    }
});
