<!-- Enhanced Edit Policy Modal -->
<div class="modal fade" id="editPolicyModal" tabindex="-1" aria-labelledby="editPolicyModalLabel" aria-hidden="true">
<<<<<<< HEAD
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
=======
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
                <h5 class="modal-title" id="editPolicyModalLabel">
                    <i class="bx bx-edit mr-2"></i>Edit Policy
                </h5>
<<<<<<< HEAD
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
=======
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
            </div>
            <div class="modal-body">
                <form id="editPolicyForm" action="include/edit-policies.php" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="policy_id" id="edit_policy_id">
                    
                    <!-- Customer & Vehicle Information -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-primary"><i class="bx bx-user mr-2"></i>Customer & Vehicle Information</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                    <input type="text" name="vehicle_number" id="edit_vehicle_number" class="form-control uppercase" required placeholder="e.g., MH12AB1234">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="edit_phone" maxlength="10" class="form-control" required placeholder="10-digit number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name" class="form-control uppercase" required placeholder="Customer name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select name="vehicle_type" id="edit_vehicle_type" class="form-control" required>
                                        <option value="">Select Vehicle Type</option>
                                        <option value="Two Wheeler">Two Wheeler</option>
                                        <option value="Four Wheeler">Four Wheeler</option>
                                        <option value="Commercial Vehicle">Commercial Vehicle</option>
                                        <option value="Tractor">Tractor</option>
                                        <option value="Three Wheeler">Three Wheeler</option>
                                        <option value="Auto">Auto</option>
                                        <option value="Car">Car</option>
                                        <option value="Trailer">Trailer</option>
                                        <option value="Bolero">Bolero</option>
                                        <option value="Lorry">Lorry</option>
                                        <option value="JCB">JCB</option>
                                        <option value="Bus">Bus</option>
                                        <option value="Misc">Misc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance & Policy Details -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-success"><i class="bx bx-shield mr-2"></i>Insurance & Policy Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                                    <select name="insurance_company" id="edit_insurance_company" class="form-control" required>
                                        <option value="">Select Insurance Company</option>
                                        <option value="HDFC ERGO General Insurance Company Ltd">HDFC ERGO</option>
                                        <option value="ICICI Lombard General Insurance Company Ltd">ICICI Lombard</option>
                                        <option value="Bharti AXA General Insurance Company Ltd">Bharti AXA</option>
                                        <option value="Bajaj Allianz General Insurance Company Ltd">Bajaj Allianz</option>
                                        <option value="Reliance General Insurance Company Ltd">Reliance General</option>
                                        <option value="Tata AIG General Insurance Company Ltd">Tata AIG</option>
                                        <option value="New India Assurance Company Ltd">New India Assurance</option>
                                        <option value="United India Insurance Company Ltd">United India</option>
                                        <option value="National Insurance Company Ltd">National Insurance</option>
                                        <option value="Oriental Insurance Company Ltd">Oriental Insurance</option>
                                        <option value="Cholamandalam MS General Insurance Company Ltd">Cholamandalam MS</option>
                                        <option value="Future Generali India Insurance Company Ltd">Future Generali</option>
                                        <option value="Iffco Tokio General Insurance Company Ltd">Iffco Tokio</option>
                                        <option value="SBI General Insurance Company Ltd">SBI General</option>
                                        <option value="Shriram General Insurance Company Ltd">Shriram General</option>
                                        <option value="Go Digit General Insurance Company Ltd">Go Digit</option>
                                        <option value="Acko General Insurance Company Ltd">Acko General</option>
                                        <option value="Kotak Mahindra General Insurance Company Ltd">Kotak Mahindra</option>
                                        <option value="Zuno General Insurance Company Ltd">Zuno General</option>
                                        <option value="Magma">Magma</option>
                                        <option value="Royal Sundaram">Royal Sundaram</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                                    <select name="policy_type" id="edit_policy_type" class="form-control" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="Comprehensive">Comprehensive</option>
                                        <option value="Third Party">Third Party</option>
                                        <option value="Own Damage">Own Damage</option>
                                        <option value="Full">Full</option>
                                        <option value="Health">Health</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_start_date" id="edit_policy_start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_end_date" id="edit_policy_end_date" class="form-control" required readonly title="Auto-calculated (Start Date + 1 Year - 1 Day)">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy Issue Date</label>
                                    <input type="date" name="policy_issue_date" id="edit_policy_issue_date" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">FC Expiry Date</label>
                                    <input type="date" name="fc_expiry_date" id="edit_fc_expiry_date" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Permit Expiry Date</label>
                                    <input type="date" name="permit_expiry_date" id="edit_permit_expiry_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial & Additional Details -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-info"><i class="bx bx-money mr-2"></i>Financial & Additional Details</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="premium" id="edit_premium" class="form-control" required placeholder="Enter premium amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Payout Amount</label>
                                    <input type="number" step="0.01" name="payout" id="edit_payout" class="form-control" placeholder="Enter payout amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Customer Paid</label>
                                    <input type="number" step="0.01" name="customer_paid" id="edit_customer_paid" class="form-control" placeholder="Amount paid by customer">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" id="edit_discount" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Revenue (New Logic)</label>
                                    <input type="text" id="edit_calculated_revenue" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Files</label>
                                    <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <div id="existing_policy_files" class="mt-2"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">RC Files</label>
                                    <input type="file" name="rc[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <div id="existing_rc_files" class="mt-2"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chassis Number</label>
                                    <input type="text" name="chassiss" id="edit_chassiss" class="form-control uppercase" placeholder="Enter chassis number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-warning"><i class="bx bx-file-blank me-2"></i>Document Verification</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Aadhar Card Image</label>
                                    <div class="file-input-wrapper">
                                        <input type="file" name="aadhar_card" id="edit_aadhar_card" class="form-control" accept=".jpg,.jpeg,.png">
                                        <label for="edit_aadhar_card" class="file-input-label">
                                            <div class="file-input-icon">
                                                <i class="bx bx-cloud-upload"></i>
                                            </div>
                                            <div>
                                                <strong>Click to upload Aadhar Card</strong><br>
                                                <small class="text-muted">Or drag and drop file here</small><br>
                                                <small class="text-muted">Supported: JPEG, PNG (Max 2MB)</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="existing_aadhar_files" class="mt-2"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Card Image</label>
                                    <div class="file-input-wrapper">
                                        <input type="file" name="pan_card" id="edit_pan_card" class="form-control" accept=".jpg,.jpeg,.png">
                                        <label for="edit_pan_card" class="file-input-label">
                                            <div class="file-input-icon">
                                                <i class="bx bx-cloud-upload"></i>
                                            </div>
                                            <div>
                                                <strong>Click to upload PAN Card</strong><br>
                                                <small class="text-muted">Or drag and drop file here</small><br>
                                                <small class="text-muted">Supported: JPEG, PNG (Max 2MB)</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="existing_pan_files" class="mt-2"></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Comments</label>
                                    <textarea name="comments" id="edit_comments" class="form-control" rows="2" placeholder="Additional comments or notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields for calculated values -->
                    <input type="hidden" name="discount" id="edit_hidden_discount">
                    <input type="hidden" name="calculated_revenue" id="edit_hidden_calculated_revenue">

                </form>
            </div>
