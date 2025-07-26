<!DOCTYPE html>
<html>
<head>
    <title>🔧 Fix Revenue Recording Issue</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .issue-box { background: #f8d7da; padding: 20px; border-radius: 10px; border-left: 5px solid #dc3545; margin: 20px 0; }
        .solution-box { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; margin: 20px 0; }
        .info-box { background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 5px solid #007bff; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 8px; font-weight: bold; color: white; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Revenue Recording Issue</h1>

        <div class="issue-box">
            <h2>❌ Issue Identified</h2>
            <p><strong>Problem:</strong> Your new policy (Vehicle: APAPAP, Premium: ₹10,000) shows Revenue: ₹1,000 in the view, but the revenue is not being recorded properly in the system.</p>
            
            <p><strong>Root Cause:</strong> The new financial system requires both <strong>Payout Amount</strong> and <strong>Customer Paid</strong> to be filled in the form for revenue calculation to work.</p>
            
            <p><strong>Current Behavior:</strong></p>
            <ul>
                <li>If Payout and Customer Paid are empty → Revenue = 0 (not recorded)</li>
                <li>If Payout and Customer Paid are filled → Revenue = Payout - Discount (recorded properly)</li>
            </ul>
        </div>

        <div class="solution-box">
            <h2>✅ Simple Solution</h2>
            <p><strong>When adding a new policy, you need to fill in these fields:</strong></p>
            
            <h3>📋 Required Fields for Proper Revenue Recording:</h3>
            <ol>
                <li><strong>Premium Amount:</strong> ₹10,000 (you already filled this)</li>
                <li><strong>Payout Amount:</strong> Enter the amount you'll receive (e.g., ₹9,000)</li>
                <li><strong>Customer Paid:</strong> Enter what customer actually paid (e.g., ₹10,000)</li>
            </ol>
            
            <p><strong>The system will then automatically calculate:</strong></p>
            <ul>
                <li><strong>Discount:</strong> Premium - Customer Paid = ₹10,000 - ₹10,000 = ₹0</li>
                <li><strong>Revenue:</strong> Payout - Discount = ₹9,000 - ₹0 = ₹9,000</li>
            </ul>
        </div>

        <div class="info-box">
            <h2>🎯 Example: How to Add Policy Correctly</h2>
            
            <p><strong>For your APAPAP policy, you should have entered:</strong></p>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Field</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Value</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Explanation</th>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Premium Amount</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">₹10,000</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">Policy premium (you did this)</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Payout Amount</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">₹1,000</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">Amount you receive as commission</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Customer Paid</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">₹10,000</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">What customer actually paid</td>
                </tr>
                <tr style="background: #e8f5e8;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Discount</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">₹0</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">Auto-calculated: 10,000 - 10,000</td>
                </tr>
                <tr style="background: #e8f5e8;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Revenue</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">₹1,000</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">Auto-calculated: 1,000 - 0</td>
                </tr>
            </table>
        </div>

        <?php
        // Quick fix option - update the existing APAPAP policy
        if (isset($_POST['fix_apapap'])) {
            include 'include/config.php';
            
            $payout = 1000; // Based on your desired revenue
            $customer_paid = 10000; // Same as premium
            $discount = 0; // Since customer paid full premium
            $calculated_revenue = 1000; // Payout - discount
            $revenue = 1000; // Final revenue
            
            $update_sql = "UPDATE policy SET 
                payout = ?, 
                customer_paid = ?, 
                discount = ?, 
                calculated_revenue = ?, 
                revenue = ? 
                WHERE vehicle_number = 'APAPAP'";
            
            $stmt = $con->prepare($update_sql);
            $stmt->bind_param("ddddd", $payout, $customer_paid, $discount, $calculated_revenue, $revenue);
            
            if ($stmt->execute()) {
                echo "<div class='solution-box'>";
                echo "<h3>✅ APAPAP Policy Fixed!</h3>";
                echo "<p>Updated with:</p>";
                echo "<ul>";
                echo "<li>Payout: ₹1,000</li>";
                echo "<li>Customer Paid: ₹10,000</li>";
                echo "<li>Discount: ₹0</li>";
                echo "<li>Revenue: ₹1,000</li>";
                echo "</ul>";
                echo "<p><strong>The revenue should now be recorded properly in the system!</strong></p>";
                echo "</div>";
                
                // Try account integration
                try {
                    include 'include/account.php';
                    
                    $table_check = $acc->query("SHOW TABLES LIKE 'income'");
                    if ($table_check->num_rows > 0) {
                        $columns = $acc->query("DESCRIBE income");
                        $column_names = [];
                        while ($col = $columns->fetch_assoc()) {
                            $column_names[] = $col['Field'];
                        }
                        
                        // Try to add to account
                        if (in_array('amount', $column_names)) {
                            $source = "Insurance Policy";
                            $details = "Revenue from policy: APAPAP (Gulla Tejal)";
                            
                            if (in_array('income_date', $column_names)) {
                                $account_sql = "INSERT INTO income (income_date, amount, source, details, created_at) VALUES (?, ?, ?, ?, NOW())";
                                $account_stmt = $acc->prepare($account_sql);
                                $income_date = date('Y-m-d');
                                $account_stmt->bind_param("sdss", $income_date, $revenue, $source, $details);
                            } else {
                                $account_sql = "INSERT INTO income (amount, source, details) VALUES (?, ?, ?)";
                                $account_stmt = $acc->prepare($account_sql);
                                $account_stmt->bind_param("dss", $revenue, $source, $details);
                            }
                            
                            if ($account_stmt->execute()) {
                                echo "<div class='solution-box'>";
                                echo "<p>✅ <strong>Revenue also added to account system!</strong></p>";
                                echo "</div>";
                            }
                        }
                    }
                    $acc->close();
                } catch (Exception $e) {
                    echo "<div class='info-box'>";
                    echo "<p>⚠️ Account integration: " . $e->getMessage() . "</p>";
                    echo "</div>";
                }
            }
            
            $con->close();
        }
        ?>

        <div class="info-box">
            <h2>🚀 Next Steps</h2>
            
            <h3>Option 1: Fix Current Policy</h3>
            <form method="post">
                <button type="submit" name="fix_apapap" class="btn btn-success" onclick="return confirm('Fix the APAPAP policy with Payout: ₹1,000, Customer Paid: ₹10,000?')">
                    ✅ Fix APAPAP Policy Now
                </button>
            </form>
            
            <h3>Option 2: Learn for Future Policies</h3>
            <p>When adding new policies, always fill in:</p>
            <ul>
                <li><strong>Premium Amount</strong> (required)</li>
                <li><strong>Payout Amount</strong> (your commission/revenue)</li>
                <li><strong>Customer Paid</strong> (what customer paid)</li>
            </ul>
            
            <p>The system will automatically calculate discount and revenue!</p>
        </div>

        <div style="text-align: center; margin: 40px 0;">
            <a href="policies.php" class="btn btn-primary">← Back to Policies</a>
            <a href="check-apapap-policy.php" class="btn btn-warning">Check APAPAP Status</a>
            <a href="debug-add-policy-revenue.php" class="btn btn-primary">Test Add Policy Form</a>
        </div>
    </div>
</body>
</html>
