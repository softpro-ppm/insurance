<?php
// get-policy-data-debug.php - Debug version to identify issues
session_start();

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../debug.log');

// Clean output buffer
if (ob_get_level()) {
    ob_end_clean();
}

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

$debug_info = [
    'timestamp' => date('Y-m-d H:i:s'),
    'request_method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'get_data' => $_GET,
    'session_exists' => isset($_SESSION),
    'session_username' => $_SESSION['username'] ?? 'not set',
    'steps' => []
];

try {
    $debug_info['steps'][] = 'Starting debug process';
    
    // Check session
    if (!isset($_SESSION['username'])) {
        $debug_info['steps'][] = 'Session check failed';
        echo json_encode(['success' => false, 'message' => 'Not authenticated', 'debug' => $debug_info]);
        exit;
    }
    $debug_info['steps'][] = 'Session check passed';

    // Get policy ID
    $policy_id = intval($_POST['policy_id'] ?? $_GET['id'] ?? 0);
    $debug_info['policy_id'] = $policy_id;
    
    if ($policy_id <= 0) {
        $debug_info['steps'][] = 'Invalid policy ID: ' . $policy_id;
        echo json_encode(['success' => false, 'message' => 'Invalid policy ID', 'debug' => $debug_info]);
        exit;
    }
    $debug_info['steps'][] = 'Policy ID validated: ' . $policy_id;

    // Try to include config
    $debug_info['steps'][] = 'Attempting to include config';
    include 'config.php';
    $debug_info['steps'][] = 'Config included successfully';

    // Check database connection
    if (!isset($con)) {
        $debug_info['steps'][] = 'Database connection variable not set';
        echo json_encode(['success' => false, 'message' => 'Database connection not initialized', 'debug' => $debug_info]);
        exit;
    }
    
    if ($con->connect_errno) {
        $debug_info['steps'][] = 'Database connection error: ' . $con->connect_error;
        echo json_encode(['success' => false, 'message' => 'Database connection failed', 'debug' => $debug_info]);
        exit;
    }
    $debug_info['steps'][] = 'Database connection successful';

    // Prepare and execute query
    $debug_info['steps'][] = 'Preparing SQL query';
    $query = "SELECT * FROM policy WHERE id = ?";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        $debug_info['steps'][] = 'SQL prepare failed: ' . $con->error;
        echo json_encode(['success' => false, 'message' => 'Database prepare failed', 'debug' => $debug_info]);
        exit;
    }
    $debug_info['steps'][] = 'SQL prepared successfully';
    
    $stmt->bind_param("i", $policy_id);
    $debug_info['steps'][] = 'Parameters bound';
    
    if (!$stmt->execute()) {
        $debug_info['steps'][] = 'SQL execute failed: ' . $stmt->error;
        echo json_encode(['success' => false, 'message' => 'Database execute failed', 'debug' => $debug_info]);
        exit;
    }
    $debug_info['steps'][] = 'SQL executed successfully';
    
    $result = $stmt->get_result();
    $debug_info['steps'][] = 'Result obtained';
    
    if ($result->num_rows === 0) {
        $debug_info['steps'][] = 'No policy found with ID: ' . $policy_id;
        echo json_encode(['success' => false, 'message' => 'Policy not found', 'debug' => $debug_info]);
        exit;
    }
    
    $policy = $result->fetch_assoc();
    $debug_info['steps'][] = 'Policy data fetched successfully';
    $debug_info['policy_fields'] = array_keys($policy);
    
    echo json_encode(['success' => true, 'data' => $policy, 'debug' => $debug_info]);
    
} catch (Exception $e) {
    $debug_info['steps'][] = 'Exception caught: ' . $e->getMessage();
    $debug_info['exception_file'] = $e->getFile();
    $debug_info['exception_line'] = $e->getLine();
    
    error_log("Debug script exception: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error', 'debug' => $debug_info]);
}

exit;
?>
