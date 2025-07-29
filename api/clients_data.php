<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include '../include/config.php';
include '../include/session.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Get request parameters
    $draw = intval($_POST['draw'] ?? 1);
    $start = intval($_POST['start'] ?? 0);
    $length = intval($_POST['length'] ?? 10);
    $search = $_POST['search']['value'] ?? '';
    $orderColumn = intval($_POST['order'][0]['column'] ?? 9);
    $orderDirection = $_POST['order'][0]['dir'] ?? 'desc';

    // Define column mapping for ordering
    $columns = [
        0 => 'id', // Sr. No. - we'll use ID for ordering
        1 => 'client_name',
        2 => 'policy_number',
        3 => 'email',
        4 => 'phone',
        5 => 'policy_type',
        6 => 'premium_amount',
        7 => 'status',
        8 => 'created_at', // Documents column - not orderable
        9 => 'created_at',
        10 => 'id' // Actions column - not orderable
    ];

    $orderColumnName = $columns[$orderColumn] ?? 'created_at';

    // Build the base query
    $baseQuery = "FROM clients WHERE 1=1";
    $params = [];

    // Add search filter
    if (!empty($search)) {
        $baseQuery .= " AND (
            client_name LIKE ? OR 
            policy_number LIKE ? OR 
            email LIKE ? OR 
            phone LIKE ? OR 
            policy_type LIKE ? OR 
            status LIKE ?
        )";
        $searchParam = "%{$search}%";
        $params = array_fill(0, 6, $searchParam);
    }

    // Get total records count
    $totalQuery = "SELECT COUNT(*) as total " . $baseQuery;
    $totalStmt = $conn->prepare($totalQuery);
    if (!empty($params)) {
        $totalStmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Get filtered records count (same as total if no search)
    $filteredRecords = $totalRecords;

    // Build main query with pagination and ordering
    $mainQuery = "SELECT 
        id,
        client_name,
        policy_number,
        email,
        phone,
        policy_type,
        premium_amount,
        status,
        aadhar_card,
        pan_card,
        created_at
        " . $baseQuery . "
        ORDER BY {$orderColumnName} {$orderDirection}
        LIMIT ? OFFSET ?";

    // Add pagination parameters
    $params[] = $length;
    $params[] = $start;

    $stmt = $conn->prepare($mainQuery);
    if (!empty($params)) {
        // Build type string for bind_param
        $types = str_repeat('s', count($params) - 2) . 'ii'; // strings + 2 integers for limit/offset
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'client_name' => htmlspecialchars($row['client_name']),
            'policy_number' => htmlspecialchars($row['policy_number']),
            'email' => htmlspecialchars($row['email']),
            'phone' => htmlspecialchars($row['phone']),
            'policy_type' => htmlspecialchars($row['policy_type']),
            'premium_amount' => number_format($row['premium_amount'], 2),
            'status' => htmlspecialchars($row['status']),
            'aadhar_card' => $row['aadhar_card'],
            'pan_card' => $row['pan_card'],
            'created_at' => $row['created_at']
        ];
    }

    // Return DataTables format response
    $response = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Return error response
    $errorResponse = [
        'draw' => $draw ?? 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Database error: ' . $e->getMessage()
    ];
    
    echo json_encode($errorResponse);
}

$conn->close();
?>
