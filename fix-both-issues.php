<!DOCTYPE html>
<html>
<head>
    <title>Fix Both Issues</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .success { background: #d4edda; padding: 15px; border-radius: 8px; color: #155724; margin: 10px 0; }
        .error { background: #f8d7da; padding: 15px; border-radius: 8px; color: #721c24; margin: 10px 0; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 8px; color: #004085; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
    </style>
</head>
<body>
    <h1>🔧 Fix Both Issues</h1>
    
    <div class="info">
        <h3>Issues Fixed:</h3>
        <ul>
            <li>✅ <strong>Add Policy Error:</strong> Fixed account integration with dynamic column detection</li>
            <li>✅ <strong>Edit Policy Error:</strong> Enhanced get-policy-data.php with better error handling</li>
        </ul>
    </div>

    <div class="success">
        <h3>🚀 Changes Made:</h3>
        
        <h4>1. Fixed Add Policy (add-policies-fixed.php):</h4>
        <ul>
            <li>Added dynamic column detection for account database</li>
            <li>Handles different income table structures</li>
            <li>Account integration won't break policy creation if it fails</li>
            <li>Supports: income_date, date, or minimal column structures</li>
        </ul>
        
        <h4>2. Fixed Edit Policy (get-policy-data.php):</h4>
        <ul>
            <li>Added proper JSON headers</li>
            <li>Enhanced error handling for database operations</li>
            <li>Better NULL value handling for new financial fields</li>
            <li>Proper numeric formatting</li>
            <li>Robust date formatting</li>
            <li>Ensures all required fields exist</li>
        </ul>
    </div>

    <div class="info">
        <h3>🧪 Test Both Fixes:</h3>
        <p><a href="policies.php" class="btn">Test Add Policy</a></p>
        <p><a href="manage-renewal.php" class="btn">Test Edit Policy</a></p>
        <p><a href="test-policy-loading.php" class="btn">Debug Policy Loading</a></p>
    </div>

    <?php
    // Quick test of policy data loading
    session_start();
    $_SESSION['username'] = 'test'; // Simulate login for testing
    
    echo "<div class='info'>";
    echo "<h3>🔍 Quick Test:</h3>";
    
    try {
        include 'include/config.php';
        
        // Get a sample policy
        $result = $con->query("SELECT id, vehicle_number FROM policy LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $sample = $result->fetch_assoc();
            $policy_id = $sample['id'];
            $vehicle = $sample['vehicle_number'];
            
            echo "<p>Testing with Policy ID: $policy_id (Vehicle: $vehicle)</p>";
            
            // Test the get-policy-data.php script
            $_GET['id'] = $policy_id;
            ob_start();
            include 'include/get-policy-data.php';
            $response = ob_get_clean();
            
            $data = json_decode($response, true);
            if ($data && $data['success']) {
                echo "<p>✅ Edit Policy Data Loading: <strong>Working</strong></p>";
                echo "<p>Premium: ₹" . number_format($data['policy']['premium'], 2) . "</p>";
                echo "<p>Revenue: ₹" . number_format($data['policy']['revenue'], 2) . "</p>";
                echo "<p>Payout: ₹" . number_format($data['policy']['payout'], 2) . "</p>";
            } else {
                echo "<p>❌ Edit Policy Data Loading: <strong>Failed</strong></p>";
                echo "<p>Error: " . ($data['message'] ?? 'Unknown error') . "</p>";
            }
        } else {
            echo "<p>No policies found to test with</p>";
        }
        
        $con->close();
    } catch (Exception $e) {
        echo "<p>❌ Test failed: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
    ?>

    <div class="success">
        <h3>✅ Both Issues Should Now Be Fixed!</h3>
        <p>Try adding a new policy and editing an existing policy to confirm everything works.</p>
    </div>
</body>
</html>
