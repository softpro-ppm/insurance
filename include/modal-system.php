<!-- Universal Bootstrap 5 Modal System -->

<!-- Add Policy Modal -->
<div class="modal fade" id="addPolicyModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Add New Policy
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPolicyForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Client Information -->
                        <div class="col-12">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Client Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Client Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="client_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="mobile_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-car me-2"></i>Vehicle Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vehicle_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="Car">Car</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Truck">Truck</option>
                                <option value="Bus">Bus</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Brand/Make</label>
                            <input type="text" class="form-control" name="vehicle_brand">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="vehicle_model">
                        </div>

                        <!-- Policy Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-file-contract me-2"></i>Policy Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="insurance_company" required>
                                <option value="">Select Insurance Company</option>
                                <option value="ICICI Lombard">ICICI Lombard</option>
                                <option value="Bajaj Allianz">Bajaj Allianz</option>
                                <option value="HDFC Ergo">HDFC Ergo</option>
                                <option value="TATA AIG">TATA AIG</option>
                                <option value="New India Assurance">New India Assurance</option>
                                <option value="Oriental Insurance">Oriental Insurance</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="policy_type" required>
                                <option value="">Select Policy Type</option>
                                <option value="Comprehensive">Comprehensive</option>
                                <option value="Third Party">Third Party</option>
                                <option value="Own Damage">Own Damage</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_start_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_end_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="premium_amount" step="0.01" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Commission Amount</label>
                            <input type="number" class="form-control" name="commission_amount" step="0.01">
                        </div>

                        <!-- File Upload -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-paperclip me-2"></i>Documents
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Document</label>
                            <input type="file" class="form-control" name="policy_document" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Additional Documents</label>
                            <input type="file" class="form-control" name="additional_documents[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Multiple files allowed</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Policy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Policy Modal -->
<div class="modal fade" id="editPolicyModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPolicyForm" enctype="multipart/form-data">
                <input type="hidden" name="policy_id" id="edit_policy_id">
                <div class="modal-body">
                    <!-- Same form fields as add modal but with edit prefix -->
                    <div class="row g-3">
                        <!-- Client Information -->
                        <div class="col-12">
                            <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Client Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Client Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="client_name" id="edit_client_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="mobile_number" id="edit_mobile_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="edit_email">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="edit_address">
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-car me-2"></i>Vehicle Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vehicle_number" id="edit_vehicle_number" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="vehicle_type" id="edit_vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="Car">Car</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Truck">Truck</option>
                                <option value="Bus">Bus</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Continue with other fields similar to add modal -->
                        <div class="col-md-6">
                            <label class="form-label">Brand/Make</label>
                            <input type="text" class="form-control" name="vehicle_brand" id="edit_vehicle_brand">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="vehicle_model" id="edit_vehicle_model">
                        </div>

                        <!-- Policy Information -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-file-contract me-2"></i>Policy Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="insurance_company" id="edit_insurance_company" required>
                                <option value="">Select Insurance Company</option>
                                <option value="ICICI Lombard">ICICI Lombard</option>
                                <option value="Bajaj Allianz">Bajaj Allianz</option>
                                <option value="HDFC Ergo">HDFC Ergo</option>
                                <option value="TATA AIG">TATA AIG</option>
                                <option value="New India Assurance">New India Assurance</option>
                                <option value="Oriental Insurance">Oriental Insurance</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="policy_type" id="edit_policy_type" required>
                                <option value="">Select Policy Type</option>
                                <option value="Comprehensive">Comprehensive</option>
                                <option value="Third Party">Third Party</option>
                                <option value="Own Damage">Own Damage</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_start_date" id="edit_policy_start_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="policy_end_date" id="edit_policy_end_date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="premium_amount" id="edit_premium_amount" step="0.01" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Commission Amount</label>
                            <input type="number" class="form-control" name="commission_amount" id="edit_commission_amount" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update Policy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Policy Modal -->
<div class="modal fade" id="viewPolicyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Policy Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewPolicyContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printPolicy()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">This action cannot be undone. The policy will be permanently deleted.</p>
                    <div id="deleteItemInfo" class="alert alert-light mt-3">
                        <!-- Item details will be shown here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Delete Policy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 id="loadingText">Processing...</h6>
                <small class="text-muted" id="loadingSubtext">Please wait</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal enhancements */
.modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.fade .modal-dialog {
    transform: scale(0.9);
}

.modal.show .modal-dialog {
    transform: scale(1);
}

.modal-header {
    border-bottom: none;
    border-radius: 8px 8px 0 0;
}

.modal-footer {
    border-top: none;
    border-radius: 0 0 8px 8px;
}

