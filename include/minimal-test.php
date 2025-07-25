<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Minimal Form Test (No Session)</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>✅ Form submitted successfully!</h3>";
    echo "<pre>POST Data:\n";
    print_r($_POST);
    echo "</pre>";
    
    // Extract basic data
    $vehicle_number = $_POST['vehicle_number'] ?? '';
    $premium = floatval($_POST['premium'] ?? 0);
    $payout = floatval($_POST['payout'] ?? 0);
    $customer_paid = floatval($_POST['customer_paid'] ?? 0);
    
    // Calculate
    $discount = $premium - $customer_paid;
    $revenue = $payout - $discount;
    
    echo "<h3>Calculated Values:</h3>";
    echo "<ul>";
    echo "<li>Discount: ₹$discount</li>";
    echo "<li>Revenue: ₹$revenue</li>";
    echo "</ul>";
    
    echo "<p style='background: #d4edda; padding: 10px; border-radius: 5px;'>✅ Form processing successful without HTTP 500 error!</p>";
} else {
    echo "<p>No form data received. Please submit the form to test.</p>";
}

echo "<br><a href='../policies.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
?>
