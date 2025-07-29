<!-- Add Policy Modal - Bootstrap 5 Standard -->
<div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPolicyModalLabel">
                    <i class="bx bx-plus-circle me-2"></i>Add New Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPolicyForm" action="include/add-policy-handler.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Customer & Vehicle Information -->
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bx bx-user me-2"></i>Customer & Vehicle Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                    <input type="text" name="vehicle_number" id="vehicle_number" class="form-control text-uppercase" required placeholder="e.g., MH12AB1234">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone" maxlength="10" pattern="[0-9]{10}" class="form-control" required placeholder="10-digit number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control text-uppercase" required placeholder="Customer name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select name="vehicle_type" id="vehicle_type" class="form-select" required>
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
                                    <input type="text" name="chassiss" id="chassiss" class="form-control text-uppercase" placeholder="Chassis number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance & Policy Details -->
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="bx bx-shield me-2"></i>Insurance & Policy Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insurance Company <span class="text-danger">*</span></label>
                                    <select name="insurance_company" id="insurance_company" class="form-select" required>
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
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                                    <select name="policy_type" id="policy_type" class="form-select" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="Comprehensive">Comprehensive</option>
                                        <option value="Third Party">Third Party</option>
                                        <option value="Own Damage">Own Damage</option>
                                        <option value="Full">Full</option>
                                        <option value="Health">Health</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_start_date" id="policy_start_date" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="policy_end_date" id="policy_end_date" class="form-control" required readonly title="Auto-calculated (Start Date + 1 Year - 1 Day)">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Policy Issue Date</label>
                                    <input type="date" name="policy_issue_date" id="policy_issue_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">FC Expiry Date</label>
                                    <input type="date" name="fc_expiry_date" id="fc_expiry_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Permit Expiry Date</label>
                                    <input type="date" name="permit_expiry_date" id="permit_expiry_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="bx bx-money me-2"></i>Financial Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="premium" id="premium" class="form-control" required placeholder="Enter premium amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Payout Amount</label>
                                    <input type="number" step="0.01" name="payout" id="payout" class="form-control" placeholder="Enter payout amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Customer Paid</label>
                                    <input type="number" step="0.01" name="customer_paid" id="customer_paid" class="form-control" placeholder="Amount paid by customer">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" id="discount" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Revenue</label>
                                    <input type="text" id="calculated_revenue" class="form-control" readonly placeholder="Auto-calculated">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Verification -->
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0"><i class="bx bx-id-card me-2"></i>Document Verification</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Aadhar Card Image</label>
                                    <div class="file-input-wrapper">
                                        <input type="file" name="aadhar_card" id="aadhar_card" class="file-input" accept="image/jpeg,image/jpg,image/png">
                                        <label for="aadhar_card" class="file-input-label">
                                            <div class="file-input-icon">
                                                <i class="bx bx-cloud-upload"></i>
                                            </div>
                                            <div class="file-input-text">
                                                <span>Click to upload or drag and drop</span><br>
                                                <small class="text-muted">JPEG, PNG up to 2MB</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="aadhar_preview" class="file-preview-container mt-2"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Card Image</label>
                                    <div class="file-input-wrapper">
                                        <input type="file" name="pan_card" id="pan_card" class="file-input" accept="image/jpeg,image/jpg,image/png">
                                        <label for="pan_card" class="file-input-label">
                                            <div class="file-input-icon">
                                                <i class="bx bx-cloud-upload"></i>
                                            </div>
                                            <div class="file-input-text">
                                                <span>Click to upload or drag and drop</span><br>
                                                <small class="text-muted">JPEG, PNG up to 2MB</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="pan_preview" class="file-preview-container mt-2"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Files -->
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bx bx-file me-2"></i>Additional Files</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Policy Documents</label>
                                    <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Select multiple files if needed</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">RC Documents</label>
                                    <input type="file" name="rc[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Registration Certificate files</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comments</label>
                                <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Any additional comments or notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check me-2"></i>Add Policy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
