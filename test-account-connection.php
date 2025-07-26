<?php
// Quick account database test
require_once 'include/config.php';

echo "<h2>🔍 Account Database Connection Test</h2>";

try {
    require_once 'include/account.php';
    echo "✅ Account database connected successfully<br>";
    
    // Check if income table exists
    $table_check = $acc->query("SHOW TABLES LIKE 'income'");
    if ($table_check->num_rows > 0) {
        echo "✅ Income table exists<br>";
        
        // Get table structure
        $columns = $acc->query("DESCRIBE income");
        echo "<h3>Income Table Structure:</h3>";
        echo "<table border='1'>";
        while ($col = $columns->fetch_assoc()) {
            echo "<tr><td>" . $col['Field'] . "</td><td>" . $col['Type'] . "</td><td>" . $col['Null'] . "</td></tr>";
        }
        echo "</table>";
        
        // Test insert
        echo "<h3>Testing Insert:</h3>";
        $test_sql = "INSERT INTO income (amount, source, details, created_at) VALUES (?, ?, ?, NOW())";
        $test_stmt = $acc->prepare($test_sql);
        $amount = 999.99;
        $source = "TEST INSERT";
        $details = "Test from debug script - " . date('Y-m-d H:i:s');
        $test_stmt->bind_param("dss", $amount, $source, $details);
        
        if ($test_stmt->execute()) {
            echo "✅ Test insert successful - ID: " . $acc->insert_id . "<br>";
            
            // Delete the test record
            $delete_sql = "DELETE FROM income WHERE id = ?";
            $delete_stmt = $acc->prepare($delete_sql);
            $test_id = $acc->insert_id;
            $delete_stmt->bind_param("i", $test_id);
            $delete_stmt->execute();
            echo "✅ Test record cleaned up<br>";
        } else {
            echo "❌ Test insert failed: " . $test_stmt->error . "<br>";
        }
        
    } else {
        echo "❌ Income table not found<br>";
        
        // Show available tables
        $tables = $acc->query("SHOW TABLES");
        echo "<h3>Available tables:</h3>";
        while ($table = $tables->fetch_array()) {
            echo "- " . $table[0] . "<br>";
        }
    }
    
    $acc->close();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Check recent policies from insurance database
echo "<h2>🔍 Recent Policies Added</h2>";
$recent_sql = "SELECT id, name, vehicle_number, premium, revenue, payout, customer_paid, discount, created_date 
               FROM policy 
               ORDER BY id DESC 
               LIMIT 3";
$recent_result = mysqli_query($con, $recent_sql);

if (mysqli_num_rows($recent_result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Vehicle</th><th>Customer</th><th>Premium</th><th>Revenue</th><th>Date</th></tr>";
    
    while ($row = mysqli_fetch_assoc($recent_result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['vehicle_number'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>₹" . $row['premium'] . "</td>";
        echo "<td>₹" . $row['revenue'] . "</td>";
        echo "<td>" . $row['created_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
