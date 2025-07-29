<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = '../uploads/documents/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    $action = $_REQUEST['action'] ?? $_POST['formAction'] ?? '';
    
    switch ($action) {
        case 'add':
        case 'edit':
            // Validate required fields
            $requiredFields = ['client_name', 'mobile_number', 'vehicle_number', 'insurance_company', 'policy_type', 'premium_amount'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
                    exit;
                }
            }
            
            // Sanitize input data
            $clientName = trim($_POST['client_name']);
            $mobileNumber = trim($_POST['mobile_number']);
            $email = trim($_POST['email'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $vehicleNumber = trim($_POST['vehicle_number']);
            $vehicleType = trim($_POST['vehicle_type']);
            $vehicleBrand = trim($_POST['vehicle_brand'] ?? '');
            $vehicleModel = trim($_POST['vehicle_model'] ?? '');
            $insuranceCompany = trim($_POST['insurance_company']);
            $policyType = trim($_POST['policy_type']);
            $policyStartDate = $_POST['policy_start_date'] ?? null;
            $policyEndDate = $_POST['policy_end_date'] ?? null;
            $premiumAmount = floatval($_POST['premium_amount']);
            $commissionAmount = floatval($_POST['commission_amount'] ?? 0);
            
            // Validate email format if provided
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
                exit;
            }
            
            // Validate phone number (basic validation)
            if (!preg_match('/^[0-9]{10}$/', $mobileNumber)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid 10-digit mobile number.']);
                exit;
            }
            
            $policyDocument = null;
            
            if ($action === 'add') {
                // Check if vehicle number already exists
                $checkQuery = "SELECT id FROM policy WHERE vehicle_number = ?";
                $checkStmt = mysqli_prepare($con, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, 's', $vehicleNumber);
                mysqli_stmt_execute($checkStmt);
                $checkResult = mysqli_stmt_get_result($checkStmt);
                
                if (mysqli_num_rows($checkResult) > 0) {
                    echo json_encode(['success' => false, 'message' => 'Vehicle number already exists.']);
                    exit;
                }
                
                // Handle file upload for new policy
                if (isset($_FILES['policy_document']) && $_FILES['policy_document']['error'] === UPLOAD_ERR_OK) {
                    $docUpload = uploadFile($_FILES['policy_document'], 'policy');
                    if (!$docUpload['success']) {
                        echo json_encode(['success' => false, 'message' => 'Document upload failed: ' . $docUpload['message']]);
                        exit;
                    }
                    $policyDocument = $docUpload['filename'];
                }
                
                // Insert new policy
                $insertQuery = "INSERT INTO policy (
                    client_name, mobile_number, email, address,
                    vehicle_number, vehicle_type, vehicle_brand, vehicle_model,
                    insurance_company, policy_type, policy_start_date, policy_end_date,
                    premium_amount, commission_amount, policy_document,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $stmt = mysqli_prepare($con, $insertQuery);
                mysqli_stmt_bind_param($stmt, 'ssssssssssssdds', 
                    $clientName, $mobileNumber, $email, $address,
                    $vehicleNumber, $vehicleType, $vehicleBrand, $vehicleModel,
                    $insuranceCompany, $policyType, $policyStartDate, $policyEndDate,
                    $premiumAmount, $commissionAmount, $policyDocument
                );
                
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'Policy added successfully!']);
                } else {
                    // Delete uploaded file if insert failed
                    if ($policyDocument) deleteFile($policyDocument);
                    echo json_encode(['success' => false, 'message' => 'Failed to add policy: ' . mysqli_error($con)]);
                }
                
            } else { // Edit action
                $policyId = intval($_POST['policy_id']);
                
                // Check if vehicle number already exists for other policies
                $checkQuery = "SELECT id FROM policy WHERE vehicle_number = ? AND id != ?";
                $checkStmt = mysqli_prepare($con, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, 'si', $vehicleNumber, $policyId);
                mysqli_stmt_execute($checkStmt);
                $checkResult = mysqli_stmt_get_result($checkStmt);
                
                if (mysqli_num_rows($checkResult) > 0) {
                    echo json_encode(['success' => false, 'message' => 'Vehicle number already exists for another policy.']);
                    exit;
                }
                
                // Get current document name
                $getCurrentQuery = "SELECT policy_document FROM policy WHERE id = ?";
                $getCurrentStmt = mysqli_prepare($con, $getCurrentQuery);
                mysqli_stmt_bind_param($getCurrentStmt, 'i', $policyId);
                mysqli_stmt_execute($getCurrentStmt);
                $currentResult = mysqli_stmt_get_result($getCurrentStmt);
                $currentData = mysqli_fetch_assoc($currentResult);
                
                $policyDocument = $currentData['policy_document'];
                
                // Handle new file upload
                if (isset($_FILES['policy_document']) && $_FILES['policy_document']['error'] === UPLOAD_ERR_OK) {
                    $docUpload = uploadFile($_FILES['policy_document'], 'policy');
                    if (!$docUpload['success']) {
                        echo json_encode(['success' => false, 'message' => 'Document upload failed: ' . $docUpload['message']]);
                        exit;
                    }
                    // Delete old file
                    if ($policyDocument) deleteFile($policyDocument);
                    $policyDocument = $docUpload['filename'];
                }
                
                // Update policy
                $updateQuery = "UPDATE policy SET 
                    client_name = ?, mobile_number = ?, email = ?, address = ?,
                    vehicle_number = ?, vehicle_type = ?, vehicle_brand = ?, vehicle_model = ?,
                    insurance_company = ?, policy_type = ?, policy_start_date = ?, policy_end_date = ?,
                    premium_amount = ?, commission_amount = ?, policy_document = ?,
                    updated_at = NOW()
                    WHERE id = ?";
                
                $stmt = mysqli_prepare($con, $updateQuery);
                mysqli_stmt_bind_param($stmt, 'ssssssssssssddsi', 
                    $clientName, $mobileNumber, $email, $address,
                    $vehicleNumber, $vehicleType, $vehicleBrand, $vehicleModel,
                    $insuranceCompany, $policyType, $policyStartDate, $policyEndDate,
                    $premiumAmount, $commissionAmount, $policyDocument, $policyId
                );
                
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'Policy updated successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update policy: ' . mysqli_error($con)]);
                }
            }
            
        case 'delete':
            $policyId = intval($_POST['policy_id']);
            
            // Get document name before deletion
            $getDocQuery = "SELECT policy_document FROM policy WHERE id = ?";
            $getDocStmt = mysqli_prepare($con, $getDocQuery);
            mysqli_stmt_bind_param($getDocStmt, 'i', $policyId);
            mysqli_stmt_execute($getDocStmt);
            $docResult = mysqli_stmt_get_result($getDocStmt);
            $docData = mysqli_fetch_assoc($docResult);
            
            // Delete policy record
            $deleteQuery = "DELETE FROM policy WHERE id = ?";
            $deleteStmt = mysqli_prepare($con, $deleteQuery);
            mysqli_stmt_bind_param($deleteStmt, 'i', $policyId);
            
            if (mysqli_stmt_execute($deleteStmt)) {
                // Delete associated document
                if ($docData['policy_document']) {
                    deleteFile($docData['policy_document']);
                }
                
                echo json_encode(['success' => true, 'message' => 'Policy and all associated data deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete policy: ' . mysqli_error($con)]);
            }
            break;
            
        case 'get':
            $policyId = intval($_GET['id']);
            
            $getQuery = "SELECT * FROM policy WHERE id = ?";
            $getStmt = mysqli_prepare($con, $getQuery);
            mysqli_stmt_bind_param($getStmt, 'i', $policyId);
            mysqli_stmt_execute($getStmt);
            $result = mysqli_stmt_get_result($getStmt);
            
            if (mysqli_num_rows($result) > 0) {
                $policyData = mysqli_fetch_assoc($result);
                echo json_encode(['success' => true, 'data' => $policyData]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Policy not found.']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// File validation and upload functions
function validateFile($file, $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'], $maxSize = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error.'];
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and PDF files are allowed.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size too large. Maximum size is 5MB.'];
    }
    
    return ['success' => true];
}

function uploadFile($file, $prefix = 'doc') {
    global $uploadDir;
    
    $validation = validateFile($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . '_' . uniqid() . '.' . $fileExtension;
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $fileName];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}

function deleteFile($filename) {
    global $uploadDir;
    $filePath = $uploadDir . $filename;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

mysqli_close($con);
?>
