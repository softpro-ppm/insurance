<!-- Professional Add Policy Modal - Bootstrap 4 Compatible -->
<div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="addPolicyModalLabel">
                    <i class="bx bx-plus-circle mr-2 font-size-18"></i>
                    <span>Add New Policy</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-0">
                <form id="addPolicyForm" action="include/add-policies-fixed.php" method="post" enctype="multipart/form-data">
                    
                    <!-- Progress Bar -->
                    <div class="px-4 py-3 bg-light border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1 text-primary">Policy Information</h6>
                                <p class="mb-0 text-muted small">Fill in the customer and policy details below</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="badge badge-primary badge-pill px-3 py-2">Step 1 of 1</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4">
                        <!-- Customer Information Section -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-3">
                                <h6 class="section-title text-primary mb-1">
                                    <i class="bx bx-user mr-2"></i>Customer Information
                                </h6>
                                <p class="section-subtitle text-muted mb-0">Basic customer and vehicle details</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label required">Vehicle Number</label>
                                    <input type="text" 
                                           name="vehicle_number" 
                                           id="modal_vehicle_number" 
                                           class="form-control form-control-lg" 
                                           required 
                                           placeholder="e.g., MH12AB1234"
                                           autocomplete="off">
                                    <div class="invalid-feedback">Please enter vehicle number</div>
                                </div>
                                
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label required">Phone Number</label>
                                    <input type="tel" 
                                           name="phone" 
                                           maxlength="10" 
                                           class="form-control form-control-lg" 
                                           required 
                                           placeholder="10-digit number"
                                           pattern="[0-9]{10}"
                                           autocomplete="tel">
                                    <div class="invalid-feedback">Please enter valid 10-digit phone number</div>
                                </div>
                                
                                <div class="col-lg-4 col-md-12 mb-3">
                                    <label class="form-label required">Customer Name</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control form-control-lg" 
                                           required 
                                           placeholder="Full customer name"
                                           autocomplete="name">
                                    <div class="invalid-feedback">Please enter customer name</div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label required">Vehicle Type</label>
                                    <select name="vehicle_type" class="form-control form-control-lg" required>
                                        <option value="">Choose Vehicle Type</option>
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
                                    <div class="invalid-feedback">Please select vehicle type</div>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance Details Section -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-3">
                                <h6 class="section-title text-success mb-1">
                                    <i class="bx bx-shield mr-2"></i>Insurance Details
                                </h6>
                                <p class="section-subtitle text-muted mb-0">Policy and coverage information</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label required">Insurance Company</label>
                                    <select name="insurance_company" class="form-control form-control-lg" required>
                                        <option value="">Choose Insurance Company</option>
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
                                    <div class="invalid-feedback">Please select insurance company</div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label required">Policy Type</label>
                                    <select name="policy_type" class="form-control form-control-lg" required>
                                        <option value="">Choose Policy Type</option>
                                        <option value="Comprehensive">Comprehensive</option>
                                        <option value="Third Party">Third Party</option>
                                        <option value="Own Damage">Own Damage</option>
                                        <option value="Full">Full</option>
                                        <option value="Health">Health</option>
                                    </select>
                                    <div class="invalid-feedback">Please select policy type</div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label required">Policy Start Date</label>
                                    <input type="date" 
                                           name="policy_start_date" 
                                           id="modal_policy_start_date" 
                                           class="form-control form-control-lg" 
                                           required>
                                    <div class="invalid-feedback">Please select start date</div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label required">Policy End Date</label>
                                    <input type="date" 
                                           name="policy_end_date" 
                                           id="modal_policy_end_date" 
                                           class="form-control form-control-lg" 
                                           required 
                                           readonly>
                                    <small class="form-text text-muted">Auto-calculated (Start Date + 1 Year - 1 Day)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details Section -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-3">
                                <h6 class="section-title text-info mb-1">
                                    <i class="bx bx-money mr-2"></i>Financial Details
                                </h6>
                                <p class="section-subtitle text-muted mb-0">Premium, payout and revenue information</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label required">Premium Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="premium" 
                                               id="modal_premium" 
                                               class="form-control form-control-lg" 
                                               required 
                                               placeholder="0.00"
                                               min="0">
                                    </div>
                                    <div class="invalid-feedback">Please enter premium amount</div>
                                </div>
                                
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label">Payout Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="payout" 
                                               id="modal_payout" 
                                               class="form-control form-control-lg" 
                                               placeholder="0.00"
                                               min="0">
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label">Customer Paid</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="customer_paid" 
                                               id="modal_customer_paid" 
                                               class="form-control form-control-lg" 
                                               placeholder="0.00"
                                               min="0">
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="text" 
                                               id="modal_discount" 
                                               class="form-control form-control-lg bg-light" 
                                               readonly 
                                               placeholder="Auto-calculated">
                                    </div>
                                    <input type="hidden" name="discount" id="hidden_discount">
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Revenue</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="text" 
                                               id="modal_calculated_revenue" 
                                               class="form-control form-control-lg bg-light" 
                                               readonly 
                                               placeholder="Auto-calculated">
                                    </div>
                                    <input type="hidden" name="calculated_revenue" id="hidden_calculated_revenue">
                                    <small class="form-text text-muted">Formula: Payout - Discount</small>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload & Comments Section -->
                        <div class="form-section">
                            <div class="section-header mb-3">
                                <h6 class="section-title text-warning mb-1">
                                    <i class="bx bx-file mr-2"></i>Additional Information
                                </h6>
                                <p class="section-subtitle text-muted mb-0">Upload documents and add comments</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Policy Files</label>
                                    <input type="file" 
                                           name="files[]" 
                                           class="form-control form-control-lg" 
                                           multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="form-text text-muted">Supported: PDF, JPG, PNG, DOC, DOCX</small>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">RC Files</label>
                                    <input type="file" 
                                           name="rc[]" 
                                           class="form-control form-control-lg" 
                                           multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="form-text text-muted">Registration Certificate documents</small>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Comments</label>
                                    <textarea name="comments" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Additional comments or notes about this policy..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-light btn-lg mr-2" data-dismiss="modal">
                    <i class="bx bx-x mr-1"></i>Cancel
                </button>
                <button type="submit" form="addPolicyForm" class="btn btn-primary btn-lg">
                    <i class="bx bx-check mr-1"></i>Add Policy
                </button>
            </div>
        </div>
    </div>
