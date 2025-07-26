<?php
// Check the specific policy that was just added
include 'include/config.php';

echo "<h3>🔍 Check Latest Added Policy (APAPAP)</h3>";

try {
    // Find the policy with vehicle number APAPAP
    $result = $con->query("SELECT * FROM policy WHERE vehicle_number = 'APAPAP' ORDER BY id DESC LIMIT 1");
    
    if ($result && $result->num_rows > 0) {
        $policy = $result->fetch_assoc();
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h4>✅ Policy Found in Database:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        
        foreach ($policy as $key => $value) {
            $display_value = $value;
            if (in_array($key, ['premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue'])) {
                $display_value = "₹" . number_format(floatval($value), 2);
            }
            echo "<tr><td><strong>$key</strong></td><td>$display_value</td></tr>";
        }
        echo "</table>";
        echo "</div>";
        
        // Analyze the values
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h4>🧮 Analysis:</h4>";
        
        $premium = floatval($policy['premium']);
        $revenue = floatval($policy['revenue']);
        $payout = floatval($policy['payout']);
        $customer_paid = floatval($policy['customer_paid']);
        $discount = floatval($policy['discount']);
        $calculated_revenue = floatval($policy['calculated_revenue']);
        
        echo "<p><strong>Premium:</strong> ₹" . number_format($premium, 2) . "</p>";
        echo "<p><strong>Revenue:</strong> ₹" . number_format($revenue, 2) . "</p>";
        echo "<p><strong>Payout:</strong> ₹" . number_format($payout, 2) . "</p>";
        echo "<p><strong>Customer Paid:</strong> ₹" . number_format($customer_paid, 2) . "</p>";
        echo "<p><strong>Discount:</strong> ₹" . number_format($discount, 2) . "</p>";
        echo "<p><strong>Calculated Revenue:</strong> ₹" . number_format($calculated_revenue, 2) . "</p>";
        
        if ($payout == 0 && $customer_paid == 0) {
            echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 10px;'>";
            echo "<p><strong>⚠️ Issue Found:</strong> Payout and Customer Paid are both 0!</p>";
            echo "<p>This means the new financial fields weren't filled in the form.</p>";
            echo "<p><strong>Solution:</strong> When adding policies, make sure to fill in:</p>";
            echo "<ul>";
            echo "<li>Payout Amount</li>";
            echo "<li>Customer Paid</li>";
            echo "</ul>";
            echo "<p>The system will then calculate:</p>";
            echo "<ul>";
            echo "<li>Discount = Premium - Customer Paid</li>";
            echo "<li>Revenue = Payout - Discount</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<p><strong>✅ Financial fields are populated correctly!</strong></p>";
        }
        
        echo "</div>";
        
        // Check account integration
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h4>💰 Account Integration Check:</h4>";
        
        if ($revenue > 0) {
            echo "<p>✅ Revenue is greater than 0, so it should be recorded in account system.</p>";
            
            // Try to check account database
            try {
                include 'include/account.php';
                $account_check = $acc->query("SELECT * FROM income WHERE details LIKE '%APAPAP%' ORDER BY id DESC LIMIT 1");
                
                if ($account_check && $account_check->num_rows > 0) {
                    $account_record = $account_check->fetch_assoc();
                    echo "<p>✅ <strong>Found in account system:</strong></p>";
                    echo "<ul>";
                    foreach ($account_record as $key => $value) {
                        echo "<li><strong>$key:</strong> $value</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>❌ <strong>Not found in account system!</strong></p>";
                    echo "<p>This could be due to:</p>";
                    echo "<ul>";
                    echo "<li>Account database connection issue</li>";
                    echo "<li>Income table structure mismatch</li>";
                    echo "<li>Account integration error during policy creation</li>";
                    echo "</ul>";
                }
                $acc->close();
            } catch (Exception $e) {
                echo "<p>❌ <strong>Account check failed:</strong> " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>⚠️ Revenue is 0, so it won't be recorded in account system.</p>";
        }
        
        echo "</div>";
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h4>❌ Policy Not Found!</h4>";
        echo "<p>No policy found with vehicle number 'APAPAP'.</p>";
        
        // Show recent policies
        $recent = $con->query("SELECT vehicle_number, premium, revenue, created_date FROM policy ORDER BY id DESC LIMIT 5");
        echo "<h5>Recent Policies:</h5>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Vehicle</th><th>Premium</th><th>Revenue</th><th>Created</th></tr>";
        while ($row = $recent->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['vehicle_number'] . "</td>";
            echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
            echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
            echo "<td>" . $row['created_date'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h4>❌ Error:</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

$con->close();
?>

<p><a href="debug-add-policy-revenue.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test Add Policy Form</a></p>
<p><a href="policies.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Policies</a></p>
