<!DOCTYPE html>
<html>
<head>
    <title>Debug Policy Insertion</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>Policy Insertion Debug</h1>

    <?php
    include 'include/config.php';

    echo "<div class='debug-info'>";
    echo "<h2>Database Connection Test</h2>";
    
    if ($con->connect_error) {
        echo "<p class='error'>❌ Database connection failed: " . $con->connect_error . "</p>";
    } else {
        echo "<p class='success'>✅ Database connected successfully</p>";
    }

    // Test if new columns exist
    echo "<h2>Table Structure Test</h2>";
    $test_query = "SHOW COLUMNS FROM policy";
    $result = mysqli_query($con, $test_query);
    
    if ($result) {
        echo "<p class='success'>✅ Policy table accessible</p>";
        echo "<h3>Current Columns:</h3>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li><strong>" . $row['Field'] . "</strong> - " . $row['Type'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>❌ Cannot access policy table: " . mysqli_error($con) . "</p>";
    }

    // Test simple insert
    echo "<h2>Test Simple Insert</h2>";
    $test_insert = "INSERT INTO policy (
        name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
        policy_issue_date, policy_start_date, policy_end_date, premium, revenue, created_date
    ) VALUES (
        'TEST USER', '9999999999', 'TEST123', 'Car', 'Test Insurance', 'Third Party',
        '2025-07-25', '2025-07-25', '2026-07-24', 5000.00, 500.00, NOW()
    )";
    
    $insert_result = mysqli_query($con, $test_insert);
    
    if ($insert_result) {
        $new_id = mysqli_insert_id($con);
        echo "<p class='success'>✅ Test policy inserted successfully! ID: $new_id</p>";
        
        // Clean up test data
        mysqli_query($con, "DELETE FROM policy WHERE id = $new_id");
        echo "<p class='warning'>⚠️ Test policy cleaned up</p>";
    } else {
        echo "<p class='error'>❌ Test insert failed: " . mysqli_error($con) . "</p>";
        echo "<p>SQL: $test_insert</p>";
    }

    // Check recent policies
    echo "<h2>Recent Policies (Last 5)</h2>";
    $recent_query = "SELECT id, name, vehicle_number, created_date FROM policy ORDER BY id DESC LIMIT 5";
    $recent_result = mysqli_query($con, $recent_query);
    
    if ($recent_result && mysqli_num_rows($recent_result) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Vehicle Number</th><th>Created Date</th></tr>";
        while ($row = mysqli_fetch_assoc($recent_result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['vehicle_number'] . "</td>";
            echo "<td>" . $row['created_date'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ No recent policies found</p>";
    }

    mysqli_close($con);
    echo "</div>";
    ?>

    <div style="margin-top: 20px;">
        <h3>Quick Actions:</h3>
        <a href="migration.php" style="display: inline-block; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Run Database Migration</a>
        <a href="policies.php" style="display: inline-block; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Go to Policies</a>
    </div>
</body>
</html>
