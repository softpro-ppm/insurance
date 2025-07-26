<?php
// get-policy-files.php - Fetch uploaded files for a policy
session_start();
include 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
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
    // Fetch policy files
    $files_query = $con->prepare("SELECT * FROM files WHERE policy_id = ?");
    $files_query->bind_param("i", $policy_id);
    $files_query->execute();
    $files_result = $files_query->get_result();
    
    $policy_files = [];
    while ($file = $files_result->fetch_assoc()) {
        $policy_files[] = [
            'id' => $file['id'],
            'filename' => $file['files'],
            'upload_path' => 'assets/uploads/' . $file['files']
        ];
    }
    $files_query->close();
    
    // Fetch RC files
    $rc_query = $con->prepare("SELECT * FROM rc WHERE policy_id = ?");
    $rc_query->bind_param("i", $policy_id);
    $rc_query->execute();
    $rc_result = $rc_query->get_result();
    
    $rc_files = [];
    while ($file = $rc_result->fetch_assoc()) {
        $rc_files[] = [
            'id' => $file['id'],
            'filename' => $file['files'],
            'upload_path' => 'assets/uploads/' . $file['files']
        ];
    }
    $rc_query->close();
    
    echo json_encode([
        'success' => true, 
        'policy_files' => $policy_files,
        'rc_files' => $rc_files
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$con->close();
?>
