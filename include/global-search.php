<?php
require 'session.php';
require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response

try {
    if ($_POST['action'] !== 'global_search') {
        throw new Exception('Invalid action');
    }

    $query = trim($_POST['query']);
    
    if (strlen($query) < 2) {
        echo json_encode([
            'success' => false,
            'message' => 'Query too short'
        ]);
        exit;
    }

    // Sanitize input
    $query = mysqli_real_escape_string($con, $query);
    $queryLike = "%{$query}%";

    $results = [
        'policies' => [],
        'renewals' => [],
        'clients' => []
    ];

    // Search Policies
    $policyQuery = "
        SELECT 
            id, 
            name, 
            phone, 
            vehicle_number, 
            insurance_company, 
            policy_type, 
            premium,
            policy_start_date,
            policy_end_date,
            created_date
        FROM policy 
        WHERE 
            vehicle_number LIKE ? OR 
            name LIKE ? OR 
            phone LIKE ? OR 
            insurance_company LIKE ? OR
            policy_type LIKE ?
        ORDER BY created_date DESC 
        LIMIT 10
    ";

    $stmt = mysqli_prepare($con, $policyQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssss', $queryLike, $queryLike, $queryLike, $queryLike, $queryLike);
        mysqli_stmt_execute($stmt);
        $policyResult = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($policyResult)) {
            $results['policies'][] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'vehicle_number' => $row['vehicle_number'],
                'insurance_company' => $row['insurance_company'],
                'policy_type' => $row['policy_type'],
                'premium' => (float)$row['premium'],
                'policy_start_date' => $row['policy_start_date'],
                'policy_end_date' => $row['policy_end_date'],
                'created_date' => $row['created_date']
            ];
        }
        mysqli_stmt_close($stmt);
    }

    // Search Renewals (policies expiring within next 90 days)
    $renewalQuery = "
        SELECT 
            id, 
            name, 
            phone, 
            vehicle_number, 
            insurance_company, 
            policy_end_date,
            premium,
            DATEDIFF(policy_end_date, CURDATE()) as days_left
        FROM policy 
        WHERE 
            (vehicle_number LIKE ? OR 
             name LIKE ? OR 
             phone LIKE ?) AND
            policy_end_date >= CURDATE() AND 
            policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
        ORDER BY policy_end_date ASC 
        LIMIT 10
    ";

    $stmt = mysqli_prepare($con, $renewalQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $queryLike, $queryLike, $queryLike);
        mysqli_stmt_execute($stmt);
        $renewalResult = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($renewalResult)) {
            $results['renewals'][] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'vehicle_number' => $row['vehicle_number'],
                'insurance_company' => $row['insurance_company'],
                'policy_end_date' => $row['policy_end_date'],
                'premium' => (float)$row['premium'],
                'days_left' => (int)$row['days_left']
            ];
        }
        mysqli_stmt_close($stmt);
    }

    // Search Clients (grouped by name/phone with policy count)
    $clientQuery = "
        SELECT 
            name, 
            phone, 
            COUNT(*) as policy_count,
            SUM(premium) as total_premium,
            MAX(created_date) as last_policy_date
        FROM policy 
        WHERE 
            name LIKE ? OR 
            phone LIKE ?
        GROUP BY name, phone 
        ORDER BY policy_count DESC, last_policy_date DESC 
        LIMIT 8
    ";

    $stmt = mysqli_prepare($con, $clientQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $queryLike, $queryLike);
        mysqli_stmt_execute($stmt);
        $clientResult = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($clientResult)) {
            $results['clients'][] = [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'policy_count' => (int)$row['policy_count'],
                'total_premium' => (float)$row['total_premium'],
                'last_policy_date' => $row['last_policy_date']
            ];
        }
        mysqli_stmt_close($stmt);
    }

    // Calculate totals
    $totalResults = count($results['policies']) + count($results['renewals']) + count($results['clients']);

    // Log search for analytics (optional)
    $logQuery = "INSERT INTO search_log (query, results_count, search_time, user_id) VALUES (?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($con, $logQuery);
    if ($stmt) {
        $userId = $_SESSION['user_id'] ?? 0;
        mysqli_stmt_bind_param($stmt, 'sii', $query, $totalResults, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    echo json_encode([
        'success' => true,
        'query' => $query,
        'total_results' => $totalResults,
        'results' => $results,
        'search_time' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    error_log("Global search error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Search failed: ' . $e->getMessage(),
        'error_code' => 'SEARCH_ERROR'
    ]);
}

mysqli_close($con);
?>
