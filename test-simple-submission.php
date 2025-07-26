<?php
// Test simplified policy submission without complex logic
require 'session.php';
require 'config.php';

echo "🔧 Simplified Policy Submission Test\n";
echo "=====================================\n\n";

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "❌ Not a POST request. Use the form to submit data.\n";
    exit;
}

echo "✅ POST request received\n";

// Log the incoming data
echo "📥 Received POST data:\n";
foreach ($_POST as $key => $value) {
    echo "   $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
}

// Test basic field extraction
echo "\n📋 Processing basic fields...\n";
try {
    $name = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
    $vehicle_number = mysqli_real_escape_string($con, trim($_POST['vehicle_number'] ?? ''));
    
    echo "✅ Name: $name\n";
    echo "✅ Phone: $phone\n";
    echo "✅ Vehicle: $vehicle_number\n";
} catch (Exception $e) {
    echo "❌ Error processing basic fields: " . $e->getMessage() . "\n";
    exit;
}

// Test financial calculations
echo "\n💰 Processing financial fields...\n";
try {
    $premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
    $payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
    $customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
    
    $discount = $premium - $customer_paid;
    $revenue = $payout - $discount;
    
    echo "✅ Premium: ₹$premium\n";
    echo "✅ Customer Paid: ₹$customer_paid\n";
    echo "✅ Payout: ₹$payout\n";
    echo "✅ Calculated Discount: ₹$discount\n";
    echo "✅ Calculated Revenue: ₹$revenue\n";
} catch (Exception $e) {
    echo "❌ Error processing financial fields: " . $e->getMessage() . "\n";
    exit;
}

// Test database insertion (simulated)
echo "\n💾 Testing database insertion...\n";
try {
    // Just test the query preparation without execution
    $sql = "INSERT INTO policy (name, phone, vehicle_number, premium, payout, customer_paid, discount, calculated_revenue) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    
    if ($stmt) {
        echo "✅ SQL query prepared successfully\n";
        $stmt->close();
    } else {
        echo "❌ SQL preparation failed: " . $con->error . "\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n✅ Test completed without HTTP 500 error!\n";
echo "This means the basic logic is working.\n";
echo "The issue might be in the complex add-policies.php file.\n";

$con->close();
?>
