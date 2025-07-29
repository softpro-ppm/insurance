<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Policies Management | Softpro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/logo.PNG">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Additional CSS -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .data-table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            margin: 2px;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        .image-preview {
            max-width: 150px;
            max-height: 150px;
            margin: 10px 0;
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            padding: 10px;
            display: none;
        }
        
        .image-preview img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        
        .file-upload-container {
            position: relative;
            margin: 15px 0;
        }
        
        .file-upload-label {
            display: block;
            padding: 12px;
            border: 2px dashed #007bff;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background-color: #f8f9fa;
            border-color: #0056b3;
        }
        
        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        <header id="page-topbar">
            <?php require 'include/header.php'; ?>
        </header>
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <?php require 'include/sidebar.php'; ?>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Policy Management</h4>
                                <p class="mb-0">Manage insurance policies and client information</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-light btn-lg me-2" onclick="exportData()">
                                    <i class="fas fa-download me-2"></i>Export
                                </button>
                                <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                                    <i class="fas fa-plus me-2"></i>Add Policy
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>
                    
                    <!-- Data Table Container -->
                    <div class="data-table-container">
                        <div class="table-responsive">
                            <table id="policiesTable" class="table table-striped table-hover" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Vehicle Number</th>
                                        <th>Client Name</th>
                                        <th>Phone</th>
                                        <th>Vehicle Type</th>
                                        <th>Policy Type</th>
                                        <th>Insurance Company</th>
                                        <th>Premium</th>
                                        <th>Policy Start</th>
                                        <th>Policy End</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
    </div> <!-- layout-wrapper -->

    <!-- Add/Edit Policy Modal -->
    <div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPolicyModalLabel">
                        <i class="fas fa-car me-2"></i>Add New Policy
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="policyForm" enctype="multipart/form-data">
                        <input type="hidden" id="policyId" name="policyId">
                        <input type="hidden" id="formAction" name="formAction" value="add">
                        
                        <div class="row">
                            <!-- Vehicle Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-primary">Vehicle Information</h6>
                                
                                <div class="mb-3">
                                    <label for="vehicleNumber" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="vehicleNumber" name="vehicleNumber" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="vehicleType" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="vehicleType" name="vehicleType" required>
                                        <option value="">Select Vehicle Type</option>
                                        <option value="Two Wheeler">Two Wheeler</option>
                                        <option value="Three Wheeler">Three Wheeler</option>
                                        <option value="Four Wheeler">Four Wheeler</option>
                                        <option value="Commercial Vehicle">Commercial Vehicle</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="engineNumber" class="form-label">Engine Number</label>
                                    <input type="text" class="form-control" id="engineNumber" name="engineNumber">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="chassisNumber" class="form-label">Chassis Number</label>
                                    <input type="text" class="form-control" id="chassisNumber" name="chassisNumber">
                                </div>
                            </div>
                            
                            <!-- Client Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-primary">Client Information</h6>
                                
                                <div class="mb-3">
                                    <label for="clientName" class="form-label">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="clientName" name="clientName" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="clientPhone" name="clientPhone" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientEmail" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="clientEmail" name="clientEmail">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientAddress" class="form-label">Address</label>
                                    <textarea class="form-control" id="clientAddress" name="clientAddress" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Policy Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-success">Policy Information</h6>
                                
                                <div class="mb-3">
                                    <label for="policyType" class="form-label">Policy Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="policyType" name="policyType" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="Comprehensive">Comprehensive</option>
                                        <option value="Third Party">Third Party</option>
                                        <option value="Stand Alone OD">Stand Alone OD</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="insuranceCompany" class="form-label">Insurance Company <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="insuranceCompany" name="insuranceCompany" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="premium" class="form-label">Premium Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="premium" name="premium" step="0.01" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyStartDate" class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="policyStartDate" name="policyStartDate" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyEndDate" class="form-label">Policy End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="policyEndDate" name="policyEndDate" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <!-- Document Upload -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-warning">Document Upload</h6>
                                
                                <!-- Aadhar Card Upload -->
                                <div class="file-upload-container">
                                    <label class="file-upload-label" for="aadharCard">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Upload Aadhar Card
                                        <small class="d-block text-muted mt-1">JPG, PNG, PDF (Max: 5MB)</small>
                                    </label>
                                    <input type="file" id="aadharCard" name="aadharCard" class="file-upload-input" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div id="aadharPreview" class="image-preview">
                                    <img id="aadharImage" src="" alt="Aadhar Card Preview">
                                </div>
                                <div id="aadharError" class="text-danger small"></div>
                                
                                <!-- PAN Card Upload -->
                                <div class="file-upload-container">
                                    <label class="file-upload-label" for="panCard">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Upload PAN Card
                                        <small class="d-block text-muted mt-1">JPG, PNG, PDF (Max: 5MB)</small>
                                    </label>
                                    <input type="file" id="panCard" name="panCard" class="file-upload-input" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div id="panPreview" class="image-preview">
                                    <img id="panImage" src="" alt="PAN Card Preview">
                                </div>
                                <div id="panError" class="text-danger small"></div>
                                
                                <!-- Additional Info -->
                                <div class="mb-3">
                                    <label for="policyNumber" class="form-label">Policy Number</label>
                                    <input type="text" class="form-control" id="policyNumber" name="policyNumber">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" form="policyForm" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Policy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5>Are you sure you want to delete this policy?</h5>
                        <p class="text-muted">
                            This action will permanently delete the policy and all related data including:
                        </p>
                        <ul class="text-start text-muted">
                            <li>Policy information</li>
                            <li>Client details</li>
                            <li>Uploaded documents (Aadhar, PAN cards)</li>
                            <li>Transaction history</li>
                        </ul>
                        <p class="text-danger fw-bold">This action cannot be undone!</p>
                    </div>
                    <input type="hidden" id="deletePolicyId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash me-2"></i>Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Documents Modal -->
    <div class="modal fade" id="viewDocumentsModal" tabindex="-1" aria-labelledby="viewDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDocumentsModalLabel">
                        <i class="fas fa-file-alt me-2"></i>Policy Documents
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="documentsContainer">
                        <!-- Documents will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with Bootstrap 5 styling
            let table = $('#policiesTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "api/policies_data.php",
                    "type": "POST"
                },
                "columns": [
                    { 
                        "data": null,
                        "render": function(data, type, row, meta) {
                            // Calculate global serial number
                            return meta.settings._iDisplayStart + meta.row + 1;
                        },
                        "orderable": false,
                        "searchable": false,
                        "width": "60px"
                    },
                    { "data": "vehicle_number" },
                    { "data": "name" },
                    { "data": "phone" },
                    { "data": "vehicle_type" },
                    { "data": "policy_type" },
                    { "data": "insurance_company" },
                    { 
                        "data": "premium",
                        "render": function(data) {
                            return '₹' + parseFloat(data).toLocaleString('en-IN', {minimumFractionDigits: 2});
                        }
                    },
                    { 
                        "data": "policy_start_date",
                        "render": function(data) {
                            return new Date(data).toLocaleDateString('en-IN');
                        }
                    },
                    { 
                        "data": "policy_end_date",
                        "render": function(data) {
                            return new Date(data).toLocaleDateString('en-IN');
                        }
                    },
                    { 
                        "data": "status",
                        "render": function(data, type, row) {
                            const endDate = new Date(row.policy_end_date);
                            const today = new Date();
                            const diffTime = endDate - today;
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            
                            if (diffDays < 0) {
                                return '<span class="badge bg-danger status-badge">Expired</span>';
                            } else if (diffDays <= 30) {
                                return '<span class="badge bg-warning status-badge">Expiring Soon</span>';
                            } else {
                                return '<span class="badge bg-success status-badge">Active</span>';
                            }
                        }
                    },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary btn-action" onclick="viewDocuments(${row.id})" title="View Documents">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" onclick="editPolicy(${row.id})" title="Edit Policy">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" onclick="deletePolicy(${row.id})" title="Delete Policy">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
                        "orderable": false,
                        "searchable": false,
                        "width": "120px"
                    }
                ],
                "order": [[8, "desc"]], // Order by policy start date (descending)
                "pageLength": 10,
                "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "language": {
                    "search": "Search policies:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ policies",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

            // File upload preview functionality
            function setupFilePreview(inputId, previewId, imageId, errorId) {
                $(document).on('change', '#' + inputId, function(e) {
                    const file = e.target.files[0];
                    const preview = $('#' + previewId);
                    const image = $('#' + imageId);
                    const error = $('#' + errorId);
                    
                    // Clear previous errors
                    error.text('');
                    
                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                        if (!allowedTypes.includes(file.type)) {
                            error.text('Please select a valid file (JPG, PNG, or PDF)');
                            preview.hide();
                            return;
                        }
                        
                        // Validate file size (5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            error.text('File size must be less than 5MB');
                            preview.hide();
                            return;
                        }
                        
                        // Show preview for images
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                image.attr('src', e.target.result);
                                preview.show();
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // For PDF files, show a placeholder
                            image.attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1zaXplPSIxMiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPFBERiBGaWxlPC90ZXh0Pjwvc3ZnPg==');
                            preview.show();
                        }
                    } else {
                        preview.hide();
                    }
                });
            }
            
            // Setup file previews
            setupFilePreview('aadharCard', 'aadharPreview', 'aadharImage', 'aadharError');
            setupFilePreview('panCard', 'panPreview', 'panImage', 'panError');

            // Form submission
            $('#policyForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const action = $('#formAction').val();
                
                $.ajax({
                    url: 'api/policy_operations.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addPolicyModal').modal('hide');
                            showAlert('success', response.message);
                            table.ajax.reload();
                            resetForm();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'An error occurred while processing your request.');
                    }
                });
            });

            // Delete confirmation
            $('#confirmDelete').on('click', function() {
                const policyId = $('#deletePolicyId').val();
                
                $.ajax({
                    url: 'api/policy_operations.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        policyId: policyId
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            showAlert('success', response.message);
                            table.ajax.reload();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'An error occurred while deleting the policy.');
                    }
                });
            });

            // Reset form when modal is hidden
            $('#addPolicyModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        // Helper functions
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('#alertContainer').html(alertHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }

        function resetForm() {
            $('#policyForm')[0].reset();
            $('#policyId').val('');
            $('#formAction').val('add');
            $('#addPolicyModalLabel').html('<i class="fas fa-car me-2"></i>Add New Policy');
            $('.image-preview').hide();
            $('.text-danger').text('');
            $('.form-control').removeClass('is-invalid');
        }

        function editPolicy(policyId) {
            $.ajax({
                url: 'api/policy_operations.php',
                type: 'GET',
                data: { action: 'get', policyId: policyId },
                success: function(response) {
                    if (response.success) {
                        const policy = response.data;
                        
                        // Populate form fields
                        $('#policyId').val(policy.id);
                        $('#formAction').val('edit');
                        $('#vehicleNumber').val(policy.vehicle_number);
                        $('#vehicleType').val(policy.vehicle_type);
                        $('#engineNumber').val(policy.engine_number);
                        $('#chassisNumber').val(policy.chassis_number);
                        $('#clientName').val(policy.name);
                        $('#clientPhone').val(policy.phone);
                        $('#clientEmail').val(policy.email);
                        $('#clientAddress').val(policy.address);
                        $('#policyType').val(policy.policy_type);
                        $('#insuranceCompany').val(policy.insurance_company);
                        $('#premium').val(policy.premium);
                        $('#policyStartDate').val(policy.policy_start_date);
                        $('#policyEndDate').val(policy.policy_end_date);
                        $('#policyNumber').val(policy.policy_number);
                        $('#remarks').val(policy.remarks);
                        
                        // Update modal title
                        $('#addPolicyModalLabel').html('<i class="fas fa-edit me-2"></i>Edit Policy');
                        
                        // Show existing document previews if available
                        if (policy.aadhar_card) {
                            $('#aadharImage').attr('src', 'uploads/documents/' + policy.aadhar_card);
                            $('#aadharPreview').show();
                        }
                        if (policy.pan_card) {
                            $('#panImage').attr('src', 'uploads/documents/' + policy.pan_card);
                            $('#panPreview').show();
                        }
                        
                        $('#addPolicyModal').modal('show');
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while fetching policy data.');
                }
            });
        }

        function deletePolicy(policyId) {
            $('#deletePolicyId').val(policyId);
            $('#deleteModal').modal('show');
        }

        function viewDocuments(policyId) {
            $.ajax({
                url: 'api/policy_operations.php',
                type: 'GET',
                data: { action: 'getDocuments', policyId: policyId },
                success: function(response) {
                    if (response.success) {
                        let documentsHtml = '';
                        const policy = response.data;
                        
                        if (policy.aadhar_card || policy.pan_card) {
                            documentsHtml += '<div class="row">';
                            
                            if (policy.aadhar_card) {
                                documentsHtml += `
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Aadhar Card</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="uploads/documents/${policy.aadhar_card}" 
                                                     class="img-fluid" 
                                                     style="max-height: 300px;" 
                                                     alt="Aadhar Card">
                                                <div class="mt-2">
                                                    <a href="uploads/documents/${policy.aadhar_card}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                            
                            if (policy.pan_card) {
                                documentsHtml += `
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>PAN Card</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="uploads/documents/${policy.pan_card}" 
                                                     class="img-fluid" 
                                                     style="max-height: 300px;" 
                                                     alt="PAN Card">
                                                <div class="mt-2">
                                                    <a href="uploads/documents/${policy.pan_card}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                            
                            documentsHtml += '</div>';
                        } else {
                            documentsHtml = `
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">No Documents Available</h5>
                                    <p class="text-muted">No documents have been uploaded for this policy.</p>
                                </div>
                            `;
                        }
                        
                        $('#documentsContainer').html(documentsHtml);
                        $('#viewDocumentsModal').modal('show');
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while fetching documents.');
                }
            });
        }

        function exportData() {
            window.location.href = 'api/export_policies.php';
        }
    </script>
</body>
</html>
