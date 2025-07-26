<?php
// Simple debug version of add-policies.php to identify the issue
error_reporting(E_ALL);
ini_set('display_e# Try simple insert first
$simple_insert = "INSERT INTO policy (
    name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
    policy_start_date, policy_end_date, premium, revenue, created_date
) VALUES (
    '$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', 
    '$policy_start_date', '$policy_end_date', $premium, $final_revenue, NOW()
)";1);

require 'session.php';
require 'config.php';

echo "<h1>🔧 Add Policy Debug Version</h1>";
echo "<pre>POST Data:\n";
print_r($_POST);
echo "</pre>";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p>No POST data received</p>";
    exit;
}

// Simple input sanitization
$name = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
$phone = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
$vehicle_number = mysqli_real_escape_string($con, trim($_POST['vehicle_number'] ?? ''));
$vehicle_type = mysqli_real_escape_string($con, trim($_POST['vehicle_type'] ?? ''));
$insurance_company = mysqli_real_escape_string($con, trim($_POST['insurance_company'] ?? ''));
$policy_type = mysqli_real_escape_string($con, trim($_POST['policy_type'] ?? ''));

echo "<h3>Basic Fields:</h3>";
echo "<ul>";
echo "<li>Name: '$name'</li>";
echo "<li>Phone: '$phone'</li>";
echo "<li>Vehicle Number: '$vehicle_number'</li>";
echo "<li>Vehicle Type: '$vehicle_type'</li>";
echo "<li>Insurance Company: '$insurance_company'</li>";
echo "<li>Policy Type: '$policy_type'</li>";
echo "</ul>";

// Handle dates
$policy_start_date = $_POST['policy_start_date'] ?? '';
$policy_end_date = $_POST['policy_end_date'] ?? '';

echo "<h3>Dates:</h3>";
echo "<ul>";
echo "<li>Start Date: '$policy_start_date'</li>";
echo "<li>End Date: '$policy_end_date'</li>";
echo "</ul>";

// Financial fields
$premium = !empty($_POST['premium']) ? floatval($_POST['premium']) : 0;
$payout = !empty($_POST['payout']) ? floatval($_POST['payout']) : 0;
$customer_paid = !empty($_POST['customer_paid']) ? floatval($_POST['customer_paid']) : 0;
$discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
$calculated_revenue = !empty($_POST['calculated_revenue']) ? floatval($_POST['calculated_revenue']) : 0;

echo "<h3>Financial Fields:</h3>";
echo "<ul>";
echo "<li>Premium: $premium</li>";
echo "<li>Payout: $payout</li>";
echo "<li>Customer Paid: $customer_paid</li>";
echo "<li>Discount: $discount</li>";
echo "<li>Calculated Revenue: $calculated_revenue</li>";
echo "</ul>";

// Calculate revenue
# Calculate revenue
$manual_discount = $premium - $customer_paid;
$manual_revenue = $payout - $manual_discount;

# Use manual calculations if form values are 0
$final_discount = $discount > 0 ? $discount : $manual_discount;
$final_revenue = $calculated_revenue > 0 ? $calculated_revenue : $manual_revenue;

echo "<h3>🧮 Calculations:</h3>";
echo "<ul>";
echo "<li><strong>Manual Discount:</strong> $manual_discount (Premium $premium - Customer Paid $customer_paid)</li>";
echo "<li><strong>Manual Revenue:</strong> $manual_revenue (Payout $payout - Discount $manual_discount)</li>";
echo "<li><strong>Form Discount:</strong> $discount</li>";
echo "<li><strong>Form Revenue:</strong> $calculated_revenue</li>";
echo "<li><strong>Final Discount:</strong> $final_discount</li>";
echo "<li><strong>Final Revenue:</strong> $final_revenue</li>";
echo "</ul>";

echo "<h3>✅ Final Revenue to Save:</h3>";
echo "<p style='background: " . ($final_revenue > 0 ? '#d4edda' : '#f8d7da') . "; padding: 10px; border-radius: 5px;'>";
echo "<strong>Revenue: ₹$final_revenue</strong>";
echo "</p>";

echo "<h3>Final Revenue: $revenue</h3>";

// Basic validation
$errors = [];
if (empty($name)) $errors[] = "Name is required";
if (empty($phone)) $errors[] = "Phone is required";
if (empty($vehicle_number)) $errors[] = "Vehicle number is required";
if (empty($vehicle_type)) $errors[] = "Vehicle type is required";
if (empty($insurance_company)) $errors[] = "Insurance company is required";
if (empty($policy_type)) $errors[] = "Policy type is required";
if (empty($policy_start_date)) $errors[] = "Policy start date is required";
if (empty($policy_end_date)) $errors[] = "Policy end date is required";
if ($premium <= 0) $errors[] = "Premium must be greater than 0";

if (!empty($errors)) {
    echo "<h3 style='color: red;'>Validation Errors:</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li style='color: red;'>$error</li>";
    }
    echo "</ul>";
    echo "<p><a href='../policies.php'>← Go Back</a></p>";
    exit;
}

echo "<h3 style='color: green;'>✅ All validations passed!</h3>";

// Try simple insert first
$simple_insert = "INSERT INTO policy (
    name, phone, vehicle_number, vehicle_type, insurance_company, policy_type, 
    policy_start_date, policy_end_date, premium, revenue, created_date
) VALUES (
    '$name', '$phone', '$vehicle_number', '$vehicle_type', '$insurance_company', '$policy_type', 
    '$policy_start_date', '$policy_end_date', $premium, $revenue, NOW()
)";

echo "<h3>SQL Query:</h3>";
echo "<pre>$simple_insert</pre>";

$result = mysqli_query($con, $simple_insert);

if ($result) {
    $policy_id = mysqli_insert_id($con);
    echo "<h3 style='color: green;'>✅ Policy inserted successfully with ID: $policy_id</h3>";
    
    // Test account integration
    echo "<h3>🔗 Testing Account Integration</h3>";
    
    try {
        include 'account.php';
        
        $date = date('Y-m-d');
        $description = 'Insurance';
        $category = 'Insurance';
        $subcategory = 'Insurance';
        $amount = $final_revenue;
        $received = $revenue;
        $balance = 0;
        $insurance_id = $policy_id;
        
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
    
    echo "<p style='color: green; font-size: 18px;'>🎉 SUCCESS! Policy and account integration completed.</p>";
    echo "<p><a href='../policies.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a></p>";
    
} else {
    $error = mysqli_error($con);
    echo "<h3 style='color: red;'>❌ Database Error:</h3>";
    echo "<p style='color: red;'>$error</p>";
    echo "<p><a href='../policies.php'>← Go Back</a></p>";
}
?>
