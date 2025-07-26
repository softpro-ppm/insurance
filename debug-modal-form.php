<!DOCTYPE html>
<html>
<head>
    <title>🔍 Modal Form Debug Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .test-box { background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff; }
        .result-box { background: #d4edda; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745; }
        .error-box { background: #f8d7da; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #dc3545; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
        code { background: #f8f9fa; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Modal Form Debug Test</h1>
        
        <div class="test-box">
            <h2>🧪 Test Current Form Submission</h2>
            <p><strong>Instructions:</strong></p>
            <ol>
                <li>Click the "Test Add Policy Modal" button below</li>
                <li>Fill out the form with test data</li>
                <li>Submit and see if you get success message and account sync</li>
                <li>Check the results below</li>
            </ol>
            
            <a href="../policies.php" class="btn">🧪 Test Add Policy Modal</a>
            <a href="../" class="btn">🏠 Go to Home</a>
        </div>

        <?php
        // Check recent policy additions
        require_once '../include/config.php';
        
        echo "<div class='test-box'>";
        echo "<h2>📊 Recent Policy Additions (Last 5)</h2>";
        
        $recent_sql = "SELECT id, name, vehicle_number, premium, revenue, payout, customer_paid, discount, calculated_revenue, created_date 
                       FROM policy 
                       ORDER BY id DESC 
                       LIMIT 5";
        $recent_result = mysqli_query($con, $recent_sql);
        
        if (mysqli_num_rows($recent_result) > 0) {
            echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
            echo "<tr style='background: #f8f9fa;'>";
            echo "<th>ID</th><th>Vehicle</th><th>Customer</th><th>Premium</th><th>Customer Paid</th><th>Discount</th><th>Revenue</th><th>Date</th>";
            echo "</tr>";
            
            while ($row = mysqli_fetch_assoc($recent_result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['vehicle_number'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
                echo "<td>₹" . number_format($row['customer_paid'], 2) . "</td>";
                echo "<td>₹" . number_format($row['discount'], 2) . "</td>";
                echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
                echo "<td>" . $row['created_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No policies found.</p>";
        }
        echo "</div>";

        // Check account database sync
        echo "<div class='test-box'>";
        echo "<h2>💰 Account Database Sync Check</h2>";
        
        try {
            require_once '../include/account.php';
            
            // Check if income table exists
            $table_check = $acc->query("SHOW TABLES LIKE 'income'");
            if ($table_check->num_rows > 0) {
                echo "<div class='result-box'>✅ Income table found in account database</div>";
                
                // Get recent income entries
                $income_sql = "SELECT * FROM income ORDER BY id DESC LIMIT 5";
                $income_result = $acc->query($income_sql);
                
                if ($income_result && $income_result->num_rows > 0) {
                    echo "<h3>Recent Income Entries:</h3>";
                    echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
                    echo "<tr style='background: #f8f9fa;'>";
                    
                    // Get column names dynamically
                    $columns = $acc->query("DESCRIBE income");
                    $column_names = [];
                    while ($col = $columns->fetch_assoc()) {
                        $column_names[] = $col['Field'];
                        echo "<th>" . ucfirst($col['Field']) . "</th>";
                    }
                    echo "</tr>";
                    
                    while ($row = $income_result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($column_names as $col) {
                            echo "<td>" . htmlspecialchars($row[$col] ?? '') . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='error-box'>❌ No income entries found</div>";
                }
            } else {
                echo "<div class='error-box'>❌ Income table not found in account database</div>";
            }
            
            $acc->close();
        } catch (Exception $e) {
            echo "<div class='error-box'>❌ Account database connection failed: " . $e->getMessage() . "</div>";
        }
        echo "</div>";

        // Check file upload directory
        echo "<div class='test-box'>";
        echo "<h2>📁 File Upload Directory Check</h2>";
        
        $upload_dir = '../assets/uploads/';
        if (is_dir($upload_dir)) {
            $files = array_diff(scandir($upload_dir), array('.', '..'));
            $file_count = count($files);
            
            echo "<div class='result-box'>✅ Upload directory exists with $file_count files</div>";
            
            if ($file_count > 0) {
                echo "<h3>Recent Files (Last 10):</h3>";
                $recent_files = array_slice(array_reverse($files), 0, 10);
                echo "<ul>";
                foreach ($recent_files as $file) {
                    $file_path = $upload_dir . $file;
                    $file_size = filesize($file_path);
                    $file_time = date('Y-m-d H:i:s', filemtime($file_path));
                    echo "<li><code>$file</code> - " . number_format($file_size) . " bytes - $file_time</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "<div class='error-box'>❌ Upload directory does not exist: $upload_dir</div>";
        }
        echo "</div>";
        ?>

        <div class="test-box">
            <h2>🎯 Quick Test Scenario</h2>
            <p><strong>Try this test:</strong></p>
            <ol>
                <li>Go to <code>policies.php</code></li>
                <li>Click "Add Policy" button</li>
                <li>Fill these values:
                    <ul>
                        <li><strong>Name:</strong> Test Customer</li>
                        <li><strong>Phone:</strong> 9999999999</li>
                        <li><strong>Vehicle Number:</strong> TEST<?php echo date('His'); ?></li>
                        <li><strong>Premium:</strong> 10000</li>
                        <li><strong>Payout:</strong> 3000</li>
                        <li><strong>Customer Paid:</strong> 8000</li>
                    </ul>
                </li>
                <li>Expected calculations:
                    <ul>
                        <li><strong>Discount:</strong> 10000 - 8000 = 2000</li>
                        <li><strong>Revenue:</strong> 3000 - 2000 = 1000</li>
                    </ul>
                </li>
                <li>Submit and check for success message</li>
                <li>Refresh this page to see if policy was added and account synced</li>
            </ol>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="?" class="btn">🔄 Refresh This Page</a>
            <a href="../policies.php" class="btn">🧪 Test Add Policy</a>
        </div>
    </div>
</body>
</html>
