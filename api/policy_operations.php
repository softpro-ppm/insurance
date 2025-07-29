<?php
include '../include/session.php';
include '../include/config.php';

header('Content-Type: application/json');

// Handle different request methods
$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            addPolicy();
            break;
        case 'edit':
            editPolicy();
            break;
        case 'delete':
            deletePolicy();
            break;
        case 'get':
            getPolicy();
            break;
        case 'getDocuments':
            getPolicyDocuments();
            break;
        default:
            // Check if it's a form submission without explicit action
            if ($_POST['formAction'] ?? '' === 'add') {
                addPolicy();
            } elseif ($_POST['formAction'] ?? '' === 'edit') {
                editPolicy();
            } else {
                throw new Exception('Invalid action specified');
            }
            break;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function addPolicy() {
    global $conn;
    
    // Validate required fields
    $required = ['vehicleNumber', 'vehicleType', 'clientName', 'clientPhone', 'policyType', 'insuranceCompany', 'premium', 'policyStartDate', 'policyEndDate'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '{$field}' is required");
        }
    }
    
    // Handle file uploads
    $aadharPath = handleFileUpload('aadharCard');
    $panPath = handleFileUpload('panCard');
    
    // Prepare data
    $data = [
        'vehicle_number' => trim($_POST['vehicleNumber']),
        'vehicle_type' => trim($_POST['vehicleType']),
        'engine_number' => trim($_POST['engineNumber'] ?? ''),
        'chassis_number' => trim($_POST['chassisNumber'] ?? ''),
        'name' => trim($_POST['clientName']),
        'phone' => trim($_POST['clientPhone']),
        'email' => trim($_POST['clientEmail'] ?? ''),
        'address' => trim($_POST['clientAddress'] ?? ''),
        'policy_type' => trim($_POST['policyType']),
        'insurance_company' => trim($_POST['insuranceCompany']),
        'premium' => floatval($_POST['premium']),
        'policy_start_date' => $_POST['policyStartDate'],
        'policy_end_date' => $_POST['policyEndDate'],
        'policy_number' => trim($_POST['policyNumber'] ?? ''),
        'aadhar_card' => $aadharPath,
        'pan_card' => $panPath,
        'remarks' => trim($_POST['remarks'] ?? ''),
        'created_by' => $_SESSION['user_id'] ?? 1,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Insert into database
    $sql = "INSERT INTO policy (
        vehicle_number, vehicle_type, engine_number, chassis_number,
        name, phone, email, address,
        policy_type, insurance_company, premium,
        policy_start_date, policy_end_date, policy_number,
        aadhar_card, pan_card, remarks, policy_issue_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssssssssssdsssss',
        $data['vehicle_number'], $data['vehicle_type'], $data['engine_number'], $data['chassis_number'],
        $data['name'], $data['phone'], $data['email'], $data['address'],
        $data['policy_type'], $data['insurance_company'], $data['premium'],
        $data['policy_start_date'], $data['policy_end_date'], $data['policy_number'],
        $data['aadhar_card'], $data['pan_card'], $data['remarks'], date('Y-m-d')
    );
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Policy added successfully!',
            'policyId' => $conn->insert_id
        ]);
    } else {
        throw new Exception('Failed to add policy: ' . $conn->error);
    }
}

