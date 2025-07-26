<!DOCTYPE html>
<html>
<head>
    <title>💰 Revenue Calculation Logic - New vs Old Policies</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        .logic-box { background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 5px solid #007bff; margin: 20px 0; }
        .old-logic { background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 5px solid #ffc107; margin: 20px 0; }
        .new-logic { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; margin: 20px 0; }
        .issue-box { background: #f8d7da; padding: 20px; border-radius: 10px; border-left: 5px solid #dc3545; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 8px; font-weight: bold; color: white; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
        .code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 Revenue Calculation Logic Explanation</h1>

        <div class="new-logic">
            <h2>🆕 NEW Revenue Calculation Logic (Current)</h2>
            <p><strong>Formula:</strong></p>
            <table>
                <tr>
                    <th>Step</th>
                    <th>Calculation</th>
                    <th>Example</th>
                </tr>
                <tr>
                    <td>1. Discount</td>
                    <td><span class="code">Premium - Customer Paid</span></td>
                    <td>₹7500 - ₹5100 = ₹2400</td>
                </tr>
                <tr>
                    <td>2. Revenue</td>
                    <td><span class="code">Payout - Discount</span></td>
                    <td>₹3100 - ₹2400 = ₹700</td>
                </tr>
            </table>
            
            <h3>🔍 Fields Required:</h3>
            <ul>
                <li><strong>Premium:</strong> Total insurance premium amount</li>
                <li><strong>Customer Paid:</strong> Amount actually paid by customer</li>
                <li><strong>Payout:</strong> Amount you receive from insurance company</li>
                <li><strong>Discount:</strong> Auto-calculated (Premium - Customer Paid)</li>
                <li><strong>Revenue:</strong> Auto-calculated (Payout - Discount)</li>
            </ul>
        </div>

        <div class="old-logic">
            <h2>🔄 OLD Policies Issue</h2>
            <p><strong>Problem:</strong> Existing policies were created before the new financial fields were added.</p>
            
            <h3>📊 What's Missing in Old Policies:</h3>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Old Policies</th>
                    <th>New Policies</th>
                    <th>Impact</th>
                </tr>
                <tr>
                    <td>Premium</td>
                    <td>✅ Available</td>
                    <td>✅ Available</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Customer Paid</td>
                    <td>❌ NULL/Empty</td>
                    <td>✅ Available</td>
                    <td>Can't calculate discount</td>
                </tr>
                <tr>
                    <td>Payout</td>
                    <td>❌ NULL/Empty</td>
                    <td>✅ Available</td>
                    <td>Can't calculate revenue</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>❌ NULL/Empty</td>
                    <td>✅ Auto-calculated</td>
                    <td>Shows as 0 or negative</td>
                </tr>
                <tr>
                    <td>Revenue</td>
                    <td>❌ NULL/Empty</td>
                    <td>✅ Auto-calculated</td>
                    <td>Shows as 0 or negative</td>
                </tr>
            </table>
        </div>

        <div class="issue-box">
            <h2>⚠️ Current Issues with Old Policies</h2>
            <ul>
                <li><strong>Revenue shows as 0 or negative:</strong> Because payout and customer_paid are empty</li>
                <li><strong>Edit button not working:</strong> Fixed JavaScript issue with data access</li>
                <li><strong>Inconsistent financial data:</strong> Old vs new policy display</li>
            </ul>
        </div>

        <div class="logic-box">
            <h2>🔧 Solutions Available</h2>
            
            <h3>Option 1: Update Old Policies Individually</h3>
            <p>Edit each old policy to add the missing financial fields:</p>
            <ol>
                <li>Click edit button on any old policy</li>
                <li>Add Customer Paid amount</li>
                <li>Add Payout amount</li>
                <li>Revenue will auto-calculate</li>
            </ol>
            
            <h3>Option 2: Database Migration (Bulk Update)</h3>
            <p>Run a script to estimate missing values for all old policies:</p>
            <ul>
                <li>Set Customer Paid = Premium (assume full payment)</li>
                <li>Set Payout = Estimated commission amount</li>
                <li>Let system calculate discount and revenue</li>
            </ul>
            
            <h3>Option 3: Display Logic Update</h3>
            <p>Modify display to show "N/A" for old policies without financial data</p>
        </div>

        <div class="new-logic">
            <h2>🎯 Recommended Action</h2>
            <p><strong>For immediate use:</strong></p>
            <ol>
                <li>✅ <strong>Edit button is now fixed</strong> - can edit old policies</li>
                <li>🔄 <strong>Add financial data to important old policies</strong> as needed</li>
                <li>📊 <strong>New policies work perfectly</strong> with auto-calculation</li>
                <li>📈 <strong>Account integration continues</strong> to track all income properly</li>
            </ol>
        </div>

        <div class="logic-box">
            <h2>🧪 Test the Fixed Edit Functionality</h2>
            <p>The edit button should now work. Test it:</p>
            <a href="manage-renewal.php" class="btn btn-primary">Go to Renewals Page</a>
            <a href="test-edit-policy.php" class="btn btn-warning">Test Database Structure</a>
            
            <h3>📝 Test Steps:</h3>
            <ol>
                <li>Go to manage-renewal.php</li>
                <li>Click edit button on any policy</li>
                <li>Modal should open with policy data loaded</li>
                <li>For old policies: Add Customer Paid and Payout values</li>
                <li>Revenue should auto-calculate</li>
            </ol>
        </div>

        <?php
        // Show current database statistics
        include 'include/config.php';
        
        echo "<div class='logic-box'>";
        echo "<h2>📊 Current Database Statistics</h2>";
        
        // Total policies
        $result = $con->query("SELECT COUNT(*) as total FROM policy");
        $total = $result->fetch_assoc()['total'];
        echo "<p><strong>Total Policies:</strong> $total</p>";
        
        // Policies with new financial fields
        $result = $con->query("SELECT COUNT(*) as new_count FROM policy WHERE payout IS NOT NULL AND payout > 0");
        $new_count = $result->fetch_assoc()['new_count'];
        echo "<p><strong>Policies with Financial Data:</strong> $new_count</p>";
        
        // Policies missing financial fields
        $old_count = $total - $new_count;
        echo "<p><strong>Policies Missing Financial Data:</strong> $old_count</p>";
        
        if ($old_count > 0) {
            echo "<p style='color: #856404;'><strong>Note:</strong> $old_count policies may show incorrect revenue until updated with Customer Paid and Payout amounts.</p>";
        }
        
        echo "</div>";
        $con->close();
        ?>
    </div>
</body>
</html>
