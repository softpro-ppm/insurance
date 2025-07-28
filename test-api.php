<?php
// test-api.php - Simple API test endpoint
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Basic test without database
$response = [
    'success' => true,
    'message' => 'API endpoint is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'session_status' => session_status(),
    'session_id' => session_id(),
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
];

// Check if user is logged in
if (isset($_SESSION['username'])) {
    $response['user'] = $_SESSION['username'];
    $response['logged_in'] = true;
} else {
    $response['logged_in'] = false;
}

// Test database connection if possible
try {
    include 'include/config.php';
    
    if (isset($con) && !$con->connect_errno) {
        $response['database'] = 'Connected';
        
        // Test basic query
        $result = $con->query("SELECT 1 as test");
        if ($result) {
            $response['database_test'] = 'Query successful';
        } else {
            $response['database_test'] = 'Query failed';
        }
    } else {
        $response['database'] = 'Connection failed';
        $response['database_error'] = $con->connect_error ?? 'Unknown error';
    }
} catch (Exception $e) {
    $response['database'] = 'Exception occurred';
    $response['database_error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
