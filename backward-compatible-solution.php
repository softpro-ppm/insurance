<!DOCTYPE html>
<html>
<head>
    <title>🔄 Backward Compatible Policy System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        .strategy-box { background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 5px solid #007bff; margin: 20px 0; }
        .old-system { background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 5px solid #ffc107; margin: 20px 0; }
        .new-system { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; margin: 20px 0; }
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
        <h1>🔄 Backward Compatible Policy System</h1>

        <div class="strategy-box">
            <h2>🎯 Strategy: Keep Old Data + New Logic for New Policies</h2>
            <p><strong>Perfect solution for your 1400+ existing policies!</strong></p>
            
            <h3>📊 How it Works:</h3>
            <table>
                <tr>
                    <th>Policy Type</th>
                    <th>Revenue Calculation</th>
                    <th>Display Logic</th>
                    <th>Data Integrity</th>
                </tr>
                <tr>
                    <td><strong>Old Policies</strong><br>(existing 1400+)</td>
                    <td>Use existing <span class="code">revenue</span> field<br>(unchanged)</td>
                    <td>Show original revenue values</td>
                    <td>✅ Preserved exactly as-is</td>
                </tr>
                <tr>
                    <td><strong>New Policies</strong><br>(from today)</td>
                    <td>Calculate: <span class="code">Payout - Discount</span><br>Store in both fields</td>
                    <td>Show calculated revenue with breakdown</td>
                    <td>✅ Full financial tracking</td>
                </tr>
            </table>
        </div>

        <div class="old-system">
            <h2>📚 Old Policies (1400+ existing)</h2>
            <p><strong>What stays the same:</strong></p>
            <ul>
                <li>✅ <strong>Revenue values preserved:</strong> Existing revenue amounts unchanged</li>
                <li>✅ <strong>No manual editing needed:</strong> Leave all 1400+ policies as-is</li>
                <li>✅ <strong>Historical data intact:</strong> Past financial records remain accurate</li>
                <li>✅ <strong>Account integration works:</strong> Old revenue values continue to sync</li>
            </ul>
            
            <p><strong>Detection logic:</strong></p>
            <p>If <span class="code">payout</span> and <span class="code">customer_paid</span> are NULL or 0 → Show as "Old Policy" with existing revenue</p>
        </div>

        <div class="new-system">
            <h2>🆕 New Policies (from today)</h2>
            <p><strong>Enhanced features:</strong></p>
            <ul>
                <li>✅ <strong>Automatic calculations:</strong> Discount and revenue calculated in real-time</li>
                <li>✅ <strong>Detailed breakdown:</strong> Premium, Customer Paid, Payout, Discount, Revenue</li>
                <li>✅ <strong>Dual storage:</strong> Save to both old <span class="code">revenue</span> field and new <span class="code">calculated_revenue</span> field</li>
                <li>✅ <strong>Account integration:</strong> Revenue automatically synced to account website</li>
            </ul>
            
            <p><strong>Detection logic:</strong></p>
            <p>If <span class="code">payout</span> and <span class="code">customer_paid</span> have values → Show as "New Policy" with breakdown</p>
        </div>

        <div class="strategy-box">
            <h2>🔧 Implementation Steps</h2>
            
            <h3>Step 1: Switch to Fixed Add Policy Script</h3>
            <p>Use the backward-compatible <span class="code">add-policies-fixed.php</span> that:</p>
            <ul>
                <li>Handles both old and new logic</li>
                <li>Uses prepared statements (safer)</li>
                <li>Maintains account integration</li>
                <li>Provides proper error handling</li>
            </ul>
            
            <h3>Step 2: Update Display Logic</h3>
            <p>Modify policy tables to show:</p>
            <ul>
                <li><strong>Old policies:</strong> Simple revenue display</li>
                <li><strong>New policies:</strong> Detailed breakdown with hover/tooltip</li>
                <li><strong>Visual indicator:</strong> Badge showing "Legacy" vs "Enhanced" policy</li>
            </ul>
            
            <h3>Step 3: Test with New Policies</h3>
            <p>From today forward, all new policies get:</p>
            <ul>
                <li>Full financial field collection</li>
                <li>Automatic calculation</li>
                <li>Enhanced reporting</li>
            </ul>
        </div>

        <div class="new-system">
            <h2>✅ Benefits of This Approach</h2>
            <ul>
                <li>🚫 <strong>No manual work:</strong> 1400+ old policies stay unchanged</li>
                <li>📊 <strong>Data integrity:</strong> Historical accuracy preserved</li>
                <li>🆕 <strong>Enhanced future:</strong> New policies get full features</li>
                <li>🔄 <strong>Gradual transition:</strong> System evolves naturally over time</li>
                <li>💰 <strong>Account sync works:</strong> Both old and new revenue values sync properly</li>
                <li>📈 <strong>Better reporting:</strong> Future reports get detailed breakdowns</li>
            </ul>
        </div>

        <div class="strategy-box">
            <h2>🚀 Ready to Implement</h2>
            <a href="#" onclick="updateFormAction()" class="btn btn-primary">Switch to Fixed Version</a>
            <a href="policies.php" class="btn btn-success">Test New Policy Submission</a>
            
            <p><strong>What happens next:</strong></p>
            <ol>
                <li>Form switches to <span class="code">add-policies-fixed.php</span></li>
                <li>You can submit new policies with full financial tracking</li>
                <li>Old policies remain exactly as they are</li>
                <li>System works seamlessly for both types</li>
            </ol>
        </div>

        <?php
        // Show database statistics
        include 'include/config.php';
        
        echo "<div class='strategy-box'>";
        echo "<h2>📊 Current Database Analysis</h2>";
        
        // Total policies
        $result = $con->query("SELECT COUNT(*) as total FROM policy");
        $total = $result->fetch_assoc()['total'];
        echo "<p><strong>Total Policies:</strong> $total</p>";
        
        // Old policies (no payout data)
        $result = $con->query("SELECT COUNT(*) as old_count FROM policy WHERE payout IS NULL OR payout = 0");
        $old_count = $result->fetch_assoc()['old_count'];
        echo "<p><strong>Legacy Policies:</strong> $old_count (will stay unchanged)</p>";
        
        // New policies (with payout data)
        $new_count = $total - $old_count;
        echo "<p><strong>Enhanced Policies:</strong> $new_count (with full financial data)</p>";
        
        // Revenue totals
        $result = $con->query("SELECT SUM(revenue) as total_revenue FROM policy WHERE revenue > 0");
        $total_revenue = $result->fetch_assoc()['total_revenue'];
        echo "<p><strong>Total Revenue in System:</strong> ₹" . number_format($total_revenue, 2) . "</p>";
        
        echo "<p style='color: #28a745;'><strong>Perfect!</strong> This approach preserves all your existing data while enabling enhanced features for new policies.</p>";
        
        echo "</div>";
        $con->close();
        ?>
    </div>

    <script>
        function updateFormAction() {
            if (confirm('Switch to the backward-compatible policy system?\n\nThis will:\n- Keep all 1400+ old policies unchanged\n- Enable enhanced features for new policies\n- Fix the HTTP 500 error')) {
                // This would update the form action in the modal
                fetch('update-form-action.php', {
                    method: 'POST',
                    body: 'action=add-policies-fixed.php'
                }).then(() => {
                    alert('✅ System updated! You can now submit new policies with enhanced features while keeping old data intact.');
                    window.location.href = 'policies.php';
                });
            }
        }
    </script>
</body>
</html>
