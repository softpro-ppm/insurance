<?php
// get-policy-data-fixed.php - Clean version to fetch policy data for editing
session_start();

// Clean output buffer and set proper headers
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

// Include config
if (file_exists('config-local.php')) {
    include 'config-local.php';
} else {
    include 'config.php';
}

// Clear any accidental output and set JSON header
ob_clean();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

// Check authentication
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Get policy ID
$policy_id = null;
if (isset($_POST['policy_id']) && is_numeric($_POST['policy_id'])) {
    $policy_id = intval($_POST['policy_id']);
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $policy_id = intval($_GET['id']);
}

if (!$policy_id) {
    echo json_encode(['success' => false, 'message' => 'Valid policy ID is required']);
    exit;
}

// Check database connection
if (!isset($con) || !$con) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // Fetch policy data
    $stmt = $con->prepare("SELECT * FROM policy WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Database prepare failed");
    }
    
    $stmt->bind_param("i", $policy_id);
    if (!$stmt->execute()) {
        throw new Exception("Database execute failed");
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        
        // Clean and format data
        foreach ($policy as $key => $value) {
            if ($value === null) {
                $policy[$key] = '';
            }
        }
        
        // Format numeric fields
        $numeric_fields = ['premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue'];
        foreach ($numeric_fields as $field) {
            if (isset($policy[$field])) {
                $policy[$field] = floatval($policy[$field]);
            }
        }
        
        // Format date fields
        $date_fields = ['policy_issue_date', 'policy_start_date', 'policy_end_date', 'fc_expiry_date', 'permit_expiry_date'];
        foreach ($date_fields as $field) {
            if (isset($policy[$field]) && $policy[$field] !== '0000-00-00' && !empty($policy[$field])) {
                $policy[$field] = date('Y-m-d', strtotime($policy[$field]));
            } else {
                $policy[$field] = '';
            }
        }
        
        echo json_encode(['success' => true, 'data' => $policy]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Policy not found']);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}

$con->close();
exit;
?>
