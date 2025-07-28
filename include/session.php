<?php 
// Secure session configuration
ini_set('session.cookie_httponly', 1);
// Only set secure cookies if we're on HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
ini_set('session.use_strict_mode', 1);

session_start(); 

// Session timeout (30 minutes)
$timeout_duration = 1800;

// Check if session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    
    // For AJAX requests, return JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Session expired']);
        exit();
    }
    
    echo "<script>alert('Session expired. Please login again.'); window.location.href='index.php';</script>";
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Check if user is logged in (but allow AJAX requests to handle this gracefully)
if (empty($_SESSION['username'])) { 
    // For AJAX requests, return JSON response instead of redirecting
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit();
    }
    
    echo "<script>window.location.href='index.php';</script>"; 
    exit();
}

// Regenerate session ID periodically for security
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > 300) { // 5 minutes
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}
?>