<<<<<<< HEAD
            <div class="modal-footer border-0" style="background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="bx bx-x mr-2"></i>Cancel
                </button>
                <button type="submit" form="editPolicyForm" class="btn btn-primary btn-lg">
                    <i class="bx bx-save mr-2"></i>Update Policy
=======
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i> Cancel
                </button>
                <button type="submit" form="editPolicyForm" class="btn btn-primary">
                    <i class="bx bx-save"></i> Update Policy
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Professional modal styling - using new color scheme */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    background-color: #ffffff;
    color: #1f2937;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    background-color: #ffffff;
    color: #1f2937;
}

.custom-outline-card {
    border-radius: 12px;
    border: 2px solid #e5e7eb !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    background: #ffffff;
}

.custom-outline-card:hover {
    border-color: #dee2e6 !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.custom-outline-card .card-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.uppercase {
    text-transform: uppercase;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Auto-calculation styling */
#edit_discount, #edit_calculated_revenue {
    font-weight: bold;
    background-color: #f8f9fa !important;
}

/* Loading spinner animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.bx-spin {
    animation: spin 1s linear infinite;
}

/* Clean form styling */
.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

.text-info {
    color: #0dcaf0 !important;
}

/* File display styling */
#existing_policy_files, #existing_rc_files {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px;
    min-height: 35px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

