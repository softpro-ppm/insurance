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
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$required_fields = ['name', 'username', 'phone', 'type'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit;
    }
}

// Sanitize and validate input
$id = mysqli_real_escape_string($con, trim($_POST['id']));
$name = mysqli_real_escape_string($con, trim($_POST['name']));
$username = mysqli_real_escape_string($con, trim($_POST['username']));
$phone = mysqli_real_escape_string($con, trim($_POST['phone']));
$type = mysqli_real_escape_string($con, trim($_POST['type']));
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, trim($_POST['email'])) : '';
$password = isset($_POST['password']) && !empty(trim($_POST['password'])) ? mysqli_real_escape_string($con, trim($_POST['password'])) : null;
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

// Validate password if provided
if ($password !== null && strlen($password) < 6) {
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
    
    // Check if user exists
    $check_user = "SELECT * FROM user WHERE id = '$id'";
    $result = mysqli_query($con, $check_user);
    
    if (!$result) {
        throw new Exception('Database error while checking user: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) == 0) {
        throw new Exception('User not found');
    }
    
    $current_user = mysqli_fetch_assoc($result);
    
    // Check if username already exists (excluding current user)
    $check_username = "SELECT id FROM user WHERE username = '$username' AND id != '$id'";
    $result = mysqli_query($con, $check_username);
    
    if (!$result) {
        throw new Exception('Database error while checking username: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) > 0) {
        throw new Exception('Username already exists. Please choose a different username.');
    }
    
    // Check if phone number already exists (excluding current user)
    $check_phone = "SELECT id FROM user WHERE phone = '$phone' AND id != '$id'";
    $result = mysqli_query($con, $check_phone);
    
    if (!$result) {
        throw new Exception('Database error while checking phone: ' . mysqli_error($con));
    }
    
    if (mysqli_num_rows($result) > 0) {
        throw new Exception('Phone number already exists. Please use a different phone number.');
    }
    
    // Prepare update query
    $update_fields = [
        "name = '$name'",
        "username = '$username'",
        "phone = '$phone'",
        "email = '$email'",
        "type = '$type'",
        "delete_flag = '$delete_flag'",
        "updated_at = NOW()"
    ];
    
    // Add password to update if provided
    if ($password !== null) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_fields[] = "password = '$hashed_password'";
    }
    
    // Update user
    $sql = "UPDATE user SET " . implode(', ', $update_fields) . " WHERE id = '$id'";
    
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database error while updating user: ' . mysqli_error($con));
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    echo json_encode([
        'success' => true,
        'message' => 'User updated successfully!'
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
