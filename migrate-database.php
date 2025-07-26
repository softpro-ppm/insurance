<?php
// migrate-database.php - Execute database migration for enhanced policy system
include 'include/config.php';

echo "=== Enhanced Policy System Database Migration ===\n\n";

// Test if new columns already exist
echo "Checking current database structure...\n";
$test_query = "SELECT payout, customer_paid, discount, calculated_revenue, comments, updated_at FROM policy LIMIT 1";
$test_result = mysqli_query($con, $test_query);

if ($test_result) {
    echo "✅ New columns already exist in the database!\n";
    echo "Database is ready for the enhanced financial system.\n\n";
} else {
    echo "❌ New columns do not exist. Starting migration...\n\n";
    
    // Migration queries
    $migrations = [
        "ALTER TABLE policy ADD COLUMN payout DECIMAL(10,2) NULL AFTER revenue",
        "ALTER TABLE policy ADD COLUMN customer_paid DECIMAL(10,2) NULL AFTER payout", 
        "ALTER TABLE policy ADD COLUMN discount DECIMAL(10,2) NULL AFTER customer_paid",
        "ALTER TABLE policy ADD COLUMN calculated_revenue DECIMAL(10,2) NULL AFTER discount",
        "ALTER TABLE policy ADD COLUMN comments TEXT NULL AFTER calculated_revenue",
        "ALTER TABLE policy ADD COLUMN updated_at TIMESTAMP NULL AFTER comments"
    ];
    
    $success_count = 0;
    $total_count = count($migrations);
    
    foreach ($migrations as $index => $migration) {
        echo "Running migration " . ($index + 1) . "/$total_count: ";
        $result = mysqli_query($con, $migration);
        
        if ($result) {
            echo "✅ SUCCESS\n";
            $success_count++;
        } else {
            echo "❌ FAILED: " . mysqli_error($con) . "\n";
        }
    }
    
    echo "\n=== Migration Summary ===\n";
    echo "Successful: $success_count/$total_count\n";
    
    if ($success_count === $total_count) {
        echo "🎉 All migrations completed successfully!\n";
        echo "The enhanced financial system is now ready to use.\n";
    } else {
        echo "⚠️  Some migrations failed. Please check the errors above.\n";
    }
}

// Test the final structure
echo "\n=== Final Database Test ===\n";
$final_test = mysqli_query($con, "SELECT payout, customer_paid, discount, calculated_revenue, comments, updated_at FROM policy LIMIT 1");

if ($final_test) {
    echo "✅ Database structure verification passed!\n";
    echo "Enhanced policy system is ready for use.\n";
} else {
    echo "❌ Database structure verification failed: " . mysqli_error($con) . "\n";
}

mysqli_close($con);
echo "\nMigration script completed.\n";
?>
