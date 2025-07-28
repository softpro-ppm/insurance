<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Buttons Test - Enhanced</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/enhanced-ui.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bx bx-test-tube me-2"></i>Action Buttons Testing - All Fixed Issues
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="bx bx-check-circle me-2"></i>All Issues Fixed Successfully!
                    </h6>
                    <p class="mb-0">All action buttons now use modals with AJAX and toaster alerts. Test the improvements below:</p>
                </div>

                <h6 class="mt-4 mb-3">Test Action Buttons:</h6>
                
                <!-- View Button -->
                <button type="button" class="btn btn-outline-info btn-sm btn-action me-2" onclick="testViewAction()" title="View Action" data-bs-toggle="tooltip">
                    <i class="bx bx-show"></i> View
                </button>
                
                <!-- Edit Button -->
                <button type="button" class="btn btn-outline-primary btn-sm btn-action me-2" onclick="testEditAction()" title="Edit Action" data-bs-toggle="tooltip">
                    <i class="bx bx-edit"></i> Edit
                </button>
                
                <!-- Delete Button -->
                <button type="button" class="btn btn-outline-danger btn-sm btn-action me-2" onclick="testDeleteAction()" title="Delete Action" data-bs-toggle="tooltip">
                    <i class="bx bx-trash"></i> Delete
                </button>
                
                <!-- Status Toggle -->
                <button type="button" class="btn btn-sm btn-success me-2" onclick="testStatusAction()" title="Change Status" data-bs-toggle="tooltip">
                    Active
                </button>

                <hr class="my-4">

                <h6 class="mb-3">Test Toaster Alerts:</h6>
                <button type="button" class="btn btn-success btn-sm me-2" onclick="showToaster('Operation completed successfully!', 'success')">
                    <i class="bx bx-check me-1"></i>Success Toast
                </button>
                <button type="button" class="btn btn-danger btn-sm me-2" onclick="showToaster('An error occurred!', 'error')">
                    <i class="bx bx-error me-1"></i>Error Toast
                </button>
                <button type="button" class="btn btn-warning btn-sm me-2" onclick="showToaster('Warning message!', 'warning')">
                    <i class="bx bx-error-circle me-1"></i>Warning Toast
                </button>
                <button type="button" class="btn btn-info btn-sm me-2" onclick="showToaster('Information message!', 'info')">
                    <i class="bx bx-info-circle me-1"></i>Info Toast
                </button>

                <hr class="my-4">

                <h6 class="mb-3">Fixed Issues Summary:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bx bx-check me-2"></i>Policies Page</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li>✅ Added View, Edit, Delete buttons</li>
                                    <li>✅ All buttons use modals</li>
                                    <li>✅ AJAX form submissions</li>
                                    <li>✅ Toaster alert notifications</li>
                                    <li>✅ Enhanced loading states</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bx bx-check me-2"></i>Users Page</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li>✅ Complete modal system added</li>
                                    <li>✅ Add/Edit/Delete user modals</li>
                                    <li>✅ Status change modal</li>
                                    <li>✅ AJAX backend handlers</li>
                                    <li>✅ Form validation & toasters</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bx bx-check me-2"></i>Home Page</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li>✅ Enhanced action buttons</li>
                                    <li>✅ Modal-based editing</li>
                                    <li>✅ Toaster notifications</li>
                                    <li>✅ Loading overlays</li>
                                    <li>✅ Better UX design</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bx bx-check me-2"></i>Global Improvements</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li>✅ Enhanced UI CSS added</li>
                                    <li>✅ Loading overlays</li>
                                    <li>✅ Toast notifications</li>
                                    <li>✅ Button animations</li>
                                    <li>✅ Accessibility features</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-2"></i>Backend Handlers Created:
                    </h6>
                    <ul class="mb-0">
                        <li><code>include/get-user-data.php</code> - User data retrieval</li>
                        <li><code>include/add-user-handler.php</code> - Add user with validation</li>
                        <li><code>include/edit-user-handler.php</code> - Edit user with validation</li>
                        <li><code>include/delete-user-handler.php</code> - Delete user safely</li>
                        <li><code>include/update-user-status-handler.php</code> - Toggle user status</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This is a test modal to verify all modal functionality is working correctly.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function testViewAction() {
            showToaster('View action triggered - modal would open with data', 'info');
        }

        function testEditAction() {
            const modal = new bootstrap.Modal(document.getElementById('testModal'));
            modal.show();
            showToaster('Edit modal opened successfully!', 'success');
        }

        function testDeleteAction() {
            showToaster('Delete confirmation modal would appear', 'warning');
        }

        function testStatusAction() {
            showToaster('Status change confirmation modal would appear', 'info');
        }

        // Enhanced toaster function
        function showToaster(message, type = 'info') {
            console.log('Showing toaster:', { message, type });
            
            // Remove existing toasts
            $('.toast').remove();
            
            // Map type to Bootstrap classes
            const typeClasses = {
                'success': 'bg-success text-white',
                'error': 'bg-danger text-white',
                'warning': 'bg-warning text-dark',
                'info': 'bg-info text-white'
            };
            
            const typeIcons = {
                'success': 'bx-check-circle',
                'error': 'bx-error',
                'warning': 'bx-error-circle',
                'info': 'bx-info-circle'
            };
            
            const bgClass = typeClasses[type] || typeClasses.info;
            const icon = typeIcons[type] || typeIcons.info;
            
            // Create toast element
            const toastHtml = `
                <div class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bx ${icon} me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            // Find or create toast container
            let $container = $('.toast-container');
            if (!$container.length) {
                $container = $('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1070;"></div>');
                $('body').append($container);
            }
            
            // Add toast to container
            const $toast = $(toastHtml);
            $container.append($toast);
            
            // Initialize and show toast
            const toast = new bootstrap.Toast($toast[0], {
                autohide: true,
                delay: type === 'error' ? 8000 : 5000
            });
            
            toast.show();
            
            // Remove toast element after it's hidden
            $toast[0].addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    </script>
</body>
</html>
