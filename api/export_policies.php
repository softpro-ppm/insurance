<?php
include '../include/session.php';
include '../include/config.php';

// Get all policies data
$sql = "SELECT 
    vehicle_number,
    name as client_name,
    phone,
    email,
    address,
    vehicle_type,
    engine_number,
    chassis_number,
    policy_type,
    insurance_company,
    premium,
    policy_start_date,
    policy_end_date,
    policy_number,
    remarks,
    created_at
FROM policies 
ORDER BY policy_start_date DESC";

$result = $conn->query($sql);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="policies_export_' . date('Y-m-d_H-i-s') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Add CSV headers
$headers = [
    'Vehicle Number',
    'Client Name',
    'Phone',
    'Email',
    'Address',
    'Vehicle Type',
    'Engine Number',
    'Chassis Number',
    'Policy Type',
    'Insurance Company',
    'Premium (₹)',
    'Policy Start Date',
    'Policy End Date',
    'Policy Number',
    'Remarks',
    'Created Date'
];

fputcsv($output, $headers);

// Add data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Format premium with currency
        $row['premium'] = '₹' . number_format($row['premium'], 2);
        
        // Format dates
        $row['policy_start_date'] = date('d-m-Y', strtotime($row['policy_start_date']));
        $row['policy_end_date'] = date('d-m-Y', strtotime($row['policy_end_date']));
        $row['created_at'] = date('d-m-Y H:i:s', strtotime($row['created_at']));
        
        fputcsv($output, array_values($row));
    }
}

fclose($output);
$conn->close();
exit;
?>
