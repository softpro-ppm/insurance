<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$policyId = $input['policy_id'] ?? 0;

if (!$policyId) {
    echo json_encode(['success' => false, 'message' => 'Policy ID is required']);
    exit;
}

try {
    // Check if policy exists
    $checkQuery = "SELECT * FROM policy WHERE id = ?";
    $checkStmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "i", $policyId);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    
    if ($policy = mysqli_fetch_assoc($result)) {
        // Add renewal reminder entry (you might want to create a renewals table)
        $insertQuery = "INSERT INTO renewal_reminders (policy_id, client_name, vehicle_number, mobile_number, policy_end_date, reminder_date, status) 
                       VALUES (?, ?, ?, ?, ?, NOW(), 'pending')
                       ON DUPLICATE KEY UPDATE reminder_date = NOW(), status = 'pending'";
        
        $insertStmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, "issss", 
            $policy['id'],
            $policy['client_name'],
            $policy['vehicle_number'],
            $policy['mobile_number'],
            $policy['policy_end_date']
        );
        
        if (mysqli_stmt_execute($insertStmt)) {
            // Log the action
            $logQuery = "INSERT INTO activity_log (user_id, action, description, created_at) VALUES (?, 'renewal_marked', ?, NOW())";
            $logStmt = mysqli_prepare($con, $logQuery);
            $description = "Marked policy {$policy['vehicle_number']} for renewal";
            mysqli_stmt_bind_param($logStmt, "ss", $_SESSION['username'], $description);
            mysqli_stmt_execute($logStmt);
            
            echo json_encode([
                'success' => true,
                'message' => 'Policy marked for renewal successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to mark policy for renewal'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Policy not found'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
