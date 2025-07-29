<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Client Management | Softpro</title>
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
    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-content {
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
        
        .table-actions {
            white-space: nowrap;
        }
        
        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        .alert-dismissible {
            margin-bottom: 20px;
        }
        
        #clientsTable_wrapper .row:first-child {
            margin-bottom: 20px;
        }
        
        .dataTables_length select {
            min-width: 80px;
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
                                <h4 class="mb-1">Client & Policy Management</h4>
                                <p class="mb-0">Manage clients, policies, and documents</p>
                            </div>
                            <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="fas fa-plus me-2"></i>Add New Client
                            </button>
                        </div>
                    </div>
                    
                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>
                    
                    <!-- Data Table Container -->
                    <div class="data-table-container">
                        <div class="table-responsive">
                            <table id="clientsTable" class="table table-striped table-hover" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Client Name</th>
                                        <th>Policy Number</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Policy Type</th>
                                        <th>Premium Amount</th>
                                        <th>Status</th>
                                        <th>Documents</th>
                                        <th>Created Date</th>
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

    <!-- Add/Edit Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Add New Client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clientForm" enctype="multipart/form-data">
                        <input type="hidden" id="clientId" name="clientId">
                        <input type="hidden" id="formAction" name="formAction" value="add">
                        
                        <div class="row">
                            <!-- Client Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Client Information</h6>
                                
                                <div class="mb-3">
                                    <label for="clientName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="clientName" name="clientName" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="clientEmail" name="clientEmail" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="clientPhone" name="clientPhone" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientAddress" class="form-label">Address</label>
                                    <textarea class="form-control" id="clientAddress" name="clientAddress" rows="3"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="clientDob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="clientDob" name="clientDob">
                                </div>
                            </div>
                            
                            <!-- Policy Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Policy Information</h6>
                                
                                <div class="mb-3">
                                    <label for="policyNumber" class="form-label">Policy Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="policyNumber" name="policyNumber" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyType" class="form-label">Policy Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="policyType" name="policyType" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="Life Insurance">Life Insurance</option>
                                        <option value="Health Insurance">Health Insurance</option>
                                        <option value="Motor Insurance">Motor Insurance</option>
                                        <option value="Home Insurance">Home Insurance</option>
                                        <option value="Travel Insurance">Travel Insurance</option>
                                        <option value="Business Insurance">Business Insurance</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="premiumAmount" class="form-label">Premium Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="premiumAmount" name="premiumAmount" step="0.01" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyStatus" class="form-label">Policy Status</label>
                                    <select class="form-select" id="policyStatus" name="policyStatus">
                                        <option value="Active">Active</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyStartDate" class="form-label">Policy Start Date</label>
                                    <input type="date" class="form-control" id="policyStartDate" name="policyStartDate">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="policyEndDate" class="form-label">Policy End Date</label>
                                    <input type="date" class="form-control" id="policyEndDate" name="policyEndDate">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Document Upload Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Document Upload</h6>
                            </div>
                            
                            <!-- Aadhar Card Upload -->
                            <div class="col-md-6">
                                <div class="file-upload-container">
                                    <label class="file-upload-label" for="aadharCard">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Upload Aadhar Card Image
                                        <small class="d-block text-muted mt-1">JPG, PNG, PDF (Max: 5MB)</small>
                                    </label>
                                    <input type="file" id="aadharCard" name="aadharCard" class="file-upload-input" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div id="aadharPreview" class="image-preview">
                                    <img id="aadharImage" src="" alt="Aadhar Card Preview">
                                </div>
                                <div id="aadharError" class="text-danger small"></div>
                            </div>
                            
                            <!-- PAN Card Upload -->
                            <div class="col-md-6">
                                <div class="file-upload-container">
                                    <label class="file-upload-label" for="panCard">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Upload PAN Card Image
                                        <small class="d-block text-muted mt-1">JPG, PNG, PDF (Max: 5MB)</small>
                                    </label>
                                    <input type="file" id="panCard" name="panCard" class="file-upload-input" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div id="panPreview" class="image-preview">
                                    <img id="panImage" src="" alt="PAN Card Preview">
                                </div>
                                <div id="panError" class="text-danger small"></div>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" form="clientForm" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Client
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
                        <h5>Are you sure you want to delete this client?</h5>
                        <p class="text-muted">
                            This action will permanently delete the client and all related data including:
                        </p>
                        <ul class="text-start text-muted">
                            <li>Client information</li>
                            <li>Policy details</li>
                            <li>Uploaded documents (Aadhar, PAN cards)</li>
                            <li>All transaction history</li>
                        </ul>
                        <p class="text-danger fw-bold">This action cannot be undone!</p>
                    </div>
                    <input type="hidden" id="deleteClientId" value="">
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
                        <i class="fas fa-file-alt me-2"></i>Client Documents
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
            let table = $('#clientsTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "api/clients_data.php",
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
                    { "data": "client_name" },
                    { "data": "policy_number" },
                    { "data": "email" },
                    { "data": "phone" },
                    { "data": "policy_type" },
                    { 
                        "data": "premium_amount",
                        "render": function(data) {
                            return '₹' + parseFloat(data).toLocaleString('en-IN', {minimumFractionDigits: 2});
                        }
                    },
                    { 
                        "data": "status",
                        "render": function(data) {
                            let badgeClass = '';
                            switch(data) {
                                case 'Active': badgeClass = 'bg-success'; break;
                                case 'Pending': badgeClass = 'bg-warning'; break;
                                case 'Expired': badgeClass = 'bg-secondary'; break;
                                case 'Cancelled': badgeClass = 'bg-danger'; break;
                                default: badgeClass = 'bg-info';
                            }
                            return '<span class="badge ' + badgeClass + ' status-badge">' + data + '</span>';
                        }
                    },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            let docs = [];
                            if (row.aadhar_card) docs.push('<i class="fas fa-id-card text-primary" title="Aadhar Card"></i>');
                            if (row.pan_card) docs.push('<i class="fas fa-credit-card text-success" title="PAN Card"></i>');
                            return docs.length > 0 ? docs.join(' ') : '<i class="fas fa-minus text-muted"></i>';
                        },
                        "orderable": false,
                        "searchable": false
                    },
                    { 
                        "data": "created_at",
                        "render": function(data) {
                            return new Date(data).toLocaleDateString('en-IN');
                        }
                    },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                                <div class="table-actions">
                                    <button class="btn btn-outline-primary btn-action" onclick="viewDocuments(${row.id})" title="View Documents">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" onclick="editClient(${row.id})" title="Edit Client">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" onclick="deleteClient(${row.id})" title="Delete Client">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        },
                        "orderable": false,
                        "searchable": false,
                        "width": "150px"
                    }
                ],
                "order": [[9, "desc"]], // Order by created_at column (descending)
                "pageLength": 10,
                "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "language": {
                    "search": "Search clients:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ clients",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> Export PDF',
                        className: 'btn btn-danger btn-sm'
                    }
                ]
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
            $('#clientForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const action = $('#formAction').val();
                
                $.ajax({
                    url: 'api/client_operations.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addClientModal').modal('hide');
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
                const clientId = $('#deleteClientId').val();
                
                $.ajax({
                    url: 'api/client_operations.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        clientId: clientId
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
                        showAlert('danger', 'An error occurred while deleting the client.');
                    }
                });
            });

            // Reset form when modal is hidden
            $('#addClientModal').on('hidden.bs.modal', function() {
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
            $('#clientForm')[0].reset();
            $('#clientId').val('');
            $('#formAction').val('add');
            $('#addClientModalLabel').html('<i class="fas fa-user-plus me-2"></i>Add New Client');
            $('.image-preview').hide();
            $('.text-danger').text('');
            $('.form-control').removeClass('is-invalid');
        }

        function editClient(clientId) {
            $.ajax({
                url: 'api/client_operations.php',
                type: 'GET',
                data: { action: 'get', clientId: clientId },
                success: function(response) {
                    if (response.success) {
                        const client = response.data;
                        
                        // Populate form fields
                        $('#clientId').val(client.id);
                        $('#formAction').val('edit');
                        $('#clientName').val(client.client_name);
                        $('#clientEmail').val(client.email);
                        $('#clientPhone').val(client.phone);
                        $('#clientAddress').val(client.address);
                        $('#clientDob').val(client.dob);
                        $('#policyNumber').val(client.policy_number);
                        $('#policyType').val(client.policy_type);
                        $('#premiumAmount').val(client.premium_amount);
                        $('#policyStatus').val(client.status);
                        $('#policyStartDate').val(client.policy_start_date);
                        $('#policyEndDate').val(client.policy_end_date);
                        
                        // Update modal title
                        $('#addClientModalLabel').html('<i class="fas fa-user-edit me-2"></i>Edit Client');
                        
                        // Show existing document previews if available
                        if (client.aadhar_card) {
                            $('#aadharImage').attr('src', 'uploads/documents/' + client.aadhar_card);
                            $('#aadharPreview').show();
                        }
                        if (client.pan_card) {
                            $('#panImage').attr('src', 'uploads/documents/' + client.pan_card);
                            $('#panPreview').show();
                        }
                        
                        $('#addClientModal').modal('show');
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while fetching client data.');
                }
            });
        }

        function deleteClient(clientId) {
            $('#deleteClientId').val(clientId);
            $('#deleteModal').modal('show');
        }

        function viewDocuments(clientId) {
            $.ajax({
                url: 'api/client_operations.php',
                type: 'GET',
                data: { action: 'getDocuments', clientId: clientId },
                success: function(response) {
                    if (response.success) {
                        let documentsHtml = '';
                        const client = response.data;
                        
                        if (client.aadhar_card || client.pan_card) {
                            documentsHtml += '<div class="row">';
                            
                            if (client.aadhar_card) {
                                documentsHtml += `
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Aadhar Card</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="uploads/documents/${client.aadhar_card}" 
                                                     class="img-fluid" 
                                                     style="max-height: 300px;" 
                                                     alt="Aadhar Card">
                                                <div class="mt-2">
                                                    <a href="uploads/documents/${client.aadhar_card}" 
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
                            
                            if (client.pan_card) {
                                documentsHtml += `
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>PAN Card</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="uploads/documents/${client.pan_card}" 
                                                     class="img-fluid" 
                                                     style="max-height: 300px;" 
                                                     alt="PAN Card">
                                                <div class="mt-2">
                                                    <a href="uploads/documents/${client.pan_card}" 
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
                                    <p class="text-muted">No documents have been uploaded for this client.</p>
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
    </script>
</body>
</html>
