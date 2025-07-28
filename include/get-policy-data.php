<?php
// get-policy-data.php - Fetch policy data for editing
session_start();

// Clean all output first
if (ob_get_level()) {
    ob_end_clean();
}

// Disable all errors from being output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set JSON header immediately
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

try {
    // Use the main config file for live environment
    include 'config.php';

    // Check database connection more thoroughly
    if (!isset($con)) {
        throw new Exception('Database connection not initialized');
    }
    
    if ($con->connect_errno) {
        throw new Exception('Database connection failed: ' . $con->connect_error);
    }
    
    // Test the connection
    if (!$con->ping()) {
        throw new Exception('Database connection lost');
    }

    // Check if user is logged in
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }

    // Check if policy ID is provided (support both GET and POST)
    $policy_id = null;

    if (isset($_POST['policy_id']) && !empty($_POST['policy_id'])) {
        $policy_id = intval($_POST['policy_id']);
    } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
        $policy_id = intval($_GET['id']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Policy ID is required']);
        exit;
    }

    if ($policy_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid policy ID']);
        exit;
    }

try {
    // Prepare and execute query to fetch policy data
    $stmt = $con->prepare("SELECT * FROM policy WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    
    $stmt->bind_param("i", $policy_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        
        // Handle NULL values for new fields and ensure proper formatting
        $policy['payout'] = $policy['payout'] ?? 0;
        $policy['customer_paid'] = $policy['customer_paid'] ?? 0;
        $policy['discount'] = $policy['discount'] ?? 0;
        $policy['calculated_revenue'] = $policy['calculated_revenue'] ?? 0;
        $policy['comments'] = $policy['comments'] ?? '';
        
        // Format numeric values
        $policy['premium'] = floatval($policy['premium'] ?? 0);
        $policy['revenue'] = floatval($policy['revenue'] ?? 0);
        $policy['payout'] = floatval($policy['payout']);
        $policy['customer_paid'] = floatval($policy['customer_paid']);
        $policy['discount'] = floatval($policy['discount']);
        $policy['calculated_revenue'] = floatval($policy['calculated_revenue']);
        
        // Format dates properly (ensure they're in YYYY-MM-DD format)
        $date_fields = ['policy_issue_date', 'policy_start_date', 'policy_end_date', 'fc_expiry_date', 'permit_expiry_date'];
        foreach ($date_fields as $field) {
            if (!empty($policy[$field]) && $policy[$field] !== '0000-00-00') {
                try {
                    $policy[$field] = date('Y-m-d', strtotime($policy[$field]));
                } catch (Exception $e) {
                    $policy[$field] = '';
                }
            } else {
                $policy[$field] = '';
            }
        }
        
        // Ensure all required fields exist
        $required_fields = [
            'id', 'vehicle_number', 'name', 'phone', 'vehicle_type', 'policy_type',
            'insurance_company', 'policy_issue_date', 'policy_start_date', 'policy_end_date',
            'premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue',
            'chassiss', 'comments', 'fc_expiry_date', 'permit_expiry_date'
        ];
        
        foreach ($required_fields as $field) {
            if (!array_key_exists($field, $policy)) {
                $policy[$field] = '';
            }
        }
        
        echo json_encode(['success' => true, 'data' => $policy]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Policy not found']);
    }
    
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    error_log("MySQL error in get-policy-data.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
} catch (Exception $e) {
    error_log("Policy data fetch error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}

// Close connection if it exists
if (isset($con) && $con) {
    $con->close();
}
?>
