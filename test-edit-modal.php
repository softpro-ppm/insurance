<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Modal Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/global.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5>Edit Modal Test</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" onclick="testEditModal()">
                    Test Edit Modal with Policy ID 1
                </button>
                <button type="button" class="btn btn-secondary ms-2" onclick="testDataFetch()">
                    Test Data Fetch Only
                </button>
                
                <div id="testResults" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Include the edit modal -->
    <?php include 'include/edit-policy-modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function testEditModal() {
            console.log('Testing edit modal...');
            if (typeof loadPolicyForEdit === 'function') {
                loadPolicyForEdit(1);
            } else {
                alert('loadPolicyForEdit function not found!');
            }
        }
        
        function testDataFetch() {
            console.log('Testing data fetch...');
            fetch('include/get-policy-data.php?id=1')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('testResults').innerHTML = 
                        '<h6>Data Fetch Result:</h6><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                })
                .catch(error => {
                    document.getElementById('testResults').innerHTML = 
                        '<h6>Error:</h6><div class="alert alert-danger">' + error.message + '</div>';
                });
        }
    </script>
</body>
</html>
