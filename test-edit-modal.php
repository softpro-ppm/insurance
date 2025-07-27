<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Modal Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Modal Test Page</h1>
        
        <div class="card">
            <div class="card-body">
                <h5>Test Edit Modal Functionality</h5>
                <p>Click the button below to test if the edit modal works correctly:</p>
                
                <button type="button" class="btn btn-primary" onclick="window.testEditModal(1)">
                    Test Edit Modal (Policy ID: 1)
                </button>
                
                <button type="button" class="btn btn-secondary" onclick="window.testEditModal(2)">
                    Test Edit Modal (Policy ID: 2)
                </button>
                
                <button type="button" class="btn btn-info" onclick="console.log('Available functions:', {openEditModal: typeof window.openEditModal, testEditModal: typeof window.testEditModal})">
                    Check Functions
                </button>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5>Debug Information</h5>
                <p>Open browser console (F12) to see detailed logs.</p>
                <pre id="debug-info"></pre>
            </div>
        </div>
    </div>

    <!-- Include the modal -->
    <?php include 'include/edit-policy-modal.php'; ?>

    <!-- Include scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/edit-policy-modal.js"></script>
    
    <script>
        // Additional debug information
        setTimeout(() => {
            const debugInfo = {
                modalExists: !!document.getElementById('editPolicyModal'),
                openEditModalFunction: typeof window.openEditModal,
                testEditModalFunction: typeof window.testEditModal,
                jQueryLoaded: typeof $ !== 'undefined',
                bootstrapLoaded: typeof bootstrap !== 'undefined'
            };
            
            document.getElementById('debug-info').textContent = JSON.stringify(debugInfo, null, 2);
            console.log('Debug Info:', debugInfo);
        }, 1000);
    </script>
</body>
</html>
