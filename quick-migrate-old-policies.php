<?php
/**
 * 🔄 Quick Migration Script for Old Policies
 * 
 * This script implements your solution:
 * - Payout = existing Revenue 
 * - Customer Paid = Premium amount
 * - Discount = 0 (since customer_paid = premium)
 * - Revenue = unchanged (preserved)
 */

include 'include/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Quick Migration</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{background:#d4edda;padding:15px;border-radius:8px;color:#155724;} .error{background:#f8d7da;padding:15px;border-radius:8px;color:#721c24;} .info{background:#e7f3ff;padding:15px;border-radius:8px;color:#004085;}</style>";
echo "</head><body>";

echo "<h1>🔄 Quick Migration for Old Policies</h1>";

if ($_POST['execute'] ?? false) {
    
    echo "<div class='info'><h3>🚀 Executing Migration...</h3></div>";
    
    try {
        // Start transaction for safety
        $con->autocommit(FALSE);
        
        // First, let's see what we're working with
        echo "<h3>📊 Pre-Migration Analysis</h3>";
        
        $result = $con->query("SELECT COUNT(*) as count FROM policy WHERE payout IS NULL OR payout = 0 OR payout = ''");
        $oldPolicyCount = $result->fetch_assoc()['count'];
        echo "<p><strong>Old policies to migrate:</strong> $oldPolicyCount</p>";
        
        // Show sample before
        $result = $con->query("SELECT vehicle_number, premium, revenue, payout, customer_paid FROM policy WHERE payout IS NULL OR payout = 0 OR payout = '' LIMIT 3");
        echo "<h4>Sample BEFORE migration:</h4><table border='1' style='border-collapse:collapse;'>";
        echo "<tr><th>Vehicle</th><th>Premium</th><th>Revenue</th><th>Payout</th><th>Customer Paid</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['vehicle_number']) . "</td>";
            echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
            echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
            echo "<td>" . ($row['payout'] ?: 'NULL') . "</td>";
            echo "<td>" . ($row['customer_paid'] ?: 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Execute your solution
        echo "<h3>⚡ Applying Your Solution</h3>";
        echo "<p><strong>Logic:</strong></p>";
        echo "<ul>";
        echo "<li>Payout = existing Revenue</li>";
        echo "<li>Customer Paid = Premium amount</li>";
        echo "<li>Discount = 0 (since customer paid full premium)</li>";
        echo "<li>Revenue = unchanged (preserved)</li>";
        echo "</ul>";
        
        $updateQuery = "
            UPDATE policy 
            SET 
                payout = CASE 
                    WHEN revenue IS NULL OR revenue = 0 THEN premium 
                    ELSE revenue 
                END,
                customer_paid = premium,
                discount = 0,
                calculated_revenue = CASE 
                    WHEN revenue IS NULL OR revenue = 0 THEN premium 
                    ELSE revenue 
                END
            WHERE payout IS NULL OR payout = 0 OR payout = ''
        ";
        
        $result = $con->query($updateQuery);
        
        if ($result) {
            $affectedRows = $con->affected_rows;
            $con->commit();
            
            echo "<div class='success'>";
            echo "<h3>✅ Migration Successful!</h3>";
            echo "<p><strong>Policies updated:</strong> $affectedRows</p>";
            echo "</div>";
            
            // Show sample after
            $result = $con->query("SELECT vehicle_number, premium, revenue, payout, customer_paid, discount FROM policy WHERE payout = revenue AND customer_paid = premium LIMIT 3");
            echo "<h4>Sample AFTER migration:</h4><table border='1' style='border-collapse:collapse;'>";
            echo "<tr><th>Vehicle</th><th>Premium</th><th>Revenue</th><th>Payout</th><th>Customer Paid</th><th>Discount</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['vehicle_number']) . "</td>";
                echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
                echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
                echo "<td>₹" . number_format($row['payout'], 2) . "</td>";
                echo "<td>₹" . number_format($row['customer_paid'], 2) . "</td>";
                echo "<td>₹" . number_format($row['discount'], 2) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<div class='success'>";
            echo "<h3>🎉 Perfect! Now your edit modal will show:</h3>";
            echo "<ul>";
            echo "<li>✅ Premium: Original premium amount</li>";
            echo "<li>✅ Payout: Original revenue (preserved)</li>";
            echo "<li>✅ Customer Paid: Premium amount</li>";
            echo "<li>✅ Discount: 0.00</li>";
            echo "<li>✅ Revenue: Original revenue (unchanged)</li>";
            echo "</ul>";
            echo "<p><strong>Result:</strong> Edit modal will display properly without negative values!</p>";
            echo "</div>";
            
        } else {
            throw new Exception($con->error);
        }
        
    } catch (Exception $e) {
        $con->rollback();
        echo "<div class='error'>";
        echo "<h3>❌ Migration Failed</h3>";
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
    
    $con->autocommit(TRUE);
    
} else {
    
    // Show preview and confirmation
    echo "<div class='info'>";
    echo "<h3>📋 Migration Preview</h3>";
    echo "<p>This will update all old policies to fix the edit modal issue.</p>";
    echo "</div>";
    
    try {
        // Analysis
        $result = $con->query("SELECT COUNT(*) as count FROM policy WHERE payout IS NULL OR payout = 0 OR payout = ''");
        $oldPolicyCount = $result->fetch_assoc()['count'];
        
        $result = $con->query("SELECT SUM(revenue) as total_revenue FROM policy WHERE (payout IS NULL OR payout = 0 OR payout = '') AND revenue > 0");
        $totalRevenue = $result->fetch_assoc()['total_revenue'];
        
        echo "<h3>📊 Current Situation</h3>";
        echo "<p><strong>Old policies needing migration:</strong> $oldPolicyCount</p>";
        echo "<p><strong>Total revenue to preserve:</strong> ₹" . number_format($totalRevenue, 2) . "</p>";
        
        // Sample preview
        $result = $con->query("
            SELECT 
                vehicle_number,
                premium,
                revenue,
                premium as will_be_customer_paid,
                CASE 
                    WHEN revenue IS NULL OR revenue = 0 THEN premium 
                    ELSE revenue 
                END as will_be_payout,
                0 as will_be_discount
            FROM policy 
            WHERE payout IS NULL OR payout = 0 OR payout = ''
            LIMIT 5
        ");
        
        echo "<h3>👀 Preview Changes (First 5 policies)</h3>";
        echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
        echo "<tr style='background:#f8f9fa;'>";
        echo "<th>Vehicle</th><th>Current Premium</th><th>Current Revenue</th>";
        echo "<th>→ New Payout</th><th>→ New Customer Paid</th><th>→ New Discount</th>";
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['vehicle_number']) . "</td>";
            echo "<td>₹" . number_format($row['premium'], 2) . "</td>";
            echo "<td>₹" . number_format($row['revenue'], 2) . "</td>";
            echo "<td style='background:#d4edda;'>₹" . number_format($row['will_be_payout'], 2) . "</td>";
            echo "<td style='background:#d4edda;'>₹" . number_format($row['will_be_customer_paid'], 2) . "</td>";
            echo "<td style='background:#d4edda;'>₹" . number_format($row['will_be_discount'], 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div class='success'>";
        echo "<h3>✅ This Solution Will:</h3>";
        echo "<ul>";
        echo "<li>Fix the edit modal display issue</li>";
        echo "<li>Preserve all existing revenue values</li>";
        echo "<li>Set logical values for payout and customer_paid</li>";
        echo "<li>Make old policies work with new system</li>";
        echo "<li>No data loss - everything preserved</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<form method='post' style='margin: 20px 0;'>";
        echo "<button type='submit' name='execute' value='1' style='background:#28a745; color:white; padding:15px 30px; border:none; border-radius:8px; font-size:16px; cursor:pointer;' onclick='return confirm(\"Execute migration for $oldPolicyCount policies?\n\nThis will:\n- Set Payout = Revenue\n- Set Customer Paid = Premium\n- Set Discount = 0\n- Preserve all revenue values\n\nProceed?\")'>🚀 Execute Migration ($oldPolicyCount policies)</button>";
        echo "</form>";
        
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<h3>❌ Error</h3>";
        echo "<p>Could not analyze database: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
}

$con->close();

echo "<p style='margin-top:40px;'><a href='policies.php' style='background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>← Back to Policies</a></p>";

echo "</body></html>";
?>
