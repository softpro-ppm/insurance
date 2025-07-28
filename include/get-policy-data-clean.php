<?php
// get-policy-data-clean.php - Ultra clean version for policy data
session_start();

// Disable all output and errors
ini_set('display_errors', 0);
error_reporting(0);

// Clean output buffer
if (ob_get_level()) {
    ob_end_clean();
}

try {
    // Include config
    require_once 'config.php';

    // Set JSON headers after config (in case config has issues)
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');

    // Check session
    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }

    // Get policy ID
    $policy_id = intval($_POST['policy_id'] ?? $_GET['id'] ?? 0);
    
    if ($policy_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid policy ID']);
        exit;
    }

    // Check database connection
    if (!isset($con) || !$con) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }
    
    if ($con->connect_errno) {
        echo json_encode(['success' => false, 'message' => 'Database connection error']);
        exit;
    }

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
