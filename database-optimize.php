<?php
/**
 * Database Optimization Script
 * Adds indexes for better performance
 */

require_once 'config.php';

// Optimization queries
$optimizationQueries = [
    // Indexes for policy table
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_end_date (policy_end_date)",
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_issue_date (policy_issue_date)", 
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_status (status)",
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_type (policy_type)",
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_vehicle_number (vehicle_number)",
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_search (name, phone, vehicle_number)",
    "ALTER TABLE policy ADD INDEX IF NOT EXISTS idx_policy_dates (policy_issue_date, policy_end_date)",
    
    // Indexes for user table
    "ALTER TABLE user ADD INDEX IF NOT EXISTS idx_username (username)",
    "ALTER TABLE user ADD INDEX IF NOT EXISTS idx_user_status (status)",
    
    // Optimize table storage
    "OPTIMIZE TABLE policy",
    "OPTIMIZE TABLE user"
];

echo "<h2>Database Optimization Results</h2>";
echo "<pre>";

foreach ($optimizationQueries as $query) {
    echo "Executing: " . $query . "\n";
    
    $result = mysqli_query($con, $query);
    
    if ($result) {
        echo "✅ Success\n\n";
    } else {
        echo "❌ Error: " . mysqli_error($con) . "\n\n";
    }
}

// Show current indexes
echo "\n=== Current Indexes on Policy Table ===\n";
$indexQuery = "SHOW INDEX FROM policy";
$result = mysqli_query($con, $indexQuery);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Key_name'] . " - " . $row['Column_name'] . "\n";
    }
}

echo "</pre>";
echo "<p><strong>Database optimization completed!</strong></p>";
?>
