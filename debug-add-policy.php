<?php
// Debug version for add-policies with enhanced error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🐛 Add Policy Debug Mode</h1>";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>📋 Form Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Check specific financial fields
    echo "<h3>💰 Financial Fields Debug:</h3>";
    $premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
    $payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
    $customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
    $discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : 0;
    
    echo "<ul>";
    echo "<li><strong>Premium:</strong> $premium</li>";
    echo "<li><strong>Payout:</strong> $payout</li>";
    echo "<li><strong>Customer Paid:</strong> $customer_paid</li>";
    echo "<li><strong>Discount:</strong> $discount</li>";
    echo "<li><strong>Calculated Revenue:</strong> $calculated_revenue</li>";
    echo "</ul>";
    
    // Manual calculation
    $manual_discount = $premium - $customer_paid;
    $manual_revenue = $payout - $manual_discount;
    
    echo "<h3>🧮 Manual Calculations:</h3>";
    echo "<ul>";
    echo "<li><strong>Manual Discount:</strong> $manual_discount (Premium $premium - Customer Paid $customer_paid)</li>";
    echo "<li><strong>Manual Revenue:</strong> $manual_revenue (Payout $payout - Discount $manual_discount)</li>";
    echo "</ul>";
    
    // Determine final revenue value
    $final_revenue = $calculated_revenue > 0 ? $calculated_revenue : ($manual_revenue > 0 ? $manual_revenue : 0);
    
    echo "<h3>✅ Final Revenue to Save:</h3>";
    echo "<p style='background: " . ($final_revenue > 0 ? '#d4edda' : '#f8d7da') . "; padding: 10px; border-radius: 5px;'>";
    echo "<strong>Revenue: ₹$final_revenue</strong>";
    echo "</p>";
    
} else {
    echo "<p>No form data received. Please submit the form to debug.</p>";
}

echo "<br><a href='policies.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
?>
