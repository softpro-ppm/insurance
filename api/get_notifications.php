<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get renewal notifications count
    $renewalQuery = "SELECT COUNT(*) as count FROM policy 
                    WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                    AND policy_end_date >= CURDATE()";
    
    $renewalResult = mysqli_query($con, $renewalQuery);
    $renewalCount = mysqli_fetch_array($renewalResult)['count'];
    
    // Get urgent renewals (within 7 days)
    $urgentQuery = "SELECT COUNT(*) as count FROM policy 
                   WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                   AND policy_end_date >= CURDATE()";
    
    $urgentResult = mysqli_query($con, $urgentQuery);
    $urgentCount = mysqli_fetch_array($urgentResult)['count'];
    
    // Get expired policies
    $expiredQuery = "SELECT COUNT(*) as count FROM policy 
                    WHERE policy_end_date < CURDATE()";
    
    $expiredResult = mysqli_query($con, $expiredQuery);
    $expiredCount = mysqli_fetch_array($expiredResult)['count'];
    
    $response = [
        'success' => true,
        'count' => $renewalCount,
        'urgent' => $urgentCount,
        'expired' => $expiredCount,
        'total' => $renewalCount + $urgentCount + $expiredCount
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
