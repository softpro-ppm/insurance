<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Management System - Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/global.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Policy Management System Test</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                                <i class="bx bx-plus me-2"></i>Add New Policy
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle me-2"></i>System Features Implemented:</h6>
                            <ul class="mb-0">
                                <li>✅ Global CSS and JavaScript architecture</li>
                                <li>✅ Bootstrap 5 modal system with document uploads</li>
                                <li>✅ Aadhar Card and PAN Card upload with preview</li>
                                <li>✅ File validation (JPEG/PNG, max 2MB)</li>
                                <li>✅ Drag and drop file upload interface</li>
                                <li>✅ DataTables with global serial numbering</li>
                                <li>✅ Export functionality (CSV, Excel, PDF, Print)</li>
                                <li>✅ Delete confirmation with file cleanup</li>
                                <li>✅ Comprehensive database management</li>
                                <li>✅ Responsive design and error handling</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-success">
                            <h6><i class="bx bx-check-circle me-2"></i>Database Setup Required:</h6>
                            <p class="mb-2">Run this SQL script to create the policy_documents table:</p>
                            <code>
                                CREATE TABLE IF NOT EXISTS `policy_documents` (<br>
                                &nbsp;&nbsp;`id` int(11) NOT NULL AUTO_INCREMENT,<br>
                                &nbsp;&nbsp;`policy_id` int(11) NOT NULL,<br>
                                &nbsp;&nbsp;`document_type` enum('aadhar_card','pan_card') NOT NULL,<br>
                                &nbsp;&nbsp;`file_name` varchar(255) NOT NULL,<br>
                                &nbsp;&nbsp;`file_path` varchar(500) NOT NULL,<br>
                                &nbsp;&nbsp;`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,<br>
                                &nbsp;&nbsp;PRIMARY KEY (`id`),<br>
                                &nbsp;&nbsp;FOREIGN KEY (`policy_id`) REFERENCES `policy`(`id`) ON DELETE CASCADE<br>
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                            </code>
                        </div>
                        
                        <div class="text-center">
                            <a href="policies.php" class="btn btn-primary btn-lg">
                                <i class="bx bx-file me-2"></i>Go to Policy Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/global.js"></script>
</body>
</html>
