<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Edit Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/global.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Test Edit Modal</h2>
        <p>Click the button below to test the edit modal with test data:</p>
        
        <button type="button" class="btn btn-primary" onclick="testEditModal()">
            <i class="fas fa-edit me-2"></i>Test Edit Modal
        </button>
        
        <button type="button" class="btn btn-success" onclick="testEditModalWithTestEndpoint()">
            <i class="fas fa-edit me-2"></i>Test with Test Endpoint
        </button>
        
        <div class="mt-3">
            <h5>Debug Console:</h5>
            <div id="debugOutput" style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; min-height: 100px; white-space: pre-wrap;"></div>
        </div>
    </div>

    <!-- Include the edit modal -->
    <?php include 'include/edit-policy-modal.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/global.js"></script>
    
    <script>
        // Debug function
        function debugLog(message) {
            const output = document.getElementById('debugOutput');
            const timestamp = new Date().toLocaleTimeString();
            output.textContent += `[${timestamp}] ${message}\n`;
            console.log(message);
        }
        
        // Test edit modal with real endpoint
        function testEditModal() {
            debugLog('Testing edit modal with real endpoint...');
            
            // Temporarily modify the AJAX URL for testing
            const originalLoadFunction = window.loadPolicyForEdit;
            
            window.loadPolicyForEdit = function(policyId) {
                debugLog('Loading policy data for edit, ID: ' + policyId);
                
                // Show the edit modal first
                const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
                
                // Show loading state
                showLoadingOverlay('#editPolicyModal .modal-content');
                
                $.ajax({
                    url: 'include/get-policy-data.php',
                    type: 'POST',
                    data: { policy_id: policyId },
                    dataType: 'json',
                    beforeSend: function() {
                        debugLog('Sending AJAX request for policy ID: ' + policyId);
                    },
                    success: function(response) {
                        debugLog('AJAX Success - Response: ' + JSON.stringify(response, null, 2));
                        hideLoadingOverlay('#editPolicyModal .modal-content');
                        
                        if (response && response.success) {
                            debugLog('Policy data loaded successfully');
                            populateEditForm(response.data);
                        } else {
                            debugLog('Failed to load policy data: ' + JSON.stringify(response));
                            const errorMsg = response && response.message ? response.message : 'Unknown error';
                            showAlert('Failed to load policy data: ' + errorMsg, 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        debugLog('AJAX Error: ' + JSON.stringify({
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status
                        }, null, 2));
                        hideLoadingOverlay('#editPolicyModal .modal-content');
                        showAlert('Error loading policy data: ' + error, 'danger');
                    }
                });
            };
            
            // Call with test ID
            loadPolicyForEdit(123);
        }
        
        // Test edit modal with test endpoint
        function testEditModalWithTestEndpoint() {
            debugLog('Testing edit modal with test endpoint...');
            
            // Show the edit modal first
            const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'), {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
            
            // Show loading state
            showLoadingOverlay('#editPolicyModal .modal-content');
            
            $.ajax({
                url: 'include/test-ajax.php',
                type: 'POST',
                data: { policy_id: 123 },
                dataType: 'json',
                beforeSend: function() {
                    debugLog('Sending AJAX request to test endpoint...');
                },
                success: function(response) {
                    debugLog('Test AJAX Success - Response: ' + JSON.stringify(response, null, 2));
                    hideLoadingOverlay('#editPolicyModal .modal-content');
                    
                    if (response && response.success) {
                        debugLog('Test data loaded successfully');
                        populateEditForm(response.data);
                        showAlert('Test data loaded successfully!', 'success');
                    } else {
                        debugLog('Failed to load test data: ' + JSON.stringify(response));
                        showAlert('Failed to load test data', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    debugLog('Test AJAX Error: ' + JSON.stringify({
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    }, null, 2));
                    hideLoadingOverlay('#editPolicyModal .modal-content');
                    showAlert('Error with test endpoint: ' + error, 'danger');
                }
            });
        }
        
        // Initialize debug
        debugLog('Test page loaded. Ready to test edit modal.');
    </script>
</body>
</html>
