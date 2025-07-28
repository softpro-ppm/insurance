<!DOCTYPE html>
<html>
<head>
    <title>Modal Test Page</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f8f9fa; }
        .test-card { background: white; border-radius: 10px; padding: 20px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-btn { margin: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Insurance Modal Test Page</h1>
        
        <div class="test-card">
            <h3>Modal Tests</h3>
            <p>Use the buttons below to test the modal functionality:</p>
            
            <button type="button" class="btn btn-primary test-btn" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                <i class="bx bx-plus-circle"></i> Test Add Policy Modal
            </button>
            
            <button type="button" class="btn btn-success test-btn" onclick="testViewPolicy(1)">
                <i class="bx bx-eye"></i> Test View Policy Modal
            </button>
            
            <button type="button" class="btn btn-warning test-btn" onclick="window.testEditModal ? window.testEditModal(1) : alert('Edit modal script not loaded')">
                <i class="bx bx-edit"></i> Test Edit Policy Modal
            </button>
            
            <button type="button" class="btn btn-info test-btn" onclick="window.testConnectivity ? window.testConnectivity() : alert('Connectivity test not available')">
                <i class="bx bx-wifi"></i> Test Connectivity
            </button>
        </div>
        
        <div class="test-card">
            <h3>Debug Information</h3>
            <div id="debug-info">
                <p>Loading debug information...</p>
            </div>
        </div>
    </div>

    <!-- Include the same modals as home.php -->
    <div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true" id="renewalpolicyview">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="viewpolicydata"></div>
        </div>
    </div>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>

    <!-- Include same scripts as home.php -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/edit-policy-modal-fixed.js"></script>

    <script>
        // Test view policy function (same as home.php)
        function testViewPolicy(policyId = 1) {
            console.log('=== TESTING VIEW POLICY MODAL ===');
            console.log('Policy ID:', policyId);
            
            $('#viewpolicydata').html(`
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">
                        <i class="bx bx-file-blank me-2"></i>Loading Policy Details...
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Please wait while we fetch the policy details...</p>
                </div>
            `);
            
            $('#renewalpolicyview').modal('show');
            
            $.ajax({
                url: "include/view-policy.php",
                type: "POST",
                data: { id: policyId },
                timeout: 10000,
                success: function(data) {
                    console.log('AJAX response received:', data.substring(0, 100));
                    if (data && data.trim().length > 0) {
                        $('#viewpolicydata').html(data);
                    } else {
                        $('#viewpolicydata').html(`
                            <div class="modal-header bg-danger text-white border-0">
                                <h5 class="modal-title">
                                    <i class="bx bx-error me-2"></i>Error
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <i class="bx bx-error-circle me-2"></i>
                                    No data received from server. Please try again.
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX call failed:', error);
                    $('#viewpolicydata').html(`
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title">
                                <i class="bx bx-error me-2"></i>Connection Error
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                <i class="bx bx-error-circle me-2"></i>
                                <strong>Error loading policy details:</strong><br>
                                ${error === 'timeout' ? 'Request timed out. Please try again.' : 'Connection error. Please check your internet connection and try again.'}
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Load debug information
        $(document).ready(function() {
            const debugInfo = document.getElementById('debug-info');
            
            let info = `
                <strong>jQuery:</strong> ${typeof $ !== 'undefined' ? '✅ Loaded (v' + $.fn.jquery + ')' : '❌ Not loaded'}<br>
                <strong>Bootstrap:</strong> ${typeof bootstrap !== 'undefined' ? '✅ Loaded' : '❌ Not loaded'}<br>
                <strong>Edit Modal Function:</strong> ${typeof window.openEditModal === 'function' ? '✅ Available' : '❌ Not available'}<br>
                <strong>Test Functions:</strong><br>
                &nbsp;&nbsp;- testEditModal: ${typeof window.testEditModal === 'function' ? '✅' : '❌'}<br>
                &nbsp;&nbsp;- testConnectivity: ${typeof window.testConnectivity === 'function' ? '✅' : '❌'}<br>
                &nbsp;&nbsp;- testAPI: ${typeof window.testAPI === 'function' ? '✅' : '❌'}<br>
                <strong>Modals in DOM:</strong><br>
                &nbsp;&nbsp;- Add Policy Modal: ${document.getElementById('addPolicyModal') ? '✅' : '❌'}<br>
                &nbsp;&nbsp;- Edit Policy Modal: ${document.getElementById('editPolicyModal') ? '✅' : '❌'}<br>
                &nbsp;&nbsp;- View Policy Modal: ${document.getElementById('renewalpolicyview') ? '✅' : '❌'}<br>
            `;
            
            debugInfo.innerHTML = info;
            
            console.log('Modal test page loaded successfully');
        });
    </script>
</body>
</html>
