<?php
session_start();
require_once '../connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$reportType = $_GET['type'] ?? 'monthly';

// Set headers for download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $reportType . '_report_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

try {
    // Generate report based on type
    switch ($reportType) {
        case 'monthly':
            generateMonthlyReport($con);
            break;
        case 'yearly':
            generateYearlyReport($con);
            break;
        case 'quick':
            generateQuickReport($con);
            break;
        case 'renewals':
            generateRenewalReport($con);
            break;
        case 'expired':
            generateExpiredReport($con);
            break;
        default:
            generateMonthlyReport($con);
    }
    
} catch (Exception $e) {
    echo 'Error generating report: ' . $e->getMessage();
}

function generateMonthlyReport($con) {
    $currentMonth = date('Y-m');
    $monthName = date('F Y');
    
    echo '<table border="1">';
    echo '<tr><th colspan="10" style="text-align:center; font-size:16px; font-weight:bold;">Monthly Report - ' . $monthName . '</th></tr>';
    echo '<tr><th>S.No</th><th>Client Name</th><th>Mobile</th><th>Vehicle Number</th><th>Insurance Company</th><th>Policy Type</th><th>Start Date</th><th>End Date</th><th>Premium</th><th>Commission</th></tr>';
    
    $query = "SELECT * FROM policy 
              WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '$currentMonth' 
              OR DATE_FORMAT(policy_end_date, '%Y-%m') = '$currentMonth'
              ORDER BY policy_start_date DESC";
    
    $result = mysqli_query($con, $query);
    $sno = 1;
    $totalPremium = 0;
    $totalCommission = 0;
    
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td>' . $sno++ . '</td>';
        echo '<td>' . htmlspecialchars($row['client_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance_company']) . '</td>';
        echo '<td>' . htmlspecialchars($row['policy_type']) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_start_date'])) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_end_date'])) . '</td>';
        echo '<td>₹' . number_format($row['premium_amount'], 2) . '</td>';
        echo '<td>₹' . number_format($row['commission_amount'], 2) . '</td>';
        echo '</tr>';
        
        $totalPremium += $row['premium_amount'];
        $totalCommission += $row['commission_amount'];
    }
    
    echo '<tr style="font-weight:bold; background-color:#f8f9fa;">';
    echo '<td colspan="8">TOTAL</td>';
    echo '<td>₹' . number_format($totalPremium, 2) . '</td>';
    echo '<td>₹' . number_format($totalCommission, 2) . '</td>';
    echo '</tr>';
    echo '</table>';
}

function generateYearlyReport($con) {
    $currentYear = date('Y');
    
    echo '<table border="1">';
    echo '<tr><th colspan="10" style="text-align:center; font-size:16px; font-weight:bold;">Yearly Report - ' . $currentYear . '</th></tr>';
    echo '<tr><th>S.No</th><th>Client Name</th><th>Mobile</th><th>Vehicle Number</th><th>Insurance Company</th><th>Policy Type</th><th>Start Date</th><th>End Date</th><th>Premium</th><th>Commission</th></tr>';
    
    $query = "SELECT * FROM policy 
              WHERE YEAR(policy_start_date) = '$currentYear'
              ORDER BY policy_start_date DESC";
    
    $result = mysqli_query($con, $query);
    $sno = 1;
    $totalPremium = 0;
    $totalCommission = 0;
    
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td>' . $sno++ . '</td>';
        echo '<td>' . htmlspecialchars($row['client_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance_company']) . '</td>';
        echo '<td>' . htmlspecialchars($row['policy_type']) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_start_date'])) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_end_date'])) . '</td>';
        echo '<td>₹' . number_format($row['premium_amount'], 2) . '</td>';
        echo '<td>₹' . number_format($row['commission_amount'], 2) . '</td>';
        echo '</tr>';
        
        $totalPremium += $row['premium_amount'];
        $totalCommission += $row['commission_amount'];
    }
    
    echo '<tr style="font-weight:bold; background-color:#f8f9fa;">';
    echo '<td colspan="8">TOTAL</td>';
    echo '<td>₹' . number_format($totalPremium, 2) . '</td>';
    echo '<td>₹' . number_format($totalCommission, 2) . '</td>';
    echo '</tr>';
    echo '</table>';
}

