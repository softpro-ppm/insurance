<?php
// Simple test to check if view-policy.php is working
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing view-policy.php functionality...<br>";

// Simulate POST data
$_POST['id'] = 1; // Test with policy ID 1

echo "POST ID: " . $_POST['id'] . "<br>";

// Include the view policy file
ob_start();
include 'include/view-policy.php';
$output = ob_get_clean();

echo "Output length: " . strlen($output) . "<br>";

if (strlen($output) > 0) {
    echo "SUCCESS: view-policy.php is working<br>";
    echo "First 500 characters:<br>";
    echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
} else {
    echo "ERROR: No output from view-policy.php<br>";
}
?>
