<?php
include '../include/session.php';
include '../include/config.php';

header('Content-Type: application/json');

// Get DataTables parameters
$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';

// Define column mapping for ordering
$columns = [
    0 => '', // Serial number - not orderable
    1 => 'vehicle_number',
    2 => 'name',
    3 => 'phone',
    4 => 'vehicle_type',
    5 => 'policy_type',
    6 => 'insurance_company',
    7 => 'premium',
    8 => 'policy_start_date',
    9 => 'policy_end_date',
    10 => '', // Status - calculated field
    11 => '' // Actions - not orderable
];

try {
    // Base query
    $baseQuery = "FROM policy p";
    
    // WHERE clause for search
    $whereClause = "WHERE 1=1";
    $params = [];
    
    if (!empty($searchValue)) {
        $whereClause .= " AND (
            p.vehicle_number LIKE ? OR 
            p.name LIKE ? OR 
            p.phone LIKE ? OR 
            p.vehicle_type LIKE ? OR 
            p.policy_type LIKE ? OR 
            p.insurance_company LIKE ? OR 
            p.policy_number LIKE ?
        )";
        $searchParam = "%{$searchValue}%";
        $params = array_fill(0, 7, $searchParam);
    }
    
    // Count total records
    $totalQuery = "SELECT COUNT(*) as total {$baseQuery} {$whereClause}";
    $totalStmt = $conn->prepare($totalQuery);
    if (!empty($params)) {
        $totalStmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];
    
    // ORDER BY clause
    $orderClause = "";
    if (isset($columns[$orderColumn]) && !empty($columns[$orderColumn])) {
        $orderClause = "ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
    } else {
        $orderClause = "ORDER BY p.policy_start_date DESC"; // Default ordering
    }
    
    // Main query with pagination
    $mainQuery = "SELECT 
        p.id,
        p.vehicle_number,
        p.name,
        p.phone,
        p.email,
        p.address,
        p.vehicle_type,
        p.engine_number,
        p.chassis_number,
        p.policy_type,
        p.insurance_company,
        p.premium,
        p.policy_start_date,
        p.policy_end_date,
        p.policy_number,
        p.aadhar_card,
        p.pan_card,
        p.remarks,
        p.policy_issue_date
        {$baseQuery} 
        {$whereClause} 
        {$orderClause}";
    
    // Add LIMIT for pagination
    if ($length != -1) {
        $mainQuery .= " LIMIT ?, ?";
        $params[] = $start;
        $params[] = $length;
    }
    
    $stmt = $conn->prepare($mainQuery);
    if (!empty($params)) {
        // Determine types for bind_param
        $types = str_repeat('s', count($params) - 2) . 'ii'; // Last two are integers for LIMIT
        if ($length == -1) {
            $types = str_repeat('s', count($params)); // All strings if no LIMIT
        }
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Calculate status based on policy end date
        $endDate = new DateTime($row['policy_end_date']);
        $today = new DateTime();
        $diffDays = $today->diff($endDate)->days;
        $isPastDue = $today > $endDate;
        
        if ($isPastDue) {
            $status = 'Expired';
        } elseif ($diffDays <= 30) {
            $status = 'Expiring Soon';
        } else {
            $status = 'Active';
        }
        
        $row['status'] = $status;
        $data[] = $row;
    }
    
    // Prepare response
    $response = [
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $data
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    // Error response
    $response = [
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => "Database error: " . $e->getMessage()
    ];
    
    echo json_encode($response);
}

$conn->close();
?>
