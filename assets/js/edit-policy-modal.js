// Enhanced Edit Policy Modal functionality with insurance company fix
// This file handles loading policy data and populating the edit modal

let currentEditPolicyId = null;

function openEditModal(policyId) {
    console.log('openEditModal called with policyId:', policyId);
    currentEditPolicyId = policyId;
    showEditModalLoading();
    loadPolicyData(policyId);
}

// Ensure the function is globally accessible
window.openEditModal = openEditModal;

function showEditModalLoading() {
    const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
    
    // Show modal with loading state
    const modalBody = document.querySelector('#editPolicyModal .modal-body');
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="loading-spinner mb-3"></div>
            <h5 class="text-muted">Loading policy data...</h5>
            <p class="text-muted">Please wait while we fetch the policy details.</p>
        </div>
    `;
    
    modal.show();
}

function loadPolicyData(policyId) {
    const formData = new FormData();
    formData.append('action', 'get_policy_data');
    formData.append('policy_id', policyId);
    
    fetch('include/policy-operations.php', {
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
            populateEditModal(data.policy);
        } else {
            showEditModalError(data.message || 'Failed to load policy data');
        }
    })
    .catch(error => {
        console.error('Error loading policy data:', error);
        showEditModalError('Network error occurred while loading policy data');
    });
}

function populateEditModal(policy) {
    // Restore the modal body with the form
    const modalBody = document.querySelector('#editPolicyModal .modal-body');
    modalBody.innerHTML = document.getElementById('editPolicyModalTemplate').innerHTML;
    
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
        if (document.getElementById('edit_payout')) {
            document.getElementById('edit_payout').value = policy.payout || '';
        }
        if (document.getElementById('edit_customer_paid')) {
            document.getElementById('edit_customer_paid').value = policy.customer_paid || '';
        }
        if (document.getElementById('edit_discount')) {
            document.getElementById('edit_discount').value = policy.discount || '';
        }
        if (document.getElementById('edit_calculated_revenue')) {
            document.getElementById('edit_calculated_revenue').value = policy.calculated_revenue || '';
        }
        
        document.getElementById('edit_comments').value = policy.comments || '';
        
        // Update modal title with vehicle number
        document.getElementById('editPolicyModalLabel').innerHTML = 
            `<i class="bx bx-edit me-2"></i>Edit Policy - ${policy.vehicle_number}`;
        
        // Initialize financial calculations if available
        if (typeof initializeFinancialCalculations === 'function') {
            initializeFinancialCalculations('edit');
        }
        
        // Apply fade-in animation
        modalBody.classList.add('fade-in');
        
    } catch (error) {
        console.error('Error populating modal:', error);
        showEditModalError('Error displaying policy data');
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
