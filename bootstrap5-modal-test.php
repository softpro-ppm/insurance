<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bootstrap 5 Modal Test | Softpro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/logo.PNG">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding: 50px 0;
        }
        
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .demo-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .btn-demo {
            margin: 10px;
            min-width: 200px;
        }
    </style>
</head>

<body>
    <div class="test-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-cogs me-3"></i>Bootstrap 5 Modal Demonstrations</h1>
            <p class="mb-0">Testing all modal components with proper Bootstrap 5 standards</p>
        </div>
        
        <!-- Demo Buttons -->
        <div class="demo-card">
            <h3 class="mb-4">Modal Types</h3>
            <div class="row text-center">
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-demo" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i>Add Modal
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-warning btn-demo" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fas fa-edit me-2"></i>Edit Modal
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-demo" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Delete Modal
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info btn-demo" data-bs-toggle="modal" data-bs-target="#viewModal">
                        <i class="fas fa-eye me-2"></i>View Modal
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Features List -->
        <div class="demo-card">
            <h3 class="mb-4">✅ Features Implemented</h3>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Bootstrap 5.3.3 Latest</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Standard Modal Structure</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Responsive Design</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Accessibility Features</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Form Validation</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>File Upload with Preview</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>DataTables Integration</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Global Serial Numbers</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Server-side Processing</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Complete CRUD Operations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addModalLabel">
                        <i class="fas fa-plus me-2"></i>Add New Client
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" placeholder="Enter email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" placeholder="Enter phone number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Policy Type <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">Select Policy Type</option>
                                        <option value="Life Insurance">Life Insurance</option>
                                        <option value="Health Insurance">Health Insurance</option>
                                        <option value="Motor Insurance">Motor Insurance</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This is a demonstration of Bootstrap 5 standard modal structure with proper form components.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Client
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Client Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" class="form-control" value="John Doe (Pre-filled)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="john.doe@example.com">
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Edit modal with pre-filled data demonstration.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update Client
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
                    <h5>Are you sure you want to delete this client?</h5>
                    <p class="text-muted">This action will permanently delete:</p>
                    <ul class="text-start text-muted">
                        <li>Client information</li>
                        <li>Policy details</li>
                        <li>Uploaded documents</li>
                        <li>Transaction history</li>
                    </ul>
                    <p class="text-danger fw-bold">This action cannot be undone!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewModalLabel">
                        <i class="fas fa-eye me-2"></i>Client Documents
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Aadhar Card</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="bg-light p-4 rounded">
                                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-2 text-muted">Document Preview</p>
                                    </div>
                                    <button class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-download me-1"></i>Download
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>PAN Card</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="bg-light p-4 rounded">
                                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="mt-2 text-muted">Document Preview</p>
                                    </div>
                                    <button class="btn btn-sm btn-success mt-2">
                                        <i class="fas fa-download me-1"></i>Download
                                    </button>
                                </div>
                            </div>
                        </div>
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
    
    <script>
        $(document).ready(function() {
            // Modal event handlers
            $('.modal').on('shown.bs.modal', function(e) {
                console.log('Modal opened:', $(this).attr('id'));
            });
            
            $('.modal').on('hidden.bs.modal', function(e) {
                console.log('Modal closed:', $(this).attr('id'));
            });
            
            // Demo functionality
            $('button[data-bs-dismiss="modal"]').on('click', function() {
                console.log('Modal dismissed');
            });
        });
    </script>
</body>
</html>
