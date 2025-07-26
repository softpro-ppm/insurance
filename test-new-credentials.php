<?php
// Quick database connection test with new credentials
echo "🔧 Testing Insurance Database with New Credentials\n";
echo "=================================================\n\n";

// Test Insurance Database with new password
$host = "localhost";
$username = "u820431346_newinsurance";
$password = "Softpro@123"; // New password
$database = "u820431346_newinsurance";

echo "Testing connection to:\n";
echo "Host: $host\n";
echo "Username: $username\n";
echo "Database: $database\n\n";

$insurance_con = @new mysqli($host, $username, $password, $database);
if ($insurance_con->connect_error) {
    echo "❌ Insurance DB Failed: " . $insurance_con->connect_error . "\n";
    $success = false;
} else {
    echo "✅ Insurance DB: Connected successfully!\n";
    
    // Test a simple query
    $result = $insurance_con->query("SHOW TABLES");
    if ($result) {
        $table_count = $result->num_rows;
        echo "✅ Database has $table_count tables\n";
    }
    
    $insurance_con->close();
    $success = true;
}

if ($success) {
    echo "\n🎉 SUCCESS! Database connection restored!\n";
    echo "Ready to update form action back to original...\n";
} else {
    echo "\n❌ Connection still failing. Please check credentials.\n";
}
?>
