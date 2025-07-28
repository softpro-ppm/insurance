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

if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$user_id = mysqli_real_escape_string($con, $_POST['user_id']);

try {
    // Get user data
    $sql = "SELECT * FROM user WHERE id = '$user_id'";
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) == 0) {
        throw new Exception('User not found');
    }
    
    $user_data = mysqli_fetch_assoc($result);
    
    echo json_encode([
        'success' => true,
        'data' => $user_data,
        'message' => 'User data loaded successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => 'Error in get-user-data.php'
    ]);
}
?>
