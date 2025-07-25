<!DOCTYPE html>
<html>
<head>
    <title>🛠️ Old Policy Migration Center</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            padding: 30px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        .step-card { 
            background: #f8f9fa; 
            padding: 25px; 
            border-radius: 12px; 
            margin: 20px 0; 
            border-left: 6px solid #007bff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .step-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .step-number {
            background: #007bff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            font-size: 18px;
        }
        .btn { 
            display: inline-block; 
            padding: 15px 30px; 
            margin: 8px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: bold; 
            color: white; 
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-primary { background: #007bff; }
        .btn-primary:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .problem-box { 
            background: #fff3cd; 
            padding: 20px; 
            border-radius: 10px; 
            border-left: 5px solid #ffc107; 
            margin: 20px 0; 
        }
        .solution-box { 
            background: #d4edda; 
            padding: 20px; 
            border-radius: 10px; 
            border-left: 5px solid #28a745; 
            margin: 20px 0; 
        }
        .warning-box { 
            background: #f8d7da; 
            padding: 20px; 
            border-radius: 10px; 
            border-left: 5px solid #dc3545; 
            margin: 20px 0; 
        }
        h1 { color: white; margin: 0; font-size: 2.5em; }
        h2 { color: #333; margin-top: 0; }
        h3 { color: #007bff; }
        .subtitle { color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 1.2em; }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ Old Policy Migration Center</h1>
            <p class="subtitle">Fix Edit Modal Issues for 1400+ Existing Policies</p>
        </div>

        <div class="problem-box">
            <h3>🎯 The Problem You're Solving</h3>
            <p><strong>Issue:</strong> When you edit old policies, the modal shows incorrect financial calculations because old policies don't have the new financial fields (payout, customer_paid, discount).</p>
            <p><strong>Example:</strong> Policy with Premium: ₹7742, Revenue: ₹400 shows negative or wrong values in edit modal.</p>
        </div>

        <div class="solution-box">
            <h3>✅ Your Perfect Solution</h3>
            <p><strong>Strategy:</strong> Populate the missing financial fields for old policies with logical values:</p>
            <ul>
                <li><strong>Payout</strong> = existing Revenue (₹400)</li>
                <li><strong>Customer Paid</strong> = Premium amount (₹7742)</li>
                <li><strong>Discount</strong> = 0 (since customer paid full premium)</li>
                <li><strong>Revenue</strong> = unchanged (₹400 preserved)</li>
            </ul>
            <p><strong>Result:</strong> Edit modal will display properly without affecting your existing revenue data!</p>
        </div>

        <div class="grid">
            <div class="step-card">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <span class="step-number">1</span>
                    <h3 style="margin: 0;">Complete Backup</h3>
                </div>
                <p>Create a comprehensive backup of all your data including database, documents, and configurations.</p>
                <p><strong>Includes:</strong></p>
                <ul>
                    <li>Full database SQL dump</li>
                    <li>All uploaded documents</li>
                    <li>Policy data as CSV</li>
                    <li>Restore script</li>
                </ul>
                <a href="create-complete-backup.php" class="btn btn-warning">🗄️ Create Backup</a>
            </div>

            <div class="step-card">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <span class="step-number">2</span>
                    <h3 style="margin: 0;">Quick Migration</h3>
                </div>
                <p>Apply your solution to fix the edit modal for all old policies.</p>
                <p><strong>What it does:</strong></p>
                <ul>
                    <li>Sets Payout = existing Revenue</li>
                    <li>Sets Customer Paid = Premium</li>
                    <li>Sets Discount = 0</li>
                    <li>Preserves all revenue values</li>
                </ul>
                <a href="quick-migrate-old-policies.php" class="btn btn-success">🚀 Migrate Policies</a>
            </div>
        </div>

        <div class="warning-box">
            <h3>⚠️ Important Safety Notes</h3>
            <ul>
                <li><strong>Always backup first!</strong> Click "Create Backup" before running migration</li>
                <li><strong>Test on a few policies:</strong> You can preview changes before applying to all</li>
                <li><strong>Revenue is preserved:</strong> No existing revenue values will be changed</li>
                <li><strong>Reversible:</strong> You can restore from backup if needed</li>
            </ul>
        </div>

        <?php
        try {
            include 'include/config.php';
            
            // Get current statistics
            $result = $con->query("SELECT COUNT(*) as total FROM policy");
            $totalPolicies = $result->fetch_assoc()['total'];
            
            $result = $con->query("SELECT COUNT(*) as old_count FROM policy WHERE payout IS NULL OR payout = 0 OR payout = ''");
            $oldPolicies = $result->fetch_assoc()['old_count'];
            
            $result = $con->query("SELECT SUM(revenue) as total_revenue FROM policy WHERE revenue > 0");
            $totalRevenue = $result->fetch_assoc()['total_revenue'];
            
            echo "<div class='step-card'>";
            echo "<h3>📊 Current System Status</h3>";
            echo "<div class='grid'>";
            echo "<div>";
            echo "<p><strong>Total Policies:</strong> " . number_format($totalPolicies) . "</p>";
            echo "<p><strong>Old Policies (need migration):</strong> " . number_format($oldPolicies) . "</p>";
            echo "<p><strong>New Policies (already fixed):</strong> " . number_format($totalPolicies - $oldPolicies) . "</p>";
            echo "</div>";
            echo "<div>";
            echo "<p><strong>Total Revenue:</strong> ₹" . number_format($totalRevenue, 2) . "</p>";
            echo "<p><strong>All revenue will be preserved</strong> ✅</p>";
            echo "<p><strong>Only financial fields will be populated</strong> ✅</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            
            $con->close();
        } catch (Exception $e) {
            echo "<div class='warning-box'>";
            echo "<h3>⚠️ Database Connection Issue</h3>";
            echo "<p>Could not fetch current statistics. Please check your database connection.</p>";
            echo "</div>";
        }
        ?>

        <div class="step-card">
            <h3>🎯 What Happens After Migration</h3>
            <div class="grid">
                <div>
                    <h4>✅ Fixed Edit Modal</h4>
                    <ul>
                        <li>Premium shows correctly</li>
                        <li>Payout = old revenue</li>
                        <li>Customer Paid = premium</li>
                        <li>Discount = 0</li>
                        <li>No negative values</li>
                    </ul>
                </div>
                <div>
                    <h4>✅ Preserved Data</h4>
                    <ul>
                        <li>All revenue values unchanged</li>
                        <li>Historical data intact</li>
                        <li>Account sync continues working</li>
                        <li>Reports remain accurate</li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin: 40px 0;">
            <h3>🚀 Ready to Get Started?</h3>
            <p>Follow the steps in order for the safest migration:</p>
            <a href="create-complete-backup.php" class="btn btn-warning">1. Create Backup First</a>
            <a href="quick-migrate-old-policies.php" class="btn btn-success">2. Then Migrate Policies</a>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee;">
            <a href="policies.php" class="btn btn-primary">← Back to Policies</a>
            <a href="manage-renewal.php" class="btn btn-primary">Renewals Management</a>
        </div>
    </div>
</body>
</html>