#existing_policy_files .btn, #existing_rc_files .btn {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

#existing_policy_files:empty::after {
    content: 'No policy files uploaded yet';
    color: #6c757d;
    font-size: 0.875rem;
}

#existing_rc_files:empty::after {
    content: 'No RC files uploaded yet'; 
    color: #6c757d;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate financial fields for edit modal
    function calculateEditFinancials() {
        const premium = parseFloat(document.getElementById('edit_premium').value) || 0;
        const payout = parseFloat(document.getElementById('edit_payout').value) || 0;
        const customerPaid = parseFloat(document.getElementById('edit_customer_paid').value) || 0;
        
        // Calculate Discount: Premium - Customer Paid
        const discount = premium - customerPaid;
        
        // Calculate Revenue: Payout - Discount
        const calculatedRevenue = payout - discount;
        
        // Update discount field
        const discountField = document.getElementById('edit_discount');
        discountField.value = discount.toFixed(2);
        
        // Update calculated revenue field
        const revenueField = document.getElementById('edit_calculated_revenue');
        revenueField.value = calculatedRevenue.toFixed(2);
        
        // Update hidden fields for form submission
        document.getElementById('edit_hidden_discount').value = discount.toFixed(2);
        document.getElementById('edit_hidden_calculated_revenue').value = calculatedRevenue.toFixed(2);
        
        // Color coding for discount
        if (discount > 0) {
            discountField.style.backgroundColor = '#f8d7da';
            discountField.style.borderColor = '#dc3545';
            discountField.style.color = '#721c24';
        } else if (discount < 0) {
            discountField.style.backgroundColor = '#d1edff';
            discountField.style.borderColor = '#0dcaf0';
            discountField.style.color = '#055160';
        } else {
            discountField.style.backgroundColor = '#f8f9fa';
            discountField.style.borderColor = '#dee2e6';
            discountField.style.color = '#495057';
        }
        
        // Color coding for calculated revenue
        if (calculatedRevenue > 0) {
            revenueField.style.backgroundColor = '#d1edff';
            revenueField.style.borderColor = '#0dcaf0';
            revenueField.style.color = '#055160';
        } else if (calculatedRevenue < 0) {
            revenueField.style.backgroundColor = '#f8d7da';
            revenueField.style.borderColor = '#dc3545';
            revenueField.style.color = '#721c24';
        } else {
            revenueField.style.backgroundColor = '#f8f9fa';
            revenueField.style.borderColor = '#dee2e6';
            revenueField.style.color = '#495057';
        }
    }

    // Auto-calculate policy end date for edit modal
    function calculateEditPolicyEndDate() {
        const startDateInput = document.getElementById('edit_policy_start_date');
        const endDateInput = document.getElementById('edit_policy_end_date');
        
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);
            
            // Add 1 year and subtract 1 day
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);
            
            // Format date as YYYY-MM-DD
            const formattedDate = endDate.toISOString().split('T')[0];
            endDateInput.value = formattedDate;
        }
    }

    // Event listeners for financial calculations
    if (document.getElementById('edit_premium')) {
        document.getElementById('edit_premium').addEventListener('input', calculateEditFinancials);
        document.getElementById('edit_payout').addEventListener('input', calculateEditFinancials);
        document.getElementById('edit_customer_paid').addEventListener('input', calculateEditFinancials);
    }

    // Event listener for policy date calculation
    if (document.getElementById('edit_policy_start_date')) {
        document.getElementById('edit_policy_start_date').addEventListener('change', calculateEditPolicyEndDate);
    }

    // Phone number validation for edit modal
    if (document.getElementById('edit_phone')) {
        document.getElementById('edit_phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    // Vehicle number formatting for edit modal
    if (document.getElementById('edit_vehicle_number')) {
        document.getElementById('edit_vehicle_number').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Chassis number formatting for edit modal
    if (document.getElementById('edit_chassiss')) {
        document.getElementById('edit_chassiss').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    }

    // Form validation and submission for edit modal
    if (document.getElementById('editPolicyForm')) {
        document.getElementById('editPolicyForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('edit_phone').value;
            if (phone.length !== 10) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number.');
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('#editPolicyModal button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Updating Policy...';
            submitBtn.disabled = true;

            // Reset after 10 seconds if form doesn't submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 10000);
        });
    }

    // Reset modal on close
    if (document.getElementById('editPolicyModal')) {
        $('#editPolicyModal').on('hidden.bs.modal', function() {
            // Reset calculated fields
            document.getElementById('edit_discount').value = '';
            document.getElementById('edit_calculated_revenue').value = '';
            
            // Clear existing files display
            document.getElementById('existing_policy_files').innerHTML = '';
            document.getElementById('existing_rc_files').innerHTML = '';
            
            // Reset field styling
            const resetFields = ['edit_discount', 'edit_calculated_revenue'];
            resetFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.style.backgroundColor = '#f8f9fa';
                field.style.borderColor = '#dee2e6';
                field.style.color = '#495057';
            });
            
            // Reset submit button if it was in loading state
            const submitBtn = document.querySelector('#editPolicyModal button[type="submit"]');
            submitBtn.innerHTML = '<i class="bx bx-save mr-2"></i>Update Policy';
            submitBtn.disabled = false;
        });
    }

    // Listen for the custom event to trigger calculations after modal is loaded
    window.addEventListener('editModalLoaded', function() {
        calculateEditFinancials();
    });
});

