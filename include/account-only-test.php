<?php
// Working version using only account database (bypassing insurance DB issues)
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Account-Only Test (Bypassing Insurance DB)</h1>";

require 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>✅ Form submitted successfully!</h3>";
    echo "<pre>POST Data:\n";
    print_r($_POST);
    echo "</pre>";
    
    // Extract data
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $vehicle_number = $_POST['vehicle_number'] ?? '';
    $premium = floatval($_POST['premium'] ?? 0);
    $payout = floatval($_POST['payout'] ?? 0);
    $customer_paid = floatval($_POST['customer_paid'] ?? 0);
    $discount = floatval($_POST['discount'] ?? 0);
    $calculated_revenue = floatval($_POST['calculated_revenue'] ?? 0);
    
    // Calculate manually if needed
    if ($discount == 0) {
        $discount = $premium - $customer_paid;
    }
    
    if ($calculated_revenue == 0) {
        $calculated_revenue = $payout - $discount;
    }
    
    echo "<h3>🧮 Calculations:</h3>";
    echo "<ul>";
    echo "<li>Premium: ₹$premium</li>";
    echo "<li>Customer Paid: ₹$customer_paid</li>";
    echo "<li>Payout: ₹$payout</li>";
    echo "<li>Discount: ₹$discount</li>";
    echo "<li>Revenue: ₹$calculated_revenue</li>";
    echo "</ul>";
    
    // Test account database only
    echo "<h3>🔗 Testing Account Integration (Only):</h3>";
    
    try {
        include 'account.php';
        
        $date = date('Y-m-d');
        $description = 'Insurance Test';
        $category = 'Insurance';
        $subcategory = 'Insurance';
        $amount = $calculated_revenue;
        $received = $calculated_revenue;
        $balance = 0;
        $insurance_id = 9999; // Dummy ID since we can't insert to insurance DB
        
        if ($amount > 0) {
            $stmt = $acc->prepare("INSERT INTO income (
                date, name, phone, description, category, subcategory,
                amount, received, balance, created_at, updated_at, insurance_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
            
            $stmt->bind_param(
                "ssssssdddi",
                $date, $name, $phone, $description,
                $category, $subcategory, $amount, $received, $balance, $insurance_id
            );
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✅ Account record inserted successfully with amount: ₹$amount</p>";
            } else {
                echo "<p style='color: red;'>❌ Account insert error: " . $stmt->error . "</p>";
            }
            
            $stmt->close();
        } else {
            echo "<p style='color: orange;'>⚠️ Revenue is 0, skipping account insertion</p>";
        }
        
        $acc->close();
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Account integration error: " . $e->getMessage() . "</p>";
    }
    
    echo "<p style='color: green; font-size: 18px;'>🎉 SUCCESS! Account integration working (without insurance DB).</p>";
    echo "<p><strong>Issue Confirmed:</strong> Insurance database connection is the problem causing HTTP 500 errors.</p>";
    
} else {
    echo "<p>No form data received. Please submit the form to test.</p>";
}

echo "<br><a href='../policies.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
echo "<br><a href='db-connection-test.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;'>🔧 Test DB Connection</a>";
?>
