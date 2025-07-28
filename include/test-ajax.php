<?php
// test-ajax.php - Test AJAX communication
session_start();

// Set JSON header
header('Content-Type: application/json');

// Create session if it doesn't exist
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'TestUser';
}

// Log the request
error_log("Test AJAX called - Method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST data: " . print_r($_POST, true));
error_log("GET data: " . print_r($_GET, true));

// Get policy ID
$policy_id = null;
if (isset($_POST['policy_id'])) {
    $policy_id = $_POST['policy_id'];
} elseif (isset($_GET['id'])) {
    $policy_id = $_GET['id'];
}

// Return test data
$test_data = [
    'id' => $policy_id ?: 123,
    'vehicle_number' => 'AP01AB1234',
    'name' => 'Test Customer',
    'phone' => '9876543210',
    'vehicle_type' => 'Car',
    'policy_type' => 'Comprehensive',
    'insurance_company' => 'Test Insurance',
    'policy_issue_date' => '2025-01-01',
    'policy_start_date' => '2025-01-01',
    'policy_end_date' => '2026-01-01',
    'premium' => 25000,
    'revenue' => 2500,
    'payout' => 22500,
    'customer_paid' => 25000,
    'discount' => 0,
    'calculated_revenue' => 2500,
    'chassiss' => 'TEST123456',
    'comments' => 'Test policy data',
    'fc_expiry_date' => '2026-01-01',
    'permit_expiry_date' => '2026-01-01'
];

echo json_encode([
    'success' => true,
    'data' => $test_data,
    'debug' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'post' => $_POST,
        'get' => $_GET,
        'session' => isset($_SESSION['username']) ? $_SESSION['username'] : 'No session'
    ]
]);
?>
