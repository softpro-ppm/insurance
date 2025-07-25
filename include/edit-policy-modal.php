<!-- Enhanced Edit Policy Modal -->
<div class="modal fade" id="editPolicyModal" tabindex="-1" aria-labelledby="editPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title" id="editPolicyModalLabel">
                    <i class="bx bx-edit me-2"></i>Edit Policy
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editPolicyForm" action="include/edit-policies.php" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="policy_id" id="edit_policy_id">
                    
                    <!-- Customer & Vehicle Information -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-primary"><i class="bx bx-user me-2"></i>Customer & Vehicle Information</h6>
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
                                    <select name="vehicle_type" id="edit_vehicle_type" class="form-select" required>
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
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chassis Number</label>
                                    <input type="text" name="chassiss" id="edit_chassiss" class="form-control uppercase" placeholder="Chassis number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance & Policy Details -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-success"><i class="bx bx-shield me-2"></i>Insurance & Policy Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                                    <select name="insurance_company" id="edit_insurance_company" class="form-select" required>
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
                                    <select name="policy_type" id="edit_policy_type" class="form-select" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="Comprehensive">Comprehensive</option>
                                        <option value="Third Party">Third Party</option>
                                        <option value="Own Damage">Own Damage</option>
                                        <option value="Full">Full</option>
                                        <option value="Health">Health</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_issue_date" id="edit_policy_issue_date" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_start_date" id="edit_policy_start_date" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_end_date" id="edit_policy_end_date" class="form-control" required readonly title="Auto-calculated (Start Date + 1 Year - 1 Day)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial & Additional Details -->
                    <div class="card border mb-4 custom-outline-card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-info"><i class="bx bx-money me-2"></i>Financial & Additional Details</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="premium" id="edit_premium" class="form-control" required placeholder="Enter premium amount">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Payout Amount</label>
                                    <input type="number" step="0.01" name="payout" id="edit_payout" class="form-control" placeholder="Enter payout amount">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Customer Paid</label>
                                    <input type="number" step="0.01" name="customer_paid" id="edit_customer_paid" class="form-control" placeholder="Amount paid by customer">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" id="edit_discount" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Revenue (New Logic)</label>
                                    <input type="text" id="edit_calculated_revenue" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Legacy Revenue</label>
                                    <input type="number" step="0.01" name="revenue" id="edit_legacy_revenue" class="form-control" placeholder="Optional legacy field">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">FC Expiry Date</label>
                                    <input type="date" name="fc_expiry_date" id="edit_fc_expiry_date" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Permit Expiry Date</label>
                                    <input type="date" name="permit_expiry_date" id="edit_permit_expiry_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
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
            <div class="modal-footer border-0" style="background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="bx bx-x me-2"></i>Cancel
                </button>
                <button type="submit" form="editPolicyForm" class="btn btn-primary btn-lg">
                    <i class="bx bx-save me-2"></i>Update Policy
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Same styling as add modal */
.bg-gradient-primary {
    background: #6f42c1 !important;
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
}

.custom-outline-card {
    border-radius: 12px;
    border: 2px solid #e9ecef !important;
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
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Updating Policy...';
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
        document.getElementById('editPolicyModal').addEventListener('hidden.bs.modal', function() {
            // Reset calculated fields
            document.getElementById('edit_discount').value = '';
            document.getElementById('edit_calculated_revenue').value = '';
            
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
            submitBtn.innerHTML = '<i class="bx bx-save me-2"></i>Update Policy';
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
    fetch(`include/get-policy-data.php?id=${policyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const policy = data.policy;
                
                // Populate form fields
                document.getElementById('edit_policy_id').value = policy.id;
                document.getElementById('edit_vehicle_number').value = policy.vehicle_number;
                document.getElementById('edit_phone').value = policy.phone;
                document.getElementById('edit_name').value = policy.name;
                document.getElementById('edit_vehicle_type').value = policy.vehicle_type;
                document.getElementById('edit_chassiss').value = policy.chassiss;
                document.getElementById('edit_insurance_company').value = policy.insurance_company;
                document.getElementById('edit_policy_type').value = policy.policy_type;
                document.getElementById('edit_policy_issue_date').value = policy.policy_issue_date;
                document.getElementById('edit_policy_start_date').value = policy.policy_start_date;
                document.getElementById('edit_policy_end_date').value = policy.policy_end_date;
                document.getElementById('edit_premium').value = policy.premium;
                document.getElementById('edit_legacy_revenue').value = policy.revenue;
                document.getElementById('edit_fc_expiry_date').value = policy.fc_expiry_date;
                document.getElementById('edit_permit_expiry_date').value = policy.permit_expiry_date;
                document.getElementById('edit_comments').value = policy.comments;
                
                // Populate new financial fields (if available)
                if (policy.payout !== null) document.getElementById('edit_payout').value = policy.payout;
                if (policy.customer_paid !== null) document.getElementById('edit_customer_paid').value = policy.customer_paid;
                if (policy.discount !== null) document.getElementById('edit_discount').value = policy.discount;
                if (policy.calculated_revenue !== null) document.getElementById('edit_calculated_revenue').value = policy.calculated_revenue;
                
                // Trigger calculations (need to call the function within DOMContentLoaded scope)
                // So we'll dispatch a custom event
                window.dispatchEvent(new CustomEvent('editModalLoaded'));
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
                modal.show();
            } else {
                alert('Error loading policy data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading policy data');
        });
}
</script>