</div>
                                    <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_start_date" id="modal_policy_start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_end_date" id="modal_policy_end_date" class="form-control" required readonly title="Auto-calculated (Start Date + 1 Year - 1 Day)">
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
                                    <input type="number" step="0.01" name="premium" id="modal_premium" class="form-control" required placeholder="Enter premium amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Payout Amount</label>
                                    <input type="number" step="0.01" name="payout" id="modal_payout" class="form-control" placeholder="Enter payout amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Customer Paid</label>
                                    <input type="number" step="0.01" name="customer_paid" id="modal_customer_paid" class="form-control" placeholder="Amount paid by customer">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" id="modal_discount" class="form-control" readonly placeholder="Auto-calculated">
                                    <input type="hidden" name="discount" id="hidden_discount">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Revenue (New Logic)</label>
                                    <input type="text" id="modal_calculated_revenue" class="form-control" readonly placeholder="Auto-calculated">
                                    <input type="hidden" name="calculated_revenue" id="hidden_calculated_revenue">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Files</label>
                                    <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">RC Files</label>
                                    <input type="file" name="rc[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Comments</label>
                                    <textarea name="comments" class="form-control" rows="2" placeholder="Additional comments or notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer border-0" style="background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                    <i class="bx bx-x mr-2"></i>Cancel
                </button>
                <button type="submit" form="addPolicyForm" class="btn btn-primary btn-lg">
                    <i class="bx bx-check mr-2"></i>Add Policy
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
#modal_profit_loss {
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
    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('modal_policy_start_date').value = today;
    
    // Calculate end date when start date changes
    function calculatePolicyEndDate() {
        const startDateInput = document.getElementById('modal_policy_start_date');
        const endDateInput = document.getElementById('modal_policy_end_date');
        
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);
            endDateInput.value = endDate.toISOString().split('T')[0];
        }
    }
    
    // Initial calculation
    calculatePolicyEndDate();
    
    // Financial calculations
    function calculateFinancials() {
        const premium = parseFloat(document.getElementById('modal_premium').value) || 0;
        const payout = parseFloat(document.getElementById('modal_payout').value) || 0;
        const customerPaid = parseFloat(document.getElementById('modal_customer_paid').value) || 0;
        
        // Calculate discount
        const discount = premium - customerPaid;
        
        // Calculate revenue
        const revenue = payout - discount;
        
        // Update display fields
        document.getElementById('modal_discount').value = discount.toFixed(2);
        document.getElementById('hidden_discount').value = discount.toFixed(2);
        
        document.getElementById('modal_calculated_revenue').value = revenue.toFixed(2);
        document.getElementById('hidden_calculated_revenue').value = revenue.toFixed(2);
        
        console.log('Financial Calculation:', {
            premium: premium,
            payout: payout,
            customerPaid: customerPaid,
            discount: discount,
            revenue: revenue
        });
    }
    
    // Event listeners
    document.getElementById('modal_premium').addEventListener('input', calculateFinancials);
    document.getElementById('modal_payout').addEventListener('input', calculateFinancials);
    document.getElementById('modal_customer_paid').addEventListener('input', calculateFinancials);
    document.getElementById('modal_policy_start_date').addEventListener('change', calculatePolicyEndDate);
    
    // Phone number validation
    document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
    
    // Vehicle number formatting
    document.getElementById('modal_vehicle_number').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
    
    // Form validation and submission
    document.getElementById('addPolicyForm').addEventListener('submit', function(e) {
        // Update calculations before submission
        calculateFinancials();
        
        const phone = document.querySelector('input[name="phone"]').value;
        if (phone.length !== 10) {
            e.preventDefault();
            alert('Please enter a valid 10-digit phone number.');
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-1"></i>Adding Policy...';
        submitBtn.disabled = true;
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // Reset form when modal is closed
    $('#addPolicyModal').on('hidden.bs.modal', function() {
        document.getElementById('addPolicyForm').reset();
        document.getElementById('modal_discount').value = '';
        document.getElementById('modal_calculated_revenue').value = '';
        document.getElementById('hidden_discount').value = '';
        document.getElementById('hidden_calculated_revenue').value = '';
        
        // Reset to today's date
        document.getElementById('modal_policy_start_date').value = today;
        calculatePolicyEndDate();
        
        // Reset submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="bx bx-check mr-1"></i>Add Policy';
        submitBtn.disabled = false;
    });
    
    // Auto-focus first input when modal opens
    $('#addPolicyModal').on('shown.bs.modal', function() {
        document.getElementById('modal_vehicle_number').focus();
    });
});
</script>
