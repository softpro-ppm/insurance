<!DOCTYPE html>
<html>
<head>
    <title>Test New Policy Addition & Account Sync</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .test-box { background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 15px 0; }
        .error-box { background: #f8d7da; padding: 20px; border-radius: 8px; margin: 15px 0; color: #721c24; }
        .success-box { background: #d4edda; padding: 20px; border-radius: 8px; margin: 15px 0; color: #155724; }
        .code-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; font-family: monospace; }
        h1 { color: #333; text-align: center; }
    </style>
</head>
<body>
    <h1>🧪 Test Account Integration Issue</h1>

    <?php
    // Debug the issue step by step
    include 'include/config.php';
    
    echo "<div class='test-box'>";
    echo "<h2>🔍 Testing Account Integration Logic</h2>";
    
    // Test 1: Check if account database connection works
    echo "<h3>1. Account Database Connection Test:</h3>";
    try {
        include 'include/account.php';
        echo "<span style='color: green;'>✅ Account database connected successfully</span><br>";
        
        // Check income table structure
        $table_check = $acc->query("SHOW TABLES LIKE 'income'");
        if ($table_check->num_rows > 0) {
            echo "<span style='color: green;'>✅ Income table exists</span><br>";
            
            $columns = $acc->query("DESCRIBE income");
            echo "<strong>Income table structure:</strong><br>";
            while ($col = $columns->fetch_assoc()) {
                echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
            }
        } else {
            echo "<span style='color: red;'>❌ Income table does not exist</span><br>";
        }
        $acc->close();
    } catch (Exception $e) {
        echo "<span style='color: red;'>❌ Account database error: " . $e->getMessage() . "</span><br>";
    }
    echo "</div>";
    
    // Test 2: Check recent policies
    echo "<div class='test-box'>";
    echo "<h3>2. Recent Policy Check:</h3>";
    $recent_policies = $con->query("SELECT id, vehicle_number, revenue, created_date FROM policy WHERE created_date >= CURDATE() ORDER BY id DESC LIMIT 5");
    
    if ($recent_policies->num_rows > 0) {
        echo "<strong>Today's policies:</strong><br>";
        while ($policy = $recent_policies->fetch_assoc()) {
            echo "ID: " . $policy['id'] . " | Vehicle: " . $policy['vehicle_number'] . " | Revenue: ₹" . $policy['revenue'] . " | Date: " . $policy['created_date'] . "<br>";
        }
    } else {
        echo "<span style='color: orange;'>⚠️ No policies added today</span><br>";
    }
    echo "</div>";
    
    // Test 3: Manual account sync test
    echo "<div class='test-box'>";
    echo "<h3>3. Manual Account Sync Test:</h3>";
    echo "Let's try to add a test revenue entry to account software manually:<br>";
    
    try {
        include 'include/account.php';
        
        $test_amount = 999.99;
        $test_source = "TEST - Manual Sync";
        $test_details = "Testing account integration - " . date('Y-m-d H:i:s');
        
        // Check table structure first
        $columns = $acc->query("DESCRIBE income");
        $column_names = [];
        while ($col = $columns->fetch_assoc()) {
            $column_names[] = $col['Field'];
        }
        
        if (in_array('income_date', $column_names)) {
            $account_sql = "INSERT INTO income (income_date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
            $account_stmt = $acc->prepare($account_sql);
            $income_date = date('Y-m-d');
            $account_stmt->bind_param("sdss", $income_date, $test_amount, $test_source, $test_details);
        } elseif (in_array('date', $column_names)) {
            $account_sql = "INSERT INTO income (date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
            $account_stmt = $acc->prepare($account_sql);
            $income_date = date('Y-m-d');
            $account_stmt->bind_param("sdss", $income_date, $test_amount, $test_source, $test_details);
        } else {
            $account_sql = "INSERT INTO income (amount, source, details) VALUES (?, ?, ?)";
            $account_stmt = $acc->prepare($account_sql);
            $account_stmt->bind_param("dss", $test_amount, $test_source, $test_details);
        }
        
        if ($account_stmt->execute()) {
            echo "<span style='color: green;'>✅ Manual sync successful! Added ₹$test_amount to account software</span><br>";
        } else {
            echo "<span style='color: red;'>❌ Manual sync failed: " . $account_stmt->error . "</span><br>";
        }
        
        $account_stmt->close();
        $acc->close();
    } catch (Exception $e) {
        echo "<span style='color: red;'>❌ Manual sync error: " . $e->getMessage() . "</span><br>";
    }
    echo "</div>";
    
    // Test 4: Check the add-policies-fixed.php logic
    echo "<div class='test-box'>";
    echo "<h3>4. Add Policy Logic Analysis:</h3>";
    echo "<div class='code-box'>";
    echo "Current logic in add-policies-fixed.php:<br><br>";
    echo "if (\$revenue > 0) {<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;// Should sync to account<br>";
    echo "}<br><br>";
    echo "This should work for any revenue > 0<br>";
    echo "</div>";
    
    echo "<strong>Suggestion:</strong> The issue might be that the form is not submitting to 'add-policies-fixed.php'<br>";
    echo "Check if the modal form action is pointing to the correct file.<br>";
    echo "</div>";
    
    $con->close();
    ?>

    <div class="success-box">
        <h2>🎯 Next Steps to Fix:</h2>
        <ol>
            <li><strong>Check Form Action:</strong> Ensure modal form submits to 'include/add-policies-fixed.php'</li>
            <li><strong>Test Revenue Calculation:</strong> Make sure revenue is calculated correctly before saving</li>
            <li><strong>Debug Account Sync:</strong> Add error logging to see what's happening during account sync</li>
            <li><strong>Update Modal Design:</strong> Create modern horizontal view modal with document downloads</li>
        </ol>
    </div>

</body>
</html>
