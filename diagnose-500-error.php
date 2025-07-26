<?php
// Quick diagnosis of add-policies.php HTTP 500 error
echo "🔧 Diagnosing HTTP 500 Error in add-policies.php\n";
echo "===============================================\n\n";

// Test 1: Database connection
echo "1. Testing database connection...\n";
include 'config.php';
if ($con->connect_error) {
    echo "❌ Database connection failed: " . $con->connect_error . "\n";
    exit;
} else {
    echo "✅ Database connection successful\n";
}

// Test 2: Check if policy table exists and structure
echo "\n2. Checking policy table structure...\n";
$result = $con->query("DESCRIBE policy");
if ($result) {
    echo "✅ Policy table exists\n";
    $fields = [];
    while ($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }
    echo "✅ Table has " . count($fields) . " columns\n";
    
    // Check for new financial fields
    $required_fields = ['payout', 'customer_paid', 'discount', 'calculated_revenue'];
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!in_array($field, $fields)) {
            $missing_fields[] = $field;
        }
    }
    
    if (empty($missing_fields)) {
        echo "✅ All required financial fields present\n";
    } else {
        echo "❌ Missing fields: " . implode(', ', $missing_fields) . "\n";
        echo "💡 This might cause the HTTP 500 error\n";
    }
} else {
    echo "❌ Cannot access policy table: " . $con->error . "\n";
}

// Test 3: Session check
echo "\n3. Testing session...\n";
session_start();
if (isset($_SESSION['username'])) {
    echo "✅ Session active: " . $_SESSION['username'] . "\n";
} else {
    echo "❌ No active session\n";
}

// Test 4: Account database check
echo "\n4. Testing account database...\n";
include 'account.php';
if ($acc->connect_error) {
    echo "❌ Account database failed: " . $acc->connect_error . "\n";
} else {
    echo "✅ Account database connected\n";
    $acc->close();
}

echo "\n===============================================\n";
echo "Diagnosis complete. Check for ❌ errors above.\n";

$con->close();
?>