function editPolicy() {
    global $conn;
    
    $policyId = intval($_POST['policyId']);
    if (!$policyId) {
        throw new Exception('Policy ID is required');
    }
    
    // Validate required fields
    $required = ['vehicleNumber', 'vehicleType', 'clientName', 'clientPhone', 'policyType', 'insuranceCompany', 'premium', 'policyStartDate', 'policyEndDate'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '{$field}' is required");
        }
    }
    
    // Get existing policy data
    $existingPolicy = getPolicyById($policyId);
    if (!$existingPolicy) {
        throw new Exception('Policy not found');
    }
    
    // Handle file uploads (keep existing files if no new files uploaded)
    $aadharPath = handleFileUpload('aadharCard') ?: $existingPolicy['aadhar_card'];
    $panPath = handleFileUpload('panCard') ?: $existingPolicy['pan_card'];
    
    // Prepare data
    $data = [
        'vehicle_number' => trim($_POST['vehicleNumber']),
        'vehicle_type' => trim($_POST['vehicleType']),
        'engine_number' => trim($_POST['engineNumber'] ?? ''),
        'chassis_number' => trim($_POST['chassisNumber'] ?? ''),
        'name' => trim($_POST['clientName']),
        'phone' => trim($_POST['clientPhone']),
        'email' => trim($_POST['clientEmail'] ?? ''),
        'address' => trim($_POST['clientAddress'] ?? ''),
        'policy_type' => trim($_POST['policyType']),
        'insurance_company' => trim($_POST['insuranceCompany']),
        'premium' => floatval($_POST['premium']),
        'policy_start_date' => $_POST['policyStartDate'],
        'policy_end_date' => $_POST['policyEndDate'],
        'policy_number' => trim($_POST['policyNumber'] ?? ''),
        'aadhar_card' => $aadharPath,
        'pan_card' => $panPath,
        'remarks' => trim($_POST['remarks'] ?? ''),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Update database
    $sql = "UPDATE policy SET 
        vehicle_number = ?, vehicle_type = ?, engine_number = ?, chassis_number = ?,
        name = ?, phone = ?, email = ?, address = ?,
        policy_type = ?, insurance_company = ?, premium = ?,
        policy_start_date = ?, policy_end_date = ?, policy_number = ?,
        aadhar_card = ?, pan_card = ?, remarks = ?
        WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssssssssssdssssssi',
        $data['vehicle_number'], $data['vehicle_type'], $data['engine_number'], $data['chassis_number'],
        $data['name'], $data['phone'], $data['email'], $data['address'],
        $data['policy_type'], $data['insurance_company'], $data['premium'],
        $data['policy_start_date'], $data['policy_end_date'], $data['policy_number'],
        $data['aadhar_card'], $data['pan_card'], $data['remarks'],
        $policyId
    );
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Policy updated successfully!'
        ]);
    } else {
        throw new Exception('Failed to update policy: ' . $conn->error);
    }
}

function deletePolicy() {
    global $conn;
    
    $policyId = intval($_POST['policyId']);
    if (!$policyId) {
        throw new Exception('Policy ID is required');
    }
    
    // Get policy data to delete associated files
    $policy = getPolicyById($policyId);
    if (!$policy) {
        throw new Exception('Policy not found');
    }
    
    // Delete from database first
    $sql = "DELETE FROM policy WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $policyId);
    
    if ($stmt->execute()) {
        // Delete associated files
        if (!empty($policy['aadhar_card']) && file_exists("../uploads/documents/" . $policy['aadhar_card'])) {
            unlink("../uploads/documents/" . $policy['aadhar_card']);
        }
        if (!empty($policy['pan_card']) && file_exists("../uploads/documents/" . $policy['pan_card'])) {
            unlink("../uploads/documents/" . $policy['pan_card']);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Policy deleted successfully!'
        ]);
    } else {
        throw new Exception('Failed to delete policy: ' . $conn->error);
    }
}

function getPolicy() {
    global $conn;
    
    $policyId = intval($_GET['policyId']);
    if (!$policyId) {
        throw new Exception('Policy ID is required');
    }
    
    $policy = getPolicyById($policyId);
    if (!$policy) {
        throw new Exception('Policy not found');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $policy
    ]);
}

function getPolicyDocuments() {
    global $conn;
    
    $policyId = intval($_GET['policyId']);
    if (!$policyId) {
        throw new Exception('Policy ID is required');
    }
    
    $policy = getPolicyById($policyId);
    if (!$policy) {
        throw new Exception('Policy not found');
    }
    
    // Return only document-related data
    $documents = [
        'id' => $policy['id'],
        'vehicle_number' => $policy['vehicle_number'],
        'name' => $policy['name'],
        'aadhar_card' => $policy['aadhar_card'],
        'pan_card' => $policy['pan_card']
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $documents
    ]);
}

function getPolicyById($policyId) {
    global $conn;
    
    $sql = "SELECT * FROM policy WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $policyId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

function handleFileUpload($fieldName) {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    $file = $_FILES[$fieldName];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error for {$fieldName}");
    }
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Invalid file type for {$fieldName}. Only JPG, PNG, and PDF files are allowed.");
    }
    
    // Validate file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("File size too large for {$fieldName}. Maximum size is 5MB.");
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = '../uploads/documents/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid($fieldName . '_') . '.' . $extension;
    $uploadPath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $filename;
    } else {
        throw new Exception("Failed to upload {$fieldName}");
    }
}

$conn->close();
?>
