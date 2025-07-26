<?php
// Check if policy table has all required columns for the insert statement
echo "🔧 Database Table Column Check\n";
echo "===============================\n\n";

include 'include/config.php';

// Get current table structure
echo "📋 Current policy table structure:\n";
$result = $con->query("DESCRIBE policy");
$existing_columns = [];
while ($row = $result->fetch_assoc()) {
    $existing_columns[] = $row['Field'];
    echo "   ✅ " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n🔍 Checking required columns for new INSERT statement:\n";
$required_columns = [
    'name', 'phone', 'vehicle_number', 'vehicle_type', 'insurance_company', 'policy_type',
    'policy_issue_date', 'policy_start_date', 'policy_end_date', 'fc_expiry_date', 'permit_expiry_date',
    'premium', 'revenue', 'payout', 'customer_paid', 'discount', 'calculated_revenue',
    'created_date', 'chassiss', 'comments', 'updated_at'
];

$missing_columns = [];
foreach ($required_columns as $column) {
    if (in_array($column, $existing_columns)) {
        echo "   ✅ $column - exists\n";
    } else {
        echo "   ❌ $column - MISSING\n";
        $missing_columns[] = $column;
    }
}

if (!empty($missing_columns)) {
    echo "\n🚨 PROBLEM FOUND:\n";
    echo "Missing columns: " . implode(', ', $missing_columns) . "\n";
    echo "This will cause HTTP 500 error in add-policies.php\n\n";
    
    echo "💡 SOLUTIONS:\n";
    echo "1. Add missing columns to database, OR\n";
    echo "2. Modify INSERT query to exclude missing columns, OR\n";
    echo "3. Use the working account-only test temporarily\n\n";
    
    // Generate ALTER TABLE statements
    echo "🔧 SQL to add missing columns:\n";
    foreach ($missing_columns as $column) {
        switch ($column) {
            case 'payout':
            case 'customer_paid':
            case 'discount':
            case 'calculated_revenue':
                echo "ALTER TABLE policy ADD COLUMN $column DECIMAL(10,2) DEFAULT 0;\n";
                break;
            case 'updated_at':
                echo "ALTER TABLE policy ADD COLUMN $column TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;\n";
                break;
            case 'comments':
                echo "ALTER TABLE policy ADD COLUMN $column TEXT;\n";
                break;
            default:
                echo "ALTER TABLE policy ADD COLUMN $column VARCHAR(255);\n";
        }
    }
} else {
    echo "\n✅ ALL COLUMNS EXIST!\n";
    echo "The table structure is correct for the new insert statement.\n";
    echo "The HTTP 500 error must be caused by something else.\n";
}

$con->close();
?>
