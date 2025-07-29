<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get all policies due for renewal in next 30 days
    $renewalQuery = "SELECT * FROM policy 
                    WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                    AND policy_end_date >= CURDATE()
                    ORDER BY policy_end_date ASC";
    
    $result = mysqli_query($con, $renewalQuery);
    $count = 0;
    $successCount = 0;
    $errors = [];
    
    while ($policy = mysqli_fetch_array($result)) {
        $count++;
        
        try {
            // Calculate days until expiry
            $daysLeft = (strtotime($policy['policy_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
            
            // Prepare reminder message
            $message = "Dear {$policy['client_name']}, your insurance policy for vehicle {$policy['vehicle_number']} expires on " . date('d-m-Y', strtotime($policy['policy_end_date'])) . " (" . ceil($daysLeft) . " days remaining). Please contact us for renewal. - Softpro Insurance";
            
            // Insert/Update renewal reminder
            $reminderQuery = "INSERT INTO renewal_reminders 
                            (policy_id, client_name, vehicle_number, mobile_number, policy_end_date, 
                             reminder_date, reminder_message, status, days_remaining) 
                            VALUES (?, ?, ?, ?, ?, NOW(), ?, 'sent', ?)
                            ON DUPLICATE KEY UPDATE 
                            reminder_date = NOW(), 
                            reminder_message = VALUES(reminder_message),
                            status = 'sent',
                            days_remaining = VALUES(days_remaining)";
            
            $reminderStmt = mysqli_prepare($con, $reminderQuery);
            mysqli_stmt_bind_param($reminderStmt, "isssssi", 
                $policy['id'],
                $policy['client_name'],
                $policy['vehicle_number'],
                $policy['mobile_number'],
                $policy['policy_end_date'],
                $message,
                ceil($daysLeft)
            );
            
            if (mysqli_stmt_execute($reminderStmt)) {
                $successCount++;
                
                // Here you would integrate with WhatsApp/SMS API
                // For now, we'll just log the action
                $logQuery = "INSERT INTO activity_log (user_id, action, description, created_at) 
                           VALUES (?, 'bulk_renewal_reminder', ?, NOW())";
                $logStmt = mysqli_prepare($con, $logQuery);
                $description = "Sent renewal reminder to {$policy['client_name']} for {$policy['vehicle_number']}";
                mysqli_stmt_bind_param($logStmt, "ss", $_SESSION['username'], $description);
                mysqli_stmt_execute($logStmt);
                
                // Simulate API call delay (remove in production)
                usleep(100000); // 0.1 second delay
                
            } else {
                $errors[] = "Failed to process reminder for {$policy['vehicle_number']}";
            }
            
        } catch (Exception $e) {
            $errors[] = "Error processing {$policy['vehicle_number']}: " . $e->getMessage();
        }
    }
    
    // Create summary log entry
    $summaryQuery = "INSERT INTO activity_log (user_id, action, description, created_at) 
                    VALUES (?, 'bulk_renewal_summary', ?, NOW())";
    $summaryStmt = mysqli_prepare($con, $summaryQuery);
    $summaryDescription = "Bulk renewal reminders sent: {$successCount}/{$count} successful";
    mysqli_stmt_bind_param($summaryStmt, "ss", $_SESSION['username'], $summaryDescription);
    mysqli_stmt_execute($summaryStmt);
    
    if ($successCount > 0) {
        echo json_encode([
            'success' => true,
            'message' => "Renewal reminders sent successfully to {$successCount} clients",
            'count' => $successCount,
            'total' => $count,
            'errors' => $errors
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No renewal reminders were sent',
            'count' => 0,
            'total' => $count,
            'errors' => $errors
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
