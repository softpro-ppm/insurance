<!-- Add Policy Modal -->
<div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addPolicyModalLabel">
                    <i class="bx bx-plus-circle me-2"></i>Add New Policy
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPolicyForm" action="include/add-policies.php" method="post" enctype="multipart/form-data" autocomplete="off">
                    <!-- Step Progress -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="step-progress">
                                <div class="step-item active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Basic Info</div>
                                </div>
                                <div class="step-item" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Policy Details</div>
                                </div>
                                <div class="step-item" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Additional Info</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Basic Information -->
                    <div class="step-content active" data-step="1">
                        <h6 class="mb-3 text-primary"><i class="bx bx-user me-2"></i>Basic Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                <input type="text" name="vehicle_number" id="modal_vehicle_number" class="form-control uppercase" required placeholder="Enter vehicle number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" maxlength="10" class="form-control" required placeholder="Enter 10-digit phone number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control uppercase" required placeholder="Enter full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                <select name="vehicle_type" class="form-control" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Tractor">Tractor</option>
                                    <option value="Two Wheeler">Two Wheeler</option>
                                    <option value="Car">Car</option>
                                    <option value="Trailer">Trailer</option>
                                    <option value="Bolero">Bolero</option>
                                    <option value="Lorry">Lorry</option>
                                    <option value="JCB">JCB</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Person">Person</option>
                                    <option value="Misc">Misc</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Policy Details -->
                    <div class="step-content" data-step="2">
                        <h6 class="mb-3 text-primary"><i class="bx bx-file me-2"></i>Policy Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                                <select name="insurance_company" class="form-control" required>
                                    <option value="">Select Insurance Company</option>
                                    <option value="Tata AIG">Tata AIG</option>
                                    <option value="Magma">Magma</option>
                                    <option value="ICICI Lombard">ICICI Lombard</option>
                                    <option value="Future Generali Insurance">Future Generali Insurance</option>
                                    <option value="Bajaj Allianz">Bajaj Allianz</option>
                                    <option value="SBI General Insurance">SBI General Insurance</option>
                                    <option value="HDFC Ergo">HDFC Ergo</option>
                                    <option value="United India">United India</option>
                                    <option value="New India Insurance">New India Insurance</option>
                                    <option value="Cholamandal">Cholamandal</option>
                                    <option value="Go Digit">Go Digit</option>
                                    <option value="Royal Sundaram">Royal Sundaram</option>
                                    <option value="Reliance">Reliance</option>
                                    <option value="Shriram">Shriram</option>
                                    <option value="Health Insurance">Health Insurance</option>
                                    <option value="Life Insurance">Life Insurance</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                                <select name="policy_type" class="form-control" required>
                                    <option value="">Select Policy Type</option>
                                    <option value="Full">Full</option>
                                    <option value="Third Party">Third Party</option>
                                    <option value="Health">Health</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Policy Issue Date <span class="text-danger">*</span></label>
                                <input type="date" name="policy_issue_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="policy_start_date" id="modal_policy_start_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                <input type="date" name="policy_end_date" id="modal_policy_end_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Premium <span class="text-danger">*</span></label>
                                <input type="number" name="premium" class="form-control" required placeholder="Enter premium amount">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Revenue</label>
                                <input type="number" name="revenue" class="form-control" placeholder="Enter revenue amount">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Additional Information -->
                    <div class="step-content" data-step="3">
                        <h6 class="mb-3 text-primary"><i class="bx bx-detail me-2"></i>Additional Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">FC Expiry Date</label>
                                <input type="date" name="fc_expiry_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permit Expiry Date</label>
                                <input type="date" name="permit_expiry_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Chassis Number</label>
                                <input type="text" name="chassiss" class="form-control" placeholder="Enter chassis number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Documents</label>
                                <input type="file" name="files[]" multiple class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted">Accepted: JPG, PNG, PDF</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RC Copy</label>
                                <input type="file" name="rc[]" multiple class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted">Accepted: JPG, PNG, PDF</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="comments" rows="3" placeholder="Add any remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="prevStep" style="display: none;">
                    <i class="bx bx-chevron-left"></i> Previous
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="nextStep">
                    Next <i class="bx bx-chevron-right"></i>
                </button>
                <button type="submit" form="addPolicyForm" class="btn btn-success" id="submitBtn" style="display: none;">
                    <i class="bx bx-check"></i> Add Policy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal CSS Styles -->