function generateQuickReport($con) {
    echo '<table border="1">';
    echo '<tr><th colspan="6" style="text-align:center; font-size:16px; font-weight:bold;">Quick Summary Report - ' . date('F j, Y') . '</th></tr>';
    
    // Total Policies
    $totalQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM policy");
    $totalCount = mysqli_fetch_array($totalQuery)['count'];
    
    // Active Policies
    $activeQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM policy WHERE policy_end_date >= CURDATE()");
    $activeCount = mysqli_fetch_array($activeQuery)['count'];
    
    // Expired Policies
    $expiredQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM policy WHERE policy_end_date < CURDATE()");
    $expiredCount = mysqli_fetch_array($expiredQuery)['count'];
    
    // Renewals Due (30 days)
    $renewalQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE()");
    $renewalCount = mysqli_fetch_array($renewalQuery)['count'];
    
    // This Month's Policies
    $thisMonthQuery = mysqli_query($con, "SELECT COUNT(*) as count, SUM(premium_amount) as premium, SUM(commission_amount) as commission FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '" . date('Y-m') . "'");
    $thisMonthData = mysqli_fetch_array($thisMonthQuery);
    
    echo '<tr><th>Metric</th><th>Count</th><th>Amount</th><th>Percentage</th><th>Status</th><th>Action Required</th></tr>';
    
    echo '<tr>';
    echo '<td><strong>Total Policies</strong></td>';
    echo '<td>' . $totalCount . '</td>';
    echo '<td>-</td>';
    echo '<td>100%</td>';
    echo '<td>Active Database</td>';
    echo '<td>Monitor Growth</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<td><strong>Active Policies</strong></td>';
    echo '<td>' . $activeCount . '</td>';
    echo '<td>-</td>';
    echo '<td>' . round(($activeCount / $totalCount) * 100, 1) . '%</td>';
    echo '<td>Currently Valid</td>';
    echo '<td>Maintain Records</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<td><strong>Expired Policies</strong></td>';
    echo '<td>' . $expiredCount . '</td>';
    echo '<td>-</td>';
    echo '<td>' . round(($expiredCount / $totalCount) * 100, 1) . '%</td>';
    echo '<td>Needs Attention</td>';
    echo '<td>Contact for Renewal</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<td><strong>Renewals Due (30 days)</strong></td>';
    echo '<td>' . $renewalCount . '</td>';
    echo '<td>-</td>';
    echo '<td>' . round(($renewalCount / $totalCount) * 100, 1) . '%</td>';
    echo '<td>Urgent</td>';
    echo '<td>Send Reminders</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<td><strong>This Month New</strong></td>';
    echo '<td>' . $thisMonthData['count'] . '</td>';
    echo '<td>₹' . number_format($thisMonthData['premium'], 2) . '</td>';
    echo '<td>' . round(($thisMonthData['count'] / $totalCount) * 100, 1) . '%</td>';
    echo '<td>Revenue Growth</td>';
    echo '<td>Continue Marketing</td>';
    echo '</tr>';
    
    echo '<tr style="font-weight:bold; background-color:#e8f5e8;">';
    echo '<td><strong>This Month Commission</strong></td>';
    echo '<td>-</td>';
    echo '<td>₹' . number_format($thisMonthData['commission'], 2) . '</td>';
    echo '<td>Profit Margin</td>';
    echo '<td>Earnings</td>';
    echo '<td>Optimize Rates</td>';
    echo '</tr>';
    
    echo '</table>';
}

function generateRenewalReport($con) {
    echo '<table border="1">';
    echo '<tr><th colspan="9" style="text-align:center; font-size:16px; font-weight:bold;">Renewal Report - Due in Next 30 Days</th></tr>';
    echo '<tr><th>S.No</th><th>Client Name</th><th>Mobile</th><th>Vehicle Number</th><th>Insurance Company</th><th>End Date</th><th>Days Left</th><th>Premium</th><th>Status</th></tr>';
    
    $query = "SELECT * FROM policy 
              WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
              AND policy_end_date >= CURDATE()
              ORDER BY policy_end_date ASC";
    
    $result = mysqli_query($con, $query);
    $sno = 1;
    
    while ($row = mysqli_fetch_array($result)) {
        $daysLeft = (strtotime($row['policy_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
        $status = $daysLeft <= 7 ? 'URGENT' : 'Due Soon';
        
        echo '<tr>';
        echo '<td>' . $sno++ . '</td>';
        echo '<td>' . htmlspecialchars($row['client_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance_company']) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_end_date'])) . '</td>';
        echo '<td>' . ceil($daysLeft) . '</td>';
        echo '<td>₹' . number_format($row['premium_amount'], 2) . '</td>';
        echo '<td>' . $status . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}

function generateExpiredReport($con) {
    echo '<table border="1">';
    echo '<tr><th colspan="9" style="text-align:center; font-size:16px; font-weight:bold;">Expired Policies Report</th></tr>';
    echo '<tr><th>S.No</th><th>Client Name</th><th>Mobile</th><th>Vehicle Number</th><th>Insurance Company</th><th>Expired Date</th><th>Days Ago</th><th>Premium</th><th>Action</th></tr>';
    
    $query = "SELECT * FROM policy 
              WHERE policy_end_date < CURDATE()
              ORDER BY policy_end_date DESC";
    
    $result = mysqli_query($con, $query);
    $sno = 1;
    
    while ($row = mysqli_fetch_array($result)) {
        $daysAgo = (strtotime(date('Y-m-d')) - strtotime($row['policy_end_date'])) / (60 * 60 * 24);
        
        echo '<tr>';
        echo '<td>' . $sno++ . '</td>';
        echo '<td>' . htmlspecialchars($row['client_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vehicle_number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance_company']) . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['policy_end_date'])) . '</td>';
        echo '<td>' . ceil($daysAgo) . '</td>';
        echo '<td>₹' . number_format($row['premium_amount'], 2) . '</td>';
        echo '<td>Contact Urgently</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}

mysqli_close($con);
?>
