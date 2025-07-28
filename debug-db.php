<?php
// Simple test to debug the database connection
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...\n";

// Include the config file
include 'include/config.php';

// Check connection
if (!isset($con)) {
    echo "ERROR: \$con variable not set\n";
    exit(1);
}

if ($con->connect_errno) {
    echo "ERROR: Database connection failed\n";
    echo "Error: " . $con->connect_error . "\n";
    exit(1);
}

echo "SUCCESS: Database connected\n";

// Test a simple query
$result = $con->query("SELECT COUNT(*) as count FROM policy LIMIT 1");
if ($result) {
    $row = $result->fetch_assoc();
    echo "SUCCESS: Query executed, policies count: " . $row['count'] . "\n";
} else {
    echo "ERROR: Query failed - " . $con->error . "\n";
}

// Test the specific policy ID from the screenshot
$policy_id = 1243;
$stmt = $con->prepare("SELECT id, vehicle_number, name FROM policy WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $policy_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $policy = $result->fetch_assoc();
            echo "SUCCESS: Found policy ID $policy_id - " . $policy['vehicle_number'] . " - " . $policy['name'] . "\n";
        } else {
            echo "WARNING: Policy ID $policy_id not found\n";
        }
    } else {
        echo "ERROR: Execute failed - " . $stmt->error . "\n";
    }
    $stmt->close();
} else {
    echo "ERROR: Prepare failed - " . $con->error . "\n";
}

echo "Test completed.\n";
?>
