<?php
include 'include/config.php';

echo "<h2>Database Column Check</h2>";

// Test if new columns exist by trying to select them
$test_query = "SELECT payout, customer_paid, discount, calculated_revenue, comments, updated_at FROM policy LIMIT 1";
$test_result = mysqli_query($con, $test_query);

if ($test_result) {
    echo "<p style='color: green;'>✅ New columns exist in the database!</p>";
    echo "<p>Database is ready for the enhanced financial system.</p>";
} else {
    echo "<p style='color: red;'>❌ New columns do not exist. Error: " . mysqli_error($con) . "</p>";
    echo "<p>Database migration is needed.</p>";
    
    // Show the migration script
    echo "<h3>Migration Script Needed:</h3>";
    echo "<pre>";
    echo "ALTER TABLE policy ADD COLUMN payout DECIMAL(10,2) NULL AFTER revenue;
ALTER TABLE policy ADD COLUMN customer_paid DECIMAL(10,2) NULL AFTER payout;
ALTER TABLE policy ADD COLUMN discount DECIMAL(10,2) NULL AFTER customer_paid;
ALTER TABLE policy ADD COLUMN calculated_revenue DECIMAL(10,2) NULL AFTER discount;
ALTER TABLE policy ADD COLUMN comments TEXT NULL AFTER calculated_revenue;
ALTER TABLE policy ADD COLUMN updated_at TIMESTAMP NULL AFTER comments;";
    echo "</pre>";
}

// Also test a sample row
$sample_query = "SELECT * FROM policy LIMIT 1";
$sample_result = mysqli_query($con, $sample_query);

if ($sample_result && mysqli_num_rows($sample_result) > 0) {
    $row = mysqli_fetch_assoc($sample_result);
    echo "<h3>Sample Row Structure:</h3>";
    echo "<pre>";
    foreach ($row as $column => $value) {
        echo "$column: $value\n";
    }
    echo "</pre>";
}

mysqli_close($con);
?>
