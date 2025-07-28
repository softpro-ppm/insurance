<?php
// get-policy-data.php - Fetch policy data for editing
session_start();

// Try local config first, fallback to original
if (file_exists('config-local.php')) {
    include 'config-local.php';
} else {
    include 'config.php';
}

// Set JSON header
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response

// Debug function to log errors
function debugLog($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, '../debug.log');
}

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    debugLog('Authentication failed - no session');
    echo json_encode(['success' => false, 'message' => 'Not authenticated', 'debug' => 'Session check failed']);
    exit;
}

// Check if policy ID is provided (support both GET and POST)
$policy_id = null;

if (isset($_POST['policy_id']) && !empty($_POST['policy_id'])) {
    $policy_id = intval($_POST['policy_id']);
    debugLog('Policy ID from POST: ' . $policy_id);
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $policy_id = intval($_GET['id']);
    debugLog('Policy ID from GET: ' . $policy_id);
} else {
    debugLog('No policy ID provided - POST: ' . print_r($_POST, true) . ' GET: ' . print_r($_GET, true));
    echo json_encode(['success' => false, 'message' => 'Policy ID is required', 'debug' => 'No ID provided']);
    exit;
}

// Debug log
debugLog("Fetching policy data for ID: " . $policy_id);

// Check database connection
if (!$con || $con->connect_errno) {
    debugLog('Database connection failed: ' . ($con ? $con->connect_error : 'No connection object'));
    echo json_encode(['success' => false, 'message' => 'Database connection failed', 'debug' => 'Connection error']);
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
        
        echo json_encode(['success' => true, 'data' => $policy], JSON_NUMERIC_CHECK);
    } else {
        echo json_encode(['success' => false, 'message' => 'Policy not found', 'debug' => 'No rows returned']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Policy data fetch error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'debug' => $e->getTraceAsString()]);
}

$con->close();
?>
