<?php
// Test database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Local database configuration for development
$host = "localhost";
$username = "root";  // Default MySQL username for local development
$password = "";      // Default empty password for local development
$database = "insurance_local";  // Local database name

// Try to connect
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error . "<br>";
    
    // Try without database first to see if MySQL is running
    $con_test = new mysqli($host, $username, $password);
    if ($con_test->connect_errno) {
        echo "MySQL server is not running or credentials are wrong.<br>";
        echo "Error: " . $con_test->connect_error . "<br>";
    } else {
        echo "MySQL server is running, but database '$database' doesn't exist.<br>";
        echo "We need to create the database or check the configuration.<br>";
        $con_test->close();
    }
} else {
    echo "✅ Successfully connected to database '$database'<br>";
    
    // Check if policy table exists
    $result = $con->query("SHOW TABLES LIKE 'policy'");
    if ($result && $result->num_rows > 0) {
        echo "✅ Policy table exists<br>";
        
        // Check table structure
        $result = $con->query("DESCRIBE policy");
        if ($result) {
            echo "<h3>Policy Table Structure:</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Count total policies
        $result = $con->query("SELECT COUNT(*) as total FROM policy");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<br>Total policies in database: " . $row['total'] . "<br>";
        }
    } else {
        echo "❌ Policy table doesn't exist<br>";
    }
    
    $con->close();
}
?>
