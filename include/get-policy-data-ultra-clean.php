<?php
// get-policy-data-ultra-clean.php - Ultra clean version
// No session includes that might output HTML

// Clean any output first
if (ob_get_level()) {
    ob_end_clean();
}

// Disable all error output
error_reporting(0);
ini_set('display_errors', 0);

// Set JSON header immediately
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache');

// Start our own session without includes
session_start();

try {
    // Check basic authentication
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }

    // Get policy ID from both POST and GET
    $policy_id = 0;
    
    if (isset($_POST['policy_id']) && !empty($_POST['policy_id'])) {
        $policy_id = intval($_POST['policy_id']);
    } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
        $policy_id = intval($_GET['id']);
    } elseif (isset($_GET['policy_id']) && !empty($_GET['policy_id'])) {
        $policy_id = intval($_GET['policy_id']);
    }
    
    if ($policy_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid policy ID']);
        exit;
    }

    // Direct database connection (bypass config includes)
    $host = "localhost";
    $username = "u820431346_newinsurance";
    $password = "Softpro@123";
    $database = "u820431346_newinsurance";

    $con = new mysqli($host, $username, $password, $database);
    
    if ($con->connect_errno) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $con->connect_error]);
        exit;
    }

    $con->set_charset("utf8");

    // Fetch policy data
    $query = "SELECT * FROM policy WHERE id = ?";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database prepare failed']);
        exit;
    }
    
    $stmt->bind_param("i", $policy_id);
    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Database execute failed']);
        exit;
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Policy not found']);
        exit;
    }
    
    $policy = $result->fetch_assoc();
    
    // Clean and format the data
    $cleaned_policy = [];
    foreach ($policy as $key => $value) {
        $cleaned_policy[$key] = $value ?? '';
    }
    
    // Format numeric fields
    $numeric_fields = ['premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue'];
    foreach ($numeric_fields as $field) {
        if (isset($cleaned_policy[$field])) {
            $cleaned_policy[$field] = floatval($cleaned_policy[$field]);
        } else {
            $cleaned_policy[$field] = 0;
        }
    }
    
    // Format date fields
    $date_fields = ['policy_issue_date', 'policy_start_date', 'policy_end_date', 'fc_expiry_date', 'permit_expiry_date'];
    foreach ($date_fields as $field) {
        if (isset($cleaned_policy[$field]) && $cleaned_policy[$field] && $cleaned_policy[$field] !== '0000-00-00') {
            $date = DateTime::createFromFormat('Y-m-d', $cleaned_policy[$field]);
            if ($date) {
                $cleaned_policy[$field] = $date->format('Y-m-d');
            } else {
                $cleaned_policy[$field] = '';
            }
        } else {
            $cleaned_policy[$field] = '';
        }
    }
    
    echo json_encode(['success' => true, 'data' => $cleaned_policy]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

exit;
?>