<style>
.step-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.step-progress::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step-title {
    margin-top: 8px;
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.step-item.active .step-number {
    background: #0d6efd;
    color: white;
}

.step-item.active .step-title {
    color: #0d6efd;
}

.step-item.completed .step-number {
    background: #198754;
    color: white;
}

.step-item.completed .step-title {
    color: #198754;
}

.step-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.step-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.modal-xl {
    max-width: 1200px;
}

.form-control.is-invalid {
    border-color: #dc3545;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@media (max-width: 768px) {
    .step-progress {
        flex-direction: column;
        gap: 1rem;
    }
    
    .step-progress::before {
        display: none;
    }
    
    .modal-xl {
        max-width: 95%;
        margin: 10px auto;
    }
}
</style>

<!-- Modal JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;
    
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitBtn');
    
    // Step navigation
    nextBtn?.addEventListener('click', function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStep();
            }
        }
    });
    
    prevBtn?.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });
    
    function updateStep() {
        // Update step indicators
        document.querySelectorAll('.step-item').forEach((item, index) => {
            item.classList.remove('active', 'completed');
            if (index + 1 === currentStep) {
                item.classList.add('active');
            } else if (index + 1 < currentStep) {
                item.classList.add('completed');
            }
        });
        
        // Update step content
        document.querySelectorAll('.step-content').forEach((content, index) => {
            content.classList.remove('active');
            if (index + 1 === currentStep) {
                content.classList.add('active');
            }
        });
        
        // Update buttons
        if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
        if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'block' : 'none';
        if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';
    }
    
    function validateCurrentStep() {
        const currentStepElement = document.querySelector('.step-content.active');
        if (!currentStepElement) return true;
        
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('is-invalid');
                setTimeout(() => field.classList.remove('is-invalid'), 3000);
                return false;
            }
        }
        return true;
    }
    
    // Auto-calculate policy end date
    const startDateField = document.getElementById('modal_policy_start_date');
    if (startDateField) {
        startDateField.addEventListener('change', function() {
            const startDate = new Date(this.value);
            if (startDate) {
                const endDate = new Date(startDate);
                endDate.setFullYear(endDate.getFullYear() + 1);
                endDate.setDate(endDate.getDate() - 1);
                
                const formattedDate = endDate.toISOString().split('T')[0];
                const endDateField = document.getElementById('modal_policy_end_date');
                if (endDateField) {
                    endDateField.value = formattedDate;
                }
            }
        });
    }
    
    // Vehicle number validation
    const vehicleNumberField = document.getElementById('modal_vehicle_number');
    if (vehicleNumberField) {
        vehicleNumberField.addEventListener('change', function() {
            const vehicleNumber = this.value.trim();
            if (vehicleNumber) {
                // Check if vehicle already exists
                fetch('include/check-data.php?vehicle_number=' + encodeURIComponent(vehicleNumber))
                .then(response => response.text())
                .then(data => {
                    if (data.trim()) {
                        if (confirm('Policy already exists for this vehicle. Do you want to renew?')) {
                            window.location.href = 'edit.php?id=' + data.trim();
                        } else {
                            this.value = '';
                            this.focus();
                        }
                    }
                });
            }
        });
    }
    
    // Phone number validation
    const phoneField = document.querySelector('#addPolicyModal input[name="phone"]');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // Uppercase inputs
    document.querySelectorAll('#addPolicyModal .uppercase').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
    
    // Reset form when modal is closed
    const modalElement = document.getElementById('addPolicyModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('addPolicyForm');
            if (form) {
                form.reset();
                currentStep = 1;
                updateStep();
            }
        });
    }
    
    // Form submission
    const formElement = document.getElementById('addPolicyForm');
    if (formElement) {
        formElement.addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> Adding...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
