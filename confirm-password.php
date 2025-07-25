<?php
// Test Insurance Database Connection with Password: Softpro@123
echo "🔧 Testing Insurance Database Connection\n";
echo "========================================\n\n";

$host = "localhost";
$username = "u820431346_newinsurance";
$password = "Softpro@123";
$database = "u820431346_newinsurance";

echo "Testing with credentials:\n";
echo "Host: $host\n";
echo "Username: $username\n";
echo "Password: $password\n";
echo "Database: $database\n\n";

// Test connection
$con = @new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    echo "❌ CONNECTION FAILED\n";
    echo "Error: " . $con->connect_error . "\n\n";
    echo "The password 'Softpro@123' is NOT working.\n";
    echo "You may need to:\n";
    echo "1. Double-check the password in your hosting panel\n";
    echo "2. Contact hosting provider to reset it\n";
    echo "3. Verify the user exists and has permissions\n";
} else {
    echo "✅ CONNECTION SUCCESSFUL!\n";
    echo "Password 'Softpro@123' is WORKING!\n\n";
    
    // Test database access
    $result = $con->query("SHOW TABLES");
    if ($result) {
        $table_count = $result->num_rows;
        echo "✅ Database access confirmed\n";
        echo "✅ Found $table_count tables in database\n\n";
        
        echo "🎉 READY TO RESTORE FULL FUNCTIONALITY!\n";
        echo "The system can now save policy records.\n";
    } else {
        echo "⚠️ Connected but cannot access tables\n";
        echo "May need to check user permissions\n";
    }
    
    $con->close();
}

echo "\n========================================\n";
echo "Test completed.\n";
?>
