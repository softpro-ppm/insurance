<?php
// Test policy data loading
session_start();
include 'include/config.php';

// Test with a specific policy ID
$test_policy_id = 1; // Change this to test different policies

echo "<h3>Testing Policy Data Loading</h3>";

try {
    // Test basic connection
    echo "<p>✅ Database connected successfully</p>";
    
    // Test query
    $stmt = $con->prepare("SELECT * FROM policy WHERE id = ?");
    $stmt->bind_param("i", $test_policy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        echo "<p>✅ Policy found: " . $policy['vehicle_number'] . "</p>";
        
        // Show policy data
        echo "<h4>Policy Data:</h4>";
        echo "<table border='1'>";
        foreach ($policy as $key => $value) {
            echo "<tr><td><strong>$key</strong></td><td>" . ($value ?? 'NULL') . "</td></tr>";
        }
        echo "</table>";
        
        // Test JSON encoding
        $json_response = json_encode(['success' => true, 'policy' => $policy]);
        if ($json_response === false) {
            echo "<p>❌ JSON encoding failed: " . json_last_error_msg() . "</p>";
        } else {
            echo "<p>✅ JSON encoding successful</p>";
        }
        
    } else {
        echo "<p>❌ No policy found with ID: $test_policy_id</p>";
        
        // Show available policies
        $result = $con->query("SELECT id, vehicle_number FROM policy LIMIT 5");
        echo "<h4>Available Policies:</h4>";
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: " . $row['id'] . " - Vehicle: " . $row['vehicle_number'] . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

// Test the actual get-policy-data.php endpoint
echo "<h3>Testing GET Request</h3>";
if (isset($_GET['test_id'])) {
    $test_id = intval($_GET['test_id']);
    echo "<p>Testing with policy ID: $test_id</p>";
    include 'include/get-policy-data.php';
} else {
    echo "<p><a href='?test_id=1'>Test with Policy ID 1</a></p>";
    echo "<p><a href='?test_id=2'>Test with Policy ID 2</a></p>";
    echo "<p><a href='?test_id=3'>Test with Policy ID 3</a></p>";
}

$con->close();
?>
