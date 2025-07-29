<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include '../include/config.php';
include '../include/session.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create uploads directory if it doesn't exist
$uploadDir = '../uploads/documents/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

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

try {
    $action = $_REQUEST['action'] ?? $_POST['formAction'] ?? '';
    
    switch ($action) {
        case 'add':
        case 'edit':
            // Validate required fields
            $requiredFields = ['clientName', 'clientEmail', 'clientPhone', 'policyNumber', 'policyType', 'premiumAmount'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
                    exit;
                }
            }
            
            // Sanitize input data
            $clientName = trim($_POST['clientName']);
            $clientEmail = trim($_POST['clientEmail']);
            $clientPhone = trim($_POST['clientPhone']);
            $clientAddress = trim($_POST['clientAddress'] ?? '');
            $clientDob = $_POST['clientDob'] ?? null;
            $policyNumber = trim($_POST['policyNumber']);
            $policyType = trim($_POST['policyType']);
            $premiumAmount = floatval($_POST['premiumAmount']);
            $policyStatus = $_POST['policyStatus'] ?? 'Active';
            $policyStartDate = $_POST['policyStartDate'] ?? null;
            $policyEndDate = $_POST['policyEndDate'] ?? null;
            
            // Validate email format
            if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
                exit;
            }
            
            // Validate phone number (basic validation)
            if (!preg_match('/^[0-9]{10}$/', $clientPhone)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid 10-digit phone number.']);
                exit;
            }
            
            $aadharCard = null;
            $panCard = null;
            
            if ($action === 'add') {
                // Check if email or policy number already exists
                $checkQuery = "SELECT id FROM clients WHERE email = ? OR policy_number = ?";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bind_param('ss', $clientEmail, $policyNumber);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                
                if ($checkResult->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'Email or Policy Number already exists.']);
                    exit;
                }
                
                // Handle file uploads for new client
                if (isset($_FILES['aadharCard']) && $_FILES['aadharCard']['error'] === UPLOAD_ERR_OK) {
                    $aadharUpload = uploadFile($_FILES['aadharCard'], 'aadhar');
                    if (!$aadharUpload['success']) {
                        echo json_encode(['success' => false, 'message' => 'Aadhar Card upload failed: ' . $aadharUpload['message']]);
                        exit;
                    }
                    $aadharCard = $aadharUpload['filename'];
                }
                
                if (isset($_FILES['panCard']) && $_FILES['panCard']['error'] === UPLOAD_ERR_OK) {
                    $panUpload = uploadFile($_FILES['panCard'], 'pan');
                    if (!$panUpload['success']) {
                        // Delete aadhar file if it was uploaded
                        if ($aadharCard) deleteFile($aadharCard);
                        echo json_encode(['success' => false, 'message' => 'PAN Card upload failed: ' . $panUpload['message']]);
                        exit;
                    }
                    $panCard = $panUpload['filename'];
                }
                
                // Insert new client
                $insertQuery = "INSERT INTO clients (
                    client_name, email, phone, address, dob, 
                    policy_number, policy_type, premium_amount, status, 
                    policy_start_date, policy_end_date, aadhar_card, pan_card, 
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param('sssssssdsssss', 
                    $clientName, $clientEmail, $clientPhone, $clientAddress, $clientDob,
                    $policyNumber, $policyType, $premiumAmount, $policyStatus,
                    $policyStartDate, $policyEndDate, $aadharCard, $panCard
                );
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Client added successfully!']);
                } else {
                    // Delete uploaded files if insert failed
                    if ($aadharCard) deleteFile($aadharCard);
                    if ($panCard) deleteFile($panCard);
                    echo json_encode(['success' => false, 'message' => 'Failed to add client.']);
                }
                
            } else { // Edit action
                $clientId = intval($_POST['clientId']);
                
                // Check if email or policy number already exists for other clients
                $checkQuery = "SELECT id FROM clients WHERE (email = ? OR policy_number = ?) AND id != ?";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bind_param('ssi', $clientEmail, $policyNumber, $clientId);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                
                if ($checkResult->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'Email or Policy Number already exists for another client.']);
                    exit;
                }
                
                // Get current file names
                $getCurrentQuery = "SELECT aadhar_card, pan_card FROM clients WHERE id = ?";
                $getCurrentStmt = $conn->prepare($getCurrentQuery);
                $getCurrentStmt->bind_param('i', $clientId);
                $getCurrentStmt->execute();
                $currentResult = $getCurrentStmt->get_result();
                $currentData = $currentResult->fetch_assoc();
                
                $aadharCard = $currentData['aadhar_card'];
                $panCard = $currentData['pan_card'];
                
                // Handle new file uploads
                if (isset($_FILES['aadharCard']) && $_FILES['aadharCard']['error'] === UPLOAD_ERR_OK) {
                    $aadharUpload = uploadFile($_FILES['aadharCard'], 'aadhar');
                    if (!$aadharUpload['success']) {
                        echo json_encode(['success' => false, 'message' => 'Aadhar Card upload failed: ' . $aadharUpload['message']]);
                        exit;
                    }
                    // Delete old file
                    if ($aadharCard) deleteFile($aadharCard);
                    $aadharCard = $aadharUpload['filename'];
                }
                
                if (isset($_FILES['panCard']) && $_FILES['panCard']['error'] === UPLOAD_ERR_OK) {
                    $panUpload = uploadFile($_FILES['panCard'], 'pan');
                    if (!$panUpload['success']) {
                        echo json_encode(['success' => false, 'message' => 'PAN Card upload failed: ' . $panUpload['message']]);
                        exit;
                    }
                    // Delete old file
                    if ($panCard) deleteFile($panCard);
                    $panCard = $panUpload['filename'];
                }
                
                // Update client
                $updateQuery = "UPDATE clients SET 
                    client_name = ?, email = ?, phone = ?, address = ?, dob = ?,
                    policy_number = ?, policy_type = ?, premium_amount = ?, status = ?,
                    policy_start_date = ?, policy_end_date = ?, aadhar_card = ?, pan_card = ?,
                    updated_at = NOW()
                    WHERE id = ?";
                
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param('sssssssdsssssi', 
                    $clientName, $clientEmail, $clientPhone, $clientAddress, $clientDob,
                    $policyNumber, $policyType, $premiumAmount, $policyStatus,
                    $policyStartDate, $policyEndDate, $aadharCard, $panCard, $clientId
                );
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Client updated successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update client.']);
                }
            }
            break;
            
        case 'delete':
            $clientId = intval($_POST['clientId']);
            
            // Get file names before deletion
            $getFilesQuery = "SELECT aadhar_card, pan_card FROM clients WHERE id = ?";
            $getFilesStmt = $conn->prepare($getFilesQuery);
            $getFilesStmt->bind_param('i', $clientId);
            $getFilesStmt->execute();
            $filesResult = $getFilesStmt->get_result();
            $filesData = $filesResult->fetch_assoc();
            
            // Delete client record
            $deleteQuery = "DELETE FROM clients WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('i', $clientId);
            
            if ($deleteStmt->execute()) {
                // Delete associated files
                if ($filesData['aadhar_card']) {
                    deleteFile($filesData['aadhar_card']);
                }
                if ($filesData['pan_card']) {
                    deleteFile($filesData['pan_card']);
                }
                
                echo json_encode(['success' => true, 'message' => 'Client and all associated data deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete client.']);
            }
            break;
            
        case 'get':
            $clientId = intval($_GET['clientId']);
            
            $getQuery = "SELECT * FROM clients WHERE id = ?";
            $getStmt = $conn->prepare($getQuery);
            $getStmt->bind_param('i', $clientId);
            $getStmt->execute();
            $result = $getStmt->get_result();
            
            if ($result->num_rows > 0) {
                $clientData = $result->fetch_assoc();
                echo json_encode(['success' => true, 'data' => $clientData]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Client not found.']);
            }
            break;
            
        case 'getDocuments':
            $clientId = intval($_GET['clientId']);
            
            $getDocsQuery = "SELECT client_name, aadhar_card, pan_card FROM clients WHERE id = ?";
            $getDocsStmt = $conn->prepare($getDocsQuery);
            $getDocsStmt->bind_param('i', $clientId);
            $getDocsStmt->execute();
            $result = $getDocsStmt->get_result();
            
            if ($result->num_rows > 0) {
                $docsData = $result->fetch_assoc();
                echo json_encode(['success' => true, 'data' => $docsData]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Client not found.']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

$conn->close();
?>
