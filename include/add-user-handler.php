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

// Validate required fields
$required_fields = ['name', 'username', 'phone', 'password', 'type'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit;
    }
}

// Sanitize and validate input
$name = mysqli_real_escape_string($con, trim($_POST['name']));
$username = mysqli_real_escape_string($con, trim($_POST['username']));
$phone = mysqli_real_escape_string($con, trim($_POST['phone']));
$password = mysqli_real_escape_string($con, trim($_POST['password']));
$type = mysqli_real_escape_string($con, trim($_POST['type']));
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, trim($_POST['email'])) : '';
$delete_flag = isset($_POST['delete_flag']) ? mysqli_real_escape_string($con, trim($_POST['delete_flag'])) : '1';

// Validate phone number
if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid 10-digit mobile number']);
    exit;
}

// Validate email if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Validate password strength
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
    exit;
}

// Validate user type
if (!in_array($type, ['1', '2'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user type']);
    exit;
}

try {
    // Start transaction
    mysqli_autocommit($con, FALSE);
    
    // Check if username already exists
    $check_username = "SELECT id FROM user WHERE username = '$username'";
    $result = mysqli_query($con, $check_username);
    
    if (!$result) {
        throw new Exception('Database error while checking username: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) > 0) {
        throw new Exception('Username already exists. Please choose a different username.');
    }
    
    // Check if phone number already exists
    $check_phone = "SELECT id FROM user WHERE phone = '$phone'";
    $result = mysqli_query($con, $check_phone);
    
    if (!$result) {
        throw new Exception('Database error while checking phone: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) > 0) {
        throw new Exception('Phone number already exists. Please use a different phone number.');
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO user (name, username, phone, email, password, type, delete_flag, created_at) 
            VALUES ('$name', '$username', '$phone', '$email', '$hashed_password', '$type', '$delete_flag', NOW())";
    
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database error while inserting user: ' . mysqli_error($con));
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    echo json_encode([
        'success' => true,
        'message' => 'User added successfully!',
        'user_id' => mysqli_insert_id($con)
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
