<?php
// get-policy-data.php - Fetch policy data for editing
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Check if policy ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Policy ID is required']);
    exit;
}

$policy_id = intval($_GET['id']);

try {
    // Prepare and execute query to fetch policy data
    $stmt = $con->prepare("SELECT * FROM policy WHERE id = ?");
    $stmt->bind_param("i", $policy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        
        // Handle NULL values for new fields
        $policy['payout'] = $policy['payout'] ?? '';
        $policy['customer_paid'] = $policy['customer_paid'] ?? '';
        $policy['discount'] = $policy['discount'] ?? '';
        $policy['calculated_revenue'] = $policy['calculated_revenue'] ?? '';
        $policy['comments'] = $policy['comments'] ?? '';
        
        // Format dates properly (ensure they're in YYYY-MM-DD format)
        if ($policy['policy_issue_date']) {
            $policy['policy_issue_date'] = date('Y-m-d', strtotime($policy['policy_issue_date']));
        }
        if ($policy['policy_start_date']) {
            $policy['policy_start_date'] = date('Y-m-d', strtotime($policy['policy_start_date']));
        }
        if ($policy['policy_end_date']) {
            $policy['policy_end_date'] = date('Y-m-d', strtotime($policy['policy_end_date']));
        }
        if ($policy['fc_expiry_date']) {
            $policy['fc_expiry_date'] = date('Y-m-d', strtotime($policy['fc_expiry_date']));
        }
        if ($policy['permit_expiry_date']) {
            $policy['permit_expiry_date'] = date('Y-m-d', strtotime($policy['permit_expiry_date']));
        }
        
        echo json_encode(['success' => true, 'policy' => $policy]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Policy not found']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$con->close();
?>
