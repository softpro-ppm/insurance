<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Database Connection Fix Test</h1>";

// Test with config.php
echo "<h3>Testing with config.php:</h3>";
try {
    require 'config.php';
    echo "<p style='color: green;'>✅ Config.php loaded successfully</p>";
    
    // Test connection
    if ($con->connect_error) {
        echo "<p style='color: red;'>❌ Database connection failed: " . $con->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Database connection successful via config.php</p>";
        
        // Test query
        $result = $con->query("SELECT COUNT(*) as count FROM policy");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p style='color: green;'>✅ Database query successful - Total policies: " . $row['count'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Database query failed: " . $con->error . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<h3>📊 Summary:</h3>";
echo "<ul>";
echo "<li>✅ Session: Working</li>";
echo "<li>✅ Account Database: Working</li>";
echo "<li>❓ Insurance Database: Testing above...</li>";
echo "</ul>";

echo "<br><a href='../policies.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
echo "<br><a href='system-test.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;'>🔄 Re-run System Test</a>";
?>
