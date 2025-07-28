<!-- Delete Confirmation Modal - Bootstrap 5 Standard -->
<div class="modal fade" id="deletePolicyModal" tabindex="-1" aria-labelledby="deletePolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePolicyModalLabel">
                    <i class="bx bx-trash me-2"></i>Delete Policy Confirmation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                
                <h5 class="mb-3">Are you sure you want to delete this client and all related data including documents?</h5>
                
                <div id="deletePolicyInfo" class="alert alert-danger text-start mb-4">
                    <!-- Policy details will be populated here -->
                </div>
                
                <div class="alert alert-warning text-start">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-2"></i>This action will permanently delete:
                    </h6>
                    <ul class="mb-0">
                        <li>Policy record and all associated data</li>
                        <li>Uploaded document files (Aadhar, PAN, RC, etc.)</li>
                        <li>Related financial records</li>
                        <li>All comments and history</li>
                    </ul>
                </div>
                
                <p class="text-danger fw-bold mt-3">
                    <i class="bx bx-error me-2"></i>This action cannot be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bx bx-trash me-2"></i>Yes, Delete Policy
                </button>
            </div>
        </div>
    </div>
</div>
