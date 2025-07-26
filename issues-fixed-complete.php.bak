<!DOCTYPE html>
<html>
<head>
    <title>🔧 Issues Fixed - Complete Solution</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .success-box { background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; }
        .info-box { background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff; }
        .warning-box { background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .error-box { background: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-danger { background: #dc3545; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
        code { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Both Issues Fixed!</h1>
        
        <div class="success-box">
            <h2>✅ Issue #1: Account Sync FIXED</h2>
            <p><strong>Problem:</strong> Policies added via modal weren't syncing revenue to account software</p>
            <p><strong>Solution Applied:</strong></p>
            <ul>
                <li>✅ Enhanced account sync with better error logging</li>
                <li>✅ Added debug logging to track sync process</li>
                <li>✅ Fixed file path for account.php inclusion</li>
                <li>✅ Improved success/error messages</li>
            </ul>
        </div>

        <div class="success-box">
            <h2>✅ Issue #2: Old Edit Modal FIXED</h2>
            <p><strong>Problem:</strong> Some edit buttons were opening old edit.php page instead of modern modal</p>
            <p><strong>Solution Applied:</strong></p>
            <ul>
                <li>✅ Fixed edit buttons in home.php (renewals section)</li>
                <li>✅ Fixed edit buttons in policies ajax data</li>
                <li>✅ Fixed edit button in view policy modal</li>
                <li>✅ Added edit modal support to home.php</li>
            </ul>
        </div>

        <?php
        // Test account database connection
        echo "<div class='info-box'>";
        echo "<h2>🔍 Account Database Test</h2>";
        
        try {
            require_once 'include/account.php';
            echo "<p>✅ Account database connection successful</p>";
            
            // Check income table
            $table_check = $acc->query("SHOW TABLES LIKE 'income'");
            if ($table_check->num_rows > 0) {
                echo "<p>✅ Income table exists</p>";
                
                // Get table structure
                $columns = $acc->query("DESCRIBE income");
                echo "<h3>Income Table Structure:</h3>";
                echo "<table>";
                echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
                while ($col = $columns->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $col['Field'] . "</td>";
                    echo "<td>" . $col['Type'] . "</td>";
                    echo "<td>" . $col['Null'] . "</td>";
                    echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Check recent entries
                $recent = $acc->query("SELECT * FROM income ORDER BY id DESC LIMIT 3");
                if ($recent && $recent->num_rows > 0) {
                    echo "<h3>Recent Income Entries:</h3>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Amount</th><th>Source</th><th>Details</th></tr>";
                    while ($row = $recent->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['source']) . "</td>";
                        echo "<td>" . htmlspecialchars(substr($row['details'], 0, 50)) . "...</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>⚠️ No recent income entries found</p>";
                }
                
            } else {
                echo "<p>❌ Income table not found</p>";
            }
            
            $acc->close();
        } catch (Exception $e) {
            echo "<p>❌ Account database error: " . $e->getMessage() . "</p>";
        }
        echo "</div>";

        // Check recent policies from insurance database  
        require_once 'include/config.php';
        echo "<div class='info-box'>";
        echo "<h2>📊 Recent Policies (Last 3)</h2>";
        
        $recent_sql = "SELECT id, name, vehicle_number, premium, revenue, payout, customer_paid, discount, created_date 
                       FROM policy 
                       ORDER BY id DESC 
                       LIMIT 3";
        $recent_result = mysqli_query($con, $recent_sql);
        
        if (mysqli_num_rows($recent_result) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Vehicle</th><th>Customer</th><th>Premium</th><th>Revenue</th><th>Payout</th><th>Customer Paid</th><th>Date</th></tr>";
            
            while ($row = mysqli_fetch_assoc($recent_result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['vehicle_number'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
                echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
                echo "<td>₹" . number_format($row['payout'], 2) . "</td>";
                echo "<td>₹" . number_format($row['customer_paid'], 2) . "</td>";
                echo "<td>" . $row['created_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No policies found.</p>";
        }
        echo "</div>";
        ?>

        <div class="warning-box">
            <h2>🧪 Test Instructions</h2>
            <h3>Test 1: Account Sync</h3>
            <ol>
                <li>Go to <code>policies.php</code></li>
                <li>Click "Add Policy" button (modal should open)</li>
                <li>Fill test data with revenue > 0 (e.g. Premium: 10000, Payout: 3000, Customer Paid: 8000)</li>
                <li>Submit form</li>
                <li><strong>Expected:</strong> Success message should show "✅ ₹1000 synced to account software"</li>
                <li>Refresh this page to verify entry appears in account database</li>
            </ol>

            <h3>Test 2: Edit Modal</h3>
            <ol>
                <li>Go to <code>home.php</code> - check renewals section edit buttons</li>
                <li>Go to <code>policies.php</code> - check edit buttons in table</li>
                <li>Click any vehicle number to open view modal, then click edit</li>
                <li><strong>Expected:</strong> All should open the modern edit modal with new financial fields</li>
            </ol>
        </div>

        <div class="success-box">
            <h2>✅ All Fixed Features</h2>
            <ul>
                <li>🔄 <strong>Account Sync:</strong> New policies automatically sync revenue to account software</li>
                <li>📱 <strong>Edit Modal:</strong> All edit buttons now use modern modal with financial calculations</li>
                <li>🔍 <strong>Debug Logging:</strong> Enhanced error logging for troubleshooting</li>
                <li>💰 <strong>Success Messages:</strong> Detailed success messages show sync status</li>
                <li>🛠️ <strong>Error Handling:</strong> Better error handling and user feedback</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="policies.php" class="btn btn-success">🧪 Test Add Policy</a>
            <a href="home.php" class="btn btn-warning">🏠 Test Home Edit Buttons</a>
            <a href="?" class="btn">🔄 Refresh This Page</a>
        </div>
    </div>
</body>
</html>
