<?php
// Test database connection and policy data
session_start();

// Set proper headers
header('Content-Type: application/json');

// Include config
require_once 'include/config.php';

try {
    // Test 1: Check if database connection exists
    if (!$con) {
        echo json_encode(['success' => false, 'error' => 'No database connection']);
        exit;
    }
    
    // Test 2: Check if connection is valid
    if ($con->connect_errno) {
        echo json_encode(['success' => false, 'error' => 'Connection error: ' . $con->connect_error]);
        exit;
    }
    
    // Test 3: Try to fetch a sample policy
    $test_id = 1015; // Using the ID from the screenshot
    $stmt = $con->prepare("SELECT id, vehicle_number, name FROM policy WHERE id = ? LIMIT 1");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $con->error]);
        exit;
    }
    
    $stmt->bind_param("i", $test_id);
    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Execute failed: ' . $stmt->error]);
        exit;
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        echo json_encode([
            'success' => true, 
            'message' => 'Connection and query successful',
            'sample_policy' => $policy,
            'session_user' => $_SESSION['username'] ?? 'Not logged in'
        ]);
    } else {
        echo json_encode([
            'success' => true, 
            'message' => 'Connection successful but no policy found with ID ' . $test_id,
            'session_user' => $_SESSION['username'] ?? 'Not logged in'
        ]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Exception: ' . $e->getMessage()]);
}

$con->close();
?>
