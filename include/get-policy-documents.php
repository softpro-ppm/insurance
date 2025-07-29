<?php
require 'session.php';
require 'config.php';

header('Content-Type: application/json');

if (!isset($_POST['policy_id']) || empty($_POST['policy_id'])) {
    echo json_encode(['success' => false, 'message' => 'No policy ID provided']);
    exit;
}

$policy_id = mysqli_real_escape_string($con, $_POST['policy_id']);

try {
    // Get existing documents for this policy
    $sql = "SELECT document_type, file_name, file_path FROM policy_documents WHERE policy_id = '$policy_id'";
    $result = mysqli_query($con, $sql);
    
    $documents = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $documents[$row['document_type']] = [
                'file_name' => $row['file_name'],
                'file_path' => $row['file_path']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'documents' => $documents
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
