<?php
// Debug Add Policy Revenue Issue
session_start();

echo "<h3>🔍 Debug: Add Policy Revenue Issue</h3>";

if ($_POST) {
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h4>📝 Form Data Received:</h4>";
    foreach ($_POST as $key => $value) {
        echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
    }
    echo "</div>";
    
    // Simulate the calculation logic from add-policies-fixed.php
    $premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
    $payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
    $customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
    $discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : 0;
    
    // Auto-calculate if values provided
    if ($discount == 0 && $premium > 0 && $customer_paid > 0) {
        $discount = $premium - $customer_paid;
    }
    
    if ($calculated_revenue == 0 && $payout > 0 && $discount >= 0) {
        $calculated_revenue = $payout - $discount;
    }
    
    // BACKWARD COMPATIBILITY: Use old logic for revenue field if new fields not provided
    if ($payout == 0 || $customer_paid == 0) {
        // Old logic: revenue = some default or manual entry
        $revenue = !empty($_POST['revenue']) ? floatval($_POST['revenue']) : 0;
    } else {
        // New logic: revenue = calculated_revenue
        $revenue = $calculated_revenue;
    }
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h4>🧮 Calculated Values:</h4>";
    echo "<p><strong>Premium:</strong> ₹" . number_format($premium, 2) . "</p>";
    echo "<p><strong>Payout:</strong> ₹" . number_format($payout, 2) . "</p>";
    echo "<p><strong>Customer Paid:</strong> ₹" . number_format($customer_paid, 2) . "</p>";
    echo "<p><strong>Discount:</strong> ₹" . number_format($discount, 2) . "</p>";
    echo "<p><strong>Calculated Revenue:</strong> ₹" . number_format($calculated_revenue, 2) . "</p>";
    echo "<p><strong>Final Revenue (to be saved):</strong> ₹" . number_format($revenue, 2) . "</p>";
    echo "</div>";
    
    if ($payout == 0 || $customer_paid == 0) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h4>⚠️ Issue Found:</h4>";
        echo "<p>The form is missing <strong>Payout</strong> and/or <strong>Customer Paid</strong> values!</p>";
        echo "<p>Without these values, the system can't calculate revenue properly.</p>";
        echo "<p><strong>Solution:</strong> Make sure you fill in both Payout and Customer Paid fields in the form.</p>";
        echo "</div>";
    }
    
    // Check if the policy was actually saved
    if (!empty($_POST['vehicle_number'])) {
        include 'include/config.php';
        $vehicle_number = $_POST['vehicle_number'];
        $result = $con->query("SELECT * FROM policy WHERE vehicle_number = '$vehicle_number' ORDER BY id DESC LIMIT 1");
        
        if ($result && $result->num_rows > 0) {
            $policy = $result->fetch_assoc();
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
            echo "<h4>✅ Policy Found in Database:</h4>";
            echo "<p><strong>Vehicle:</strong> " . $policy['vehicle_number'] . "</p>";
            echo "<p><strong>Premium in DB:</strong> ₹" . number_format($policy['premium'], 2) . "</p>";
            echo "<p><strong>Revenue in DB:</strong> ₹" . number_format($policy['revenue'], 2) . "</p>";
            echo "<p><strong>Payout in DB:</strong> ₹" . number_format($policy['payout'], 2) . "</p>";
            echo "<p><strong>Customer Paid in DB:</strong> ₹" . number_format($policy['customer_paid'], 2) . "</p>";
            echo "<p><strong>Discount in DB:</strong> ₹" . number_format($policy['discount'], 2) . "</p>";
            echo "<p><strong>Calculated Revenue in DB:</strong> ₹" . number_format($policy['calculated_revenue'], 2) . "</p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
            echo "<h4>❌ Policy Not Found in Database!</h4>";
            echo "<p>The policy might not have been saved properly.</p>";
            echo "</div>";
        }
        $con->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Add Policy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h3>🧪 Test Add Policy Form</h3>
    <p>Use this form to test what values are being sent and calculated:</p>
    
    <form method="post">
        <div class="form-group">
            <label>Vehicle Number:</label>
            <input type="text" name="vehicle_number" value="<?php echo $_POST['vehicle_number'] ?? 'TEST123'; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Premium Amount:</label>
            <input type="number" step="0.01" name="premium" value="<?php echo $_POST['premium'] ?? '10000'; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Payout Amount:</label>
            <input type="number" step="0.01" name="payout" value="<?php echo $_POST['payout'] ?? ''; ?>" placeholder="Enter payout">
        </div>
        
        <div class="form-group">
            <label>Customer Paid:</label>
            <input type="number" step="0.01" name="customer_paid" value="<?php echo $_POST['customer_paid'] ?? ''; ?>" placeholder="Amount paid by customer">
        </div>
        
        <div class="form-group">
            <label>Discount (auto-calculated):</label>
            <input type="number" step="0.01" name="discount" value="<?php echo $_POST['discount'] ?? ''; ?>" placeholder="Will be calculated">
        </div>
        
        <div class="form-group">
            <label>Calculated Revenue (auto-calculated):</label>
            <input type="number" step="0.01" name="calculated_revenue" value="<?php echo $_POST['calculated_revenue'] ?? ''; ?>" placeholder="Will be calculated">
        </div>
        
        <div class="form-group">
            <label>Manual Revenue (old system):</label>
            <input type="number" step="0.01" name="revenue" value="<?php echo $_POST['revenue'] ?? ''; ?>" placeholder="Manual entry">
        </div>
        
        <button type="submit">Test Calculation</button>
    </form>
    
    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h4>📋 How It Should Work:</h4>
        <p><strong>If you provide Payout and Customer Paid:</strong></p>
        <ul>
            <li>Discount = Premium - Customer Paid</li>
            <li>Calculated Revenue = Payout - Discount</li>
            <li>Final Revenue = Calculated Revenue</li>
        </ul>
        
        <p><strong>If you don't provide Payout or Customer Paid:</strong></p>
        <ul>
            <li>Revenue = Manual Revenue entry (or 0)</li>
        </ul>
    </div>

    <p><a href="policies.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Policies</a></p>
</body>
</html>
