<?php
// Test account database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Account Database Connection Test</h1>";

try {
    // Include account connection
    include 'include/account.php';
    
    echo "<p style='color: green;'>✅ Account database connection successful!</p>";
    
    // Test income table structure
    $result = $acc->query("DESCRIBE income");
    if ($result) {
        echo "<h3>📋 Income Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test recent records
    $result = $acc->query("SELECT * FROM income ORDER BY created_at DESC LIMIT 5");
    if ($result && $result->num_rows > 0) {
        echo "<h3>🔍 Recent Income Records:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px;'>";
        echo "<tr><th>ID</th><th>Date</th><th>Name</th><th>Phone</th><th>Description</th><th>Amount</th><th>Received</th><th>Insurance ID</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['date']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['phone']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "<td>₹{$row['amount']}</td>";
            echo "<td>₹{$row['received']}</td>";
            echo "<td>{$row['insurance_id']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No income records found</p>";
    }
    
    $acc->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><a href='policies.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
?>
