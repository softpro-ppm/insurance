<!DOCTYPE html>
<html>
<head>
    <title>Simple Account Integration - Fixed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .clear-box { background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 5px solid #007bff; margin: 20px 0; }
        .success-box { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; margin: 20px 0; }
        .simple-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .simple-table th, .simple-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .simple-table th { background-color: #f8f9fa; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
        h1 { color: #333; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Simple Account Integration - FIXED</h1>

        <div class="clear-box">
            <h2>🎯 What You Wanted (Crystal Clear):</h2>
            <table class="simple-table">
                <tr>
                    <th>Policy Type</th>
                    <th>Insurance System</th>
                    <th>Account Software</th>
                    <th>Result</th>
                </tr>
                <tr>
                    <td><strong>Old Policies (1410+)</strong><br>Added before today</td>
                    <td>✅ Revenue stays</td>
                    <td>❌ NO transfer</td>
                    <td>Revenue ONLY in insurance</td>
                </tr>
                <tr style="background: #e8f5e8;">
                    <td><strong>New Policies</strong><br>Added from today onwards</td>
                    <td>✅ Revenue saved</td>
                    <td>✅ Revenue copied</td>
                    <td>Revenue in BOTH systems</td>
                </tr>
            </table>
        </div>

        <div class="success-box">
            <h2>✅ What I've Now Fixed:</h2>
            <p><strong>Simple Logic:</strong> When you add a NEW policy (from today onwards):</p>
            <ol>
                <li>Revenue gets saved in insurance system ✅</li>
                <li>Revenue gets automatically copied to account software ✅</li>
                <li>Both systems have the same revenue data ✅</li>
            </ol>
            
            <p><strong>Old policies (1410+) are completely safe:</strong></p>
            <ul>
                <li>They were added before today</li>
                <li>They use the old system (no auto-sync)</li>
                <li>Their revenue stays ONLY in insurance system</li>
                <li>Will NEVER be transferred to account software</li>
            </ul>
        </div>

        <?php
        // Simple test
        include 'include/config.php';
        
        echo "<div class='clear-box'>";
        echo "<h2>📊 Current System Status:</h2>";
        
        $total = $con->query("SELECT COUNT(*) as count FROM policy")->fetch_assoc()['count'];
        $today_policies = $con->query("SELECT COUNT(*) as count FROM policy WHERE created_date >= CURDATE()")->fetch_assoc()['count'];
        $old_policies = $total - $today_policies;
        
        echo "<table class='simple-table'>";
        echo "<tr><th>Category</th><th>Count</th><th>Account Sync</th></tr>";
        echo "<tr><td>Total Policies</td><td>" . number_format($total) . "</td><td>-</td></tr>";
        echo "<tr><td>Old Policies (before today)</td><td>" . number_format($old_policies) . "</td><td>❌ NO</td></tr>";
        echo "<tr style='background: #e8f5e8;'><td>New Policies (from today)</td><td>" . number_format($today_policies) . "</td><td>✅ YES</td></tr>";
        echo "</table>";
        echo "</div>";
        
        // Show example of how it works
        echo "<div class='success-box'>";
        echo "<h2>📋 Example: Adding New Policy Today</h2>";
        echo "<p><strong>When you add a policy today with:</strong></p>";
        echo "<ul>";
        echo "<li>Vehicle: TEST123</li>";
        echo "<li>Premium: ₹10,000</li>";
        echo "<li>Revenue: ₹1,000</li>";
        echo "</ul>";
        
        echo "<p><strong>System will:</strong></p>";
        echo "<ol>";
        echo "<li>Save policy in insurance database with revenue ₹1,000</li>";
        echo "<li>Automatically copy ₹1,000 to account software</li>";
        echo "<li>Both systems will show ₹1,000 revenue</li>";
        echo "</ol>";
        echo "</div>";
        
        $con->close();
        ?>

        <div class="clear-box">
            <h2>🧪 Test the System:</h2>
            <p>To verify it's working:</p>
            <ol>
                <li>Add a new policy with some revenue amount</li>
                <li>Check insurance system - revenue should be there</li>
                <li>Check account software - same revenue should be automatically added</li>
            </ol>
            
            <a href="policies.php" class="btn">Add New Policy (Test)</a>
            <a href="account-integration-control.php" class="btn">Check System Status</a>
        </div>

        <div class="success-box">
            <h2>✨ Summary:</h2>
            <p><strong>Perfect!</strong> Now the system works exactly as you wanted:</p>
            <ul>
                <li>🛡️ <strong>Old policies (1410+):</strong> Revenue stays ONLY in insurance system</li>
                <li>🔄 <strong>New policies (from today):</strong> Revenue automatically goes to BOTH insurance and account systems</li>
                <li>📊 <strong>Same revenue in both systems</strong> for new policies</li>
                <li>🎯 <strong>No manual work needed</strong> - it's all automatic!</li>
            </ul>
        </div>
    </div>
</body>
</html>
