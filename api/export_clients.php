<?php
session_start();
require_once '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$format = isset($_GET['format']) ? $_GET['format'] : 'excel';

try {
    // Get client summary data
    $query = "
        SELECT 
            client_name,
            mobile_number,
            email,
            COUNT(*) as total_policies,
            SUM(CASE WHEN policy_end_date >= CURDATE() THEN 1 ELSE 0 END) as active_policies,
            SUM(premium_amount) as total_premium,
            SUM(commission_amount) as total_commission,
            MAX(policy_start_date) as last_policy_date,
            MIN(policy_start_date) as first_policy_date,
            CASE 
                WHEN SUM(CASE WHEN policy_end_date >= CURDATE() THEN 1 ELSE 0 END) > 0 THEN 'Active'
                WHEN MAX(policy_end_date) >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) THEN 'Recently Expired'
                ELSE 'Inactive'
            END as status
        FROM policy 
        WHERE client_name IS NOT NULL AND client_name != ''
        GROUP BY client_name, mobile_number, email
        ORDER BY last_policy_date DESC
    ";
    
    $result = mysqli_query($con, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($con));
    }
    
    $clients = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clients[] = $row;
    }
    
    switch ($format) {
        case 'excel':
            exportToExcel($clients);
            break;
        case 'pdf':
            exportToPDF($clients);
            break;
        case 'contacts':
            exportToContacts($clients);
            break;
        default:
            throw new Exception("Invalid export format");
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

function exportToExcel($clients) {
    $filename = 'clients_export_' . date('Y-m-d_H-i-s') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    // Write CSV header
    fputcsv($output, [
        'Client Name',
        'Mobile Number',
        'Email',
        'Total Policies',
        'Active Policies',
        'Total Premium (₹)',
        'Total Commission (₹)',
        'First Policy Date',
        'Last Policy Date',
        'Status'
    ]);
    
    // Write data rows
    foreach ($clients as $client) {
        fputcsv($output, [
            $client['client_name'],
            $client['mobile_number'],
            $client['email'] ?: 'N/A',
            $client['total_policies'],
            $client['active_policies'],
            number_format($client['total_premium'], 2),
            number_format($client['total_commission'], 2),
            $client['first_policy_date'] ? date('d-m-Y', strtotime($client['first_policy_date'])) : 'N/A',
            $client['last_policy_date'] ? date('d-m-Y', strtotime($client['last_policy_date'])) : 'N/A',
            $client['status']
        ]);
    }
    
    fclose($output);
}

function exportToPDF($clients) {
    // Basic HTML to PDF conversion
    $filename = 'clients_export_' . date('Y-m-d_H-i-s') . '.html';
    
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Clients Export</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            .header { text-align: center; margin-bottom: 20px; }
            .total-row { background-color: #f8f9fa; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Softpro Insurance Management System</h2>
            <h3>Clients Export Report</h3>
            <p>Generated on: ' . date('d F Y, H:i:s') . '</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Client Name</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Total Policies</th>
                    <th>Active Policies</th>
                    <th>Total Premium (₹)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
    $serialNo = 1;
    $totalPremium = 0;
    $totalPolicies = 0;
    $totalActive = 0;
    
    foreach ($clients as $client) {
        $totalPremium += $client['total_premium'];
        $totalPolicies += $client['total_policies'];
        $totalActive += $client['active_policies'];
        
        echo '<tr>
                <td>' . $serialNo++ . '</td>
                <td>' . htmlspecialchars($client['client_name']) . '</td>
                <td>' . htmlspecialchars($client['mobile_number']) . '</td>
                <td>' . htmlspecialchars($client['email'] ?: 'N/A') . '</td>
                <td>' . $client['total_policies'] . '</td>
                <td>' . $client['active_policies'] . '</td>
                <td>₹' . number_format($client['total_premium'], 2) . '</td>
                <td>' . htmlspecialchars($client['status']) . '</td>
              </tr>';
    }
    
    echo '      <tr class="total-row">
                    <td colspan="4"><strong>TOTAL</strong></td>
                    <td><strong>' . $totalPolicies . '</strong></td>
                    <td><strong>' . $totalActive . '</strong></td>
                    <td><strong>₹' . number_format($totalPremium, 2) . '</strong></td>
                    <td><strong>' . count($clients) . ' Clients</strong></td>
                </tr>
            </tbody>
        </table>
        
        <div style="margin-top: 30px; font-size: 10px; color: #666;">
            <p>Report generated by Softpro Insurance Management System</p>
            <p>User: ' . htmlspecialchars($_SESSION['username']) . ' | Date: ' . date('d F Y, H:i:s') . '</p>
        </div>
    </body>
    </html>';
}

function exportToContacts($clients) {
    $filename = 'client_contacts_' . date('Y-m-d_H-i-s') . '.vcf';
    
    header('Content-Type: text/vcard');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    foreach ($clients as $client) {
        echo "BEGIN:VCARD\r\n";
        echo "VERSION:3.0\r\n";
        echo "FN:" . $client['client_name'] . "\r\n";
        echo "N:" . $client['client_name'] . ";;;;\r\n";
        echo "TEL;TYPE=CELL:" . $client['mobile_number'] . "\r\n";
        
        if (!empty($client['email'])) {
            echo "EMAIL:" . $client['email'] . "\r\n";
        }
        
        echo "ORG:Softpro Insurance Client\r\n";
        echo "NOTE:Insurance Client - " . $client['total_policies'] . " policies, Status: " . $client['status'] . "\r\n";
        echo "END:VCARD\r\n\r\n";
    }
}
?>
