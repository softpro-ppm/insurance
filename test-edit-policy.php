<?php
// Test get-policy-data.php functionality
session_start();
include 'config.php';

echo "🔧 Testing Edit Policy Functionality\n";
echo "====================================\n\n";

// Check session
if (isset($_SESSION['username'])) {
    echo "✅ Session: " . $_SESSION['username'] . " is logged in\n";
} else {
    echo "❌ Session: No user logged in\n";
    exit;
}

// Check database connection
if ($con->connect_error) {
    echo "❌ Database: Connection failed - " . $con->connect_error . "\n";
    exit;
} else {
    echo "✅ Database: Connected successfully\n";
}

// Check if policy table exists and get structure
$result = $con->query("DESCRIBE policy");
if ($result) {
    echo "✅ Policy table exists with columns:\n";
    while ($row = $result->fetch_assoc()) {
        echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "❌ Policy table: " . $con->error . "\n";
    exit;
}

// Get a sample policy to test with
echo "\n📋 Sample policies in database:\n";
$result = $con->query("SELECT id, vehicle_number, name, premium, payout, customer_paid, discount, calculated_revenue FROM policy ORDER BY id DESC LIMIT 5");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   ID: {$row['id']} | Vehicle: {$row['vehicle_number']} | Name: {$row['name']}\n";
        echo "     Premium: ₹{$row['premium']} | Payout: ₹{$row['payout']} | Customer Paid: ₹{$row['customer_paid']}\n";
        echo "     Discount: ₹{$row['discount']} | Revenue: ₹{$row['calculated_revenue']}\n\n";
    }
} else {
    echo "   No policies found or error: " . $con->error . "\n";
}

echo "\n🧮 Revenue Calculation Logic:\n";
echo "   Discount = Premium - Customer Paid\n";
echo "   Revenue = Payout - Discount\n";
echo "   (This is the NEW logic implemented)\n\n";

echo "💡 For existing old policies:\n";
echo "   - If payout/customer_paid are empty, revenue may show as 0 or negative\n";
echo "   - You may need to update old policies with the new financial fields\n";

$con->close();
?>