.modal-content {
    border: none;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

/* Form styling within modals */
.modal .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.modal .form-control:focus,
.modal .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Section dividers */
.modal h6.fw-bold {
    font-size: 1rem;
    margin-bottom: 1rem;
}

/* Loading animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-lg {
        max-width: 90%;
    }
}
</style>

<script>
// Modal Management System
const ModalSystem = {
    // Show loading modal
    showLoading: function(text = 'Processing...', subtext = 'Please wait') {
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingSubtext').textContent = subtext;
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        return loadingModal;
    },

    // Hide loading modal
    hideLoading: function() {
        const loadingModal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
        if (loadingModal) {
            loadingModal.hide();
        }
    },

    // Show delete confirmation
    showDeleteConfirm: function(itemInfo, onConfirm) {
        document.getElementById('deleteItemInfo').innerHTML = itemInfo;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        
        // Set up confirm button
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        confirmBtn.onclick = function() {
            deleteModal.hide();
            onConfirm();
        };
        
        deleteModal.show();
    },

    // Populate edit modal with data
    populateEditModal: function(data) {
        Object.keys(data).forEach(key => {
            const element = document.getElementById('edit_' + key);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = data[key] == 1;
                } else {
                    element.value = data[key] || '';
                }
            }
        });
    },

    // Clear form data
    clearForm: function(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
        }
    }
};

// Policy Modal Functions
function showAddPolicyModal() {
    ModalSystem.clearForm('addPolicyForm');
    const modal = new bootstrap.Modal(document.getElementById('addPolicyModal'));
    modal.show();
}

function showEditPolicyModal(policyId) {
    // Fetch policy data and populate modal
    ModalSystem.showLoading('Loading policy data...');
    
    fetch(`api/get_policy.php?id=${policyId}`)
        .then(response => response.json())
        .then(data => {
            ModalSystem.hideLoading();
            if (data.success) {
                ModalSystem.populateEditModal(data.policy);
                const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
                modal.show();
            } else {
                showErrorToast(data.message || 'Failed to load policy data');
            }
        })
        .catch(error => {
            ModalSystem.hideLoading();
            showErrorToast('Error loading policy data');
            console.error('Error:', error);
        });
}

function showViewPolicyModal(policyId) {
    ModalSystem.showLoading('Loading policy details...');
    
    fetch(`api/get_policy_details.php?id=${policyId}`)
        .then(response => response.text())
        .then(html => {
            ModalSystem.hideLoading();
            document.getElementById('viewPolicyContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('viewPolicyModal'));
            modal.show();
        })
        .catch(error => {
            ModalSystem.hideLoading();
            showErrorToast('Error loading policy details');
            console.error('Error:', error);
        });
}

function showDeletePolicyModal(policyId, vehicleNumber) {
    const itemInfo = `
        <strong>Vehicle Number:</strong> ${vehicleNumber}<br>
        <strong>Policy ID:</strong> ${policyId}
    `;
    
    ModalSystem.showDeleteConfirm(itemInfo, function() {
        deletePolicy(policyId);
    });
}

function deletePolicy(policyId) {
    ModalSystem.showLoading('Deleting policy...');
    
    fetch('api/delete_policy.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ policy_id: policyId })
    })
    .then(response => response.json())
    .then(data => {
        ModalSystem.hideLoading();
        if (data.success) {
            showSuccessToast('Policy deleted successfully');
            // Refresh DataTable if it exists
            if (typeof table !== 'undefined' && table.ajax) {
                table.ajax.reload();
            }
        } else {
            showErrorToast(data.message || 'Failed to delete policy');
        }
    })
    .catch(error => {
        ModalSystem.hideLoading();
        showErrorToast('Error deleting policy');
        console.error('Error:', error);
    });
}

function printPolicy() {
    const content = document.getElementById('viewPolicyContent').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Policy Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    ${content}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    // Add Policy Form
    const addForm = document.getElementById('addPolicyForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            ModalSystem.showLoading('Saving policy...');
            
            fetch('api/add_policy.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                ModalSystem.hideLoading();
                if (data.success) {
                    showSuccessToast('Policy added successfully');
                    bootstrap.Modal.getInstance(document.getElementById('addPolicyModal')).hide();
                    ModalSystem.clearForm('addPolicyForm');
                    
                    // Refresh DataTable if it exists
                    if (typeof table !== 'undefined' && table.ajax) {
                        table.ajax.reload();
                    }
                } else {
                    showErrorToast(data.message || 'Failed to add policy');
                }
            })
            .catch(error => {
                ModalSystem.hideLoading();
                showErrorToast('Error saving policy');
                console.error('Error:', error);
            });
        });
    }

    // Edit Policy Form
    const editForm = document.getElementById('editPolicyForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            ModalSystem.showLoading('Updating policy...');
            
            fetch('api/update_policy.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                ModalSystem.hideLoading();
                if (data.success) {
                    showSuccessToast('Policy updated successfully');
                    bootstrap.Modal.getInstance(document.getElementById('editPolicyModal')).hide();
                    
                    // Refresh DataTable if it exists
                    if (typeof table !== 'undefined' && table.ajax) {
                        table.ajax.reload();
                    }
                } else {
                    showErrorToast(data.message || 'Failed to update policy');
                }
            })
            .catch(error => {
                ModalSystem.hideLoading();
                showErrorToast('Error updating policy');
                console.error('Error:', error);
            });
        });
    }
});

// Export modal system for global use
window.ModalSystem = ModalSystem;
</script>
