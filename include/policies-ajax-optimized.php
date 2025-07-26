<?php
include 'session.php';
include 'config.php';

// Set JSON response header
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // DataTables parameters
    $draw = intval($_POST['draw'] ?? 1);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 25);
    $searchValue = $_POST['search']['value'] ?? '';
    
    // Custom filters
    $startDate = $_POST['startdate'] ?? '';
    $endDate = $_POST['enddate'] ?? '';
    $searchName = $_POST['searchval'] ?? '';
    
    // Order parameters
    $orderColumn = intval($_POST['order'][0]['column'] ?? 0);
    $orderDir = $_POST['order'][0]['dir'] ?? 'desc';
    
    // Column mapping for ordering
    $columns = [
        0 => 'id',
        1 => 'name',
        2 => 'phone', 
        3 => 'vehicle_number',
        4 => 'vehicle_type',
        5 => 'insurance_company',
        6 => 'policy_type',
        7 => 'policy_issue_date',
        8 => 'policy_start_date',
        9 => 'policy_end_date',
        10 => 'premium',
        11 => 'revenue'
    ];
    
    $orderBy = $columns[$orderColumn] ?? 'id';
    $orderDirection = ($orderDir === 'asc') ? 'ASC' : 'DESC';
    
    // Base query
    $baseQuery = "FROM policy WHERE 1=1";
    $params = [];
    $types = '';
    
    // Apply filters
    if (!empty($startDate)) {
        $baseQuery .= " AND DATE(created_date) >= ?";
        $params[] = date('Y-m-d', strtotime($startDate));
        $types .= 's';
    }
    
    if (!empty($endDate)) {
        $baseQuery .= " AND DATE(created_date) <= ?";
        $params[] = date('Y-m-d', strtotime($endDate));
        $types .= 's';
    }
    
    if (!empty($searchName)) {
        $baseQuery .= " AND name LIKE ?";
        $params[] = "%{$searchName}%";
        $types .= 's';
    }
    
    // Global search
    if (!empty($searchValue)) {
        $baseQuery .= " AND (
            name LIKE ? OR 
            phone LIKE ? OR 
            vehicle_number LIKE ? OR 
            vehicle_type LIKE ? OR 
            insurance_company LIKE ? OR 
            policy_type LIKE ?
        )";
        $searchParam = "%{$searchValue}%";
        for ($i = 0; $i < 6; $i++) {
            $params[] = $searchParam;
            $types .= 's';
        }
    }
    
    // Count total records (without pagination)
    $countQuery = "SELECT COUNT(*) as total " . $baseQuery;
    $countStmt = mysqli_prepare($con, $countQuery);
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($countStmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($countStmt);
    $countResult = mysqli_stmt_get_result($countStmt);
    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
    mysqli_stmt_close($countStmt);
    
    // Main data query with pagination
    $dataQuery = "
        SELECT 
            id, name, phone, vehicle_number, vehicle_type, 
            insurance_company, policy_type, policy_issue_date,
            policy_start_date, policy_end_date, fc_expiry_date, 
            permit_expiry_date, premium, revenue, created_date
        " . $baseQuery . "
        ORDER BY {$orderBy} {$orderDirection}
        LIMIT ?, ?
    ";
    
    // Add pagination parameters
    $params[] = $start;
    $params[] = $length;
    $types .= 'ii';
    
    $dataStmt = mysqli_prepare($con, $dataQuery);
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($dataStmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($dataStmt);
    $dataResult = mysqli_stmt_get_result($dataStmt);
    
    $data = [];
    $rowNumber = $start + 1;
    
    while ($row = mysqli_fetch_assoc($dataResult)) {
        // Format dates safely
        $formatDate = function($date) {
            return (!empty($date) && $date !== '0000-00-00') ? date('d-m-Y', strtotime($date)) : '-';
        };
        
        // Format currency
        $formatCurrency = function($amount) {
            return is_numeric($amount) ? '₹' . number_format($amount, 2) : '₹0.00';
        };
        
        // Generate action buttons
        $actions = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" 
                        onclick="openEditModal(' . $row['id'] . ')" 
                        title="Edit Policy">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-info btn-sm" 
                        onclick="viewPolicy(' . $row['id'] . ')" 
                        title="View Policy">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" 
                        onclick="deletePolicy(' . $row['id'] . ')" 
                        title="Delete Policy">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        ';
        
        // Document links
        $documents = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" 
                        onclick="viewDocuments(' . $row['id'] . ')" 
                        title="View Documents">
                    <i class="fas fa-file-alt"></i>
                </button>
            </div>
        ';
        
        $data[] = [
            $rowNumber++,
            htmlspecialchars($row['name']),
            htmlspecialchars($row['phone']),
            '<a href="javascript:void(0)" onclick="viewPolicy(' . $row['id'] . ')" class="text-primary fw-bold">' . 
                htmlspecialchars($row['vehicle_number']) . '</a>',
            htmlspecialchars($row['vehicle_type']),
            htmlspecialchars($row['insurance_company']),
            htmlspecialchars($row['policy_type']),
            $formatDate($row['policy_issue_date']),
            $formatDate($row['policy_start_date']),
            $formatDate($row['policy_end_date']),
            $formatDate($row['fc_expiry_date']),
            $formatDate($row['permit_expiry_date']),
            $formatCurrency($row['premium']),
            $formatCurrency($row['revenue']),
            $documents,
            $actions
        ];
    }
    
    mysqli_stmt_close($dataStmt);
    
    // Response for DataTables
    $response = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords, // Same as total since we filter in SQL
        'data' => $data,
        'sql_debug' => [
            'query' => $dataQuery,
            'params' => $params,
            'total_records' => $totalRecords
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Policies AJAX error: " . $e->getMessage());
    
    echo json_encode([
        'draw' => $draw ?? 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Failed to load policies: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
