<!DOCTYPE html>
<html>
<head>
    <title>Database Migration - Enhanced Policy System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .migration-box { background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Enhanced Policy System - Database Migration</h1>
    
    <?php
    if (isset($_POST['run_migration'])) {
        include 'include/config.php';
        
        echo "<div class='migration-box'>";
        echo "<h2>Migration Results</h2>";
        
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
            echo "<p><strong>Migration " . ($index + 1) . "/$total_count:</strong> ";
            $result = mysqli_query($con, $migration);
            
            if ($result) {
                echo "<span class='success'>✅ SUCCESS</span></p>";
                $success_count++;
            } else {
                $error = mysqli_error($con);
                if (strpos($error, 'Duplicate column name') !== false) {
                    echo "<span class='warning'>⚠️ COLUMN ALREADY EXISTS</span></p>";
                    $success_count++;
                } else {
                    echo "<span class='error'>❌ FAILED: $error</span></p>";
                }
            }
        }
        
        echo "<hr>";
        echo "<h3>Migration Summary</h3>";
        echo "<p><strong>Successful:</strong> $success_count/$total_count</p>";
        
        if ($success_count === $total_count) {
            echo "<p class='success'>🎉 <strong>All migrations completed successfully!</strong></p>";
            echo "<p class='info'>The enhanced financial system is now ready to use.</p>";
        } else {
            echo "<p class='error'>⚠️ Some migrations failed. Please check the errors above.</p>";
        }
        
        // Test the final structure
        echo "<h3>Database Structure Verification</h3>";
        $final_test = mysqli_query($con, "SELECT payout, customer_paid, discount, calculated_revenue, comments, updated_at FROM policy LIMIT 1");
        
        if ($final_test) {
            echo "<p class='success'>✅ Database structure verification passed!</p>";
            echo "<p class='info'>Enhanced policy system is ready for use.</p>";
        } else {
            echo "<p class='error'>❌ Database structure verification failed: " . mysqli_error($con) . "</p>";
        }
        
        mysqli_close($con);
        echo "</div>";
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>✅ Migration Complete!</h3>";
        echo "<p>You can now:</p>";
        echo "<ul>";
        echo "<li>Use the enhanced Add Policy modal with 6 financial fields</li>";
        echo "<li>Use the new Edit Policy modal</li>";
        echo "<li>Auto-calculate discount (Premium - Customer Paid) and revenue (Payout - Discount)</li>";
        echo "<li>Auto-calculate policy end dates (Start Date + 1 Year - 1 Day)</li>";
        echo "</ul>";
        echo "<p><a href='policies.php'>Go to Policies Page</a> to test the new features!</p>";
        echo "</div>";
        
    } else {
        // Check current status
        include 'include/config.php';
        
        echo "<div class='migration-box'>";
        echo "<h2>Current Database Status</h2>";
        
        $test_query = "SELECT payout, customer_paid, discount, calculated_revenue, comments, updated_at FROM policy LIMIT 1";
        $test_result = mysqli_query($con, $test_query);
        
        if ($test_result) {
            echo "<p class='success'>✅ New columns already exist in the database!</p>";
            echo "<p class='info'>Database is ready for the enhanced financial system.</p>";
            echo "<p><a href='policies.php'>Go to Policies Page</a> to use the enhanced features!</p>";
        } else {
            echo "<p class='error'>❌ New columns do not exist.</p>";
            echo "<p class='warning'>Database migration is required to enable enhanced features.</p>";
            
            echo "<h3>What this migration will add:</h3>";
            echo "<ul>";
            echo "<li><strong>payout</strong> - Amount to be paid out</li>";
            echo "<li><strong>customer_paid</strong> - Amount paid by customer</li>";
            echo "<li><strong>discount</strong> - Auto-calculated (Premium - Customer Paid)</li>";
            echo "<li><strong>calculated_revenue</strong> - Auto-calculated (Payout - Discount)</li>";
            echo "<li><strong>comments</strong> - Additional notes and comments</li>";
            echo "<li><strong>updated_at</strong> - Timestamp for record updates</li>";
            echo "</ul>";
            
            echo "<h3>Migration Script</h3>";
            echo "<pre>";
            echo "ALTER TABLE policy ADD COLUMN payout DECIMAL(10,2) NULL AFTER revenue;\n";
            echo "ALTER TABLE policy ADD COLUMN customer_paid DECIMAL(10,2) NULL AFTER payout;\n";
            echo "ALTER TABLE policy ADD COLUMN discount DECIMAL(10,2) NULL AFTER customer_paid;\n";
            echo "ALTER TABLE policy ADD COLUMN calculated_revenue DECIMAL(10,2) NULL AFTER discount;\n";
            echo "ALTER TABLE policy ADD COLUMN comments TEXT NULL AFTER calculated_revenue;\n";
            echo "ALTER TABLE policy ADD COLUMN updated_at TIMESTAMP NULL AFTER comments;";
            echo "</pre>";
            
            echo "<form method='POST'>";
            echo "<button type='submit' name='run_migration' class='btn'>Run Database Migration</button>";
            echo "</form>";
        }
        
        mysqli_close($con);
        echo "</div>";
    }
    ?>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3>Enhanced Features Overview</h3>
        <p>After migration, your insurance system will have:</p>
        <ul>
            <li><strong>6 Financial Fields:</strong> Premium, Payout, Customer Paid, Discount, Revenue (New Logic), Legacy Revenue</li>
            <li><strong>Auto-Calculations:</strong> Discount = Premium - Customer Paid, Revenue = Payout - Discount</li>
            <li><strong>Auto-Date Calculation:</strong> Policy End Date = Start Date + 1 Year - 1 Day</li>
            <li><strong>Enhanced Modals:</strong> Clean design with outline styling and hover effects</li>
            <li><strong>Edit Policy Modal:</strong> Edit existing policies with all new features</li>
            <li><strong>Comments System:</strong> Add notes and comments to policies</li>
            <li><strong>Backward Compatibility:</strong> All existing data preserved</li>
        </ul>
    </div>
</body>
</html>
