<?php
require 'session.php';
require 'config.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

if (!isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Status is required']);
    exit;
}

$id = mysqli_real_escape_string($con, $_POST['id']);
$current_status = mysqli_real_escape_string($con, $_POST['status']);

// Calculate new status (toggle)
$new_status = ($current_status == '1') ? '0' : '1';

try {
    // Start transaction
    mysqli_autocommit($con, FALSE);
    
    // Check if user exists
    $check_user = "SELECT * FROM user WHERE id = '$id'";
    $result = mysqli_query($con, $check_user);
    
    if (!$result) {
        throw new Exception('Database error while checking user: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) == 0) {
        throw new Exception('User not found');
    }
    
    $user_data = mysqli_fetch_assoc($result);
    
    // Prevent deactivation of admin users if this is the only active admin (optional security check)
    if ($user_data['type'] == '1' && $new_status == '0') {
        $active_admin_count = "SELECT COUNT(*) as count FROM user WHERE type = '1' AND delete_flag = '1' AND id != '$id'";
        $admin_result = mysqli_query($con, $active_admin_count);
        $admin_data = mysqli_fetch_assoc($admin_result);
        
        if ($admin_data['count'] == 0) {
            throw new Exception('Cannot deactivate the last active admin user. Please activate another admin first.');
        }
    }
    
    // Update user status
    $sql = "UPDATE user SET delete_flag = '$new_status', updated_at = NOW() WHERE id = '$id'";
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database error while updating status: ' . mysqli_error($con));
    }
    
    if (mysqli_affected_rows($con) == 0) {
        throw new Exception('User not found or status unchanged');
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    $status_text = ($new_status == '1') ? 'activated' : 'deactivated';
    
    echo json_encode([
        'success' => true,
        'message' => "User {$status_text} successfully!",
        'new_status' => $new_status
    ]);

} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($con);
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Restore autocommit
mysqli_autocommit($con, TRUE);
?>