// Function to load policy data into edit modal (globally accessible)
function loadPolicyForEdit(policyId) {
    console.log('Loading policy for edit, ID:', policyId);
    
    // Show loading state
    const modal = document.getElementById('editPolicyModal');
    if (!modal) {
        console.error('Edit policy modal not found');
        alert('Edit modal not found. Please refresh the page.');
        return;
    }
    
    // Clear existing form data
    const form = document.getElementById('editPolicyForm');
    if (form) {
        form.reset();
    }
    
    fetch(`include/get-policy-data.php?id=${policyId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (data.success) {
                const policy = data.policy;
                console.log('Policy data:', policy);
                
                // Populate form fields with error checking
                const fields = [
                    { id: 'edit_policy_id', value: policy.id },
                    { id: 'edit_vehicle_number', value: policy.vehicle_number || '' },
                    { id: 'edit_phone', value: policy.phone || '' },
                    { id: 'edit_name', value: policy.name || '' },
                    { id: 'edit_vehicle_type', value: policy.vehicle_type || '' },
                    { id: 'edit_insurance_company', value: policy.insurance_company || '' },
                    { id: 'edit_policy_type', value: policy.policy_type || '' },
                    { id: 'edit_policy_start_date', value: policy.policy_start_date || '' },
                    { id: 'edit_policy_end_date', value: policy.policy_end_date || '' },
                    { id: 'edit_premium', value: policy.premium || '' },
                    { id: 'edit_comments', value: policy.comments || '' }
                ];
                
                // Optional fields
                const optionalFields = [
                    { id: 'edit_policy_issue_date', value: policy.policy_issue_date || '' },
                    { id: 'edit_fc_expiry_date', value: policy.fc_expiry_date || '' },
                    { id: 'edit_permit_expiry_date', value: policy.permit_expiry_date || '' },
                    { id: 'edit_chassiss', value: policy.chassiss || '' },
                    { id: 'edit_payout', value: policy.payout || '' },
                    { id: 'edit_customer_paid', value: policy.customer_paid || '' },
                    { id: 'edit_discount', value: policy.discount || '' },
                    { id: 'edit_calculated_revenue', value: policy.calculated_revenue || '' }
                ];
                
                // Populate required fields
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (element) {
                        element.value = field.value;
                        console.log(`Set ${field.id} = ${field.value}`);
                    } else {
                        console.warn(`Element not found: ${field.id}`);
                    }
                });
                
                // Populate optional fields
                optionalFields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (element && field.value) {
                        element.value = field.value;
                        console.log(`Set ${field.id} = ${field.value}`);
                    }
                });
                
                // Load existing files
                loadExistingFiles(policyId);
                
                // Trigger calculations (need to call the function within DOMContentLoaded scope)
                // So we'll dispatch a custom event
                window.dispatchEvent(new CustomEvent('editModalLoaded'));
                
                // Show modal
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();
                
                console.log('Modal shown successfully');
            } else {
                console.error('Server error:', data.message);
                alert('Error loading policy data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error loading policy data: ' + error.message);
        });
}

// Function to load existing files
function loadExistingFiles(policyId) {
    fetch(`include/get-policy-files.php?id=${policyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display existing policy files
                const policyFilesContainer = document.getElementById('existing_policy_files');
                if (data.policy_files.length > 0) {
                    let policyFilesHtml = '<small class="text-muted">Existing Policy Files:</small><br>';
                    data.policy_files.forEach(file => {
                        policyFilesHtml += `
                            <a href="include/file-download.php?file=${file.filename}" target="_blank" class="btn btn-outline-primary btn-sm mr-1 mb-1">
                                <i class="bx bx-download mr-1"></i>${file.filename}
                            </a>
                        `;
                    });
                    policyFilesContainer.innerHTML = policyFilesHtml;
                } else {
                    policyFilesContainer.innerHTML = '<small class="text-muted">No policy files uploaded yet</small>';
                }
                
                // Display existing RC files
                const rcFilesContainer = document.getElementById('existing_rc_files');
                if (data.rc_files.length > 0) {
                    let rcFilesHtml = '<small class="text-muted">Existing RC Files:</small><br>';
                    data.rc_files.forEach(file => {
                        rcFilesHtml += `
                            <a href="include/file-download.php?file=${file.filename}" target="_blank" class="btn btn-outline-success btn-sm mr-1 mb-1">
                                <i class="bx bx-download mr-1"></i>${file.filename}
                            </a>
                        `;
                    });
                    rcFilesContainer.innerHTML = rcFilesHtml;
                } else {
                    rcFilesContainer.innerHTML = '<small class="text-muted">No RC files uploaded yet</small>';
                }
            } else {
                console.warn('Could not load existing files:', data.message);
                document.getElementById('existing_policy_files').innerHTML = '<small class="text-warning">Could not load existing files</small>';
                document.getElementById('existing_rc_files').innerHTML = '<small class="text-warning">Could not load existing files</small>';
            }
        })
        .catch(error => {
            console.error('Error loading files:', error);
            document.getElementById('existing_policy_files').innerHTML = '<small class="text-danger">Error loading files</small>';
            document.getElementById('existing_rc_files').innerHTML = '<small class="text-danger">Error loading files</small>';
        });
    
    // Load existing documents (Aadhar and PAN cards)
    fetch(`include/get-policy-documents.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `policy_id=${policyId}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear previous document previews
                const aadharPreview = document.getElementById('edit_aadhar_preview');
                const panPreview = document.getElementById('edit_pan_preview');
                
                if (aadharPreview) aadharPreview.innerHTML = '';
                if (panPreview) panPreview.innerHTML = '';
                
                // Display existing Aadhar card
                if (data.documents.aadhar_card) {
                    const aadharDoc = data.documents.aadhar_card;
                    const aadharHtml = `
                        <div class="existing-document-preview">
                            <img src="../${aadharDoc.file_path}" alt="Existing Aadhar Card" class="document-preview-img">
                            <div class="existing-document-info">
                                <small class="text-muted">Current Aadhar Card</small><br>
                                <small class="text-primary">${aadharDoc.file_name}</small>
                            </div>
                        </div>
                    `;
                    if (aadharPreview) aadharPreview.innerHTML = aadharHtml;
                }
                
                // Display existing PAN card
                if (data.documents.pan_card) {
                    const panDoc = data.documents.pan_card;
                    const panHtml = `
                        <div class="existing-document-preview">
                            <img src="../${panDoc.file_path}" alt="Existing PAN Card" class="document-preview-img">
                            <div class="existing-document-info">
                                <small class="text-muted">Current PAN Card</small><br>
                                <small class="text-primary">${panDoc.file_name}</small>
                            </div>
                        </div>
                    `;
                    if (panPreview) panPreview.innerHTML = panHtml;
                }
            }
        })
        .catch(error => {
            console.error('Error loading documents:', error);
        });
}
</script>

