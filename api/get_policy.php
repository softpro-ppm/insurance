<?php
session_start();
require_once '../connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$policyId = $_GET['id'] ?? 0;

if (!$policyId) {
    echo json_encode(['success' => false, 'message' => 'Policy ID is required']);
    exit;
}

try {
    $query = "SELECT * FROM policy WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $policyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($policy = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'policy' => $policy
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Policy not found'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
