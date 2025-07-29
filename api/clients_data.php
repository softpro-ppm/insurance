<?php
session_start();
require_once '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Set content type
header('Content-Type: application/json');

try {
    // DataTables parameters
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 25;
    $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
    $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 7;
    $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';
    
    // Column mapping for ordering
    $columns = [
        0 => 'client_name',
        1 => 'client_name',
        2 => 'mobile_number',
        3 => 'email',
        4 => 'total_policies',
        5 => 'active_policies',
        6 => 'total_premium',
        7 => 'last_policy_date',
        8 => 'status'
    ];
    
    $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'last_policy_date';
    
    // Base query to get client summary data
    $baseQuery = "
        SELECT 
            client_name,
            mobile_number,
            email,
            COUNT(*) as total_policies,
            SUM(CASE WHEN policy_end_date >= CURDATE() THEN 1 ELSE 0 END) as active_policies,
            SUM(premium_amount) as total_premium,
            MAX(policy_start_date) as last_policy_date,
            CASE 
                WHEN SUM(CASE WHEN policy_end_date >= CURDATE() THEN 1 ELSE 0 END) > 0 THEN 'Active'
                WHEN MAX(policy_end_date) >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) THEN 'Recently Expired'
                ELSE 'Inactive'
            END as status,
            CASE 
                WHEN SUM(CASE WHEN policy_end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) > 0 THEN 1
                ELSE 0
            END as has_renewal_due
        FROM policy 
        WHERE client_name IS NOT NULL AND client_name != ''
        GROUP BY client_name, mobile_number, email
    ";
    
    // Add search filter
    $whereClause = "";
    if (!empty($search)) {
        $searchEscaped = mysqli_real_escape_string($con, $search);
        $whereClause = " HAVING (client_name LIKE '%$searchEscaped%' 
                        OR mobile_number LIKE '%$searchEscaped%' 
                        OR email LIKE '%$searchEscaped%'
                        OR status LIKE '%$searchEscaped%')";
    }
    
    // Count total records (without pagination)
    $countQuery = "SELECT COUNT(*) as total FROM ($baseQuery $whereClause) as client_summary";
    $countResult = mysqli_query($con, $countQuery);
    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
    
    // Main query with pagination and ordering
    $query = "$baseQuery $whereClause ORDER BY $orderBy $orderDir LIMIT $start, $length";
    $result = mysqli_query($con, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($con));
    }
    
    $data = [];
    $serialNumber = $start + 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Format values
        $clientName = htmlspecialchars($row['client_name'] ?? 'Unknown');
        $mobileNumber = htmlspecialchars($row['mobile_number'] ?? 'N/A');
        $email = htmlspecialchars($row['email'] ?? 'N/A');
        $totalPolicies = intval($row['total_policies']);
        $activePolicies = intval($row['active_policies']);
        $totalPremium = floatval($row['total_premium']);
        $lastPolicyDate = $row['last_policy_date'] ? date('d M Y', strtotime($row['last_policy_date'])) : 'N/A';
        $status = htmlspecialchars($row['status']);
        $hasRenewalDue = intval($row['has_renewal_due']);
        
        // Status badge
        $statusBadge = '';
        switch ($status) {
            case 'Active':
                $badgeClass = $hasRenewalDue ? 'bg-warning' : 'bg-success';
                $statusText = $hasRenewalDue ? 'Renewal Due' : 'Active';
                break;
            case 'Recently Expired':
                $badgeClass = 'bg-warning';
                $statusText = 'Recently Expired';
                break;
            default:
                $badgeClass = 'bg-secondary';
                $statusText = 'Inactive';
        }
        $statusBadge = "<span class='badge $badgeClass'>$statusText</span>";
        
        // Client avatar
        $avatar = "<div class='client-avatar'>" . strtoupper(substr($clientName, 0, 1)) . "</div>";
        
        // Format premium amount
        $premiumFormatted = '₹' . number_format($totalPremium, 2);
        
        // Action buttons
        $clientNameEncoded = urlencode($clientName);
        $mobileEncoded = urlencode($mobileNumber);
        
        $actions = "
            <div class='btn-group btn-group-sm' role='group'>
                <button type='button' class='btn btn-outline-primary' 
                        onclick='showClientDetails(\"$clientNameEncoded\", \"$mobileEncoded\")' 
                        title='View Details'>
                    <i class='fas fa-eye'></i>
                </button>
                <div class='btn-group btn-group-sm' role='group'>
                    <button type='button' class='btn btn-outline-secondary dropdown-toggle' 
                            data-bs-toggle='dropdown' title='More Actions'>
                        <i class='fas fa-ellipsis-v'></i>
                    </button>
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' href='#' onclick='addPolicyForClient(\"$clientNameEncoded\", \"$mobileEncoded\")'>
                            <i class='fas fa-plus me-2'></i>Add Policy
                        </a></li>
                        <li><a class='dropdown-item' href='#' onclick='sendCommunication(\"$clientNameEncoded\", \"$mobileEncoded\")'>
                            <i class='fas fa-sms me-2'></i>Send Message
                        </a></li>
                        <li><a class='dropdown-item' href='#' onclick='generateClientReport(\"$clientNameEncoded\", \"$mobileEncoded\")'>
                            <i class='fas fa-file-pdf me-2'></i>Generate Report
                        </a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item text-danger' href='#' onclick='mergeClientRecords(\"$clientNameEncoded\", \"$mobileEncoded\")'>
                            <i class='fas fa-code-branch me-2'></i>Merge Records
                        </a></li>
                    </ul>
                </div>
            </div>
        ";
        
        $data[] = [
            'serial' => $serialNumber++,
            'client_name' => "<div class='d-flex align-items-center'>
                                $avatar
                                <div class='ms-2'>
                                    <div class='fw-bold'>$clientName</div>
                                    <small class='text-muted'>Client ID: CL" . str_pad($serialNumber-1, 4, '0', STR_PAD_LEFT) . "</small>
                                </div>
                              </div>",
            'mobile_number' => "<a href='tel:$mobileNumber' class='text-decoration-none'>
                                  <i class='fas fa-phone me-1 text-success'></i>$mobileNumber
                                </a>",
            'email' => $email !== 'N/A' ? 
                      "<a href='mailto:$email' class='text-decoration-none'>
                         <i class='fas fa-envelope me-1 text-primary'></i>$email
                       </a>" : 
                      "<span class='text-muted'>$email</span>",
            'total_policies' => "<span class='badge bg-info'>$totalPolicies</span>",
            'active_policies' => "<span class='badge bg-success'>$activePolicies</span>",
            'total_premium' => "<strong class='text-success'>$premiumFormatted</strong>",
            'last_policy_date' => $lastPolicyDate,
            'status' => $statusBadge,
            'actions' => $actions
        ];
    }
    
    // Return DataTables JSON response
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $data
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error occurred',
        'message' => $e->getMessage()
    ]);
}
?>