<script>
// Make sure the function is globally accessible
window.loadPolicyForEdit = function(policyId) {
    console.log('Loading policy for edit, ID:', policyId);
    
    // Show loading state
    const modal = document.getElementById('editPolicyModal');
    if (!modal) {
        console.error('Edit policy modal not found');
        alert('Edit modal not found. Please refresh the page.');
        return;
    }
    
    // Clear existing form data
    const form = document.getElementById('editPolicyForm');
    if (form) {
        form.reset();
    }
    
    fetch(`include/get-policy-data.php?id=${policyId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (data.success) {
                const policy = data.policy;
                console.log('Policy data:', policy);
                
                // Populate form fields with error checking
                const fields = [
                    { id: 'edit_policy_id', value: policy.id },
                    { id: 'edit_vehicle_number', value: policy.vehicle_number || '' },
                    { id: 'edit_phone', value: policy.phone || '' },
                    { id: 'edit_name', value: policy.name || '' },
                    { id: 'edit_vehicle_type', value: policy.vehicle_type || '' },
                    { id: 'edit_insurance_company', value: policy.insurance_company || '' },
                    { id: 'edit_policy_type', value: policy.policy_type || '' },
                    { id: 'edit_policy_start_date', value: policy.policy_start_date || '' },
                    { id: 'edit_policy_end_date', value: policy.policy_end_date || '' },
                    { id: 'edit_premium', value: policy.premium || '' },
                    { id: 'edit_comments', value: policy.comments || '' }
                ];
                
                // Optional fields
                const optionalFields = [
                    { id: 'edit_policy_issue_date', value: policy.policy_issue_date || '' },
                    { id: 'edit_fc_expiry_date', value: policy.fc_expiry_date || '' },
                    { id: 'edit_permit_expiry_date', value: policy.permit_expiry_date || '' },
                    { id: 'edit_chassiss', value: policy.chassiss || '' },
                    { id: 'edit_payout', value: policy.payout || '' },
                    { id: 'edit_customer_paid', value: policy.customer_paid || '' },
                    { id: 'edit_discount', value: policy.discount || '' },
                    { id: 'edit_calculated_revenue', value: policy.calculated_revenue || '' }
                ];
                
                // Populate required fields
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (element) {
                        element.value = field.value;
                        console.log(`Set ${field.id} = ${field.value}`);
                    } else {
                        console.warn(`Element not found: ${field.id}`);
                    }
                });
                
                // Populate optional fields
                optionalFields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (element && field.value) {
                        element.value = field.value;
                        console.log(`Set ${field.id} = ${field.value}`);
                    }
                });
                
                // Load existing files
                if (typeof loadExistingFiles === 'function') {
                    loadExistingFiles(policyId);
                }
                
                // Trigger calculations (need to call the function within DOMContentLoaded scope)
                // So we'll dispatch a custom event
                window.dispatchEvent(new CustomEvent('editModalLoaded'));
                
                // Show modal
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();
                
                console.log('Modal shown successfully');
            } else {
                console.error('Server error:', data.message);
                alert('Error loading policy data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error loading policy data: ' + error.message);
        });
};

// Also create a local function to ensure it works in all contexts
function loadPolicyForEdit(policyId) {
    window.loadPolicyForEdit(policyId);
}
